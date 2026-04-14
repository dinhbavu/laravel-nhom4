<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuThich extends Model
{
    protected $table = 'yeu_thich';
    public $timestamps = false;

    protected $fillable = ['khach_hang_id', 'tour_id'];

    protected $casts = ['created_at' => 'datetime'];

    public function khachHang()
    {
        return $this->belongsTo(NguoiDung::class, 'khach_hang_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
