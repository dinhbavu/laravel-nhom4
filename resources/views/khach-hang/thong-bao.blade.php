@extends('layouts.khach-hang')
@section('title', 'Thông Báo - VietGo')

@section('css')
<style>
@media(max-width:768px){
    .profile-container{grid-template-columns:1fr!important}
    .profile-sidebar{position:static!important}
}
</style>
@endsection

@section('content')
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <h1 style="font-family:var(--font-heading);font-size:1.85rem;font-weight:900;color:white;margin:0 0 .3rem;">
            <i class="fa-regular fa-bell text-primary mr-2" style="color:#4ade80 !important;"></i> Thông Báo
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.9rem;">Cập nhật về đặt tour và ưu đãi</p>
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
                    <a href="{{ route('khach-hang.yeu-thich') }}" class="profile-nav-item"><i class="fa-regular fa-heart"></i> Tour yêu thích</a>
                    <a href="{{ route('khach-hang.thong-bao') }}" class="profile-nav-item active"><i class="fa-regular fa-bell"></i> Thông báo</a>
                </nav>
            </div>
        </div>
        <div>
            <div class="section-card animate-fade-in">
                <div class="section-card-title"><i class="fa-solid fa-bell"></i> Thông Báo Của Bạn</div>
                <div class="empty-state">
                    <i class="fa-regular fa-bell-slash"></i>
                    <h4 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">Chưa có thông báo mới</h4>
                    <p class="text-light mb-6">Các thông báo về đặt tour và khuyến mãi sẽ hiển thị tại đây.</p>
                    <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary">Xem tour ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
