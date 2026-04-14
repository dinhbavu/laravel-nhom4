@extends('layouts.khach-hang')
@section('title', 'Thanh toán đơn #' . $dat_tour->ma_dat_tour . ' - VietGo')

@section('css')
<style>
.checkout-page { padding: 3rem 0 5rem; }
.checkout-wrap { max-width: 700px; margin: 0 auto; }

.checkout-header {
    text-align: center; margin-bottom: 2rem;
}
.checkout-header h1 {
    font-family: var(--font-heading); font-weight: 900; font-size: 1.8rem;
    color: #0f172a; margin-bottom: .5rem;
}
.checkout-header p { color: #64748b; font-size: .95rem; }

.checkout-summary {
    background: white; border-radius: 20px; border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(15,23,42,.08); padding: 1.5rem; margin-bottom: 1.5rem;
}
.cs-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .6rem 0; border-bottom: 1px solid #f1f5f9; font-size: .9rem;
}
.cs-row:last-child { border-bottom: none; }
.cs-row .label { color: #64748b; }
.cs-row .val { font-weight: 700; color: #0f172a; }

.cs-total {
    background: linear-gradient(135deg, var(--primary), #059669);
    border-radius: 14px; padding: 1.1rem 1.25rem; display: flex;
    justify-content: space-between; align-items: center; color: white; margin-top: 1rem;
}
.cs-total .tl { font-size: .85rem; opacity: .85; }
.cs-total .tv { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 900; }

.checkout-form-wrap {
    background: white; border-radius: 20px; border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(15,23,42,.08); padding: 2rem; text-align: center;
}
.checkout-form-wrap h3 {
    font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800;
    color: #0f172a; margin-bottom: .5rem;
}
.checkout-form-wrap p { color: #64748b; font-size: .85rem; margin-bottom: 1.5rem; }

/* SePay form styling */
.checkout-form-wrap form { display: inline-block; }
.checkout-form-wrap input[type="submit"],
.checkout-form-wrap button[type="submit"] {
    background: linear-gradient(135deg, #0f172a, #1e293b) !important;
    color: white !important; border: none !important; border-radius: 14px !important;
    padding: 1rem 3rem !important; font-size: 1.05rem !important; font-weight: 800 !important;
    cursor: pointer !important; font-family: inherit !important;
    box-shadow: 0 4px 20px rgba(15,23,42,.25) !important; transition: all .3s !important;
}
.checkout-form-wrap input[type="submit"]:hover,
.checkout-form-wrap button[type="submit"]:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 30px rgba(15,23,42,.35) !important;
}

.safety-badges {
    display: flex; gap: 1.5rem; justify-content: center; margin-top: 1.5rem;
    flex-wrap: wrap;
}
.safety-badge {
    display: flex; align-items: center; gap: .4rem;
    font-size: .78rem; color: #64748b;
}
.safety-badge i { color: var(--primary); }

.back-link {
    display: inline-flex; align-items: center; gap: .5rem;
    color: #64748b; text-decoration: none; font-size: .85rem; margin-bottom: 1.5rem;
    transition: color .2s;
}
.back-link:hover { color: var(--primary); }
</style>
@endsection

@section('content')
<div class="checkout-page">
    <div class="checkout-wrap container">
        <a href="{{ route('khach-hang.dat-tour.chi-tiet', $dat_tour) }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Quay lại chi tiết đơn
        </a>

        <div class="checkout-header">
            <h1>🔒 Thanh Toán An Toàn</h1>
            <p>Hoàn tất thanh toán cho đơn đặt tour <strong>#{{ $dat_tour->ma_dat_tour }}</strong></p>
        </div>

        {{-- Tóm tắt đơn --}}
        <div class="checkout-summary">
            <div class="cs-row">
                <span class="label">🗺️ Tour</span>
                <span class="val">{{ Str::limit($dat_tour->lichKhoiHanh->tour->ten_tour, 40) }}</span>
            </div>
            <div class="cs-row">
                <span class="label">📅 Khởi hành</span>
                <span class="val">{{ $dat_tour->lichKhoiHanh->ngay_di->format('d/m/Y') }}</span>
            </div>
            <div class="cs-row">
                <span class="label">👥 Hành khách</span>
                <span class="val">{{ $dat_tour->so_nguoi_lon }} NL @if($dat_tour->so_tre_em > 0), {{ $dat_tour->so_tre_em }} TE @endif</span>
            </div>
            <div class="cs-row">
                <span class="label">🏷️ Mã đơn</span>
                <span class="val" style="color:var(--primary);">{{ $dat_tour->ma_dat_tour }}</span>
            </div>
            <div class="cs-total">
                <div class="tl">Tổng thanh toán</div>
                <div class="tv">{{ number_format($dat_tour->tong_tien_thanh_toan, 0, ',', '.') }}đ</div>
            </div>
        </div>

        {{-- SePay Form --}}
        <div class="checkout-form-wrap">
            <h3><i class="fa-solid fa-shield-halved" style="color:var(--primary);"></i> Cổng Thanh Toán SePay</h3>
            <p>Bạn sẽ được chuyển đến trang thanh toán bảo mật của SePay. Hỗ trợ Chuyển khoản NH, MoMo, ZaloPay, VNPay.</p>
            
            {!! $formHtml !!}

            <div class="safety-badges">
                <div class="safety-badge"><i class="fa-solid fa-shield-halved"></i> SSL 256-bit</div>
                <div class="safety-badge"><i class="fa-solid fa-lock"></i> PCI-DSS</div>
                <div class="safety-badge"><i class="fa-solid fa-check-circle"></i> SePay Verified</div>
            </div>
        </div>
    </div>
</div>
@endsection
