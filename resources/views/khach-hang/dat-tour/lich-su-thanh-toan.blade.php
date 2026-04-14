@extends('layouts.khach-hang')
@section('title', 'Lịch Sử Thanh Toán - VietGo')

@section('css')
<style>
.payment-list-page { padding: 2.5rem 0 5rem; }
.ol-grid { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start; }
@media(max-width:900px){ .ol-grid { grid-template-columns: 1fr; } }

/* Sidebar consistency fix */
.profile-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(15px);
}
.avatar-upload-wrapper { text-align: center; margin-bottom: 1rem; }
.avatar-preview { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(74, 222, 128, 0.3); }
.profile-name { font-size: 1.1rem; font-weight: 700; color: #fff; text-align: center; margin-bottom: .2rem; }
.profile-email { font-size: .8rem; color: rgba(255, 255, 255, 0.5); text-align: center; }
.profile-nav { margin-top: 1.5rem; }
.profile-nav-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .75rem 1rem; border-radius: 12px; text-decoration: none;
    font-size: .875rem; font-weight: 600; color: rgba(255, 255, 255, 0.6);
    transition: all .25s; margin-bottom: .25rem;
}
.profile-nav-item:hover, .profile-nav-item.active { background: rgba(74, 222, 128, 0.1); color: #4ade80; }
.profile-nav-item i { width: 1.1rem; text-align: center; color: #4ade80; }

/* Order cards */
.order-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(15px);
    margin-bottom: 1.25rem; transition: all .3s;
}
.order-card:hover { transform: translateY(-2px); border-color: rgba(74, 222, 128, 0.3); }
.badge { display: inline-block; padding: .35rem .75rem; border-radius: 6px; font-weight: 700; font-size: .75rem; }
.b-success { background: rgba(74, 222, 128, 0.1); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.2); }
.b-pending { background: rgba(254, 249, 195, 0.1); color: #fef08a; border: 1px solid rgba(254, 249, 195, 0.2); }
.b-failed { background: rgba(239, 68, 68, 0.1); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.2); }

/* Empty state */
.empty-state { 
    text-align: center; padding: 4rem 2rem; 
    background: rgba(255, 255, 255, 0.03); border-radius: 16px; 
    border: 2px dashed rgba(255,255,255,0.1); 
}
</style>
@endsection

@section('content')
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <h1 style="font-family:var(--font-heading);font-size:1.85rem;font-weight:900;color:white;margin:0 0 .3rem;">
            💳 Lịch Sử Thanh Toán
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.9rem;">Theo dõi các giao dịch của bạn</p>
    </div>
</div>

<div class="container" style="margin-top:-2rem;">
    <div class="payment-list-page">
        <div class="ol-grid">
            {{-- ═══ SIDEBAR ═══ --}}
            <div>
                <div class="profile-card">
                    <div class="avatar-upload-wrapper">
                        <img src="{{ auth()->user()->anh_dai_dien_url }}" alt="Avatar" class="avatar-preview">
                    </div>
                    <div class="profile-name">{{ auth()->user()->ho_ten }}</div>
                    <div class="profile-email">{{ auth()->user()->email }}</div>
                    <nav class="profile-nav">
                        <a href="{{ route('khach-hang.ho-so') }}" class="profile-nav-item">
                            <i class="fa-regular fa-user"></i> Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="profile-nav-item">
                            <i class="fa-solid fa-clipboard-list"></i> Lịch sử đặt tour
                        </a>
                        <a href="{{ route('khach-hang.dat-tour.thanh-toan') }}" class="profile-nav-item active">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử thanh toán
                        </a>
                        <a href="{{ route('khach-hang.yeu-thich') }}" class="profile-nav-item">
                            <i class="fa-regular fa-heart"></i> Tour yêu thích
                        </a>
                        <a href="{{ route('khach-hang.thong-bao') }}" class="profile-nav-item">
                            <i class="fa-regular fa-bell"></i> Thông báo
                        </a>
                    </nav>
                </div>
            </div>

            {{-- ═══ MAIN ═══ --}}
            <div>
                @if($danh_sach->count() > 0)
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
                    <div>
                        <h2 style="font-family:var(--font-heading);font-size:1.1rem;font-weight:900;color:#fff;margin:0 0 .15rem;">Giao Dịch Của Tôi</h2>
                        <p style="font-size:.8rem;color:rgba(255,255,255,0.5);margin:0;">Tổng cộng <strong>{{ $danh_sach->total() }}</strong> giao dịch</p>
                    </div>
                </div>

                @foreach($danh_sach as $tt)
                <div class="order-card" style="padding: 1.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1rem; border-bottom:1px solid #f1f5f9; padding-bottom:1rem;">
                        <div>
                            <div style="font-size:.8rem; color:#64748b; margin-bottom:.3rem;">
                                <i class="fa-regular fa-clock"></i> {{ $tt->created_at->format('d/m/Y H:i') }}
                            </div>
                            <a href="{{ route('khach-hang.dat-tour.chi-tiet', $tt->datTour) }}" style="font-family:var(--font-heading);font-size:1.1rem;font-weight:800;color:var(--primary);text-decoration:none;display:block;margin-bottom:.25rem;transition:color .2s;" onmouseover="this.style.color='#059669'" onmouseout="this.style.color='var(--primary)'">
                                #{{ $tt->datTour->ma_dat_tour }}
                            </a>
                            <div style="font-size:.85rem;color:rgba(255,255,255,0.7);">
                                {{ \Illuminate\Support\Str::limit($tt->datTour->lichKhoiHanh->tour->ten_tour, 80) }}
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-family:var(--font-heading);font-size:1.3rem;font-weight:900;color:#ef4444;margin-bottom:.3rem;">
                                {{ number_format($tt->so_tien, 0, ',', '.') }}đ
                            </div>
                            @if($tt->trang_thai === 'thanh_cong')
                                <span class="badge b-success"><i class="fa-solid fa-check"></i> Thành công</span>
                            @elseif($tt->trang_thai === 'cho_xu_ly')
                                <span class="badge b-pending"><i class="fa-solid fa-clock"></i> Chờ xử lý</span>
                            @else
                                <span class="badge b-failed"><i class="fa-solid fa-xmark"></i> Thất bại</span>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; font-size:.85rem; color:#64748b;">
                        <div>
                            <span style="color:rgba(255,255,255,0.5);">Phương thức: </span>
                            <strong style="color:#fff;">{{ $tt->phuong_thuc_ten }}</strong>
                        </div>
                        <a href="{{ route('khach-hang.dat-tour.chi-tiet', $tt->datTour) }}" class="btn btn-primary" style="padding:.5rem 1.25rem; border-radius:10px; font-size:.85rem; font-weight:700;">
                            Chi tiết đơn <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                <div style="margin-top:1.5rem;display:flex;justify-content:center;">
                    {{ $danh_sach->links('pagination::bootstrap-4') }}
                </div>
                @else
                <div class="empty-state">
                    <div style="font-size:3rem;color:#cbd5e1;margin-bottom:1rem;"><i class="fa-solid fa-receipt"></i></div>
                    <h3 style="font-family:var(--font-heading);font-weight:800;color:#0f172a;margin:0 0 .5rem;">Chưa Có Giao Dịch Nào</h3>
                    <p style="color:#64748b;font-size:.9rem;margin-bottom:1.5rem;">Bạn chưa thực hiện thanh toán nào trên hệ thống.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
