<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'danh_gia';

    protected $fillable = [
        'tour_id', 'khach_hang_id', 'dat_tour_id',
        'diem_so', 'tieu_de', 'noi_dung', 'hinh_anh', 'trang_thai',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function khachHang()
    {
        return $this->belongsTo(NguoiDung::class, 'khach_hang_id');
    }

    public function datTour()
    {
        return $this->belongsTo(DatTour::class, 'dat_tour_id');
    }
}
