<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichKhoiHanh extends Model
{
    protected $table = 'lich_khoi_hanh';

    protected $fillable = [
        'tour_id', 'ngay_di', 'ngay_ve', 'so_cho_toi_da',
        'so_cho_con', 'gia_nguoi_lon', 'gia_tre_em', 'trang_thai',
    ];

    protected $casts = [
        'ngay_di' => 'date',
        'ngay_ve' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function datTour()
    {
        return $this->hasMany(DatTour::class, 'lich_khoi_hanh_id');
    }

    public function getGiaNguoiLonHienTaiAttribute()
    {
        return $this->gia_nguoi_lon ?? $this->tour->gia_nguoi_lon;
    }

    public function getGiaTreEmHienTaiAttribute()
    {
        return $this->gia_tre_em ?? $this->tour->gia_tre_em;
    }

    public function scopeConCho($query)
    {
        return $query->where('trang_thai', 'con_cho')->where('so_cho_con', '>', 0);
    }
}
