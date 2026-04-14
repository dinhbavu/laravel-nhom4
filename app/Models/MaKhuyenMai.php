<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaKhuyenMai extends Model
{
    protected $table = 'ma_khuyen_mai';

    protected $fillable = [
        'ma', 'loai', 'gia_tri', 'gia_tri_toi_da', 'dieu_kien_toi_thieu',
        'so_luot_su_dung', 'so_luot_toi_da', 'ngay_bat_dau', 'ngay_ket_thuc',
        'trang_thai', 'mo_ta',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'trang_thai' => 'boolean',
    ];

    public function tinhGiamGia(int $tong_tien): int
    {
        if ($this->loai === 'phan_tram') {
            $giam = $tong_tien * $this->gia_tri / 100;
            if ($this->gia_tri_toi_da) {
                $giam = min($giam, $this->gia_tri_toi_da);
            }
            return (int) $giam;
        }
        return min($this->gia_tri, $tong_tien);
    }

    public function conHieuLuc(): bool
    {
        $hom_nay = now()->toDateString();
        return $this->trang_thai
            && $this->ngay_bat_dau <= $hom_nay
            && $this->ngay_ket_thuc >= $hom_nay
            && $this->so_luot_su_dung < $this->so_luot_toi_da;
    }
}
