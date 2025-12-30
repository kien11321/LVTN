<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheoDoiTienDo extends Model
{
    protected $table = 'theo_doi_tien_do';
    
    protected $fillable = [
        'nhom_sinhvien_id',
        'detai_id',
        'tien_do',
        'quyet_dinh',
        'ghi_chu',
        'giangvien_id',
        'ngay_cap_nhat',
    ];

    protected $casts = [
        'tien_do' => 'integer',
        'ngay_cap_nhat' => 'datetime',
    ];

    public function nhomSinhVien()
    {
        return $this->belongsTo(NhomSinhVien::class, 'nhom_sinhvien_id');
    }

    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'detai_id');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giangvien_id');
    }
}







