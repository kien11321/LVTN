<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\PhanCong;
use App\Models\DeTai;
use App\Models\NhomSinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhanCongController extends Controller
{
    /**
     * Hiển thị danh sách phân công
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            // Lấy danh sách sinh viên với bộ lọc tìm kiếm
            $query = DB::table('sinhvien')
                ->leftJoin('nhom_sinhvien_chitiet', 'sinhvien.id', '=', 'nhom_sinhvien_chitiet.sinhvien_id')
                ->leftJoin('nhom_sinhvien', 'nhom_sinhvien_chitiet.nhom_sinhvien_id', '=', 'nhom_sinhvien.id')
                ->leftJoin('detai', 'nhom_sinhvien.id', '=', 'detai.nhom_sinhvien_id')
                ->leftJoin('giangvien', 'detai.giangvien_id', '=', 'giangvien.id')
                ->select(
                    'sinhvien.id as sinhvien_id',
                    'sinhvien.mssv',
                    'sinhvien.hoten',
                    'nhom_sinhvien.id as nhom_id',
                    DB::raw('COALESCE(nhom_sinhvien.ten_nhom, "-") as nhom'),
                    'giangvien.id as giangvien_id',
                    'giangvien.hoten as giangvien_hoten',
                    DB::raw('CASE WHEN giangvien.id IS NOT NULL THEN "Đã phân công" ELSE "Chưa phân công" END as trang_thai')
                );

            // Thêm điều kiện tìm kiếm nếu có
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('sinhvien.hoten', 'like', "%{$search}%")
                        ->orWhere('sinhvien.mssv', 'like', "%{$search}%");
                });
            }
            $phanCongs = $query
                ->orderByRaw(
                    "CASE 
            WHEN nhom_sinhvien.ten_nhom IS NULL THEN 9999
            ELSE CAST(REPLACE(nhom_sinhvien.ten_nhom, 'Nhóm ', '') AS UNSIGNED)
            END"
                )->orderBy('sinhvien.mssv')->get();

            // Giữ lại các biến khác cho View
            $giangViens = GiangVien::orderBy('hoten')->get();

            return view('phancong.index', compact('phanCongs', 'giangViens', 'search'));
        } catch (\Exception $e) {
            return view('phancong.index', ['phanCongs' => collect([]), 'giangViens' => collect([])])
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật phân công giảng viên cho sinh viên
     */
    public function update(Request $request)
    {
        $request->validate([
            'sinhvien_id' => 'required|exists:sinhvien,id',
            'giangvien_id' => 'required|exists:giangvien,id',
        ]);

        try {
            DB::beginTransaction();

            $sinhVienId = $request->sinhvien_id;
            $giangVienId = $request->giangvien_id;

            // Tìm nhóm của sinh viên
            $nhomChiTiet = DB::table('nhom_sinhvien_chitiet')
                ->where('sinhvien_id', $sinhVienId)
                ->first();

            if (!$nhomChiTiet) {
                return redirect()
                    ->route('phancong.index')
                    ->with('error', 'Sinh viên này chưa thuộc nhóm nào!');
            }

            // Tìm đề tài của nhóm (nếu có)
            $deTai = DeTai::where('nhom_sinhvien_id', $nhomChiTiet->nhom_sinhvien_id)->first();

            if ($deTai) {
                // Nhóm đã có đề tài -> cập nhật giảng viên
                $deTai->giangvien_id = $giangVienId;
                $deTai->save();
            } else {
                // Nhóm chưa có đề tài -> tạo đề tài tạm để lưu giảng viên
                $nhom = NhomSinhVien::find($nhomChiTiet->nhom_sinhvien_id);
                $tenNhom = $nhom?->ten_nhom ?: 'chưa đặt tên';

                $deTai = DeTai::create([
                    'ten_detai' =>   /*$tenNhom .*/ 'Chưa cập nhật tên đề tài',
                    'mo_ta' => 'Đề tài tạm được tạo khi phân công giảng viên trước',
                    'giangvien_id' => $giangVienId,
                    'nhom_sinhvien_id' => $nhomChiTiet->nhom_sinhvien_id,
                    'loai' => 'nhom',
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['ok' => true]);
            }

            return redirect()
                ->route('phancong.index')
                ->with('success', 'Phân công giảng viên hướng dẫn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
            }

            return redirect()->route('phancong.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật nhóm cho sinh viên (tự động tạo nhóm mới nếu cần)
     */
    public function updateNhom(Request $request)
    {
        $request->validate([
            'sinhvien_id' => 'required|exists:sinhvien,id',
            'nhom_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $sinhVienId = $request->sinhvien_id;
            $nhomId = $request->nhom_id;

            // Nếu chọn "Tạo nhóm mới"
            if ($nhomId === 'new') {
                // Tự động tạo nhóm mới với tên "Nhóm X" (X là số tiếp theo)
                $allNhoms = DB::table('nhom_sinhvien')
                    ->where('ten_nhom', 'like', 'Nhóm %')
                    ->get();

                $maxSo = 0;
                foreach ($allNhoms as $nhom) {
                    $so = (int) str_replace('Nhóm ', '', $nhom->ten_nhom);
                    if ($so > $maxSo) {
                        $maxSo = $so;
                    }
                }

                $soNhomMoi = $maxSo + 1;
                $tenNhomMoi = "Nhóm {$soNhomMoi}";

                // Tạo nhóm mới
                $nhomMoiId = DB::table('nhom_sinhvien')->insertGetId([
                    'ten_nhom' => $tenNhomMoi,
                    'truong_nhom_id' => $sinhVienId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $nhomId = $nhomMoiId;
            }

            $nhomCuId = DB::table('nhom_sinhvien_chitiet')
                ->where('sinhvien_id', $sinhVienId)
                ->value('nhom_sinhvien_id');


            // Xóa nhóm cũ của sinh viên
            DB::table('nhom_sinhvien_chitiet')
                ->where('sinhvien_id', $sinhVienId)
                ->delete();

            if ($nhomCuId) {
                $soThanhVienConLai = DB::table('nhom_sinhvien_chitiet')
                    ->where('nhom_sinhvien_id', $nhomCuId)
                    ->count();

                if ($soThanhVienConLai == 0) {
                    // Bỏ gán đề tài của nhóm cũ
                    DB::table('detai')
                        ->where('nhom_sinhvien_id', $nhomCuId)
                        ->update(['nhom_sinhvien_id' => null]);
                }
            }


            // Kiểm tra nhóm đã có trưởng nhóm chưa
            $nhom = DB::table('nhom_sinhvien')->find($nhomId);
            $isTruongNhom = false;

            if ($request->nhom_id === 'new') {
                // Nhóm mới -> sinh viên này là trưởng nhóm
                $isTruongNhom = true;
            } else {
                // Nhóm cũ -> kiểm tra đã có trưởng nhóm chưa
                $hasTruongNhom = $nhom && $nhom->truong_nhom_id;
                if (!$hasTruongNhom) {
                    $isTruongNhom = true;
                }
            }

            // Thêm sinh viên vào nhóm
            DB::table('nhom_sinhvien_chitiet')->insert([
                'nhom_sinhvien_id' => $nhomId,
                'sinhvien_id' => $sinhVienId,
                'vai_tro' => $isTruongNhom ? 'truong_nhom' : 'thanh_vien',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Cập nhật trưởng nhóm nếu cần
            if ($isTruongNhom) {
                DB::table('nhom_sinhvien')
                    ->where('id', $nhomId)
                    ->update(['truong_nhom_id' => $sinhVienId]);
            }

            DB::commit();

            return redirect()
                ->route('phancong.index')
                ->with('success', 'Cập nhật nhóm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('phancong.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function updateNhomBulk(Request $request)
    {
        $request->validate([
            'so_nhom' => 'array', // so_nhom[SV_ID] => number
        ]);

        $data = $request->input('so_nhom', []); // mảng [sinhvien_id => so_nhom]

        try {
            DB::beginTransaction();

            foreach ($data as $sinhVienId => $soNhom) {
                // bỏ qua ô trống
                if ($soNhom === null || $soNhom === '') continue;

                $sinhVienId = (int) $sinhVienId;
                $soNhom = (int) $soNhom;

                if ($soNhom < 1) continue;

                $tenNhom = "Nhóm {$soNhom}";

                // tìm nhóm theo tên
                $nhomId = DB::table('nhom_sinhvien')
                    ->where('ten_nhom', $tenNhom)
                    ->value('id');

                // chưa có thì tạo
                if (!$nhomId) {
                    $nhomId = DB::table('nhom_sinhvien')->insertGetId([
                        'ten_nhom' => $tenNhom,
                        'truong_nhom_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // lấy nhóm cũ
                $nhomCuId = DB::table('nhom_sinhvien_chitiet')
                    ->where('sinhvien_id', $sinhVienId)
                    ->value('nhom_sinhvien_id');

                // nếu đã ở đúng nhóm rồi thì bỏ qua
                if ($nhomCuId == $nhomId) continue;

                // xóa nhóm cũ của SV
                DB::table('nhom_sinhvien_chitiet')
                    ->where('sinhvien_id', $sinhVienId)
                    ->delete();

                // nếu nhóm cũ rỗng -> bỏ gán đề tài (giữ logic giống bạn)
                if ($nhomCuId) {
                    $soThanhVienConLai = DB::table('nhom_sinhvien_chitiet')
                        ->where('nhom_sinhvien_id', $nhomCuId)
                        ->count();

                    if ($soThanhVienConLai == 0) {
                        DB::table('detai')
                            ->where('nhom_sinhvien_id', $nhomCuId)
                            ->update(['nhom_sinhvien_id' => null]);
                    }
                }

                // kiểm tra trưởng nhóm: nếu nhóm chưa có trưởng -> set SV này làm trưởng
                $nhom = DB::table('nhom_sinhvien')->find($nhomId);
                $isTruongNhom = ($nhom && !$nhom->truong_nhom_id);

                DB::table('nhom_sinhvien_chitiet')->insert([
                    'nhom_sinhvien_id' => $nhomId,
                    'sinhvien_id' => $sinhVienId,
                    'vai_tro' => $isTruongNhom ? 'truong_nhom' : 'thanh_vien',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($isTruongNhom) {
                    DB::table('nhom_sinhvien')
                        ->where('id', $nhomId)
                        ->update(['truong_nhom_id' => $sinhVienId]);
                }
            }

            DB::commit();

            return redirect()->route('phancong.index')
                ->with('success', 'Đã lưu nhóm cho các sinh viên!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('phancong.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
