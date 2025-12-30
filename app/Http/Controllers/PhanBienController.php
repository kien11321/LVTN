<?php

namespace App\Http\Controllers;

use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhanBienController extends Controller
{
    /**
     * Danh sách nhóm và phân công giảng viên phản biện
     */
    public function index()
    {
        // Lấy tất cả nhóm có đề tài để có thể phân công giảng viên phản biện
        // Chỉ lấy nhóm có thành viên và có đề tài
        // Loại bỏ nhóm có quyết định "Tạm dừng"
        $nhoms = NhomSinhVien::with([
            'sinhViens',
            'deTai.giangVien',
            'deTai.giangVienPhanBien',
            'theoDoiTienDo', // Load relationship để kiểm tra
        ])
            ->whereHas('sinhViens') // Chỉ lấy nhóm có thành viên
            ->whereHas('deTai') // Chỉ lấy nhóm có đề tài
            ->whereDoesntHave('theoDoiTienDo', function ($query) {
                // Loại bỏ nhóm có quyet_dinh = 'tam_dung'
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
        \Log::info('PhanBienController@update', [
            'request_data' => $request->all(),
        ]);

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
                return redirect()
                    ->back()
                    ->with('error', 'Giảng viên phản biện không được trùng với giảng viên hướng dẫn!');
            }

            $deTai->giangvien_phanbien_id = $request->gvpb_id;
            $deTai->save();

            \Log::info('PhanBienController@update - Success', [
                'detai_id' => $deTai->id,
                'gvpb_id' => $request->gvpb_id,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Phân công giảng viên phản biện thành công!');
        } catch (\Exception $e) {
            \Log::error('PhanBienController@update - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xuất Excel (CSV) danh sách sinh viên với GVHD & GVPB
     */
    public function exportCsv(): StreamedResponse
    {
        DB::select('SELECT 1 FROM detai LIMIT 1'); // ensure table exists

        $user = Auth::user();

        $query = DB::table('sinhvien')
            ->leftJoin('nhom_sinhvien_chitiet', 'sinhvien.id', '=', 'nhom_sinhvien_chitiet.sinhvien_id')
            ->leftJoin('nhom_sinhvien', 'nhom_sinhvien_chitiet.nhom_sinhvien_id', '=', 'nhom_sinhvien.id')
            ->leftJoin('detai', 'nhom_sinhvien.id', '=', 'detai.nhom_sinhvien_id')
            ->leftJoin('giangvien as gvhd', 'detai.giangvien_id', '=', 'gvhd.id')
            ->leftJoin('giangvien as gvpb', 'detai.giangvien_phanbien_id', '=', 'gvpb.id')
            ->whereNotNull('nhom_sinhvien.id')
            ->select(
                'sinhvien.mssv',
                'sinhvien.hoten',
                'sinhvien.lop',
                'detai.ten_detai',
                'gvhd.hoten as gvhd_hoten',
                'gvpb.hoten as gvpb_hoten'
            );

        // Nếu là giảng viên, chỉ xuất nhóm mình hướng dẫn hoặc phản biện
        if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien') {
            $gv = GiangVien::where('nguoidung_id', $user->id)->first();
            if ($gv) {
                $query->where(function ($q) use ($gv) {
                    $q->where('detai.giangvien_id', $gv->id)
                        ->orWhere('detai.giangvien_phanbien_id', $gv->id);
                });
            }
        }

        $rows = $query->orderBy('sinhvien.mssv')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="phan_cong_gv_phan_bien.csv"',
        ];

        $columns = [
            'STT',
            'MSSV',
            'Họ và tên SV',
            'Lớp',
            'Tên đề tài (GVHD nhập)',
            'GVHD',
            'GVPB',
        ];

        $callback = static function () use ($rows, $columns) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($output, $columns);

            $stt = 1;
            foreach ($rows as $row) {
                fputcsv($output, [
                    $stt++,
                    $row->mssv,
                    $row->hoten,
                    $row->lop,
                    $row->ten_detai ?? '-',
                    $row->gvhd_hoten ?? '-',
                    $row->gvpb_hoten ?? '-',
                ]);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, 'phan_cong_gv_phan_bien.csv', $headers);
    }
}
