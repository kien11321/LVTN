<?php

namespace App\Http\Controllers;

use App\Models\TheoDoiTienDo;
use App\Models\NhomSinhVien;
use App\Models\GiangVien;
use App\Models\HoiDong;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font as SpreadsheetFont;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TheoDoiTienDoController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên và tiến độ
     */
    /**
     * Hiển thị danh sách sinh viên và tiến độ
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // ✅ (THÊM) Lấy từ khóa tìm kiếm
            $search = trim($request->get('search', ''));

            // Kiểm tra bảng có tồn tại không
            try {
                DB::select('SELECT 1 FROM theo_doi_tien_do LIMIT 1');
            } catch (\Exception $e) {
                // Bảng chưa tồn tại, redirect đến route tạo bảng
                return redirect('/create-theo-doi-tien-do-table')
                    ->with('error', 'Bảng theo_doi_tien_do chưa được tạo. Đang tạo bảng...');
            }

            // Lấy danh sách sinh viên với thông tin nhóm, đề tài và tiến độ
            $query = DB::table('sinhvien')
                ->leftJoin('nhom_sinhvien_chitiet', 'sinhvien.id', '=', 'nhom_sinhvien_chitiet.sinhvien_id')
                ->leftJoin('nhom_sinhvien', 'nhom_sinhvien_chitiet.nhom_sinhvien_id', '=', 'nhom_sinhvien.id')
                ->leftJoin('detai', 'nhom_sinhvien.id', '=', 'detai.nhom_sinhvien_id')
                ->leftJoin('theo_doi_tien_do', 'nhom_sinhvien.id', '=', 'theo_doi_tien_do.nhom_sinhvien_id')
                ->leftJoin('giangvien', 'detai.giangvien_id', '=', 'giangvien.id')
                ->select(
                    'giangvien.hoten as gvhd',
                    'sinhvien.id as sinhvien_id',
                    'sinhvien.mssv',
                    'sinhvien.hoten',
                    'sinhvien.lop',
                    'sinhvien.email',
                    'sinhvien.sdt',
                    'nhom_sinhvien.id as nhom_id',
                    'nhom_sinhvien.ten_nhom',
                    'detai.id as detai_id',
                    'detai.ten_detai',
                    'theo_doi_tien_do.tien_do',
                    'theo_doi_tien_do.quyet_dinh',
                    'theo_doi_tien_do.ghi_chu'
                );

            // ✅ (THÊM) Tìm kiếm theo MSSV hoặc Họ tên
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('sinhvien.mssv', 'like', "%{$search}%")
                        ->orWhere('sinhvien.hoten', 'like', "%{$search}%");
                });
            }

            // Nếu là giảng viên, chỉ hiển thị sinh viên của nhóm mình hướng dẫn
            if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien') {
                $giangVien = GiangVien::where('nguoidung_id', $user->id)->first();
                if ($giangVien) {
                    $query->where('detai.giangvien_id', $giangVien->id);
                }
            }

            // Chỉ lấy sinh viên đã có nhóm
            $query->whereNotNull('nhom_sinhvien.id');

            // Tối ưu query: chỉ lấy dữ liệu cần thiết và giới hạn số lượng nếu cần
            $sinhViens = $query
                ->orderBy('nhom_sinhvien.ten_nhom')
                ->orderBy('sinhvien.mssv')
                ->get();

            // Group theo nhóm - kiểm tra collection không rỗng
            $nhomGroups = $sinhViens->isNotEmpty() ? $sinhViens->groupBy('nhom_id') : collect();

            // ✅ (THÊM) Truyền $search để view giữ lại input tìm kiếm
            return view('theo-doi-tien-do.index', compact('nhomGroups', 'search'));
        } catch (\Exception $e) {
            return redirect('/create-theo-doi-tien-do-table')
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    /**
     * Hiển thị form cập nhật tiến độ
     */
    public function edit($nhomId)
    {
        $nhom = NhomSinhVien::with(['deTai', 'sinhViens', 'theoDoiTienDo'])->findOrFail($nhomId);

        $tienDo = $nhom->theoDoiTienDo;

        return view('theo-doi-tien-do.edit', compact('nhom', 'tienDo'));
    }

    /**
     * Cập nhật tiến độ
     */
    public function update(Request $request, $nhomId)
    {
        $request->validate([
            'tien_do' => 'required|integer|min:0|max:100',
            'quyet_dinh' => 'required|in:duoc_lam_tiep,tam_dung,huy',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            $nhom = NhomSinhVien::with('deTai')->findOrFail($nhomId);
            $user = Auth::user();

            // Lấy giảng viên ID
            $giangVienId = null;
            if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien') {
                $giangVien = GiangVien::where('nguoidung_id', $user->id)->first();
                if ($giangVien) {
                    $giangVienId = $giangVien->id;
                }
            }

            // Tự động set quyết định dựa trên tiến độ
            // Nếu < 50%: tự động set "Tạm dừng"
            // Nếu >= 50%: tự động set "Được làm tiếp"
            $tienDoValue = (int) $request->tien_do;
            $quyetDinh = $request->quyet_dinh;

            // Chỉ tự động set nếu quyết định không phải là "Hủy" (cho phép giảng viên override)
            if ($quyetDinh !== 'huy') {
                if ($tienDoValue < 50) {
                    $quyetDinh = 'tam_dung';
                } else {
                    $quyetDinh = 'duoc_lam_tiep';
                }
            }

            // Tìm hoặc tạo bản ghi tiến độ
            $tienDo = TheoDoiTienDo::updateOrCreate(
                ['nhom_sinhvien_id' => $nhomId],
                [
                    'detai_id' => $nhom->deTai->id ?? null,
                    'tien_do' => $tienDoValue,
                    'quyet_dinh' => $quyetDinh,
                    'ghi_chu' => $request->ghi_chu,
                    'giangvien_id' => $giangVienId,
                    'ngay_cap_nhat' => now(),
                ]
            );

            return redirect()
                ->route('theo-doi-tien-do.index')
                ->with('success', 'Cập nhật tiến độ thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xuất Excel danh sách đánh giá khối lượng hoàn thành giữa kỳ
     */
    public function exportExcel(): StreamedResponse
    {
        $user = Auth::user();

        $query = DB::table('sinhvien')
            ->leftJoin('nhom_sinhvien_chitiet', 'sinhvien.id', '=', 'nhom_sinhvien_chitiet.sinhvien_id')
            ->leftJoin('nhom_sinhvien', 'nhom_sinhvien_chitiet.nhom_sinhvien_id', '=', 'nhom_sinhvien.id')
            ->leftJoin('detai', 'nhom_sinhvien.id', '=', 'detai.nhom_sinhvien_id')
            ->leftJoin('giangvien', 'detai.giangvien_id', '=', 'giangvien.id')
            ->leftJoin('theo_doi_tien_do', 'nhom_sinhvien.id', '=', 'theo_doi_tien_do.nhom_sinhvien_id')
            ->whereNotNull('nhom_sinhvien.id')
            ->select(
                'sinhvien.mssv',
                'sinhvien.hoten',
                'sinhvien.lop',
                'sinhvien.email',
                'sinhvien.sdt',
                'nhom_sinhvien.ten_nhom',
                'giangvien.hoten as gvhd',
                'detai.ten_detai',
                'theo_doi_tien_do.tien_do',
                'theo_doi_tien_do.quyet_dinh',
                'theo_doi_tien_do.ghi_chu'
            );

        // Nếu là giảng viên, chỉ xuất nhóm do mình hướng dẫn
        if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien') {
            $gv = GiangVien::where('nguoidung_id', $user->id)->first();
            if ($gv) {
                $query->where('detai.giangvien_id', $gv->id);
            }
        }

        $rows = $query
            ->orderBy('nhom_sinhvien.ten_nhom')
            ->orderBy('sinhvien.mssv')
            ->get();

        // Tạo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header trường và khoa
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'KHOA CÔNG NGHỆ THÔNG TIN');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Tiêu đề chính (màu đỏ)
        $sheet->mergeCells('A3:N3');
        $sheet->setCellValue('A3', 'DANH SÁCH ĐÁNH GIÁ KHỐI LƯỢNG HOÀN THÀNH GIỮA KỲ (%)');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(25);

        // Subtitle (màu tím)
        $sheet->mergeCells('A4:N4');
        $sheet->setCellValue('A4', 'ĐẠI HỌC 2021 VÀ KHÓA CŨ LÀM LẠI');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('800080');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Ngành (màu đen)
        $sheet->mergeCells('A5:N5');
        $sheet->setCellValue('A5', 'NGÀNH : CÔNG NGHỆ THÔNG TIN');
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Header row 7-8 (merge 2 rows)
        $headers = [
            'A7' => 'STT',
            'B7' => 'MSSV',
            'C7' => 'HỌ TÊN SINH VIÊN',
            'D7' => 'LỚP',
            'E7' => 'SĐT',
            'F7' => 'Email',
            'G7' => 'Nhóm',
            'H7' => 'GVHD',
            'I7' => 'Tên đề tài',
            'J7' => 'Khối lượng hoàn thành giữa kỳ (%)',
            'K7' => 'Quyết định',
            'L7' => 'GVHD GHI CHÚ',
            'M7' => '',
            'N7' => '',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Merge header cells (A7:A8, B7:B8, etc.)
        foreach (range('A', 'N') as $col) {
            $sheet->mergeCells($col . '7:' . $col . '8');
        }

        // Format header rows (màu vàng)
        $headerStyle = [
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
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A7:N8')->applyFromArray($headerStyle);
        $sheet->getRowDimension(7)->setRowHeight(25);
        $sheet->getRowDimension(8)->setRowHeight(25);

        // Map quyết định
        $quyetDinhMap = [
            'duoc_lam_tiep' => 'Được làm tiếp',
            'tam_dung' => 'Tạm dừng',
            'huy' => 'Hủy',
        ];

        // Dữ liệu
        $row = 9;
        $stt = 1;
        foreach ($rows as $dataRow) {
            $quyetDinhText = '-';
            if ($dataRow->quyet_dinh && isset($quyetDinhMap[$dataRow->quyet_dinh])) {
                $quyetDinhText = $quyetDinhMap[$dataRow->quyet_dinh];
            }

            $sheet->setCellValue('A' . $row, $stt++);
            $sheet->setCellValue('B' . $row, $dataRow->mssv ?? '-');
            $sheet->setCellValue('C' . $row, $dataRow->hoten ?? '-');
            $sheet->setCellValue('D' . $row, $dataRow->lop ?? '-');
            $sheet->setCellValue('E' . $row, $dataRow->sdt ?? '-');
            $sheet->setCellValue('F' . $row, $dataRow->email ?? '-');
            $sheet->setCellValue('G' . $row, $dataRow->ten_nhom ?? '-');
            $sheet->setCellValue('H' . $row, $dataRow->gvhd ?? '-');
            $sheet->setCellValue('I' . $row, $dataRow->ten_detai ?? '-');
            $sheet->setCellValue('J' . $row, $dataRow->tien_do ?? 0);
            $sheet->setCellValue('K' . $row, $quyetDinhText);
            $sheet->setCellValue('L' . $row, $dataRow->ghi_chu ?? '-');
            $sheet->setCellValue('M' . $row, '');
            $sheet->setCellValue('N' . $row, '');

            // Format data row
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];
            $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray($dataStyle);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Auto width columns
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(40);
        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(30);
        $sheet->getColumnDimension('M')->setWidth(10);
        $sheet->getColumnDimension('N')->setWidth(10);

        // Writer
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Danh_Sach_Danh_Gia_Khoi_Luong_Hoan_Thanh_Giua_Ky.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
