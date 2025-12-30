<?php

namespace App\Http\Controllers;

use App\Models\HoiDong;
use App\Models\GiangVien;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font as SpreadsheetFont;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HoiDongController extends Controller
{
    /**
     * Hiển thị danh sách hội đồng
     */
    public function index()
    {
        $hoiDongs = HoiDong::with(['chuTich', 'thuKy', 'uyVien1', 'uyVien2', 'deTais.nhomSinhVien.sinhViens', 'deTais.giangVien'])
            ->orderBy('ngay_bao_ve', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $giangViens = GiangVien::orderBy('hoten')->get();

        // Lấy danh sách đề tài chưa được gán vào hội đồng
        // Loại bỏ nhóm bị tạm ngưng
        $deTaisChuaGan = DeTai::with(['nhomSinhVien.sinhViens', 'giangVien'])
            ->whereNull('hoi_dong_id')
            ->whereNotNull('nhom_sinhvien_id')
            ->whereDoesntHave('nhomSinhVien.theoDoiTienDo', function ($query) {
                $query->where('quyet_dinh', 'tam_dung');
            })
            ->orderBy('ten_detai')
            ->get();

        // Debug: Log để kiểm tra
        \Log::info('HoiDongController@index', [
            'hoiDongs_count' => $hoiDongs->count(),
            'deTaisChuaGan_count' => $deTaisChuaGan->count(),
        ]);
        // LẤY ĐỀ TÀI CHƯA GÁN VÀ ĐÃ ĐƯỢC DUYỆT BẢO VỆ
        $deTaisChuaGan = DeTai::whereNull('hoi_dong_id')
            ->whereHas('nhomSinhVien')
            // Kiểm tra xem có ít nhất một bản ghi chấm điểm có trạng thái 'duoc_bao_ve'
            ->whereHas('chamDiemPhanBiens', function ($query) {
                $query->where('de_nghi', 'duoc_bao_ve');
            })
            ->with(['nhomSinhVien.sinhViens', 'giangVien'])
            ->get();
        return view('hoi-dong.index', compact('hoiDongs', 'giangViens', 'deTaisChuaGan'));
    }

    /**
     * Lưu hội đồng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_hoi_dong' => 'required|string|max:255',
            'ngay_bao_ve' => 'required|date',
            'phong_bao_ve' => 'nullable|string|max:100',
            'chu_tich_id' => 'required|exists:giangvien,id',
            'thu_ky_id' => 'required|exists:giangvien,id',
            'uy_vien_1_id' => 'nullable|exists:giangvien,id',
            'uy_vien_2_id' => 'nullable|exists:giangvien,id',
        ], [
            'ten_hoi_dong.required' => 'Tên hội đồng là bắt buộc',
            'ngay_bao_ve.required' => 'Ngày bảo vệ là bắt buộc',
            'ngay_bao_ve.date' => 'Ngày bảo vệ không hợp lệ',
            'chu_tich_id.required' => 'Chủ tịch là bắt buộc',
            'chu_tich_id.exists' => 'Chủ tịch không tồn tại',
            'thu_ky_id.required' => 'Thư ký là bắt buộc',
            'thu_ky_id.exists' => 'Thư ký không tồn tại',
            'uy_vien_1_id.exists' => 'Ủy viên 1 không tồn tại',
            'uy_vien_2_id.exists' => 'Ủy viên 2 không tồn tại',
        ]);

        try {
            DB::beginTransaction();

            HoiDong::create([
                'ten_hoi_dong' => $request->ten_hoi_dong,
                'ngay_bao_ve' => $request->ngay_bao_ve,
                'phong_bao_ve' => $request->phong_bao_ve,
                'chu_tich_id' => $request->chu_tich_id,
                'thu_ky_id' => $request->thu_ky_id,
                'uy_vien_1_id' => $request->uy_vien_1_id ?: null,
                'uy_vien_2_id' => $request->uy_vien_2_id ?: null,
            ]);

            DB::commit();

            return redirect()
                ->route('hoi-dong.index')
                ->with('success', 'Tạo hội đồng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating hoi dong: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo hội đồng: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật hội đồng
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ten_hoi_dong' => 'required|string|max:255',
            'ngay_bao_ve' => 'required|date',
            'phong_bao_ve' => 'nullable|string|max:100',
            'chu_tich_id' => 'required|exists:giangvien,id',
            'thu_ky_id' => 'required|exists:giangvien,id',
            'uy_vien_1_id' => 'nullable|exists:giangvien,id',
            'uy_vien_2_id' => 'nullable|exists:giangvien,id',
        ], [
            'ten_hoi_dong.required' => 'Tên hội đồng là bắt buộc',
            'ngay_bao_ve.required' => 'Ngày bảo vệ là bắt buộc',
            'ngay_bao_ve.date' => 'Ngày bảo vệ không hợp lệ',
            'chu_tich_id.required' => 'Chủ tịch là bắt buộc',
            'chu_tich_id.exists' => 'Chủ tịch không tồn tại',
            'thu_ky_id.required' => 'Thư ký là bắt buộc',
            'thu_ky_id.exists' => 'Thư ký không tồn tại',
            'uy_vien_1_id.exists' => 'Ủy viên 1 không tồn tại',
            'uy_vien_2_id.exists' => 'Ủy viên 2 không tồn tại',
        ]);

        try {
            DB::beginTransaction();

            $hoiDong = HoiDong::findOrFail($id);
            $hoiDong->update([
                'ten_hoi_dong' => $request->ten_hoi_dong,
                'ngay_bao_ve' => $request->ngay_bao_ve,
                'phong_bao_ve' => $request->phong_bao_ve,
                'chu_tich_id' => $request->chu_tich_id,
                'thu_ky_id' => $request->thu_ky_id,
                'uy_vien_1_id' => $request->uy_vien_1_id ?: null,
                'uy_vien_2_id' => $request->uy_vien_2_id ?: null,
            ]);

            DB::commit();

            return redirect()
                ->route('hoi-dong.index')
                ->with('success', 'Cập nhật hội đồng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating hoi dong: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật hội đồng: ' . $e->getMessage());
        }
    }

    /**
     * Xóa hội đồng
     */
    public function destroy(string $id)
    {
        try {
            $hoiDong = HoiDong::findOrFail($id);
            $hoiDong->delete();

            return redirect()
                ->route('hoi-dong.index')
                ->with('success', 'Xóa hội đồng thành công!');
        } catch (\Exception $e) {
            \Log::error('Error deleting hoi dong: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa hội đồng: ' . $e->getMessage());
        }
    }

    /**
     * Gán đề tài vào hội đồng
     */
    public function assignDetai(Request $request)
    {
        $request->validate([
            'hoi_dong_id' => 'required|exists:hoi_dong,id',
            'detai_id' => 'required|exists:detai,id',
        ]);

        try {
            $deTai = DeTai::findOrFail($request->detai_id);
            $deTai->hoi_dong_id = $request->hoi_dong_id;
            $deTai->save();

            return redirect()
                ->route('hoi-dong.index')
                ->with('success', 'Gán đề tài vào hội đồng thành công!');
        } catch (\Exception $e) {
            \Log::error('Error assigning detai: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi gán đề tài: ' . $e->getMessage());
        }
    }

    /**
     * Hủy gán đề tài khỏi hội đồng
     */
    public function unassignDetai(Request $request)
    {
        $request->validate([
            'detai_id' => 'required|exists:detai,id',
        ]);

        try {
            $deTai = DeTai::findOrFail($request->detai_id);
            $deTai->hoi_dong_id = null;
            $deTai->save();

            return redirect()
                ->route('hoi-dong.index')
                ->with('success', 'Hủy gán đề tài thành công!');
        } catch (\Exception $e) {
            \Log::error('Error unassigning detai: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi hủy gán đề tài: ' . $e->getMessage());
        }
    }

    /**
     * Xuất Excel danh sách thứ tự sinh viên bảo vệ tại hội đồng
     */
    public function exportExcel(): StreamedResponse
    {
        $hoiDongs = HoiDong::with([
            'deTais' => function ($query) {
                $query->whereNotNull('nhom_sinhvien_id');
            },
            'deTais.nhomSinhVien.sinhViens',
            'deTais.giangVien'
        ])
            ->whereHas('deTais', function ($query) {
                $query->whereNotNull('nhom_sinhvien_id');
            })
            ->orderBy('ngay_bao_ve', 'asc')
            ->orderBy('ten_hoi_dong')
            ->get();

        // Lấy ngày bảo vệ đầu tiên để hiển thị
        $ngayBaoVe = $hoiDongs->first()?->ngay_bao_ve;
        $soHoiDong = $hoiDongs->count();

        // Tạo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header trái: Trường
        $sheet->setCellValue('A1', 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        // Header phải: Phụ lục 3
        $sheet->setCellValue('H1', 'Phụ lục 3');
        $sheet->getStyle('H1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Tiêu đề chính (màu đỏ)
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'DANH SÁCH THỨ TỰ SINH VIÊN BẢO VỆ TẠI HỘI ĐỒNG ĐẠI HỌC 2021 HỆ CHÍNH QUY TẬP TRUNG');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Ngành
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'NGÀNH : CÔNG NGHỆ THÔNG TIN');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Ngày
        $ngayText = '';
        if ($ngayBaoVe) {
            try {
                $dateObj = \Carbon\Carbon::parse($ngayBaoVe);
                $thu = ['Chủ nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'][$dateObj->dayOfWeek];
                $ngayText = $thu . ' ngày ' . $dateObj->format('d/m/Y');
            } catch (\Exception $e) {
                $ngayText = '';
            }
        }

        $sheet->mergeCells('A4:H4');
        $sheet->setCellValue('A4', $ngayText);
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Số hội đồng bảo vệ
        $sheet->mergeCells('A5:H5');
        $sheet->setCellValue('A5', '- Ngày bảo vệ : ' . ($ngayText ?: '-') . ' - Số hội đồng bảo vệ : ' . $soHoiDong . ' hội đồng');
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Header bảng (màu vàng) - merge 2 dòng
        $headerRow = 6;
        $sheet->setCellValue('A' . $headerRow, 'STT');
        $sheet->setCellValue('B' . $headerRow, 'HỘI ĐỒNG');
        $sheet->setCellValue('C' . $headerRow, 'MSSV');
        $sheet->setCellValue('D' . $headerRow, 'HỌ TÊN SINH VIÊN');
        $sheet->setCellValue('E' . $headerRow, 'LỚP');
        $sheet->setCellValue('F' . $headerRow, 'GVHD');
        $sheet->setCellValue('G' . $headerRow, 'TÊN ĐỀ TÀI');
        $sheet->setCellValue('H' . $headerRow, 'Phòng Hội đồng');

        // Format header (màu vàng)
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

        // Format cột "Phòng Hội đồng" (màu xanh lá)
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
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->applyFromArray($headerStyle);
        $sheet->getStyle('H' . $headerRow)->applyFromArray($greenStyle);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Dữ liệu
        $row = $headerRow + 1;
        $stt = 1;
        foreach ($hoiDongs as $hoiDong) {
            foreach ($hoiDong->deTais as $deTai) {
                $nhom = $deTai->nhomSinhVien;
                if (!$nhom) continue;

                $sinhViens = $nhom->sinhViens;
                if ($sinhViens->isEmpty()) continue;

                foreach ($sinhViens as $sinhVien) {
                    $sheet->setCellValue('A' . $row, $stt++);
                    $sheet->setCellValue('B' . $row, $hoiDong->ten_hoi_dong);
                    $sheet->setCellValue('C' . $row, $sinhVien->mssv ?? '-');
                    $sheet->setCellValue('D' . $row, $sinhVien->hoten ?? '-');
                    $sheet->setCellValue('E' . $row, $sinhVien->lop ?? '-');
                    $sheet->setCellValue('F' . $row, $deTai->giangVien->hoten ?? '-');
                    $sheet->setCellValue('G' . $row, $deTai->ten_detai ?? '-');
                    $sheet->setCellValue('H' . $row, $hoiDong->phong_bao_ve ?? '-');

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
                    $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray($dataStyle);
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('90EE90');

                    $row++;
                }
            }
        }

        // Auto width columns
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(20);

        // Writer
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Danh_Sach_Thu_Tu_Sinh_Vien_Bao_Ve_Tai_Hoi_Dong.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
