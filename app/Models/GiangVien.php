<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    protected $table = 'giangvien';

    protected $fillable = [
        'nguoidung_id',
        'magv',
        'hoten',
        'email',
        'sdt',
        'bo_mon',
    ];
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id');
    }
}
