<?php

namespace App\Http\Controllers;

use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PhanBienController extends Controller
{
    /**
     * Danh sách nhóm và phân công giảng viên phản biện
     */
    public function index()
    {
        $nhoms = NhomSinhVien::with([
            'sinhViens',
            'deTai.giangVien',
            'deTai.giangVienPhanBien',
            'theoDoiTienDo',
        ])
            ->whereHas('sinhViens')
            ->whereHas('deTai')
            ->whereDoesntHave('theoDoiTienDo', function ($query) {
                $query->where('quyet_dinh', 'tam_dung');
            })
            ->orderBy('ten_nhom')
            ->get();

        $giangViens = GiangVien::orderBy('hoten')->get();

        return view('phan-bien.index', compact('nhoms', 'giangViens'));
    }

    /**
     * Cập nhật giảng viên phản biện
     */
    public function update(Request $request)
    {
        $request->validate([
            'detai_id' => 'required|exists:detai,id',
            'gvpb_id' => 'required|exists:giangvien,id',
        ], [
            'detai_id.required' => 'Vui lòng chọn đề tài',
            'detai_id.exists' => 'Đề tài không tồn tại',
            'gvpb_id.required' => 'Vui lòng chọn giảng viên phản biện',
            'gvpb_id.exists' => 'Giảng viên phản biện không tồn tại',
        ]);

        try {
            $deTai = DeTai::with('giangVien')->findOrFail($request->detai_id);

            // Không cho GVHD phản biện đề tài của mình
            if ($deTai->giangvien_id && $deTai->giangvien_id === (int) $request->gvpb_id) {
                return redirect()->back()->with('error', 'Giảng viên phản biện không được trùng với giảng viên hướng dẫn!');
            }

            $deTai->giangvien_phanbien_id = (int) $request->gvpb_id;
            $deTai->save();

            return redirect()->back()->with('success', 'Phân công giảng viên phản biện thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tìm giảng viên theo user (KHÔNG tự tạo cho admin)
     */
    private function getGiangVienForUser($user): ?GiangVien
    {
        // Admin: không cần map sang giảng viên
        if (($user->vaitro ?? '') === 'admin') {
            return null;
        }

        // Tìm qua nguoidung_id
        $gv = GiangVien::where('nguoidung_id', $user->id)->first();
        if ($gv) return $gv;

        // Thử map qua email (nếu có)
        if (!empty($user->email)) {
            $gv = GiangVien::where('email', $user->email)
                ->whereNull('nguoidung_id')
                ->first();

            if ($gv) {
                $gv->nguoidung_id = $user->id;
                $gv->save();
                return $gv;
            }
        }

        // Nếu là gvhd/giangvien/gvpb mà chưa có record giảng viên -> có thể tạo (tuỳ bạn)
        // Nếu bạn KHÔNG muốn tự tạo, thì comment block này và return null.
        if (in_array($user->vaitro ?? '', ['gvhd', 'giangvien', 'gvpb'], true)) {
            $magv = 'GV' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $counter = 1;
            while (GiangVien::where('magv', $magv)->exists()) {
                $magv = 'GV' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '_' . $counter;
                $counter++;
            }

            return GiangVien::create([
                'nguoidung_id' => $user->id,
                'magv' => $magv,
                'hoten' => $user->hoten ?? $user->name ?? ('Giảng viên ' . $user->id),
                'email' => $user->email,
                'sdt' => $user->sdt ?? null,
                'bo_mon' => 'CNTT',
            ]);
        }

        return null;
    }

    /**
     * Xuất Excel danh sách SV + GVHD + GVPB
     */
    public function exportCsv(): StreamedResponse
    {
        $user = Auth::user();

        // CHỈ lọc theo giảng viên khi user là gvhd/giangvien/gvpb
        $gv = $this->getGiangVienForUser($user);

        $query = DeTai::with([
            'nhomSinhVien.sinhViens',
            'giangVien',
            'giangVienPhanBien',
        ])
            ->whereNotNull('nhom_sinhvien_id')
            ->whereHas('nhomSinhVien.sinhViens');

        if ($gv) {
            $query->where(function ($q) use ($gv) {
                $q->where('giangvien_id', $gv->id)
                    ->orWhere('giangvien_phanbien_id', $gv->id);
            });
        }

        $deTais = $query->orderBy('ten_detai')->get();

        // ====== TẠO EXCEL ======
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DANH SÁCH SINH VIÊN - GIÁO VIÊN HƯỚNG DẪN- GIÁO VIÊN PHẢN BIỆN - THU QUYỀN LVTN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(25);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'ĐẠI HỌC 2025 VÀ KHÓA CŨ LÀM LẠI (ĐỢT 1_THÁNG 4)');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('800080');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'NGÀNH : CÔNG NGHỆ THÔNG TIN');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header
        $sheet->setCellValue('A4', 'STT');
        $sheet->setCellValue('B4', 'MSSV');
        $sheet->setCellValue('C4', 'Họ và tên SV');
        $sheet->setCellValue('D4', 'Lớp');
        $sheet->setCellValue('E4', "Tên đề tài\n(GVHD nhập)");
        $sheet->setCellValue('F4', 'GVHD');
        $sheet->setCellValue('G4', 'GVPB');

        $yellowStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $greenStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '90EE90']],
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $sheet->getStyle('A4')->applyFromArray($yellowStyle);
        $sheet->getStyle('B4')->applyFromArray($yellowStyle);
        $sheet->getStyle('C4')->applyFromArray($yellowStyle);
        $sheet->getStyle('D4')->applyFromArray($yellowStyle);
        $sheet->getStyle('F4')->applyFromArray($yellowStyle);
        $sheet->getStyle('G4')->applyFromArray($yellowStyle);

        $sheet->getStyle('E4')->applyFromArray($greenStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Data
        $row = 5;
        $stt = 1;

        $borderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        foreach ($deTais as $deTai) {
            if (!$deTai->nhomSinhVien || $deTai->nhomSinhVien->sinhViens->isEmpty()) continue;

            foreach ($deTai->nhomSinhVien->sinhViens as $sv) {
                $sheet->setCellValue("A{$row}", $stt++);
                $sheet->setCellValue("B{$row}", $sv->mssv ?? '-');
                $sheet->setCellValue("C{$row}", $sv->hoten ?? '-');
                $sheet->setCellValue("D{$row}", $sv->lop ?? '-');
                $sheet->setCellValue("E{$row}", $deTai->ten_detai ?? '-');
                $sheet->setCellValue("F{$row}", $deTai->giangVien?->hoten ?? '-');
                $sheet->setCellValue("G{$row}", $deTai->giangVienPhanBien?->hoten ?? '-');

                $sheet->getStyle("A{$row}:G{$row}")->applyFromArray($borderAll);

                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
            }
        }

        // Nếu rỗng
        if ($row === 5) {
            $sheet->mergeCells('A5:G5');
            $sheet->setCellValue('A5', 'KHÔNG CÓ DỮ LIỆU');
            $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A5:G5')->applyFromArray($borderAll);
            $sheet->getRowDimension(5)->setRowHeight(30);
        }

        // Width
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(28);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(45);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(22);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Danh_Sach_SV_GVHD_GVPB.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
