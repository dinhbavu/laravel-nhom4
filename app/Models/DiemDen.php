<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemDen extends Model
{
    protected $table = 'diem_den';

    protected $fillable = [
        'ten_diem_den', 'tinh_thanh', 'vung_mien', 'mo_ta', 'hinh_anh', 'trang_thai',
    ];

    protected $casts = ['trang_thai' => 'boolean'];

    public function tour()
    {
        return $this->hasMany(Tour::class, 'diem_den_id');
    }

    public function getVungMienTenAttribute()
    {
        return match($this->vung_mien) {
            'mien_bac' => 'Miền Bắc',
            'mien_trung' => 'Miền Trung',
            'mien_nam' => 'Miền Nam',
            default => '',
        };
    }

    public function getHinhAnhUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('uploads/diem-den/' . $this->hinh_anh);
        }
        return asset('images/destination-default.jpg');
    }
}
