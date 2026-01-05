<?php

namespace App\Http\Controllers;

use App\Models\DeTai;
use App\Models\NhomSinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhanCongDeTaiController extends Controller
{
    /**
     * Helper: Lấy giảng viên theo user đăng nhập (nếu user là giảng viên)
     */
    private function currentGiangVien()
    {
        $user = Auth::user();
        if (!$user) return null;

        if ($user->vaitro === 'gvhd' || $user->vaitro === 'giangvien') {
            return GiangVien::where('nguoidung_id', $user->id)->first();
        }

        return null; // không phải giảng viên
    }

    /**
     * Hiển thị trang phân công đề tài cho nhóm
     * - Giảng viên: chỉ thấy nhóm+đề tài thuộc giảng viên đó
     * - Admin/role khác: thấy tất cả
     */
    public function index()
    {
        $user = Auth::user();
        $giangVien = $this->currentGiangVien();

        // GIẢNG VIÊN: chỉ xem nhóm có thành viên + có đề tài thuộc mình
        if ($giangVien) {
            $allNhomSinhViens = NhomSinhVien::with(['deTai', 'truongNhom', 'sinhViens'])
                ->whereHas('sinhViens')
                ->whereHas('deTai', function ($q) use ($giangVien) {
                    $q->where('giangvien_id', $giangVien->id);
                })
                ->get();

            $nhomDeTaiMap = [];
            foreach ($allNhomSinhViens as $nhom) {
                if ($nhom->deTai) {
                    $nhomDeTaiMap[$nhom->id] = $nhom->deTai->ten_detai;
                }
            }

            return view('phancong-detai.index', [
                'nhomSinhViens' => $allNhomSinhViens,
                'allNhomSinhViens' => $allNhomSinhViens,
                'nhomDeTaiMap' => $nhomDeTaiMap
            ]);
        }

        // ADMIN/ROLE KHÁC: giữ logic như cũ
        $nhomSinhViens = NhomSinhVien::with(['deTai', 'truongNhom', 'sinhViens'])
            ->whereHas('sinhViens')
            ->whereDoesntHave('deTai')
            ->get();

        $allNhomSinhViens = NhomSinhVien::with(['deTai', 'truongNhom', 'sinhViens'])
            ->whereHas('sinhViens')
            ->get();

        $nhomDeTaiMap = [];
        foreach ($allNhomSinhViens as $nhom) {
            if ($nhom->deTai) {
                $nhomDeTaiMap[$nhom->id] = $nhom->deTai->ten_detai;
            }
        }

        return view('phancong-detai.index', [
            'nhomSinhViens' => $allNhomSinhViens,
            'allNhomSinhViens' => $allNhomSinhViens,
            'nhomDeTaiMap' => $nhomDeTaiMap
        ]);
    }

    /**
     * Hiển thị form phân công đề tài cho nhóm cụ thể
     * - Giảng viên: chỉ chọn đề tài của mình
     * - Admin: chọn mọi đề tài trống
     */
    public function create($nhomId)
    {
        $nhom = NhomSinhVien::with(['sinhViens', 'truongNhom', 'deTai'])->findOrFail($nhomId);

        // Nhóm đã có đề tài thì không cho tạo mới ở route này
        if ($nhom->deTai) {
            return redirect()
                ->route('phancong-detai.index')
                ->with('error', 'Nhóm này đã được phân công đề tài!');
        }

        $giangVien = $this->currentGiangVien();

        $query = DeTai::query()
            ->with('giangVien')
            ->whereNull('nhom_sinhvien_id');

        // Giảng viên: chỉ thấy đề tài của mình
        if ($giangVien) {
            $query->where('giangvien_id', $giangVien->id);
        }

        $deTais = $query->orderBy('ten_detai')->get();

        return view('phancong-detai.create', compact('nhom', 'deTais'));
    }

    /**
     *  tìm kiếm đề tài theo tên
     * - Giảng viên: chỉ tìm trong đề tài của mình
     */
    public function searchDeTai(Request $request)
    {
        $keyword = $request->input('q', '');
        $giangVien = $this->currentGiangVien();

        $query = DeTai::query()
            ->with('giangVien')
            ->whereNull('nhom_sinhvien_id');

        if ($giangVien) {
            $query->where('giangvien_id', $giangVien->id);
        }

        if ($keyword) {
            $query->where('ten_detai', 'LIKE', '%' . $keyword . '%');
        }

        $deTais = $query->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $deTais->map(function ($deTai) {
                return [
                    'id' => $deTai->id,
                    'ten_detai' => $deTai->ten_detai,
                    'mo_ta' => $deTai->mo_ta,
                    'giangvien' => $deTai->giangVien ? $deTai->giangVien->hoten : 'Chưa có',
                    'loai' => $deTai->loai,
                ];
            })
        ]);
    }

    /**
     * Lưu phân công đề tài đơn giản (chọn nhóm + nhập tên)
     * - Giảng viên: tạo đề tài mới sẽ gắn giangvien_id = mình
     */
    public function storeSimple(Request $request)
    {
        Log::info('PhanCongDeTai storeSimple - Request data:', $request->all());

        $request->validate([
            'nhom_id' => 'required|exists:nhom_sinhvien,id',
            'ten_detai' => 'required|string|max:255',
        ], [
            'nhom_id.required' => 'Vui lòng chọn nhóm!',
            'nhom_id.exists' => 'Nhóm không tồn tại!',
            'ten_detai.required' => 'Vui lòng nhập tên đề tài!',
            'ten_detai.max' => 'Tên đề tài không được vượt quá 255 ký tự!',
        ]);

        try {
            DB::beginTransaction();

            $nhom = NhomSinhVien::with('sinhViens')->findOrFail($request->nhom_id);

            if ($nhom->sinhViens->isEmpty()) {
                DB::rollBack();
                return back()
                    ->withInput()
                    ->with('error', 'Nhóm này chưa có thành viên! Vui lòng thêm thành viên trước khi phân công.');
            }

            $giangVien = $this->currentGiangVien();

            // Nếu nhóm đã có đề tài -> chỉ cho update nếu đề tài thuộc giảng viên hiện tại (nếu là GV)
            $existingDeTai = DeTai::where('nhom_sinhvien_id', $nhom->id)->first();

            if ($existingDeTai) {
                if ($giangVien && $existingDeTai->giangvien_id != $giangVien->id) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Bạn không có quyền sửa đề tài của giảng viên khác!');
                }

                $existingDeTai->ten_detai = $request->ten_detai;
                $existingDeTai->mo_ta = 'Đề tài được cập nhật từ form phân công';
                $existingDeTai->save();
            } else {
                // tạo mới -> giảng viên thì gắn theo mình, admin thì lấy giảng viên đầu tiên 
                $giangVienId = null;

                if ($giangVien) {
                    $giangVienId = $giangVien->id;
                } else {
                    $firstGiangVien = GiangVien::first();
                    if (!$firstGiangVien) {
                        DB::rollBack();
                        throw new \Exception('Không tìm thấy giảng viên nào trong hệ thống!');
                    }
                    $giangVienId = $firstGiangVien->id;
                }

                DeTai::create([
                    'ten_detai' => $request->ten_detai,
                    'mo_ta' => 'Đề tài được tạo từ form phân công',
                    'giangvien_id' => $giangVienId,
                    'loai' => 'nhom',
                    'nhom_sinhvien_id' => $nhom->id,
                ]);
            }

            DB::commit();

            $message = ($existingDeTai ? 'Cập nhật đề tài thành công!' : 'Phân công đề tài thành công!');

            return redirect()
                ->route('phancong-detai.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi phân công đề tài: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Lưu phân công đề tài (chọn nhóm + chọn đề tài)
     * - Giảng viên: chỉ được phân công đề tài của mình
     */
    public function store(Request $request)
    {
        $request->validate([
            'nhom_sinhvien_id' => 'required|exists:nhom_sinhvien,id',
            'detai_id' => 'required|exists:detai,id',
        ]);

        try {
            DB::beginTransaction();

            $nhom = NhomSinhVien::with('deTai')->findOrFail($request->nhom_sinhvien_id);
            $deTai = DeTai::findOrFail($request->detai_id);

            // nhóm đã có đề tài
            if ($nhom->deTai) {
                DB::rollBack();
                return redirect()
                    ->route('phancong-detai.index')
                    ->with('error', 'Nhóm này đã được phân công đề tài!');
            }

            // đề tài đã có nhóm
            if ($deTai->nhom_sinhvien_id) {
                DB::rollBack();
                return back()->with('error', 'Đề tài này đã được phân cho nhóm khác!');
            }

            // check quyền giảng viên
            $giangVien = $this->currentGiangVien();
            if ($giangVien && $deTai->giangvien_id != $giangVien->id) {
                DB::rollBack();
                return back()->with('error', 'Bạn không có quyền phân công đề tài của giảng viên khác!');
            }

            $deTai->nhom_sinhvien_id = $nhom->id;
            $deTai->save();

            DB::commit();

            return redirect()
                ->route('phancong-detai.index')
                ->with('success', 'Phân công đề tài thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hủy phân công đề tài
     * - Giảng viên: chỉ hủy nếu đề tài thuộc mình
     */
    public function destroy($nhomId)
    {
        try {
            DB::beginTransaction();

            $nhom = NhomSinhVien::with('deTai')->findOrFail($nhomId);

            if (!$nhom->deTai) {
                DB::rollBack();
                return redirect()
                    ->route('phancong-detai.index')
                    ->with('error', 'Nhóm này chưa được phân công đề tài!');
            }

            $deTai = $nhom->deTai;

            $giangVien = $this->currentGiangVien();
            if ($giangVien && $deTai->giangvien_id != $giangVien->id) {
                DB::rollBack();
                return redirect()
                    ->route('phancong-detai.index')
                    ->with('error', 'Bạn không có quyền hủy đề tài của giảng viên khác!');
            }

            $deTai->nhom_sinhvien_id = null;
            $deTai->save();

            DB::commit();

            return redirect()
                ->route('phancong-detai.index')
                ->with('success', 'Đã hủy phân công đề tài!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách đề tài
     * - Giảng viên: chỉ thấy đề tài của mình
     */
    public function danhSachDeTai()
    {
        $giangVien = $this->currentGiangVien();

        $query = DeTai::query()->with(['giangVien', 'nhomSinhVien']);

        if ($giangVien) {
            $query->where('giangvien_id', $giangVien->id);
        }

        $deTais = $query->orderByDesc('created_at')->get();

        return view('phancong-detai.danh-sach', compact('deTais'));
    }

    /**
     * Form tạo đề tài mới
     * - Nếu là giảng viên: chỉ cần tạo cho chính mình (không cần dropdown giảng viên)
     * - Admin: vẫn cho chọn giảng viên
     */
    public function createDeTai()
    {
        $giangVien = $this->currentGiangVien();

        // giảng viên -> không cần list
        if ($giangVien) {
            $giangViens = collect([$giangVien]);
            return view('phancong-detai.create-detai', compact('giangViens'));
        }

        // admin -> lấy tất cả
        $giangViens = GiangVien::orderBy('hoten')->get();
        return view('phancong-detai.create-detai', compact('giangViens'));
    }

    /**
     * Lưu đề tài mới
     * - Giảng viên:  giangvien_id = mình
     */
    public function storeDeTai(Request $request)
    {
        $giangVien = $this->currentGiangVien();

        $rules = [
            'ten_detai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai' => 'required|in:ca_nhan,nhom',
        ];

        // admin mới cần validate giangvien_id
        if (!$giangVien) {
            $rules['giangvien_id'] = 'required|exists:giangvien,id';
        }

        $request->validate($rules);

        try {
            $giangVienId = $giangVien ? $giangVien->id : $request->giangvien_id;

            DeTai::create([
                'ten_detai' => $request->ten_detai,
                'mo_ta' => $request->mo_ta,
                'giangvien_id' => $giangVienId,
                'loai' => $request->loai,
            ]);

            return redirect()
                ->route('phancong-detai.danh-sach')
                ->with('success', 'Tạo đề tài mới thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
