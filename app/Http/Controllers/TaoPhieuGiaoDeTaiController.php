<?php

namespace App\Http\Controllers;

use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaoPhieuGiaoDeTaiController extends Controller
{
    /**
     * Hiển thị giao diện Form nhập liệu
     */
    public function index()
    {
        try {
            // Lấy danh sách nhóm đã có đề tài để hỗ trợ tự động điền
            $nhoms = NhomSinhVien::with(['sinhViens', 'deTai.giangVien'])
                ->whereHas('deTai')
                ->whereHas('sinhViens')
                ->orderBy('ten_nhom')
                ->get();

            $nhomData = $nhoms->map(function ($nhom) {
                $sv1 = $nhom->sinhViens->first();
                $sv2 = $nhom->sinhViens->count() > 1 ? $nhom->sinhViens->skip(1)->first() : null;
                return [
                    'id' => $nhom->id,
                    'sv1' => $sv1 ? ['hoten' => $sv1->hoten, 'mssv' => $sv1->mssv, 'lop' => $sv1->lop] : null,
                    'sv2' => $sv2 ? ['hoten' => $sv2->hoten, 'mssv' => $sv2->mssv, 'lop' => $sv2->lop] : null,
                    'detai' => $nhom->deTai ? ['ten_detai' => $nhom->deTai->ten_detai, 'mo_ta' => $nhom->deTai->mo_ta] : null,
                    'gv' => ($nhom->deTai && $nhom->deTai->giangVien) ? ['hoten' => $nhom->deTai->giangVien->hoten] : null,
                ];
            });

            return view('tao-phieu-giao-detai.index', compact('nhoms', 'nhomData'));
        } catch (\Exception $e) {
            return view('tao-phieu-giao-detai.index', ['nhoms' => collect([]), 'nhomData' => collect([])])
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý xuất file Word từ Template FormNhiemVu.docx
     */
    public function exportWord(Request $request)
    {
        // 1. Kiểm tra thư viện ZipArchive
        if (!class_exists('ZipArchive')) {
            return back()->with('error', 'Lỗi: Hãy bật PHP extension ZipArchive trong php.ini');
        }

        // 2. Xác định đường dẫn file mẫu chính xác trên Windows
        $templatePath = storage_path('app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'FormNhiemVu.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Không tìm thấy file mẫu tại: ' . $templatePath);
        }

        // 3. Validate dữ liệu nhập vào
        $request->validate([
            'sv1_hoten' => 'required',
            'sv1_mssv'  => 'required',
            'ten_detai' => 'required',
            'nhiem_vu'  => 'required',
            'gv_hoten'  => 'required',
        ]);

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // 4. Đổ dữ liệu Sinh viên 1 & 2
            $templateProcessor->setValue('sv1_hoten', $request->sv1_hoten);
            $templateProcessor->setValue('sv1_mssv',  $request->sv1_mssv);
            $templateProcessor->setValue('sv1_lop',   $request->sv1_lop ?? '');

            $templateProcessor->setValue('sv2_hoten', $request->sv2_hoten ?? '');
            $templateProcessor->setValue('sv2_mssv',  $request->sv2_mssv ?? '');
            $templateProcessor->setValue('sv2_lop',   $request->sv2_lop ?? '');

            // 5. Đổ dữ liệu Đề tài & Giảng viên (Tự động thay thế tất cả vị trí có biến gv_hoten)
            $templateProcessor->setValue('ten_detai', $request->ten_detai);
            $templateProcessor->setValue('gv_hoten',  $request->gv_hoten);

            // 6. Xử lý nội dung Nhiệm vụ & Tài liệu (Xuống dòng đúng cách)
            $nhiemVu = str_replace("\n", '</w:t><w:br/><w:t>', $request->nhiem_vu);
            $hoSo    = str_replace("\n", '</w:t><w:br/><w:t>', $request->ho_so_tai_lieu ?? '-');
            $templateProcessor->setValue('nhiem_vu', $nhiemVu);
            $templateProcessor->setValue('ho_so_tai_lieu', $hoSo);

            // 7. Tách Ngày - Tháng - Năm từ ngày giao
            $ngayGiao = $request->ngay_giao ? Carbon::parse($request->ngay_giao) : Carbon::now();
            $templateProcessor->setValue('ngay',  $ngayGiao->format('d'));
            $templateProcessor->setValue('thang', $ngayGiao->format('m'));
            $templateProcessor->setValue('nam',   $ngayGiao->format('Y'));

            // 8. Tạo file tạm ngẫu nhiên để tránh lỗi dính cache file cũ
            $fileName = 'Phieu_Nhiem_Vu_' . $request->sv1_mssv . '_' . time() . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word_');
            $templateProcessor->saveAs($tempFile);

            // Gửi file về trình duyệt và tự động xóa file tạm sau khi tải xong
            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Lỗi xuất file Word: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
