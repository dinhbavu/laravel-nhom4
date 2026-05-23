@extends('layouts.khach-hang')
@section('title', 'Lịch Sử Đặt Tour - VietGo')

@section('css')
<style>
.order-list-page { padding: 2.5rem 0 5rem; }
.ol-grid { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start; }
@media(max-width:900px){ .ol-grid { grid-template-columns: 1fr; } }

/* Sidebar consistency fix */
.profile-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(15px);
}
.avatar-preview { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(74, 222, 128, 0.3); }
.profile-name { font-size: 1.1rem; font-weight: 700; color: #fff; text-align: center; margin-bottom: .2rem; }
.profile-email { font-size: .8rem; color: rgba(255, 255, 255, 0.5); text-align: center; }

/* Order cards */
.order-card {
    background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(25px);
    margin-bottom: 1.25rem; transition: box-shadow .3s, transform .3s;
}
.order-card:hover { transform: translateY(-3px); border-color: rgba(74, 222, 128, 0.3); box-shadow: 0 15px 35px rgba(0,0,0,0.4); }
.order-card-top { display: flex; padding: 1.25rem; gap: 1.5rem; }
.order-thumb { width: 220px; height: 140px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
.order-thumb img { width: 100%; height: 100%; object-fit: cover; }
.order-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }
.order-tour-name { font-family: 'Outfit'; font-weight: 800; font-size: 1.15rem; color: #fff; text-decoration: none; margin-bottom: .5rem; transition: color .2s; }
.order-tour-name:hover { color: var(--primary-accent); }
.order-meta { display: flex; flex-wrap: wrap; gap: .75rem; margin-bottom: .6rem; }
.order-chip { display: flex; align-items: center; gap: .4rem; font-size: .8rem; color: rgba(255, 255, 255, 0.6); }
.order-chip i { color: var(--primary-accent); font-size: .75rem; }

.s-badge { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .75rem; border-radius: 8px; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .02em; }
.s-cho_duyet { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
.s-da_duyet { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
.s-hoan_thanh { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
.s-done { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.s-da_huy { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }

.order-bottom { 
    background: rgba(0,0,0,0.25); border-top: 1px solid rgba(255,255,255,0.06); 
    padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;
}
.order-ma { font-size: .85rem; color: rgba(255, 255, 255, 0.5); }
.order-ma strong { color: rgba(255, 255, 255, 0.9); }
.order-price-badge { font-family: 'Outfit'; font-weight: 900; color: #fff; font-size: 1.1rem; }
.btn-detail { color: var(--primary-accent); font-weight: 700; font-size: .85rem; display: flex; align-items: center; gap: .4rem; transition: transform .2s; }
.btn-detail:hover { transform: translateX(4px); color: #fff; }

@media(max-width:640px){
    .order-card-top { flex-direction: column; gap: 1rem; }
    .order-thumb { width: 100%; height: 180px; }
    .order-bottom { flex-direction: column; align-items: flex-start; }
}

/* Empty state */
.empty-bookings {
    text-align: center; padding: 5rem 2rem;
}
</style>
@endsection

@section('content')
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <h1 style="font-family:var(--font-heading);font-size:1.85rem;font-weight:900;color:white;margin:0 0 .3rem;">
            📋 Lịch Sử Đặt Tour
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.9rem;">Theo dõi và quản lý tất cả các chuyến đi của bạn</p>
    </div>
</div>

<div class="container" style="margin-top:-2rem;">
    <div class="order-list-page">
        <div class="ol-grid">
            {{-- ═══ SIDEBAR ═══ --}}
            <div>
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
                        <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="profile-nav-item active">
                            <i class="fa-solid fa-history"></i> Lịch sử đặt tour
                        </a>
                        <a href="{{ route('khach-hang.dat-tour.thanh-toan') }}" class="profile-nav-item">
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
            </div>

            {{-- ═══ MAIN ═══ --}}
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
                    <div>
                        <h2 style="font-family:var(--font-heading);font-size:1.1rem;font-weight:900;color:#fff;margin:0 0 .15rem;">Đơn Đặt Tour Của Tôi</h2>
                        <p style="font-size:.8rem;color:rgba(255,255,255,0.5);margin:0;">Tổng cộng <strong>{{ $danh_sach->total() }}</strong> đơn đặt tour</p>
                    </div>
                    <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary" style="font-size:.85rem;">
                        <i class="fa-solid fa-plus mr-1"></i> Đặt tour mới
                    </a>
                </div>

                @forelse($danh_sach as $dt)
                <div class="order-card">
                    <div class="order-card-top">
                        <div class="order-thumb">
                            <img src="{{ $dt->lichKhoiHanh->tour->hinh_bia_url }}" alt="{{ $dt->lichKhoiHanh->tour->ten_tour }}">
                        </div>
                        <div class="order-info">
                            <a href="{{ route('tour.chi-tiet', $dt->lichKhoiHanh->tour) }}" class="order-tour-name">
                                {{ $dt->lichKhoiHanh->tour->ten_tour }}
                            </a>
                            <div class="order-meta">
                                <span class="order-chip"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($dt->lichKhoiHanh->ngay_di)->format('d/m/Y') }}</span>
                                <span class="order-chip"><i class="fa-regular fa-clock"></i> {{ $dt->lichKhoiHanh->tour->so_ngay }}N{{ $dt->lichKhoiHanh->tour->so_dem }}Đ</span>
                                <span class="order-chip"><i class="fa-solid fa-users"></i> {{ $dt->so_nguoi_lon }} NL{{ $dt->so_tre_em > 0 ? ', ' . $dt->so_tre_em . ' TE' : '' }}</span>
                                <span class="order-chip"><i class="fa-solid fa-location-dot"></i> {{ $dt->lichKhoiHanh->tour->diemDen->ten_diem_den }}</span>
                            </div>
                            <div style="margin-top:.35rem;">
                                @php $badges=['cho_duyet'=>['🕐','Chờ duyệt'],'da_duyet'=>['✅','Đã duyệt'],'da_xac_nhan'=>['🎉','Xác nhận'],'da_huy'=>['❌','Đã hủy'],'hoan_thanh'=>['🏆','Hoàn thành'],'done'=>['✨','Hoàn tất']]; $b=$badges[$dt->trang_thai]??['📋',$dt->trang_thai]; @endphp
                                <span class="s-badge s-{{ $dt->trang_thai }}">{{ $b[0] }} {{ $b[1] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-bottom">
                        <div class="order-ma">
                            Mã đặt: <strong>#{{ $dt->ma_dat_tour }}</strong>
                            <span style="color:#cbd5e1;margin:0 .5rem;">•</span>
                            {{ $dt->created_at->format('d/m/Y') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <span class="order-price-badge">{{ number_format($dt->tong_tien_thanh_toan, 0, ',', '.') }}đ</span>
                            @if($dt->trang_thai === 'hoan_thanh')
                                @if(!$dt->danhGia)
                                    <button type="button" class="btn btn-secondary" style="font-size:.82rem;padding:.5rem 1.25rem;border-radius:10px;" onclick="openReviewModal('{{ route('khach-hang.dat-tour.danh-gia', $dt) }}', '{{ $dt->lichKhoiHanh->tour->ten_tour }}')">
                                        <i class="fa-solid fa-star text-warning mr-1"></i> Đánh giá
                                    </button>
                                @else
                                    <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:.78rem;padding:.5rem 1rem;border-radius:10px;">
                                        <i class="fa-solid fa-check mr-1"></i> Đã đánh giá
                                    </span>
                                @endif
                            @endif
                            @if(in_array($dt->trang_thai, ['hoan_thanh', 'done', 'da_xac_nhan']))
                            <a href="{{ route('khach-hang.dat-tour.hoa-don', $dt) }}" class="btn-detail" style="color:#f59e0b;" target="_blank">
                                <i class="fa-solid fa-file-invoice"></i> In hóa đơn
                            </a>
                            @endif
                            <a href="{{ route('khach-hang.dat-tour.chi-tiet', $dt) }}" class="btn-detail">
                                Xem chi tiết <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-bookings">
                    <div style="font-size:4rem;margin-bottom:1rem;">🌍</div>
                    <h3 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:.5rem;">Chưa có chuyến đi nào!</h3>
                    <p style="color:#64748b;max-width:320px;margin:0 auto 1.5rem;font-size:.9rem;">Hãy bắt đầu hành trình của bạn cùng VietGo — trải nghiệm những điểm đến tuyệt vời của Việt Nam!</p>
                    <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary">
                        <i class="fa-solid fa-search mr-1"></i> Khám phá tour ngay
                    </a>
                </div>
                @endforelse

                @if($danh_sach->hasPages())
                <div style="margin-top:1.5rem;display:flex;justify-content:center;">
                    {{ $danh_sach->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL ĐÁNH GIÁ TOUR --}}
<div id="reviewModal" class="modal-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div class="modal-content" style="background:white;width:90%;max-width:500px;border-radius:20px;padding:2rem;position:relative;animation: modalFadeIn 0.3s ease;">
        <button type="button" onclick="closeReviewModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;color:#94a3b8;cursor:pointer;">&times;</button>
        <h3 style="font-family:var(--font-heading);font-weight:800;font-size:1.35rem;margin:0 0 .5rem;">Đánh Giá Tour</h3>
        <p id="reviewTourName" style="font-size:.9rem;color:#64748b;margin-bottom:1.5rem;"></p>

        <form id="reviewForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:1.5rem;text-align:center;">
                <div class="star-rating" style="display:inline-flex;flex-direction:row-reverse;gap:0.5rem;">
                    <input type="radio" id="star5" name="diem_so" value="5" required style="display:none;"><label for="star5" title="Tuyệt vời" style="font-size:2rem;color:#cbd5e1;cursor:pointer;transition:color .2s;">★</label>
                    <input type="radio" id="star4" name="diem_so" value="4" style="display:none;"><label for="star4" style="font-size:2rem;color:#cbd5e1;cursor:pointer;transition:color .2s;">★</label>
                    <input type="radio" id="star3" name="diem_so" value="3" style="display:none;"><label for="star3" style="font-size:2rem;color:#cbd5e1;cursor:pointer;transition:color .2s;">★</label>
                    <input type="radio" id="star2" name="diem_so" value="2" style="display:none;"><label for="star2" style="font-size:2rem;color:#cbd5e1;cursor:pointer;transition:color .2s;">★</label>
                    <input type="radio" id="star1" name="diem_so" value="1" style="display:none;"><label for="star1" style="font-size:2rem;color:#cbd5e1;cursor:pointer;transition:color .2s;">★</label>
                </div>
                <div style="font-size:.85rem;color:#64748b;margin-top:.25rem;">(Bấm để chọn sao)</div>
                <style>
                    .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #f59e0b !important; }
                </style>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-size:.85rem;font-weight:700;margin-bottom:.5rem;">Cảm nhận của bạn <span style="color:#ef4444">*</span></label>
                <textarea name="noi_dung" rows="4" style="width:100%;padding:.75rem;border:1.5px solid #e2e8f0;border-radius:12px;outline:none;" placeholder="Chia sẻ kinh nghiệm và trải nghiệm của bạn (tối thiểu 10 ký tự)..." required minlength="10" maxlength="1000"></textarea>
            </div>

            <div style="display:flex;gap:1rem;">
                <button type="button" onclick="closeReviewModal()" class="btn btn-secondary" style="flex:1;">Hủy</button>
                <button type="submit" class="btn btn-primary" style="flex:1;"><i class="fa-solid fa-paper-plane mr-2"></i> Gửi Đánh Giá</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    function openReviewModal(actionUrl, tourName) {
        document.getElementById('reviewTourName').innerText = tourName;
        document.getElementById('reviewForm').action = actionUrl;
        document.getElementById('reviewModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
        document.body.style.overflow = '';
        document.getElementById('reviewForm').reset();
    }
</script>
@endsection
