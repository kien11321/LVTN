<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoidung';

    protected $fillable = [
        'hoten',
        'email',
        'matkhau',
        'sdt',
        'vaitro',
    ];

    protected $hidden = [
        'matkhau',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getAuthPassword()
    {
        return $this->matkhau;
    }

    public function getNameAttribute()
    {
        return $this->hoten;
    }

    public function getPasswordAttribute()
    {
        return $this->matkhau;
    }

    public function giangVien()
    {
        return $this->hasOne(GiangVien::class, 'nguoidung_id');
    }
}
