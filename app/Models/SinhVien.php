<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'sinhvien';

    // Bảng sinhvien chỉ có created_at, không có updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'nguoidung_id',
        'mssv',
        'hoten',
        'email',
        'sdt',
        'lop',
        'nienkhoa',
        'khoa',
        'trangthai',
    ];

    protected $casts = [
        'nienkhoa' => 'integer',
        'created_at' => 'datetime',
    ];

    // Quan hệ với giảng viên hướng dẫn (qua nhóm và phân công)
    public function giangVienHuongDan()
    {
        // Cần join qua nhom_sinhvien_chitiet -> nhom_sinhvien -> phancong -> giangvien
        // Tạm thời return null, sẽ implement sau
        return null;
    }
    public function nhomSinhViens()
    {
        // Giả sử bạn dùng bảng trung gian nhom_sinhvien_chitiet
        return $this->belongsToMany(NhomSinhVien::class, 'nhom_sinhvien_chitiet', 'sinhvien_id', 'nhom_sinhvien_id');
    }

    // Quan hệ với nhóm sinh viên
    public function nhomSinhVien()
    {
        return $this->belongsToMany(
            NhomSinhVien::class,
            'nhom_sinhvien_chitiet',
            'sinhvien_id',
            'nhom_sinhvien_id'
        );
    }
}
