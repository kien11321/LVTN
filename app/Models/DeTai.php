<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ChamDiemPhanBien;

class DeTai extends Model
{
    protected $table = 'detai';

    protected $fillable = [
        'ten_detai',
        'mo_ta',
        'giangvien_id',
        'giangvien_phanbien_id',
        'nhom_sinhvien_id',
        'hoi_dong_id',
        'loai',
    ];

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giangvien_id');
    }

    public function giangVienPhanBien()
    {
        return $this->belongsTo(GiangVien::class, 'giangvien_phanbien_id');
    }

    public function nhomSinhVien()
    {
        return $this->belongsTo(NhomSinhVien::class, 'nhom_sinhvien_id');
    }

    public function phanCongs()
    {
        return $this->hasMany(PhanCong::class, 'detai_id');
    }

    public function hoiDong()
    {
        return $this->belongsTo(HoiDong::class, 'hoi_dong_id');
    }



    public function chamDiemPhanBiens()
    {
        return $this->hasMany(ChamDiemPhanBien::class, 'detai_id');
    }
}
