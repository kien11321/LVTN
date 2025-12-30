<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhapDiemBaoVe extends Model
{
    protected $table = 'nhap_diem_bao_ve';

    protected $fillable = [
        'detai_id',
        'sinhvien_id',
        'diem_bao_ve',
        'diem_gv',
        'diem_tong',
        'trang_thai',
    ];

    protected $casts = [
        'diem_bao_ve' => 'decimal:2',
        'diem_gv' => 'decimal:2',
        'diem_tong' => 'decimal:2',
    ];

    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'detai_id');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sinhvien_id');
    }
}
