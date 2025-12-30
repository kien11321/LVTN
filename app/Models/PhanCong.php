<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanCong extends Model
{
    protected $table = 'phancong';
    
    protected $fillable = [
        'detai_id',
        'giang_vien_id',
        'nguoi_phan_cong_id',
        'ngay_phan_cong',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_phan_cong' => 'datetime',
    ];

    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'detai_id');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giang_vien_id');
    }
}













