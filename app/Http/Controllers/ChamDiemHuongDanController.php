<?php

namespace App\Http\Controllers;

use App\Models\ChamDiemHuongDan;
use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font as SpreadsheetFont;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpWord\TemplateProcessor;

class ChamDiemHuongDanController extends Controller
{
    /**
     * Tìm giảng viên theo user (tự động liên kết hoặc tạo mới nếu cần)
     */
    private function getGiangVienForUser($user)
    {
        // Tìm qua nguoidung_id
        $gv = GiangVien::where('nguoidung_id', $user->id)->first();

        if ($gv) {
            return $gv;
        }

        // Nếu không tìm thấy, thử tìm qua email và tự động liên kết
        if ($user->email) {
            $gv = GiangVien::where('email', $user->email)
                ->whereNull('nguoidung_id')
                ->first();

            if ($gv) {
                // Tự động liên kết
                $gv->nguoidung_id = $user->id;
                $gv->save();
                \Log::info('ChamDiemHuongDanController - Tự động liên kết giảng viên', [
                    'user_id' => $user->id,
                    'giangvien_id' => $gv->id,
                    'email' => $user->email,
                ]);
                return $gv;
            }
        }

        // Nếu user có vai trò là giảng viên hoặc admin nhưng chưa có giảng viên, tự động tạo mới
        if (in_array($user->vaitro ?? '', ['gvhd', 'giangvien', 'admin'])) {
            // Tạo mã giảng viên duy nhất
            $magv = 'GV' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $counter = 1;
            while (GiangVien::where('magv', $magv)->exists()) {
                $magv = 'GV' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '_' . $counter;
                $counter++;
            }

            // Tạo giảng viên mới dựa trên thông tin user
            $gv = GiangVien::create([
                'nguoidung_id' => $user->id,
                'magv' => $magv,
                'hoten' => $user->hoten ?? $user->name ?? 'Giảng viên ' . $user->id,
                'email' => $user->email,
                'sdt' => $user->sdt ?? null,
                'bo_mon' => 'CNTT', // Mặc định
            ]);

            \Log::info('ChamDiemHuongDanController - Tự động tạo giảng viên mới', [
                'user_id' => $user->id,
                'vaitro' => $user->vaitro,
                'giangvien_id' => $gv->id,
                'magv' => $magv,
                'email' => $user->email,
            ]);

            return $gv;
        }

        return null;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $gv = $this->getGiangVienForUser($user);

        // Lấy tất cả nhóm để hiển thị trong dropdown
        $allNhomSinhViens = NhomSinhVien::with([
            'sinhViens',
            'deTai.giangVien',
            'theoDoiTienDo',
        ])
            ->when($gv && $user->vaitro !== 'admin', function ($q) use ($gv) {
                // Nếu không phải admin, chỉ hiển thị nhóm mà giảng viên này hướng dẫn
                $q->whereHas('deTai', function ($qq) use ($gv) {
                    $qq->where('giangvien_id', $gv->id);
                });
            })
            ->when($user->vaitro === 'admin', function ($q) {
                $q->whereHas('deTai');
            })
            ->whereHas('sinhViens') // Chỉ hiển thị nhóm có thành viên
            ->whereDoesntHave('theoDoiTienDo', function ($query) {
                $query->where('quyet_dinh', 'tam_dung');
            })
            ->orderBy('ten_nhom')
            ->get();

        $nhomDaChamIds = [];
        if ($gv) {
            foreach ($allNhomSinhViens as $nhom) {
                $detaiId = $nhom->deTai->id ?? null;
                if (!$detaiId) continue;

                $svIds = $nhom->sinhViens->pluck('id')->toArray();
                if (empty($svIds)) continue;

                $countDaCham = ChamDiemHuongDan::where('giangvien_id', $gv->id)
                    ->where('detai_id', $detaiId)
                    ->whereIn('sinhvien_id', $svIds)
                    ->count();

                if ($countDaCham === count($svIds)) {
                    $nhomDaChamIds[] = $nhom->id;
                }
            }
        }

        // Nhóm được chọn (nếu có)
        $selectedNhomId = $request->get('nhom_id');
        $selectedNhom = null;
        $chamDiems = collect([]);

        if ($selectedNhomId) {
            $selectedNhom = $allNhomSinhViens->firstWhere('id', $selectedNhomId);
            if ($selectedNhom && $gv) {
                $chamDiems = ChamDiemHuongDan::where('giangvien_id', $gv->id)
                    ->where('detai_id', $selectedNhom->deTai->id ?? 0)
                    ->get()
                    ->groupBy('sinhvien_id');
            }
        }

        return view('cham-diem-hd.index', compact(
            'allNhomSinhViens',
            'selectedNhom',
            'chamDiems',
            'nhomDaChamIds'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'detai_id' => 'required|exists:detai,id',
            'sinhvien_ids' => 'required|array',
            'sinhvien_ids.*' => 'exists:sinhvien,id',
            'phan_tich' => 'array',
            'thiet_ke' => 'array',
            'hien_thuc' => 'array',
            'bao_cao' => 'array',
        ]);

        $user = Auth::user();
        $gv = $this->getGiangVienForUser($user);

        if (!$gv) {
            \Log::warning('ChamDiemHuongDanController@store - Không tìm thấy giảng viên', [
                'user_id' => $user->id,
                'user_email' => $user->email ?? null,
                'vaitro' => $user->vaitro ?? null,
            ]);
            return back()->with('error', 'Không tìm thấy thông tin giảng viên! Vui lòng liên hệ quản trị viên để liên kết tài khoản với giảng viên.');
        }

        DB::beginTransaction();
        try {
            // Lấy detai_id từ request hoặc từ nhóm đầu tiên
            $detaiId = $request->detai_id;

            foreach ($request->sinhvien_ids as $svId) {
                // Nhập điểm theo thang 2.5 (max 2.5 mỗi phần)
                $ptThang25 = (float) ($request->phan_tich[$svId] ?? 0);
                $tkThang25 = (float) ($request->thiet_ke[$svId] ?? 0);
                $htThang25 = (float) ($request->hien_thuc[$svId] ?? 0);
                $bcThang25 = (float) ($request->bao_cao[$svId] ?? 0);

                // Chuyển từ thang 2.5 sang % (0-25% mỗi phần) để lưu vào DB
                $pt = ($ptThang25 / 2.5) * 25;
                $tk = ($tkThang25 / 2.5) * 25;
                $ht = ($htThang25 / 2.5) * 25;
                $bc = ($bcThang25 / 2.5) * 25;

                // Tổng % = tổng các phần % (pt + tk + ht + bc)
                $tong = $pt + $tk + $ht + $bc;

                if ($tong > 100) {
                    $tong = 100;
                }

                // Điểm chấm = tổng % x 0.1
                $diem10 = round($tong * 0.1, 2);

                ChamDiemHuongDan::updateOrCreate(
                    [
                        'detai_id' => $detaiId,
                        'sinhvien_id' => $svId,
                        'giangvien_id' => $gv->id,
                    ],
                    [
                        'phan_tich' => $pt,
                        'thiet_ke' => $tk,
                        'hien_thuc' => $ht,
                        'bao_cao' => $bc,
                        'tong_phan_tram' => $tong,
                        'diem_10' => $diem10,
                        'ghi_chu' => $request->ghi_chu[$svId] ?? null,
                        'noi_dung_dieu_chinh' => $request->noi_dung_dieu_chinh ?? null,
                        'nhan_xet_tong_quat' => $request->nhan_xet_tong_quat ?? null,
                        'thuyet_minh' => $request->thuyet_minh ?? null,
                        'uu_diem' => $request->uu_diem ?? null,
                        'thieu_sot' => $request->thieu_sot ?? null,
                        'cau_hoi' => $request->cau_hoi ?? null,
                        'de_nghi' => $request->de_nghi ?? null,
                    ]
                );
            }

            DB::commit();
            return back()->with('success', 'Lưu điểm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Xuất phiếu chấm điểm Word
     */
    /**
     * Xuất phiếu chấm điểm Word khớp 100% mẫu filexuatdiem.docx
     */
    public function exportWord($nhomId)
    {
        try {
            // 1. Lấy dữ liệu
            $nhom = NhomSinhVien::with(['sinhViens', 'deTai.giangVien'])->findOrFail($nhomId);
            $chamDiems = ChamDiemHuongDan::where('detai_id', $nhom->deTai->id)->get()->keyBy('sinhvien_id');

            // 2. Load Template
            $templatePath = storage_path('app/templates/filexuatdiem.docx');
            if (!file_exists($templatePath)) {
                return back()->with('error', 'Không tìm thấy file mẫu tại storage/app/templates/filexuatdiem.docx');
            }
            $template = new TemplateProcessor($templatePath);

            // Định nghĩa ký hiệu tích
            $check = '☑';
            $uncheck = '☐';

            // 3. Đổ dữ liệu chung (Khớp tag trong ảnh)
            $template->setValue('ten_detai', $nhom->deTai->ten_detai);
            $template->setValue('gv_hoten', $nhom->deTai->giangVien->hoten ?? '');

            // 4. Nhận xét chung (dat/kdat)
            $f = $chamDiems->first();
            if ($f) {
                // Tích Đạt/Không đạt
                $template->setValue('dat', ($f->thuyet_minh === 'dat') ? $check : $uncheck);
                $template->setValue('kdat', ($f->thuyet_minh === 'khong_dat') ? $check : $uncheck);

                // Nội dung text (nhận xét, ưu điểm, thiếu sót, câu hỏi)
                $template->setValue('nhan_xet_tong_quat', $f->nhan_xet_tong_quat ?? '');
                $template->setValue('uu_diem', $f->uu_diem ?? '');
                $template->setValue('thieu_sot', $f->thieu_sot ?? '');
                $template->setValue('cau_hoi', $f->cau_hoi ?? '');
            }

            // 5. Vòng lặp xử lý từng sinh viên (SV1 và SV2)
            for ($i = 1; $i <= 2; $i++) {
                $sv = $nhom->sinhViens->values()->get($i - 1);

                if ($sv) {
                    // Thông tin SV
                    $template->setValue("sv{$i}_name", $sv->hoten);
                    $template->setValue("sv{$i}_mssv", $sv->mssv);
                    $template->setValue("sv{$i}_lop", $sv->lop ?? '');

                    $d = $chamDiems->get($sv->id);
                    if ($d) {
                        // Đổ điểm bảng chi tiết
                        $template->setValue("pt{$i}", number_format(($d->phan_tich / 25) * 2.5, 1));
                        $template->setValue("tk{$i}", number_format(($d->thiet_ke / 25) * 2.5, 1));
                        $template->setValue("ht{$i}", number_format(($d->hien_thuc / 25) * 2.5, 1));
                        $template->setValue("bc{$i}", number_format(($d->bao_cao / 25) * 2.5, 1));
                        $template->setValue("tong{$i}", $d->tong_phan_tram . '%');
                        $template->setValue("d10_{$i}", number_format($d->diem_10, 1));

                        // --- PHẦN TÍCH ĐỀ NGHỊ BẢO VỆ (SV1 và SV2) ---
                        // Bạn hãy đặt biến trong Word giống hệt tên trong ngoặc kép này:
                        $template->setValue(
                            "dbv_{$i}",
                            ($d->de_nghi === 'duoc_bao_ve') ? $check : $uncheck
                        );

                        $template->setValue(
                            "kdbv_{$i}",
                            ($d->de_nghi === 'khong_bao_ve') ? $check : $uncheck
                        );

                        $template->setValue(
                            "bs_{$i}",
                            ($d->de_nghi === 'bo_sung') ? $check : $uncheck
                        );
                    }
                } else {
                    // Nếu nhóm chỉ có 1 SV, xóa trắng các tag của SV2
                    $template->setValues([
                        "sv{$i}_name" => '',
                        "sv{$i}_mssv" => '',
                        "sv{$i}_lop" => '',
                        "pt{$i}" => '',
                        "tk{$i}" => '',
                        "ht{$i}" => '',
                        "bc{$i}" => '',
                        "tong{$i}" => '',
                        "d10_{$i}" => '',
                        "dbv_{$i}" => $uncheck,
                        "kdbv_{$i}" => $uncheck,
                        "bs_{$i}" => $uncheck
                    ]);
                }
            }

            // 6. Xuất file
            $filename = 'Phieu_Cham_HD_' . $nhom->ten_nhom . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            $template->saveAs($tempFile);
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
