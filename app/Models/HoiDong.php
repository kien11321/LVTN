<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoiDong extends Model
{
    protected $table = 'hoi_dong';
    
    protected $fillable = [
        'ten_hoi_dong',
        'ngay_bao_ve',
        'phong_bao_ve',
        'chu_tich_id',
        'thu_ky_id',
        'uy_vien_1_id',
        'uy_vien_2_id',
    ];

    protected $casts = [
        'ngay_bao_ve' => 'date',
    ];

    // Relationships
    public function chuTich()
    {
        return $this->belongsTo(GiangVien::class, 'chu_tich_id');
    }

    public function thuKy()
    {
        return $this->belongsTo(GiangVien::class, 'thu_ky_id');
    }

    public function uyVien1()
    {
        return $this->belongsTo(GiangVien::class, 'uy_vien_1_id');
    }

    public function uyVien2()
    {
        return $this->belongsTo(GiangVien::class, 'uy_vien_2_id');
    }

    public function deTais()
    {
        return $this->hasMany(DeTai::class, 'hoi_dong_id');
    }
}
