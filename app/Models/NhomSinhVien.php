<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhomSinhVien extends Model
{
    protected $table = 'nhom_sinhvien';
    
    protected $fillable = [
        'ten_nhom',
        'truong_nhom_id',
    ];

    public function sinhViens()
    {
        return $this->belongsToMany(
            SinhVien::class,
            'nhom_sinhvien_chitiet',
            'nhom_sinhvien_id',
            'sinhvien_id'
        );
    }

    public function truongNhom()
    {
        return $this->belongsTo(SinhVien::class, 'truong_nhom_id');
    }

    public function deTai()
    {
        return $this->hasOne(DeTai::class, 'nhom_sinhvien_id');
    }

    public function theoDoiTienDo()
    {
        return $this->hasOne(TheoDoiTienDo::class, 'nhom_sinhvien_id');
    }
}




