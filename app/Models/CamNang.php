<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CamNang extends Model
{
    protected $table = 'cam_nang';

    protected $fillable = [
        'tieu_de', 'slug', 'chuyen_muc', 'tom_tat', 'noi_dung', 'hinh_anh', 'luot_xem', 'nguoi_dang_id', 'trang_thai'
    ];

    public function nguoiDang()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dang_id');
    }

    public function getHinhAnhUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('uploads/cam-nang/' . $this->hinh_anh);
        }
        return asset('images/no-image.jpg');
    }

    public function getChuyenMucTenAttribute()
    {
        return match($this->chuyen_muc) {
            'meo_du_lich' => 'Mẹo Du Lịch',
            'am_thuc' => 'Ẩm Thực & Văn Hóa',
            'diem_den' => 'Điểm Đến Hấp Dẫn',
            default => 'Khác',
        };
    }

    public function getNgayDangAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
}
