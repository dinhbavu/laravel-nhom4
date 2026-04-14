<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatTour extends Model
{
    protected $table = 'dat_tour';

    protected $fillable = [
        'ma_dat_tour', 'khach_hang_id', 'lich_khoi_hanh_id', 'ma_khuyen_mai_id',
        'so_nguoi_lon', 'so_tre_em', 'so_em_be',
        'tong_tien_goc', 'giam_gia', 'tong_tien_thanh_toan',
        'ghi_chu', 'trang_thai', 'ly_do_huy', 'nguoi_duyet_id',
    ];

    public function khachHang()
    {
        return $this->belongsTo(NguoiDung::class, 'khach_hang_id');
    }

    public function lichKhoiHanh()
    {
        return $this->belongsTo(LichKhoiHanh::class, 'lich_khoi_hanh_id');
    }

    public function maKhuyenMai()
    {
        return $this->belongsTo(MaKhuyenMai::class, 'ma_khuyen_mai_id');
    }

    public function hanhKhach()
    {
        return $this->hasMany(HanhKhach::class, 'dat_tour_id');
    }

    public function thanhToan()
    {
        return $this->hasOne(ThanhToan::class, 'dat_tour_id');
    }

    public function danhGia()
    {
        return $this->hasOne(DanhGia::class, 'dat_tour_id');
    }

    public function nguoiDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    public function getTrangThaiTenAttribute()
    {
        return match($this->trang_thai) {
            'cho_duyet' => 'Chờ Duyệt',
            'da_duyet' => 'Đã Duyệt',
            'da_xac_nhan' => 'Xác Nhận',
            'da_huy' => 'Đã Hủy',
            'hoan_thanh' => 'Khởi Hành',
            'done' => 'Hoàn Thành',
            default => 'Không xác định',
        };
    }

    public function getTrangThaiMauAttribute()
    {
        return match($this->trang_thai) {
            'cho_duyet' => 'warning',
            'da_duyet' => 'success',
            'da_xac_nhan' => 'primary',
            'da_huy' => 'danger',
            'hoan_thanh' => 'info',
            'done' => 'success',
            default => 'secondary',
        };
    }

    public function getTongTienDinhDangAttribute()
    {
        return number_format($this->tong_tien_thanh_toan, 0, ',', '.') . ' đ';
    }

    public function getTongTienThanhToanDinhDangAttribute()
    {
        return number_format($this->tong_tien_thanh_toan, 0, ',', '.') . ' đ';
    }

    // Aliases so_luong → so_ field names used in older views
    public function getSoLuongNguoiLonAttribute()  { return $this->so_nguoi_lon; }
    public function getSoLuongTreEmAttribute()      { return $this->so_tre_em; }
    public function getSoLuongEmBeAttribute()       { return $this->so_em_be; }

    public function getPhuongThucThanhToanAttribute()
    {
        return $this->thanhToan?->phuong_thuc ?? 'tien_mat';
    }

    public static function taoMaDatTour()
    {
        $so = self::count() + 1;
        return 'DT' . date('ymd') . str_pad($so, 4, '0', STR_PAD_LEFT);
    }
}
