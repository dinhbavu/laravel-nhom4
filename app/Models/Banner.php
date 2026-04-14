<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'tieu_de',
        'hinh_anh_url',
        'duong_dan',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    public function getHinhAnhUrlAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return asset('uploads/banners/' . $value);
    }
}
