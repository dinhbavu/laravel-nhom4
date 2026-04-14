@extends('layouts.khach-hang')
@section('title', 'Đặt Tour: ' . $lich_khoi_hanh->tour->ten_tour . ' - VietGo')

@section('css')
<style>
/* ═══ Layout ═══ */
.booking-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
    padding: 2.5rem 0 5rem;
}
@media(max-width: 900px){
    .booking-wrap { grid-template-columns: 1fr; }
    .booking-sticky { position: static !important; }
}

.booking-steps {
    display: flex; gap: 0; margin-bottom: 2rem;
    background: rgba(255, 255, 255, 0.04); border-radius: 16px;
    padding: 1.25rem 1.5rem; border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
}
.booking-step {
    flex: 1; display: flex; align-items: center; gap: .75rem; position: relative;
}
.booking-step::after {
    content: ''; position: absolute; right: 0; top: 50%;
    transform: translateY(-50%); width: 40px; height: 2px;
    background: #e2e8f0;
}
.booking-step:last-child::after { display: none; }
.step-num {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .85rem;
    background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.4); border: 2px solid rgba(255,255,255,0.1);
    transition: all .3s;
}
.step-active .step-num { background: var(--primary-accent); color: #000; border-color: var(--primary-accent); }
.step-done .step-num { background: #4ade80; color: #000; border-color: #4ade80; }
.step-label { font-size: .8rem; font-weight: 700; color: rgba(255,255,255,0.4); }
.step-active .step-label { color: #fff; }
.step-done .step-label { color: #4ade80; }

/* ═══ Cards ═══ */
.book-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(20px);
    margin-bottom: 1.5rem;
}
.book-card-head {
    padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.05);
    display: flex; align-items: center; gap: .75rem;
    font-weight: 800; font-size: 1rem; color: #fff;
}
.book-card-head i { width: 32px; height: 32px; border-radius: 8px; background: rgba(74, 222, 128, 0.1); color: var(--primary-accent); display: flex; align-items: center; justify-content: center; font-size: .9rem; }
.book-card-body { padding: 1.75rem; }

/* ═══ Contact info block ═══ */
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:520px){ .contact-grid { grid-template-columns: 1fr; } }
.cfield { display: flex; flex-direction: column; gap: .4rem; }
.cfield-label { font-size: .75rem; font-weight: 800; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .05em; }
.cfield-val {
    padding: .75rem 1rem; background: rgba(255,255,255,0.05);
    border-radius: 10px; border: 1.5px solid rgba(255,255,255,0.1);
    font-size: .9rem; font-weight: 600; color: #fff;
    display: flex; align-items: center; gap: .5rem;
}
.cfield-val i { color: var(--primary-accent); font-size: .8rem; }
.cfield-input {
    padding: .75rem 1rem; background: rgba(255,255,255,0.05);
    border-radius: 10px; border: 2px solid rgba(255,255,255,0.1);
    font-size: .9rem; font-weight: 500; color: #fff;
    outline: none; font-family: inherit; resize: vertical;
    transition: all .25s;
}
.cfield-input:focus { border-color: var(--primary-accent); background: rgba(255,255,255,0.08); box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.1); }

