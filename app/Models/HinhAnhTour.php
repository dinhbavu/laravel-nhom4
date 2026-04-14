<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhTour extends Model
{
    protected $table = 'hinh_anh_tour';
    public $timestamps = false;

    protected $fillable = ['tour_id', 'duong_dan', 'thu_tu'];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function getUrlAttribute()
    {
        return asset('uploads/tours/' . $this->duong_dan);
    }
}
