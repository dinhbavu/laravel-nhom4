<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichTrinhTour extends Model
{
    protected $table = 'lich_trinh_tour';
    public $timestamps = false;

    protected $fillable = [
        'tour_id', 'ngay_thu', 'tieu_de', 'noi_dung', 'bua_an', 'khach_san',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
