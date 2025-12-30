<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\GiangVien;

class SinhVienController extends Controller
{

    // Giảng viên chỉ thấy được danh sách sinh viên của mình
    private function currentGiangVien()
    {
        $user = Auth::user();
        if (!$user) return null;

        if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien' || $user->vaitro === 'gvpb') {
            return GiangVien::where('nguoidung_id', $user->id)->first();
        }

        return null;
    }


    /**
     * Hiển thị danh sách sinh viên
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = DB::table('sinhvien')
            ->leftJoin('nhom_sinhvien_chitiet', 'sinhvien.id', '=', 'nhom_sinhvien_chitiet.sinhvien_id')
            ->leftJoin('nhom_sinhvien', 'nhom_sinhvien_chitiet.nhom_sinhvien_id', '=', 'nhom_sinhvien.id')
            ->leftJoin('detai', 'nhom_sinhvien.id', '=', 'detai.nhom_sinhvien_id')
            ->leftJoin('giangvien', 'detai.giangvien_id', '=', 'giangvien.id')
            ->select(
                'sinhvien.id',
                'sinhvien.mssv',
                'sinhvien.hoten',
                'sinhvien.lop',
                'sinhvien.khoa',
                'sinhvien.email',
                DB::raw('COALESCE(nhom_sinhvien.ten_nhom, "-") as nhom'),
                DB::raw('COALESCE(giangvien.hoten, "-") as gvhd')
            );

        // ✅ Lọc theo giảng viên đăng nhập
        $giangVien = $this->currentGiangVien();
        if ($giangVien) {
            $query->where('detai.giangvien_id', $giangVien->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sinhvien.hoten', 'like', "%{$search}%")
                    ->orWhere('sinhvien.mssv', 'like', "%{$search}%");
            });
        }

        $sinhViens = $query->orderBy('sinhvien.mssv')->get();

        return view('sinhvien.index', compact('sinhViens', 'search'));
    }



    public function nhomChuaCoDeTai(Request $request)
    {
        $search = $request->get('search', '');

        // Truy vấn sinh viên chưa có đề tài (bao gồm chưa nhóm hoặc có nhóm nhưng nhóm chưa đề tài)
        $query = \App\Models\SinhVien::where(function ($q) {
            $q->whereDoesntHave('nhomSinhViens')
                ->orWhereHas('nhomSinhViens', function ($sq) {
                    $sq->whereDoesntHave('deTai');
                });
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('hoten', 'like', "%{$search}%")
                    ->orWhere('mssv', 'like', "%{$search}%");
            });
        }

        $sinhViens = $query->orderBy('mssv')->get();

        return view('sinhvien.nhom_chua_de_tai', compact('sinhViens', 'search'));
    }
    /**
     * Hiển thị form tạo sinh viên mới
     */
    public function create()
    {
        return view('sinhvien.create');
    }

    /**
     * Lưu sinh viên mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'mssv' => 'required|string|max:20|unique:sinhvien,mssv',
            'hoten' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:sinhvien,email',
            'sdt' => 'nullable|string|max:20',
            'lop' => 'nullable|string|max:50',
            'nienkhoa' => 'nullable|integer',
            'khoa' => 'nullable|string|max:100',
        ], [
            'mssv.required' => 'Mã sinh viên không được để trống',
            'mssv.unique' => 'Mã sinh viên đã tồn tại',
            'hoten.required' => 'Họ tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
        ]);

        try {
            SinhVien::create([
                'mssv' => $request->mssv,
                'hoten' => $request->hoten,
                'email' => $request->email,
                'sdt' => $request->sdt,
                'lop' => $request->lop,
                'nienkhoa' => $request->nienkhoa,
                'khoa' => $request->khoa,
                'trangthai' => 'chuaphancong',
            ]);

            return redirect()
                ->route('sinhvien.index')
                ->with('success', 'Thêm sinh viên thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form sửa sinh viên
     */
    public function edit($id)
    {
        $sinhVien = SinhVien::findOrFail($id);
        return view('sinhvien.edit', compact('sinhVien'));
    }

    /**
     * Cập nhật thông tin sinh viên
     */
    public function update(Request $request, $id)
    {
        $sinhVien = SinhVien::findOrFail($id);

        $request->validate([
            'mssv' => 'required|string|max:20|unique:sinhvien,mssv,' . $id,
            'hoten' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:sinhvien,email,' . $id,
            'sdt' => 'nullable|string|max:20',
            'lop' => 'nullable|string|max:50',
            'nienkhoa' => 'nullable|integer',
            'khoa' => 'nullable|string|max:100',
        ], [
            'mssv.required' => 'Mã sinh viên không được để trống',
            'mssv.unique' => 'Mã sinh viên đã tồn tại',
            'hoten.required' => 'Họ tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
        ]);

        try {
            $sinhVien->update([
                'mssv' => $request->mssv,
                'hoten' => $request->hoten,
                'email' => $request->email,
                'sdt' => $request->sdt,
                'lop' => $request->lop,
                'nienkhoa' => $request->nienkhoa,
                'khoa' => $request->khoa,
            ]);

            return redirect()
                ->route('sinhvien.index')
                ->with('success', 'Cập nhật sinh viên thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa sinh viên
     */
    public function destroy($id)
    {
        try {
            $sinhVien = SinhVien::findOrFail($id);

            // Xóa liên kết với nhóm trước
            DB::table('nhom_sinhvien_chitiet')
                ->where('sinhvien_id', $id)
                ->delete();

            // Xóa sinh viên
            $sinhVien->delete();

            return redirect()
                ->route('sinhvien.index')
                ->with('success', 'Xóa sinh viên thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật nhóm cho sinh viên
     */
    public function updateNhom(Request $request)
    {
        $request->validate([
            'sinhvien_id' => 'required|exists:sinhvien,id',
            'nhom_id' => 'nullable|exists:nhom_sinhvien,id',
        ]);

        try {
            $sinhVienId = $request->sinhvien_id;
            $nhomId = $request->nhom_id;

            // Xóa nhóm cũ
            DB::table('nhom_sinhvien_chitiet')
                ->where('sinhvien_id', $sinhVienId)
                ->delete();

            // Thêm vào nhóm mới (nếu có chọn)
            if ($nhomId) {
                DB::table('nhom_sinhvien_chitiet')->insert([
                    'nhom_sinhvien_id' => $nhomId,
                    'sinhvien_id' => $sinhVienId,
                    'vai_tro' => 'thanh_vien',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()
                ->route('sinhvien.index')
                ->with('success', 'Cập nhật nhóm thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý import sinh viên từ file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ], [
            'file.required' => 'Vui lòng chọn file để import',
            'file.mimes' => 'File phải có định dạng Excel (.xlsx hoặc .xls)',
            'file.max' => 'File không được vượt quá 10MB',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Bỏ qua dòng đầu tiên (header) và dòng tiêu đề
            $imported = 0;
            $skipped = 0;
            $errors = [];

            // Tìm dòng bắt đầu dữ liệu (bỏ qua header)
            $startRow = 2; // Giả sử dòng 1 là header, dòng 2 bắt đầu dữ liệu

            for ($i = $startRow; $i <= count($rows); $i++) {
                $row = $rows[$i - 1] ?? null;

                if (!$row || empty($row[1])) { // Nếu không có MASV thì bỏ qua
                    continue;
                }

                // Lấy dữ liệu từ các cột theo mẫu:
                // STT (A), MASV (B), HỌ (C), TÊN (D), LỚP (E), MAMH (F), TÊN MÔN HỌC (G), SỐ ĐIỆN THOẠI (H), EMAIL (I), GHI CHÚ (J)
                $mssv = trim($row[1] ?? ''); // Cột B - MASV
                $ho = trim($row[2] ?? ''); // Cột C - HỌ
                $ten = trim($row[3] ?? ''); // Cột D - TÊN
                $lop = trim($row[4] ?? ''); // Cột E - LỚP
                $sdt = trim($row[7] ?? ''); // Cột H - SỐ ĐIỆN THOẠI
                $email = trim($row[8] ?? ''); // Cột I - EMAIL

                // Bỏ qua nếu không có MASV
                if (empty($mssv)) {
                    continue;
                }

                // Gộp họ và tên
                $hoten = trim($ho . ' ' . $ten);

                // Kiểm tra sinh viên đã tồn tại chưa
                $existing = SinhVien::where('mssv', $mssv)->first();

                if ($existing) {
                    // Cập nhật thông tin nếu đã tồn tại
                    $existing->update([
                        'hoten' => $hoten ?: $existing->hoten,
                        'lop' => $lop ?: $existing->lop,
                        'sdt' => $sdt ?: $existing->sdt,
                        'email' => $email ?: $existing->email,
                    ]);
                    $imported++;
                } else {
                    // Tạo mới nếu chưa tồn tại
                    try {
                        SinhVien::create([
                            'mssv' => $mssv,
                            'hoten' => $hoten ?: 'Chưa có tên',
                            'lop' => $lop,
                            'sdt' => $sdt,
                            'email' => $email,
                            'trangthai' => 'chuaphancong',
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        $skipped++;
                        $errors[] = "Dòng $i (MSSV: $mssv): " . $e->getMessage();
                    }
                }
            }

            $message = "Import thành công: $imported sinh viên";
            if ($skipped > 0) {
                $message .= ", bỏ qua: $skipped sinh viên";
            }

            return redirect()
                ->route('sinhvien.index')
                ->with('success', $message)
                ->with('errors', $errors);
        } catch (\Exception $e) {
            \Log::error('SinhVienController@import - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi import file: ' . $e->getMessage());
        }
    }
}
