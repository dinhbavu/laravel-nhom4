<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HanhKhach extends Model
{
    protected $table = 'hanh_khach';
    public $timestamps = false;

    protected $fillable = [
        'dat_tour_id', 'ho_ten', 'ngay_sinh', 'gioi_tinh',
        'so_cmnd', 'so_dien_thoai', 'loai',
    ];

    protected $casts = ['ngay_sinh' => 'date'];

    public function datTour()
    {
        return $this->belongsTo(DatTour::class, 'dat_tour_id');
    }
}
