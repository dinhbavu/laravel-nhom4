@extends('layouts.khach-hang')
@section('title', 'Tour Yêu Thích - VietGo')

@section('content')
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <h1 style="font-family:var(--font-heading);font-size:1.85rem;font-weight:900;color:white;margin:0 0 .3rem;">
            <i class="fa-regular fa-heart text-primary mr-2" style="color:#4ade80 !important;"></i> Tour Yêu Thích
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.9rem;">Các tour bạn đã lưu để xem lại</p>
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
                    <a href="{{ route('khach-hang.yeu-thich') }}" class="profile-nav-item active"><i class="fa-regular fa-heart"></i> Tour yêu thích</a>
                    <a href="{{ route('khach-hang.thong-bao') }}" class="profile-nav-item"><i class="fa-regular fa-bell"></i> Thông báo</a>
                </nav>
            </div>
        </div>
        <div>
            <div class="section-card animate-fade-in">
                <div class="section-card-title"><i class="fa-solid fa-heart"></i> Tour đã lưu</div>
                @if(isset($yeu_thich) && $yeu_thich->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
                    @foreach($yeu_thich as $yt)
                    <div class="tour-card-v2">
                        <div class="tour-img-box">
                            <a href="{{ route('tour.chi-tiet', $yt->tour) }}">
                                <img src="{{ $yt->tour->hinh_bia_url }}" alt="{{ $yt->tour->ten_tour }}" loading="lazy">
                            </a>
                        </div>
                        <div class="tour-body-v2">
                            <a href="{{ route('tour.chi-tiet', $yt->tour) }}"><h3 class="tour-title-v2">{{ $yt->tour->ten_tour }}</h3></a>
                            <div class="tour-footer-v2">
                                <span style="font-weight:800;color:var(--primary);">{{ $yt->tour->gia_nguoi_lon_dinh_dang }}</span>
                                <a href="{{ route('tour.chi-tiet', $yt->tour) }}" class="tour-btn-v2">Xem <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fa-regular fa-heart"></i>
                    <h4 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">Chưa có tour yêu thích</h4>
                    <p class="text-light mb-6">Lưu tour bạn thích để dễ tìm lại!</p>
                    <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary">Khám phá tour</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
