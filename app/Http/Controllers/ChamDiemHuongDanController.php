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
                // Nếu là admin, chỉ hiển thị nhóm có đề tài
                $q->whereHas('deTai');
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
                $chamDiems = ChamDiemHuongDan::where('giangvien_id', $gv->id)
                    ->where('detai_id', $selectedNhom->deTai->id ?? 0)
                    ->get()
                    ->groupBy('sinhvien_id');
            }
        }

        return view('cham-diem-hd.index', compact('allNhomSinhViens', 'selectedNhom', 'chamDiems'));
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
    /**
     * Xuất Excel danh sách sinh viên - GVHD - GVPB
     */
    public function exportExcel(): StreamedResponse
    {
        $user = Auth::user();
        $gv = $this->getGiangVienForUser($user);

        // Lấy tất cả đề tài mà GV này THAM GIA (hướng dẫn HOẶC phản biện)
        $query = DeTai::with([
            'nhomSinhVien.sinhViens',
            'giangVien',
            'giangVienPhanBien',
        ])
            ->whereNotNull('nhom_sinhvien_id')
            ->whereHas('nhomSinhVien.sinhViens');

        if ($gv) {
            // Nếu là GV: lấy cả đề tài mình hướng dẫn VÀ phản biện
            $query->where(function ($q) use ($gv) {
                $q->where('giangvien_id', $gv->id)           // GVHD
                    ->orWhere('giangvien_phanbien_id', $gv->id); // GVPB
            });
        }

        $deTais = $query->orderBy('ten_detai')->get();

        // Tạo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tiêu đề chính (màu đỏ) - Chỉ còn 7 cột (A:G)
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DANH SÁCH SINH VIÊN - GIÁO VIÊN HƯỚNG DẪN- GIÁO VIÊN PHẢN BIỆN - THU QUYỀN LVTN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Subtitle (màu tím) - 7 cột
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'ĐẠI HỌC 2025 VÀ KHÓA CŨ LÀM LẠI (ĐỢT 1_THÁNG 4)');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('800080');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Ngành (màu đen) - 7 cột
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'NGÀNH : CÔNG NGHỆ THÔNG TIN');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Header row 4 - CHỈ CÒN 7 CỘT (A:G)
        $sheet->setCellValue('A4', 'STT');
        $sheet->setCellValue('B4', 'MSSV');
        $sheet->setCellValue('C4', 'Họ và tên SV');
        $sheet->setCellValue('D4', 'Lớp');
        $sheet->setCellValue('E4', 'Tên đề tài' . "\n" . '(GVHD nhập)');
        $sheet->setCellValue('F4', 'GVHD'); // Cột F bây giờ là GVHD
        $sheet->setCellValue('G4', 'GVPB'); // Cột G bây giờ là GVPB

        // Format header rows (màu vàng và xanh lá)
        $yellowStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'], // Màu vàng
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM, // Đường viền đậm hơn
                ],
            ],
        ];

        $greenStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '90EE90'], // Màu xanh lá nhạt
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ],
            ],
        ];

        // Merge các cells header - CHỈ CÒN 7 CỘT
        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->mergeCells('D4:D5');
        $sheet->mergeCells('E4:E5');
        $sheet->mergeCells('F4:F5');
        $sheet->mergeCells('G4:G5');

        // Áp dụng màu vàng cho A, B, C, D, F, G
        $sheet->getStyle('A4')->applyFromArray($yellowStyle);
        $sheet->getStyle('B4')->applyFromArray($yellowStyle);
        $sheet->getStyle('C4')->applyFromArray($yellowStyle);
        $sheet->getStyle('D4')->applyFromArray($yellowStyle);
        $sheet->getStyle('F4')->applyFromArray($yellowStyle);
        $sheet->getStyle('G4')->applyFromArray($yellowStyle);

        // Áp dụng màu xanh lá cho E (cột đề tài)
        $sheet->getStyle('E4')->applyFromArray($greenStyle);
        $sheet->getStyle('E4')->getAlignment()->setWrapText(true);

        $sheet->getRowDimension(4)->setRowHeight(25);
        $sheet->getRowDimension(5)->setRowHeight(25);

        // Dữ liệu
        $row = 6;
        $stt = 1;

        // Style cho từng cell border riêng biệt
        $fullBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        foreach ($deTais as $deTai) {
            if (!$deTai->nhomSinhVien || $deTai->nhomSinhVien->sinhViens->isEmpty()) {
                continue;
            }

            foreach ($deTai->nhomSinhVien->sinhViens as $sv) {
                // Không xuất các ô trống
                if (empty($sv->mssv) && empty($sv->hoten) && empty($sv->lop)) {
                    continue;
                }

                $sheet->setCellValue('A' . $row, $stt++);
                $sheet->setCellValue('B' . $row, $sv->mssv ?? '-');
                $sheet->setCellValue('C' . $row, $sv->hoten ?? '-');
                $sheet->setCellValue('D' . $row, $sv->lop ?? '-');
                $sheet->setCellValue('E' . $row, $deTai->ten_detai ?? '-');
                $sheet->setCellValue('F' . $row, $deTai->giangVien ? $deTai->giangVien->hoten : '-');
                $sheet->setCellValue('G' . $row, $deTai->giangVienPhanBien ? $deTai->giangVienPhanBien->hoten : '-');

                // Áp dụng border đầy đủ cho dòng dữ liệu
                $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($fullBorderStyle);

                // Căn giữa cho các cột số và mã
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
            }
        }

        // Thêm border cho phần tiêu đề (nếu chưa đủ)
        $sheet->getStyle('A1:G3')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ],
            ],
        ]);

        // Thêm border đầy đủ cho toàn bộ header (dòng 4-5)
        $sheet->getStyle('A4:G5')->applyFromArray($fullBorderStyle);

        // Tô màu nền cho phần tiêu đề
        $titleStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F0F0'], // Màu xám nhạt
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:G3')->applyFromArray($titleStyle);

        // Auto width columns - CHỈ CÒN 7 CỘT
        $sheet->getColumnDimension('A')->setWidth(8);      // STT
        $sheet->getColumnDimension('B')->setWidth(12);     // MSSV
        $sheet->getColumnDimension('C')->setWidth(25);     // Họ tên
        $sheet->getColumnDimension('D')->setWidth(12);     // Lớp
        $sheet->getColumnDimension('E')->setWidth(40);     // Đề tài
        $sheet->getColumnDimension('F')->setWidth(20);     // GVHD
        $sheet->getColumnDimension('G')->setWidth(20);     // GVPB

        // Đặt chiều cao tự động cho các dòng có nội dung dài
        for ($i = 6; $i < $row; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1); // Auto height
        }

        // Writer
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Danh_Sach_SV_GVHD_GVPB.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
