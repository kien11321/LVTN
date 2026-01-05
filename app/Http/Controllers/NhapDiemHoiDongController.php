<?php

namespace App\Http\Controllers;

use App\Models\NhapDiemBaoVe;
use App\Models\ChamDiemHuongDan;
use App\Models\ChamDiemPhanBien;
use App\Models\DeTai;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NhapDiemHoiDongController extends Controller
{
    /**
     * Lấy giảng viên đang đăng nhập (theo nguoidung_id)
     */
    private function currentGiangVien(): ?GiangVien
    {
        $user = Auth::user();
        if (!$user) return null;

        return GiangVien::where('nguoidung_id', $user->id)->first();
    }

    public function index()
    {
        $gv = $this->currentGiangVien();

        // Nếu không phải admin và không tìm thấy giảng viên -> chặn
        if (!$gv && (Auth::user()->vaitro ?? '') !== 'admin') {
            return back()->with('error', 'Không tìm thấy thông tin giảng viên để lọc danh sách!');
        }

        // ✅ CHỈ LẤY ĐỀ TÀI MÀ GVHD = GIẢNG VIÊN ĐANG LOGIN (trừ admin)
        $deTais = DeTai::with(['nhomSinhVien.sinhViens', 'hoiDong'])
            ->whereNotNull('hoi_dong_id')
            ->whereNotNull('nhom_sinhvien_id')
            ->when((Auth::user()->vaitro ?? '') !== 'admin', function ($q) use ($gv) {
                $q->where('giangvien_id', $gv->id);
            })
            ->orderBy('ten_detai')
            ->get();

        $nhapDiems = NhapDiemBaoVe::all()->keyBy(fn($item) => $item->detai_id . '_' . $item->sinhvien_id);

        $sinhVienList = [];

        foreach ($deTais as $deTai) {
            if (!$deTai->nhomSinhVien) continue;

            foreach ($deTai->nhomSinhVien->sinhViens as $sv) {
                $dHD = ChamDiemHuongDan::where('detai_id', $deTai->id)
                    ->where('sinhvien_id', $sv->id)
                    ->value('diem_10') ?? 0;

                $dPB = ChamDiemPhanBien::where('detai_id', $deTai->id)
                    ->where('sinhvien_id', $sv->id)
                    ->value('diem_10') ?? 0;

                $diemGV_40 = ($dHD * 0.2) + ($dPB * 0.2);
                $nhapDiem = $nhapDiems->get($deTai->id . '_' . $sv->id);

                $sinhVienList[] = [
                    'mssv' => $sv->mssv,
                    'hoten' => $sv->hoten,
                    'detai_id' => $deTai->id,
                    'sinhvien_id' => $sv->id,
                    'ten_detai' => $deTai->ten_detai,
                    'diem_gv' => round($diemGV_40, 2),
                    'diem_bao_ve' => $nhapDiem ? $nhapDiem->diem_bao_ve : 0,
                    'diem_tong' => $nhapDiem ? $nhapDiem->diem_tong : 0,
                ];
            }
        }

        return view('nhap-diem-hoi-dong.index', compact('sinhVienList'));
    }

    public function store(Request $request)
    {
        $request->validate(['diem_bao_ve' => 'required|array']);

        $gv = $this->currentGiangVien();

        if (!$gv && (Auth::user()->vaitro ?? '') !== 'admin') {
            return back()->with('error', 'Không tìm thấy thông tin giảng viên để lưu điểm!');
        }

        DB::beginTransaction();
        try {
            foreach ($request->diem_bao_ve as $key => $diemNhap) {
                if ($diemNhap === null || $diemNhap === '') continue;

                [$detaiId, $sinhvienId] = explode('_', $key);

                // ✅ CHẶN LƯU NẾU ĐỀ TÀI KHÔNG THUỘC GVHD ĐANG LOGIN (trừ admin)
                if ((Auth::user()->vaitro ?? '') !== 'admin') {
                    $belongs = DeTai::where('id', $detaiId)
                        ->where('giangvien_id', $gv->id)
                        ->exists();

                    if (!$belongs) {
                        // bỏ qua hoặc bạn có thể abort(403)
                        continue;
                    }
                }

                $dHD = ChamDiemHuongDan::where('detai_id', $detaiId)
                    ->where('sinhvien_id', $sinhvienId)
                    ->value('diem_10') ?? 0;

                $dPB = ChamDiemPhanBien::where('detai_id', $detaiId)
                    ->where('sinhvien_id', $sinhvienId)
                    ->value('diem_10') ?? 0;

                $diemGV_40 = ($dHD * 0.2) + ($dPB * 0.2);

                // (giữ công thức của bạn)
                $diemTong = ((float) $diemGV_40 * 0.4) + ((float) $diemNhap * 0.6);

                NhapDiemBaoVe::updateOrCreate(
                    ['detai_id' => $detaiId, 'sinhvien_id' => $sinhvienId],
                    [
                        'diem_bao_ve' => $diemNhap,
                        'diem_gv' => round($diemGV_40, 2),
                        'diem_tong' => round($diemTong, 2),
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Lưu bảng điểm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function exportExcel(): StreamedResponse
    {
        $gv = $this->currentGiangVien();
        if (!$gv && (Auth::user()->vaitro ?? '') !== 'admin') {
            abort(403, 'Không tìm thấy giảng viên để xuất Excel');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tiêu đề
        $sheet->mergeCells('D3:G3');
        $sheet->setCellValue('D3', 'BẢNG ĐIỂM TỔNG KẾT ĐỒ ÁN TỐT NGHIỆP');
        $sheet->getStyle('D3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('D4:G4');
        $sheet->setCellValue('D4', 'KHOA CÔNG NGHỆ THÔNG TIN NĂM HỌC 2024-2025');

        // Header bảng
        $headers = [
            'A6' => 'STT',
            'B6' => 'MSSV',
            'C6' => 'Họ và tên',
            'D6' => 'Đề tài',
            'E6' => 'GVHD',
            'F6' => 'GVPB',
            'G6' => 'Hội đồng',
            'H6' => 'Điểm GV (40%)',
            'I6' => 'Điểm HĐ (60%)',
            'J6' => 'Điểm TB',
        ];
        foreach ($headers as $cell => $value) $sheet->setCellValue($cell, $value);

        $sheet->getStyle('A6:J6')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']],
        ]);

        // ✅ CHỈ LẤY ĐỀ TÀI THUỘC GVHD (trừ admin)
        $deTais = DeTai::with(['nhomSinhVien.sinhViens', 'hoiDong', 'giangVien', 'giangVienPhanBien'])
            ->whereNotNull('hoi_dong_id')
            ->when((Auth::user()->vaitro ?? '') !== 'admin', function ($q) use ($gv) {
                $q->where('giangvien_id', $gv->id);
            })
            ->get();

        $nhapDiems = NhapDiemBaoVe::all()->keyBy(fn($item) => $item->detai_id . '_' . $item->sinhvien_id);

        $row = 7;
        $stt = 1;

        foreach ($deTais as $deTai) {
            if (!$deTai->nhomSinhVien) continue;

            foreach ($deTai->nhomSinhVien->sinhViens as $sv) {
                $dHD = ChamDiemHuongDan::where('detai_id', $deTai->id)->where('sinhvien_id', $sv->id)->value('diem_10') ?? 0;
                $dPB = ChamDiemPhanBien::where('detai_id', $deTai->id)->where('sinhvien_id', $sv->id)->value('diem_10') ?? 0;

                $diemGV_40 = ($dHD * 0.2) + ($dPB * 0.2);

                $nhapDiem = $nhapDiems->get($deTai->id . '_' . $sv->id);
                $diemHD_raw = $nhapDiem ? $nhapDiem->diem_bao_ve : 0;

                // Giữ đúng logic file Excel của bạn đang tính
                $diemTB = $diemGV_40 + ($diemHD_raw * 0.6);

                $sheet->setCellValue('A' . $row, $stt++);
                $sheet->setCellValue('B' . $row, $sv->mssv);
                $sheet->setCellValue('C' . $row, $sv->hoten);
                $sheet->setCellValue('D' . $row, $deTai->ten_detai);
                $sheet->setCellValue('E' . $row, $deTai->giangVien->hoten ?? '-');
                $sheet->setCellValue('F' . $row, $deTai->giangVienPhanBien->hoten ?? '-');
                $sheet->setCellValue('G' . $row, $deTai->hoiDong->ten_hoi_dong ?? '-');
                $sheet->setCellValue('H' . $row, number_format($diemGV_40, 2));
                $sheet->setCellValue('I' . $row, number_format($diemHD_raw * 0.6, 2));
                $sheet->setCellValue('J' . $row, number_format($diemTB, 2));
                $row++;
            }
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(fn() => $writer->save('php://output'), 'Bang_Diem_Tong_Ket.xlsx');
    }
}
