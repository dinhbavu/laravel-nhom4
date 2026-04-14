<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'so_dien_thoai',
        'dia_chi',
        'anh_dai_dien',
        'vai_tro',
        'trang_thai',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'trang_thai' => 'boolean',
    ];

    // Tên trường mật khẩu
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    // Kiểm tra vai trò
    public function laAdmin(): bool
    {
        return $this->vai_tro === 'admin';
    }

    public function laNhanVien(): bool
    {
        return $this->vai_tro === 'nhan_vien';
    }

    public function laKhachHang(): bool
    {
        return $this->vai_tro === 'khach_hang';
    }

    public function laQuanTri(): bool
    {
        return in_array($this->vai_tro, ['admin', 'nhan_vien']);
    }

    // Relationships
    public function danhSachDatTour()
    {
        return $this->hasMany(DatTour::class, 'khach_hang_id');
    }

    public function danhSachYeuThich()
    {
        return $this->hasMany(YeuThich::class, 'khach_hang_id');
    }

    public function tourYeuThich()
    {
        return $this->belongsToMany(Tour::class, 'yeu_thich', 'khach_hang_id', 'tour_id');
    }

    public function thongBao()
    {
        return $this->hasMany(ThongBao::class, 'nguoi_nhan_id');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'khach_hang_id');
    }

    // Accessor
    public function getAnhDaiDienUrlAttribute()
    {
        if ($this->anh_dai_dien && $this->anh_dai_dien !== 'default-avatar.png') {
            return asset('storage/avatars/' . $this->anh_dai_dien);
        }
        return asset('images/default-avatar.png');
    }

    public function getTenVaiTroAttribute()
    {
        return match($this->vai_tro) {
            'admin' => 'Quản Trị Viên',
            'nhan_vien' => 'Nhân Viên',
            'khach_hang' => 'Khách Hàng',
            default => 'Không xác định',
        };
    }
}