/* ═══ Guest counter ═══ */
.guest-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);
}
.guest-row:last-child { border-bottom: none; padding-bottom: 0; }
.guest-info { display: flex; flex-direction: column; gap: .15rem; }
.guest-label { font-size: .95rem; font-weight: 700; color: #fff; font-family: var(--font-heading); }
.guest-sub { font-size: .78rem; color: rgba(255,255,255,0.5); }
.guest-price { font-size: .8rem; color: var(--primary-accent); font-weight: 700; margin-top: .25rem; }
.counter {
    display: flex; align-items: center; gap: .6rem;
}
.counter-btn {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .85rem;
    transition: all .25s;
}
.counter-btn:hover { background: var(--primary-accent); color: #000; border-color: var(--primary-accent); }
.counter-btn:disabled { opacity: .2; cursor: not-allowed; }
.counter-input {
    width: 44px; text-align: center; font-size: 1rem;
    font-weight: 800; color: #fff; background: transparent;
    border: none; outline: none; font-family: var(--font-heading);
}

/* ═══ Payment method ═══ */
.pttt-grid { display: flex; flex-direction: column; gap: .75rem; }
.pttt-label { display: flex; align-items: center; gap: 1rem; cursor: pointer; }
.pttt-label input[type=radio] { display: none; }
.pttt-card {
    flex: 1; display: flex; align-items: center; gap: 1rem;
    padding: 1rem 1.25rem; border-radius: 14px;
    border: 2px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03);
    transition: all .25s;
}
.pttt-label:has(input:checked) .pttt-card {
    border-color: var(--primary-accent); background: rgba(74, 222, 128, 0.1);
    box-shadow: 0 0 15px rgba(74, 222, 128, 0.15);
}
.pttt-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.pttt-name { font-weight: 700; font-size: .9rem; color: #fff; margin-bottom: .15rem; }
.pttt-desc { font-size: .75rem; color: rgba(255,255,255,0.5); }
.pttt-radio {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.2); flex-shrink: 0; margin-left: auto;
    display: flex; align-items: center; justify-content: center;
}
.pttt-label:has(input:checked) .pttt-radio { border-color: var(--primary-accent); background: var(--primary-accent); }
.pttt-label:has(input:checked) .pttt-radio::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #000; }

/* ═══ Sticky summary panel ═══ */
.booking-sticky { position: sticky; top: 5rem; }
.summary-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(25px);
    box-shadow: 0 4px 24px rgba(0,0,0,.3);
}
.summary-tour-thumb { height: 160px; overflow: hidden; position: relative; }
.summary-tour-thumb img { width: 100%; height: 100%; object-fit: cover; }
.summary-tour-thumb-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,0.85), transparent);
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 1rem 1.25rem;
}
.summary-body { padding: 1.25rem 1.5rem; }
.summary-trip-info { display: flex; flex-direction: column; gap: .5rem; margin-bottom: 1.1rem; }
.trip-row { display: flex; align-items: center; gap: .6rem; font-size: .82rem; color: rgba(255,255,255,0.5); }
.trip-row i { color: var(--primary-accent); width: 16px; flex-shrink: 0; }
.trip-row strong { color: #fff; }

.sum-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 1rem 0; }

/* Discount notice */
.disc-notice {
    display: flex; align-items: center; gap: .65rem;
    background: rgba(74, 222, 128, 0.1); border: 1px dashed rgba(74, 222, 128, 0.3);
    border-radius: 12px; padding: .75rem 1rem;
    margin-bottom: 1rem; font-size: .82rem; font-weight: 600; color: #4ade80;
}

