@extends('layouts.khach-hang')
@section('title', 'Thanh toán thành công - VietGo')

@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem;">
    <div style="text-align:center;max-width:550px;background:white;border-radius:24px;padding:3rem 2rem;box-shadow:0 10px 40px rgba(16,185,129,.12);animation:slideUp .6s cubic-bezier(.16,1,.3,1);">
        
        <div style="width:90px;height:90px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:#10b981;font-size:2.8rem;border:4px solid #d1fae5;">
            <i class="fa-solid fa-check"></i>
        </div>
        
        <h1 style="font-family:var(--font-heading);font-weight:900;font-size:2rem;color:#064e3b;margin-bottom:.5rem;">
            Thanh toán thành công!
        </h1>
        
        <p style="font-size:1.05rem;color:#64748b;margin-bottom:.75rem;line-height:1.6;">
            Hệ thống đã nhận thanh toán cho đơn 
            <strong style="color:#0f172a;">{{ $dat_tour->ma_dat_tour }}</strong>
        </p>

        <div style="background:#f0fdf4;border-radius:14px;padding:1rem 1.5rem;margin:1.5rem 0;border:1px solid #bbf7d0;">
            <div style="font-size:.82rem;color:#059669;line-height:1.6;">
                ✅ Đơn tour đã được <strong>xác nhận tự động</strong><br>
                📧 Thông báo đã được gửi đến tài khoản của bạn<br>
                🗓️ Khởi hành: <strong>{{ $dat_tour->lichKhoiHanh->ngay_di->format('d/m/Y') }}</strong>
            </div>
        </div>

        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:2rem;">
            <a href="{{ route('khach-hang.dat-tour.chi-tiet', $dat_tour) }}" 
                class="btn btn-primary" style="padding:.9rem 2rem;border-radius:12px;font-size:.95rem;">
                <i class="fa-solid fa-receipt"></i> Xem Hoá Đơn
            </a>
            <a href="{{ route('khach-hang.dat-tour.lich-su') }}" 
                class="btn btn-secondary" style="padding:.9rem 2rem;border-radius:12px;font-size:.95rem;">
                <i class="fa-solid fa-list"></i> Lịch Sử Đặt Tour
            </a>
        </div>
    </div>
</div>

<style>
@keyframes slideUp { from { opacity:0; transform:translateY(30px) scale(0.95); } to { opacity:1; transform:translateY(0) scale(1); } }
</style>
@endsection
