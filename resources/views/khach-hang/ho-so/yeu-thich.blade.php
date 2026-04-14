@extends('layouts.khach-hang')
@section('title', 'Tour Yêu Thích - VietGo')

@section('css')
<style>
@media(max-width:768px){
    .profile-container{grid-template-columns:1fr!important}
    .profile-sidebar{position:static!important}
}
.yeu-thich-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem}
@media(max-width:640px){.yeu-thich-grid{grid-template-columns:1fr}}
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title"><i class="fa-regular fa-heart text-primary mr-2"></i>Tour Yêu Thích</h1>
        <p class="page-header-sub">Các tour bạn đã lưu để xem lại</p>
    </div>
</div>

<div class="container pb-16">
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="avatar-upload-wrapper">
                    <img src="{{ auth()->user()->anh_dai_dien_url }}" alt="Avatar" class="avatar-preview">
                </div>
                <div class="profile-name">{{ auth()->user()->ho_ten }}</div>
                <div class="profile-email">{{ auth()->user()->email }}</div>
                <nav class="profile-nav">
                    <a href="{{ route('khach-hang.ho-so') }}" class="profile-nav-item"><i class="fa-regular fa-user"></i> Hồ sơ cá nhân</a>
                    <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="profile-nav-item"><i class="fa-solid fa-clipboard-list"></i> Lịch sử đặt tour</a>
                    <a href="{{ route('khach-hang.dat-tour.thanh-toan') }}" class="profile-nav-item"><i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử thanh toán</a>
                    <a href="{{ route('khach-hang.yeu-thich') }}" class="profile-nav-item active"><i class="fa-regular fa-heart"></i> Tour yêu thích</a>
                    <a href="{{ route('khach-hang.thong-bao') }}" class="profile-nav-item"><i class="fa-regular fa-bell"></i> Thông báo</a>
                </nav>
            </div>
        </div>

        <div>
            <div class="section-card animate-fade-in">
                <div class="section-card-title"><i class="fa-solid fa-heart"></i> Tour đã lưu ({{ $danh_sach_yeu_thich->total() }})</div>
                @if($danh_sach_yeu_thich->total() > 0)
                <div class="yeu-thich-grid">
                    @foreach($danh_sach_yeu_thich as $yt)
                    <div class="tour-card-v2">
                        <div class="tour-img-box">
                            <a href="{{ route('tour.chi-tiet', $yt->tour) }}">
                                <img src="{{ $yt->tour->hinh_bia_url }}" alt="{{ $yt->tour->ten_tour }}" loading="lazy">
                            </a>
                            <div class="tour-img-price-tag">{{ $yt->tour->gia_nguoi_lon_dinh_dang }}</div>
                        </div>
                        <div class="tour-body-v2">
                            <div class="tour-meta">
                                <span><i class="fa-solid fa-location-dot text-primary mr-1"></i>{{ $yt->tour->diemDen->ten_diem_den }}</span>
                                <span><i class="fa-regular fa-clock mr-1"></i>{{ $yt->tour->so_ngay }}N{{ $yt->tour->so_dem }}Đ</span>
                            </div>
                            <a href="{{ route('tour.chi-tiet', $yt->tour) }}">
                                <h3 class="tour-title-v2">{{ $yt->tour->ten_tour }}</h3>
                            </a>
                            <div class="tour-stars" style="display:flex;align-items:center;gap:.25rem;color:#f59e0b;font-size:.8rem;margin-bottom:1rem;">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-{{ $i <= round($yt->tour->danh_gia_trung_binh) ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                                <span style="color:#64748b;margin-left:.25rem;font-size:.75rem;">({{ $yt->tour->danh_gia_trung_binh > 0 ? $yt->tour->danh_gia_trung_binh : 'Chưa có' }} sao)</span>
                            </div>
                            <div class="tour-footer-v2">
                                <a href="{{ route('tour.chi-tiet', $yt->tour) }}" class="tour-btn-v2">Xem tour <i class="fa-solid fa-arrow-right"></i></a>
<a href="{{ route('tour.chi-tiet', $yt->tour) }}" class="btn btn-sm btn-primary">
                                        Đặt ngay
                                    </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fa-regular fa-heart"></i>
                    <h4 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">Chưa có tour yêu thích</h4>
                    <p class="text-light mb-6">Lưu những tour bạn thích để dễ tìm lại sau nhé!</p>
                    <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary">Khám phá tour</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
