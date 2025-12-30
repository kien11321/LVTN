<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        // Query để lấy sinh viên với thông tin nhóm và giảng viên hướng dẫn
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

        // Tìm kiếm
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('sinhvien.hoten', 'like', "%{$search}%")
                  ->orWhere('sinhvien.mssv', 'like', "%{$search}%");
            });
        }

        $sinhViens = $query->orderBy('sinhvien.mssv')->get();

        return view('dashboard', compact('sinhViens', 'search'));
    }
}

