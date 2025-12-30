<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamDiemHuongDan extends Model
{
    const DE_NGHI_DUOC = 'duoc_bao_ve';
    const DE_NGHI_KHONG = 'khong_bao_ve';
    const DE_NGHI_BO_SUNG = 'bo_sung';
    protected $table = 'cham_diem_huong_dan';

    protected $fillable = [
        'detai_id',
        'sinhvien_id',
        'giangvien_id',
        'phan_tich',
        'thiet_ke',
        'hien_thuc',
        'bao_cao',
        'tong_phan_tram',
        'diem_10',
        'ghi_chu',
        'noi_dung_dieu_chinh',
        'nhan_xet_tong_quat',
        'thuyet_minh',
        'uu_diem',
        'thieu_sot',
        'cau_hoi',
        'de_nghi',
    ];

    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'detai_id');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sinhvien_id');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giangvien_id');
    }
}
