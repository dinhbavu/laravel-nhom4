<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuDangNhap extends Model
{
    protected $table = 'lich_su_dang_nhap';

    protected $fillable = [
        'khach_hang_id',
        'ip_address',
        'user_agent',
    ];

    public function khachHang()
    {
        return $this->belongsTo(NguoiDung::class, 'khach_hang_id');
    }
}
