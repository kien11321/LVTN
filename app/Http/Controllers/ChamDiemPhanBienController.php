<?php

namespace App\Http\Controllers;

use App\Models\ChamDiemPhanBien;
use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class ChamDiemPhanBienController extends Controller
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
                \Log::info('ChamDiemPhanBienController - Tự động liên kết giảng viên', [
                    'user_id' => $user->id,
                    'giangvien_id' => $gv->id,
                    'email' => $user->email,
                ]);
                return $gv;
            }
        }

        // Nếu user có vai trò là giảng viên hoặc admin nhưng chưa có giảng viên, tự động tạo mới
        if (in_array($user->vaitro ?? '', ['gvhd', 'giangvien', 'admin', 'gvpb'])) {
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

            \Log::info('ChamDiemPhanBienController - Tự động tạo giảng viên mới', [
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
            'deTai.giangVienPhanBien',
            'theoDoiTienDo',
        ])
            ->when($gv && $user->vaitro !== 'admin', function ($q) use ($gv) {
                // Nếu không phải admin, chỉ hiển thị nhóm mà giảng viên này phản biện
                $q->whereHas('deTai', function ($qq) use ($gv) {
                    $qq->where('giangvien_phanbien_id', $gv->id);
                });
            })
            ->when($user->vaitro === 'admin', function ($q) {
                // Nếu là admin, hiển thị tất cả nhóm đã có giảng viên phản biện
                $q->whereHas('deTai', function ($qq) {
                    $qq->whereNotNull('giangvien_phanbien_id');
                });
            })
            ->whereHas('sinhViens') // Chỉ hiển thị nhóm có thành viên
            ->whereDoesntHave('theoDoiTienDo', function ($query) {
                $query->where('quyet_dinh', 'tam_dung');
            })
            ->orderBy('ten_nhom')
            ->get();

        // Nhóm được chọn (nếu có)
        $selectedNhomId = $request->get('nhom_id');
        $selectedNhom = null;
        $chamDiems = collect([]);

        if ($selectedNhomId) {
            $selectedNhom = $allNhomSinhViens->firstWhere('id', $selectedNhomId);
            if ($selectedNhom && $gv) {
                $chamDiems = ChamDiemPhanBien::where('giangvien_id', $gv->id)
                    ->where('detai_id', $selectedNhom->deTai->id ?? 0)
                    ->get()
                    ->groupBy('sinhvien_id');
            }
        }

        return view('cham-diem-pb.index', compact('allNhomSinhViens', 'selectedNhom', 'chamDiems'));
    }

    // app/Http/Controllers/ChamDiemPhanBienController.php

    public function store(Request $request)
    {
        $request->validate([
            'detai_id' => 'required|exists:detai,id',
            'sinhvien_ids' => 'required|array',
            'de_nghi' => 'nullable|in:duoc_bao_ve,khong_bao_ve,bo_sung', // Khớp ENUM trong SQL
        ]);

        $user = Auth::user();
        $gv = GiangVien::where('nguoidung_id', $user->id)->first();

        DB::beginTransaction();
        try {
            foreach ($request->sinhvien_ids as $svId) {
                $pt = ((float)($request->phan_tich[$svId] ?? 0) / 2.5) * 25;
                $tk = ((float)($request->thiet_ke[$svId] ?? 0) / 2.5) * 25;
                $ht = ((float)($request->hien_thuc[$svId] ?? 0) / 2.5) * 25;
                $bc = ((float)($request->bao_cao[$svId] ?? 0) / 2.5) * 25;
                $tong = min($pt + $tk + $ht + $bc, 100);

                ChamDiemPhanBien::updateOrCreate(
                    ['detai_id' => $request->detai_id, 'sinhvien_id' => $svId, 'giangvien_id' => $gv->id],
                    [
                        'phan_tich' => $pt,
                        'thiet_ke' => $tk,
                        'hien_thuc' => $ht,
                        'bao_cao' => $bc,
                        'tong_phan_tram' => $tong,
                        'diem_10' => round($tong * 0.1, 2),

                        'nhan_xet_tong_quat' => $request->nhan_xet_tong_quat,

                        'thuyet_minh' => $request->thuyet_minh,
                        'uu_diem' => $request->uu_diem,
                        'thieu_sot' => $request->thieu_sot,
                        'cau_hoi' => $request->cau_hoi,
                        'de_nghi' => $request->de_nghi, // Lưu 'duoc_bao_ve', 'khong_bao_ve' hoặc 'bo_sung'
                    ]
                );
            }
            DB::commit();
            return back()->with('success', 'Lưu điểm và nhận xét thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function exportWord($nhomId)
    {
        try {
            $nhom = NhomSinhVien::with(['sinhViens', 'deTai.giangVienPhanBien'])->findOrFail($nhomId);

            if (!$nhom->deTai) {
                return back()->with('error', 'Nhóm này chưa có đề tài.');
            }

            // ✅ Lấy giảng viên đang đăng nhập (chính người xuất)
            $user = Auth::user();
            $gvLogin = GiangVien::where('nguoidung_id', $user->id)->first();

            // ✅ Fallback nếu không tìm thấy giảng viên theo user
            $gvIdExport = $gvLogin?->id ?? $nhom->deTai->giangvien_phanbien_id;

            $chamDiems = ChamDiemPhanBien::where('detai_id', $nhom->deTai->id)
                ->where('giangvien_id', $gvIdExport)
                ->get()
                ->keyBy('sinhvien_id');

            $template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/templates/filexuatdiempb.docx'));

            $vars = $template->getVariables();
            $has  = fn($name) => in_array($name, $vars, true);

            $checked = '☑';
            $unchecked = '☐';

            // 1) Thông tin chung
            if ($has('ten_dt'))   $template->setValue('ten_dt', $nhom->deTai->ten_detai ?? '');
            if ($has('hoten_gv')) $template->setValue('hoten_gv', $nhom->deTai->giangVienPhanBien->hoten ?? ($gvLogin->hoten ?? ''));

            // 2) Nhận xét chung (lấy bản ghi đầu tiên)
            $f = $chamDiems->values()->first();
            if ($f) {
                $tm = trim((string)$f->thuyet_minh);

                if ($has('tich_dat'))  $template->setValue('tich_dat', ($tm === 'dat') ? $checked : $unchecked);
                if ($has('tich_kdat')) $template->setValue('tich_kdat', ($tm === 'khong_dat') ? $checked : $unchecked);

                if ($has('nx_tong_quat')) $template->setValue('nx_tong_quat', $f->nhan_xet_tong_quat ?? '');
                if ($has('nx_uu_diem'))   $template->setValue('nx_uu_diem',   $f->uu_diem ?? '');
                if ($has('nx_thieu_sot')) $template->setValue('nx_thieu_sot', $f->thieu_sot ?? '');
                if ($has('nx_cau_hoi'))   $template->setValue('nx_cau_hoi',   $f->cau_hoi ?? '');
            } else {
                // nếu chưa có nhận xét: để checkbox rỗng
                if ($has('tich_dat'))  $template->setValue('tich_dat', $unchecked);
                if ($has('tich_kdat')) $template->setValue('tich_kdat', $unchecked);
            }

            // 3) SV1, SV2
            for ($i = 1; $i <= 2; $i++) {
                $sv = $nhom->sinhViens->values()->get($i - 1);

                // Nếu không có SV2 -> để trống
                if (!$sv) {
                    foreach (["sv{$i}_ten", "sv{$i}_ms", "sv{$i}_l", "pt_{$i}", "tk_{$i}", "ht_{$i}", "bc_{$i}", "tong_{$i}", "diem10_{$i}"] as $k) {
                        if ($has($k)) $template->setValue($k, '');
                    }
                    foreach (["tich_duoc_{$i}", "tich_khong_{$i}", "tich_bs_{$i}"] as $k) {
                        if ($has($k)) $template->setValue($k, $unchecked);
                    }
                    continue;
                }

                if ($has("sv{$i}_ten")) $template->setValue("sv{$i}_ten", $sv->hoten ?? '');
                if ($has("sv{$i}_ms"))  $template->setValue("sv{$i}_ms",  $sv->mssv ?? '');
                if ($has("sv{$i}_l"))   $template->setValue("sv{$i}_l",   $sv->lop ?? '');

                $d = $chamDiems->get($sv->id);
                if (!$d) {
                    foreach (["pt_{$i}", "tk_{$i}", "ht_{$i}", "bc_{$i}", "tong_{$i}", "diem10_{$i}"] as $k) {
                        if ($has($k)) $template->setValue($k, '');
                    }
                    foreach (["tich_duoc_{$i}", "tich_khong_{$i}", "tich_bs_{$i}"] as $k) {
                        if ($has($k)) $template->setValue($k, $unchecked);
                    }
                    continue;
                }

                // điểm (đang lưu % 0..100 -> xuất lại thang 2.5)
                if ($has("pt_{$i}")) $template->setValue("pt_{$i}", number_format(($d->phan_tich / 25) * 2.5, 1));
                if ($has("tk_{$i}")) $template->setValue("tk_{$i}", number_format(($d->thiet_ke / 25) * 2.5, 1));
                if ($has("ht_{$i}")) $template->setValue("ht_{$i}", number_format(($d->hien_thuc / 25) * 2.5, 1));
                if ($has("bc_{$i}")) $template->setValue("bc_{$i}", number_format(($d->bao_cao / 25) * 2.5, 1));

                if ($has("tong_{$i}"))   $template->setValue("tong_{$i}", ($d->tong_phan_tram ?? 0) . '%');
                if ($has("diem10_{$i}")) $template->setValue("diem10_{$i}", number_format((float)$d->diem_10, 1));

                // đề nghị: tick ☑/☐
                $dn = trim((string)$d->de_nghi);
                if ($has("tich_duoc_{$i}"))  $template->setValue("tich_duoc_{$i}", ($dn === 'duoc_bao_ve') ? $checked : $unchecked);
                if ($has("tich_khong_{$i}")) $template->setValue("tich_khong_{$i}", ($dn === 'khong_bao_ve') ? $checked : $unchecked);
                if ($has("tich_bs_{$i}"))    $template->setValue("tich_bs_{$i}", ($dn === 'bo_sung') ? $checked : $unchecked);
            }

            $filename = 'Phieu_Cham_Diem_PB_' . ($nhom->ten_nhom ?? 'Nhom') . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word_');
            $template->saveAs($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
