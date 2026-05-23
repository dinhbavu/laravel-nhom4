<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tour';

    protected $fillable = [
        'ma_tour',
        'ten_tour',
        'diem_den_id',
        'loai_tour',
        'so_ngay',
        'so_dem',
        'mo_ta_ngan',
        'mo_ta_day_du',
        'gia_nguoi_lon',
        'gia_tre_em',
        'gia_em_be',
        'hinh_bia',
        'noi_bat',
        'phan_tram_giam_gia',
        'trang_thai',
        'nguoi_tao_id',
        'danh_gia_trung_binh',
        'luot_xem',
    ];

    protected $casts = [
        'gia_nguoi_lon' => 'integer',
        'gia_tre_em' => 'integer',
        'gia_em_be' => 'integer',
        'noi_bat' => 'boolean',
    ];

    // Relationships
    public function diemDen()
    {
        return $this->belongsTo(DiemDen::class, 'diem_den_id');
    }

    public function hinhAnh()
    {
        return $this->hasMany(HinhAnhTour::class, 'tour_id')->orderBy('thu_tu');
    }

    public function lichTrinh()
    {
        return $this->hasMany(LichTrinhTour::class, 'tour_id')->orderBy('ngay_thu');
    }

    public function lichKhoiHanh()
    {
        return $this->hasMany(LichKhoiHanh::class, 'tour_id')->orderBy('ngay_di');
    }

    public function datTour()
    {
        return $this->hasManyThrough(DatTour::class, LichKhoiHanh::class, 'tour_id', 'lich_khoi_hanh_id');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'tour_id')->where('trang_thai', 'da_duyet');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_id');
    }

    // Accessors
    public function getHinhBiaUrlAttribute()
    {
        if ($this->hinh_bia) {
            return asset('uploads/tours/' . $this->hinh_bia);
        }
        return asset('images/tour-default.jpg');
    }

    public function getGiaNguoiLonDinhDangAttribute()
    {
        return number_format($this->gia_nguoi_lon, 0, ',', '.') . ' đ';
    }

    public function getGiaKhuyenMaiNguoiLonAttribute()
    {
        if ($this->phan_tram_giam_gia > 0) {
            return $this->gia_nguoi_lon * (1 - $this->phan_tram_giam_gia / 100);
        }
        return $this->gia_nguoi_lon;
    }

    public function getGiaKhuyenMaiNguoiLonDinhDangAttribute()
    {
        return number_format($this->getGiaKhuyenMaiNguoiLonAttribute(), 0, ',', '.') . ' đ';
    }

    public function getLoaiTourTenAttribute()
    {
        return match($this->loai_tour) {
            'trong_nuoc' => 'Trong Nước',
            'quoc_te' => 'Quốc Tế',
            'mao_hiem' => 'Mạo Hiểm',
            'nghi_duong' => 'Nghỉ Dưỡng',
            'van_hoa' => 'Văn Hóa',
            default => 'Khác',
        };
    }

    // Scope
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    public function scopeNoiBat($query)
    {
        return $query->where('noi_bat', true);
    }

    public function scopeTimKiem($query, $tuKhoa)
    {
        return $query->where(function ($q) use ($tuKhoa) {
            $q->where('ten_tour', 'like', '%' . $tuKhoa . '%')
              ->orWhere('mo_ta_ngan', 'like', '%' . $tuKhoa . '%');
        });
    }

    // Tạo mã tour tự động
    public static function taoMaTour()
    {
        $so_thu_tu = self::count() + 1;
        return 'VG' . date('Y') . str_pad($so_thu_tu, 4, '0', STR_PAD_LEFT);
    }
}
