<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GiangVienController extends Controller
{
    /**
     * Danh sách giảng viên
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        try {
            $query = DB::table('giangvien');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('hoten', 'like', "%{$search}%")
                        ->orWhere('magv', 'like', "%{$search}%");
                });
            }

            $giangViens = $query->orderBy('magv')->get();

            // Nếu muốn hiển thị vai trò thật từ nguoidung thì join luôn:
            // (mình giữ tạm vaitro mặc định như bạn đang làm)
            foreach ($giangViens as $gv) {
                $gv->vaitro = 'huongdan';
            }

            return view('giangvien.index', compact('giangViens', 'search'));
        } catch (\Exception $e) {
            Log::error('Error loading giangvien: ' . $e->getMessage());
            $giangViens = collect([]);

            return view('giangvien.index', compact('giangViens', 'search'))
                ->with('error', 'Có lỗi xảy ra khi tải dữ liệu: ' . $e->getMessage());
        }
    }

    /**
     * Form thêm
     */
    public function create()
    {
        return view('giangvien.create');
    }

    /**
     * Thêm giảng viên + tạo luôn tài khoản nguoidung 
     */
    public function store(Request $request)
    {
        $request->validate([
            'magv'   => 'required|string|max:20|unique:giangvien,magv',
            'hoten'  => 'required|string|max:100',
            'email'  => 'nullable|email|max:100|unique:nguoidung,email',
            'sdt'    => 'nullable|string|max:20',
            'bo_mon' => 'nullable|string|max:100',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 1) Tạo tài khoản trong bảng nguoidung
                $nguoiDung = NguoiDung::create([
                    'hoten'   => $request->hoten,
                    'email'   => $request->email,
                    'matkhau' => Hash::make('123456'),
                    'sdt'     => $request->sdt,
                    'vaitro'  => 'giangvien',
                ]);

                // 2) Tạo giảng viên + liên kết nguoidung_id
                GiangVien::create([
                    'nguoidung_id' => $nguoiDung->id,
                    'magv'         => $request->magv,
                    'hoten'        => $request->hoten,
                    'email'        => $request->email,
                    'sdt'          => $request->sdt,
                    'bo_mon'       => $request->bo_mon,
                ]);
            });

            return redirect()
                ->route('giangvien.index')
                ->with('success', 'Thêm giảng viênthành công!');
        } catch (\Exception $e) {
            Log::error('Error creating giangvien/nguoidung: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi khi thêm giảng viên: ' . $e->getMessage());
        }
    }

    /**
     * Form sửa
     */
    public function edit($id)
    {
        $giangVien = GiangVien::findOrFail($id);
        return view('giangvien.edit', compact('giangVien'));
    }

    /**
     * Cập nhật giảng viên + đồng bộ sang nguoidung nếu có liên kết
     */
    public function update(Request $request, $id)
    {
        $giangVien = GiangVien::findOrFail($id);

        // Nếu giảng viên đã có nguoidung_id thì cho phép unique email ở nguoidung (trừ chính nó)
        $nguoiDungId = $giangVien->nguoidung_id;

        $request->validate([
            'magv'   => 'required|string|max:20|unique:giangvien,magv,' . $id,
            'hoten'  => 'required|string|max:100',
            'email'  => 'nullable|email|max:100' . ($nguoiDungId ? '|unique:nguoidung,email,' . $nguoiDungId : ''),
            'sdt'    => 'nullable|string|max:20',
            'bo_mon' => 'nullable|string|max:100',
        ]);

        try {
            DB::transaction(function () use ($request, $giangVien, $nguoiDungId) {

                // update bảng giangvien
                $giangVien->update([
                    'magv'   => $request->magv,
                    'hoten'  => $request->hoten,
                    'email'  => $request->email,
                    'sdt'    => $request->sdt,
                    'bo_mon' => $request->bo_mon,
                ]);

                // update bảng nguoidung (đồng bộ)
                if ($nguoiDungId) {
                    $nguoiDung = NguoiDung::find($nguoiDungId);
                    if ($nguoiDung) {
                        $nguoiDung->update([
                            'hoten'  => $request->hoten,
                            'email'  => $request->email,
                            'sdt'    => $request->sdt,
                            // vaitro giữ nguyên
                        ]);
                    }
                }
            });

            return redirect()
                ->route('giangvien.index')
                ->with('success', 'Cập nhật giảng viên thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating giangvien/nguoidung: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi khi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Xóa giảng viên + xóa luôn tài khoản nguoidung liên kết (nếu có)
     */
    public function destroy($id)
    {
        $giangVien = GiangVien::findOrFail($id);

        try {
            DB::transaction(function () use ($giangVien) {
                $nguoiDungId = $giangVien->nguoidung_id;

                $giangVien->delete();

                if ($nguoiDungId) {
                    NguoiDung::where('id', $nguoiDungId)->delete();
                }
            });

            return redirect()
                ->route('giangvien.index')
                ->with('success', 'Xóa giảng viên thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting giangvien/nguoidung: ' . $e->getMessage());

            return redirect()
                ->route('giangvien.index')
                ->with('error', 'Có lỗi khi xóa: ' . $e->getMessage());
        }
    }
}