/* Price rows */
.price-rows { display: flex; flex-direction: column; gap: .4rem; margin-bottom: 1rem; }
.price-row { display: flex; justify-content: space-between; font-size: .85rem; }
.price-row .label { color: rgba(255,255,255,0.5); }
.price-row .val { font-weight: 600; color: #fff; }
.price-row.disc .label { color: #f87171; }
.price-row.disc .val { color: #f87171; }

.price-total-box {
    background: linear-gradient(135deg, var(--primary-accent), #22c55e);
    border-radius: 14px; padding: 1.1rem 1.25rem;
    display: flex; justify-content: space-between; align-items: center;
    color: #000;
}
.price-total-label { font-size: .82rem; font-weight: 800; opacity: .85; }
.price-total-num {
    font-family: var(--font-heading); font-size: 1.4rem; font-weight: 1000;
}

.btn-book {
    width: 100%; padding: 1rem; margin-top: 1rem;
    background: #fff;
    color: #000; border: none; border-radius: 14px;
    font-size: 1.05rem; font-weight: 900; cursor: pointer;
    font-family: var(--font-heading); transition: all .3s;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    box-shadow: 0 4px 20px rgba(255,255,255,.1);
}
.btn-book:hover { transform: translateY(-3px); background: var(--primary-accent); box-shadow: 0 8px 30px rgba(74, 222, 128, 0.4); }

.btn-book-pulse {
    animation: bookPulse 2s infinite;
}
@keyframes bookPulse {
    0%, 100% { box-shadow: 0 4px 20px rgba(255,255,255,.05); }
    50% { box-shadow: 0 4px 30px rgba(74, 222, 128, 0.4); }
}

.safety-note { 
    display: flex; align-items: center; gap: .5rem; 
    font-size: .75rem; color: #94a3b8; text-align: center;
    justify-content: center; margin-top: .75rem;
}
</style>
@endsection

@section('content')
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        {{-- Breadcrumb --}}
        <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;color:rgba(255,255,255,.5);margin-bottom:1.25rem;flex-wrap:wrap;">
            <a href="{{ route('trang-chu') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size:.6rem;"></i>
            <a href="{{ route('tour.danh-sach') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">Tour</a>
            <i class="fa-solid fa-angle-right" style="font-size:.6rem;"></i>
            <span style="color:white;font-weight:700;">Đặt tour</span>
        </div>
        <h1 style="font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,2.2rem);font-weight:900;color:white;margin:0 0 .3rem;">
            🗓️ Xác Nhận Đặt Tour
        </h1>
        <p style="color:rgba(255,255,255,.6);font-size:.9rem;">Hoàn tất thông tin cuối cùng để sẵn sàng lên đường!</p>
    </div>
</div>

<div class="container" style="margin-top:-2rem;">
    @if($errors->any())
    <div style="background:rgba(239, 68, 68, 0.1); border:1px solid rgba(239, 68, 68, 0.3); backdrop-filter:blur(10px); border-radius:16px; padding:1.25rem; margin-bottom:1.5rem; display:flex; align-items:flex-start; gap:.75rem; color:#f87171;">
        <i class="fa-solid fa-circle-exclamation" style="margin-top:.15rem; flex-shrink:0;"></i>
        <div>
            @foreach($errors->all() as $loi)
                <div style="font-size:.875rem; font-weight:600;">{{ $loi }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <form action="{{ route('khach-hang.dat-tour.xu-ly', $lich_khoi_hanh) }}" method="POST" id="formDatTour">
        @csrf
        <div class="booking-wrap">

            {{-- ═══ CỘT TRÁI ═══ --}}
            <div>
                {{-- Steps --}}
                <div class="booking-steps">
                    <div class="booking-step step-active">
                        <div class="step-num">1</div>
                        <span class="step-label">Thông tin</span>
                    </div>
                    <div class="booking-step">
                        <div class="step-num">2</div>
                        <span class="step-label">Thanh toán</span>
                    </div>
                    <div class="booking-step">
                        <div class="step-num">3</div>
                        <span class="step-label">Xác nhận</span>
                    </div>
                </div>

                {{-- Thông tin liên hệ --}}
                <div class="book-card">
                    <div class="book-card-head">
                        <i class="fa-solid fa-address-card"></i>
                        Thông Tin Liên Hệ
                    </div>
                    <div class="book-card-body">
                        <div class="contact-grid" style="margin-bottom:1rem;">
                            <div class="cfield">
                                <span class="cfield-label">Họ và tên</span>
                                <div class="cfield-val"><i class="fa-solid fa-user"></i> {{ auth()->user()->ho_ten }}</div>
                            </div>
                            <div class="cfield">
                                <span class="cfield-label">Email</span>
                                <div class="cfield-val"><i class="fa-solid fa-envelope"></i> {{ auth()->user()->email }}</div>
                            </div>
                            <div class="cfield">
                                <span class="cfield-label">Số điện thoại</span>
                                <div class="cfield-val"><i class="fa-solid fa-phone"></i> {{ auth()->user()->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                        <div class="cfield">
                            <span class="cfield-label">Ghi chú đặc biệt <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.72rem;color:#94a3b8;">(ăn kiêng, yêu cầu đặc biệt...)</span></span>
                            <textarea name="ghi_chu" id="ghi_chu" rows="3" class="cfield-input" placeholder="Nhập yêu cầu nếu có...">{{ old('ghi_chu') }}</textarea>
                        </div>
                        <div style="margin-top:1.25rem; padding:1.25rem; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; font-size:.8rem; color:rgba(255,255,255,0.7); display:flex; align-items:center; gap:1rem;">
                            <i class="fa-solid fa-circle-info" style="color:var(--primary-accent); font-size:1.1rem;"></i>
                            <span>Thông tin liên hệ được đồng bộ từ hồ sơ của bạn. <a href="{{ route('khach-hang.ho-so') }}" style="color:var(--primary-accent); font-weight:800; text-decoration:underline;">Cập nhật hồ sơ</a></span>
                        </div>
                    </div>
                </div>

                {{-- Số lượng khách --}}
                <div class="book-card">
                    <div class="book-card-head">
                        <i class="fa-solid fa-users"></i>
                        Số Lượng Hành Khách
                        <span style="margin-left:auto; font-size:.75rem; font-weight:800; color:#fff; background:rgba(239, 68, 68, 0.4); border:1px solid rgba(239, 68, 68, 0.4); padding:.3rem .8rem; border-radius:999px;">
                            Còn {{ $lich_khoi_hanh->so_cho_con }} chỗ
                        </span>
                    </div>
                    <div class="book-card-body">
                        {{-- Người lớn --}}
                        <div class="guest-row">
                            <div class="guest-info">
                                <span class="guest-label">👨 Người lớn</span>
                                <span class="guest-sub">Từ 12 tuổi trở lên</span>
                                <span class="guest-price">{{ number_format($lich_khoi_hanh->gia_nguoi_lon_hien_tai, 0, ',', '.') }}đ / người</span>
                            </div>
                            <div class="counter">
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_nguoi_lon', -1)" id="btnMinusNL">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" id="so_nguoi_lon" name="so_nguoi_lon"
                                    class="counter-input" value="{{ old('so_nguoi_lon', 1) }}"
                                    min="1" max="{{ $lich_khoi_hanh->so_cho_con }}" readonly>
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_nguoi_lon', 1)" id="btnPlusNL">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        @if($lich_khoi_hanh->tour->gia_tre_em > 0)
                        {{-- Trẻ em --}}
                        <div class="guest-row">
                            <div class="guest-info">
                                <span class="guest-label">🧒 Trẻ em</span>
                                <span class="guest-sub">Dưới 12 tuổi</span>
                                <span class="guest-price">{{ number_format($lich_khoi_hanh->gia_tre_em_hien_tai, 0, ',', '.') }}đ / trẻ</span>
                            </div>
                            <div class="counter">
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_tre_em', -1)">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" id="so_tre_em" name="so_tre_em"
                                    class="counter-input" value="{{ old('so_tre_em', 0) }}"
                                    min="0" max="{{ $lich_khoi_hanh->so_cho_con }}" readonly>
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_tre_em', 1)">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="so_tre_em" value="0">
                        @endif

                        {{-- Em bé --}}
                        <div class="guest-row">
                            <div class="guest-info">
                                <span class="guest-label">👶 Em bé</span>
                                <span class="guest-sub">Dưới 2 tuổi — Miễn phí</span>
                                <span class="guest-price">Không tính phí</span>
                            </div>
                            <div class="counter">
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_em_be', -1)">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" id="so_em_be" name="so_em_be"
                                    class="counter-input" value="{{ old('so_em_be', 0) }}"
                                    min="0" max="10" readonly>
                                <button type="button" class="counter-btn" onclick="thayDoiSoLuong('so_em_be', 1)">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="book-card">
                    <div class="book-card-head">
                        <i class="fa-solid fa-credit-card"></i>
                        Phương Thức Thanh Toán
                    </div>
                    <div class="book-card-body">
                        <div class="pttt-grid">
                            <label class="pttt-label">
                                <input type="radio" name="phuong_thuc_thanh_toan" value="chuyen_khoan" {{ old('phuong_thuc_thanh_toan','chuyen_khoan')=='chuyen_khoan'?'checked':'' }}>
                                <div class="pttt-card">
                                    <div class="pttt-icon" style="background:#dbeafe;font-size:1.3rem;">🏦</div>
                                    <div>
                                        <div class="pttt-name">Chuyển khoản ngân hàng</div>
                                        <div class="pttt-desc">Thanh toán sau khi nhận thông tin TK</div>
                                    </div>
                                    <div class="pttt-radio"><span></span></div>
                                </div>
                            </label>



                            <label class="pttt-label">
                                <input type="radio" name="phuong_thuc_thanh_toan" value="momo" {{ old('phuong_thuc_thanh_toan')=='momo'?'checked':'' }}>
                                <div class="pttt-card">
                                    <div class="pttt-icon" style="background:#fce7f3;color:#db2777;font-size:1.3rem;">
                                        <i class="fa-solid fa-wallet"></i>
                                    </div>
                                    <div>
                                        <div class="pttt-name">Ví MoMo</div>
                                        <div class="pttt-desc">Quét QR hoặc chuyển qua ví MoMo</div>
                                    </div>
                                    <div class="pttt-radio"><span></span></div>
                                </div>
                            </label>

                            <label class="pttt-label">
                                <input type="radio" name="phuong_thuc_thanh_toan" value="zalopay" {{ old('phuong_thuc_thanh_toan')=='zalopay'?'checked':'' }}>
                                <div class="pttt-card">
                                    <div class="pttt-icon" style="background:#dbeafe;color:#2563eb;font-size:1.3rem;">
                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                    </div>
                                    <div>
                                        <div class="pttt-name">ZaloPay</div>
                                        <div class="pttt-desc">Quét QR hoặc chuyển qua ví ZaloPay</div>
                                    </div>
                                    <div class="pttt-radio"><span></span></div>
                                </div>
                            </label>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ CỘT PHẢI — TÓM TẮT ═══ --}}
            <div class="booking-sticky">
                <div class="summary-card">
                    {{-- Tour thumb --}}
                    <div class="summary-tour-thumb">
                        <img src="{{ $lich_khoi_hanh->tour->hinh_bia_url }}" alt="{{ $lich_khoi_hanh->tour->ten_tour }}">
                        <div class="summary-tour-thumb-overlay">
                            <a href="{{ route('tour.chi-tiet', $lich_khoi_hanh->tour) }}"
                                style="color:white;font-family:var(--font-heading);font-size:1rem;font-weight:800;text-decoration:none;line-height:1.3;text-shadow:0 1px 6px rgba(0,0,0,.4);">
                                {{ $lich_khoi_hanh->tour->ten_tour }}
                            </a>
                        </div>
                    </div>

                    <div class="summary-body">
                        {{-- Trip info --}}
                        <div class="summary-trip-info">
                            <div class="trip-row"><i class="fa-regular fa-calendar"></i> Khởi hành: <strong>{{ $lich_khoi_hanh->ngay_di->format('d/m/Y') }}</strong></div>
                            <div class="trip-row"><i class="fa-regular fa-calendar-check"></i> Về ngày: <strong>{{ $lich_khoi_hanh->ngay_ve->format('d/m/Y') }}</strong></div>
                            <div class="trip-row"><i class="fa-regular fa-clock"></i> Thời gian: <strong>{{ $lich_khoi_hanh->tour->so_ngay }}N{{ $lich_khoi_hanh->tour->so_dem }}Đ</strong></div>
                            <div class="trip-row"><i class="fa-solid fa-location-dot"></i> Điểm đến: <strong>{{ $lich_khoi_hanh->tour->diemDen->ten_diem_den }}</strong></div>
                        </div>

                        <div class="sum-divider"></div>

                        {{-- Discount notice --}}
                        @if($lich_khoi_hanh->tour->phan_tram_giam_gia > 0)
                        <div class="disc-notice">
                            <i class="fa-solid fa-gift"></i>
                            Tự động giảm <strong>{{ $lich_khoi_hanh->tour->phan_tram_giam_gia }}%</strong> cho tour này!
                        </div>
                        @endif

                        {{-- Price rows --}}
                        <div class="price-rows">
                            <div class="price-row">
                                <span class="label">Người lớn (<span id="psum-nl">1</span>×)</span>
                                <span class="val" id="psum-nl-total">{{ number_format($lich_khoi_hanh->gia_nguoi_lon_hien_tai, 0, ',', '.') }}đ</span>
                            </div>
                            @if($lich_khoi_hanh->tour->gia_tre_em > 0)
                            <div class="price-row" id="prow-te" style="display:none;">
                                <span class="label">Trẻ em (<span id="psum-te">0</span>×)</span>
                                <span class="val" id="psum-te-total">0đ</span>
                            </div>
                            @endif
                            <div class="price-row disc" id="prow-disc" style="display:none;">
                                <span class="label"><i class="fa-solid fa-tag"></i> Giảm giá ({{ $lich_khoi_hanh->tour->phan_tram_giam_gia }}%)</span>
                                <span class="val" id="psum-disc">-0đ</span>
                            </div>
                        </div>

                        <div class="price-total-box">
                            <div>
                                <div class="price-total-label">Tổng thanh toán</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.65);">Đã bao gồm thuế & phí</div>
                            </div>
                            <div class="price-total-num" id="psum-total">{{ number_format($lich_khoi_hanh->gia_nguoi_lon_hien_tai, 0, ',', '.') }}đ</div>
                        </div>

                        <input type="hidden" name="tong_tien" id="input-tong-tien" value="{{ $lich_khoi_hanh->gia_nguoi_lon_hien_tai }}">

                        <button type="submit" class="btn-book btn-book-pulse">
                            <i class="fa-solid fa-calendar-check"></i>
                            XÁC NHẬN ĐẶT TOUR
                        </button>

                        <div class="safety-note">
                            <i class="fa-solid fa-shield-halved" style="color:var(--primary);"></i>
                            Thanh toán an toàn • Bảo mật 256-bit SSL
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('js')
<script>
const GIA_NL = {{ $lich_khoi_hanh->gia_nguoi_lon_hien_tai }};
const GIA_TE = {{ $lich_khoi_hanh->gia_tre_em_hien_tai ?? 0 }};
const SO_CHO = {{ $lich_khoi_hanh->so_cho_con }};
const PCT_GIAM = {{ $lich_khoi_hanh->tour->phan_tram_giam_gia ?? 0 }};
const fmt = n => new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';

function thayDoiSoLuong(id, step) {
    const el = document.getElementById(id);
    let v = parseInt(el.value) + step;
    const min = id === 'so_nguoi_lon' ? 1 : 0;
    const max = id === 'so_em_be' ? 10 : SO_CHO;
    v = Math.max(min, Math.min(max, v));
    el.value = v;
    tinh();
}

function tinh() {
    const nl = parseInt(document.getElementById('so_nguoi_lon').value) || 1;
    const teEl = document.getElementById('so_tre_em');
    const te = teEl ? (parseInt(teEl.value) || 0) : 0;

    if (nl + te > SO_CHO) {
        alert('Tổng hành khách vượt số chỗ còn lại!');
        document.getElementById('so_nguoi_lon').value = Math.max(1, SO_CHO - te);
        return tinh();
    }

    const tongNL = nl * GIA_NL;
    const tongTE = te * GIA_TE;
    const tongGoc = tongNL + tongTE;
    const giam = Math.round(tongGoc * PCT_GIAM / 100);
    const cuoi = tongGoc - giam;

    // Update summary
    document.getElementById('psum-nl').textContent = nl;
    document.getElementById('psum-nl-total').textContent = fmt(tongNL);

    if (teEl) {
        const rowTE = document.getElementById('prow-te');
        if (rowTE) {
            rowTE.style.display = te > 0 ? 'flex' : 'none';
            document.getElementById('psum-te').textContent = te;
            document.getElementById('psum-te-total').textContent = fmt(tongTE);
        }
    }

    const discRow = document.getElementById('prow-disc');
    if (discRow) {
        discRow.style.display = giam > 0 ? 'flex' : 'none';
        document.getElementById('psum-disc').textContent = '-' + fmt(giam);
    }

    document.getElementById('psum-total').textContent = fmt(cuoi);
    document.getElementById('input-tong-tien').value = cuoi;
}

tinh();
</script>
@endsection
