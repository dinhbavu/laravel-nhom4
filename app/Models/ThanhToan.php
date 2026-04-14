<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanh_toan';

    protected $fillable = [
        'dat_tour_id', 'so_tien', 'phuong_thuc', 'ma_giao_dich', 'trang_thai', 'ghi_chu',
    ];

    public function datTour()
    {
        return $this->belongsTo(DatTour::class, 'dat_tour_id');
    }

    public function getPhuongThucTenAttribute()
    {
        return match($this->phuong_thuc) {
            'vnpay' => 'VNPay',
            'momo' => 'Ví MoMo',
            'zalopay' => 'ZaloPay',
            'cod' => 'Thanh Toán Khi Nhận Tour',
            'chuyen_khoan' => 'Chuyển Khoản Ngân Hàng',
            'tien_mat' => 'Tại Văn Phòng',
            default => 'Khác',
        };
    }
}
