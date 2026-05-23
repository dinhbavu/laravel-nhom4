@extends('layouts.3d-app')
@section('title', 'Ưu Đãi Đặc Biệt - VietGo')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
/* ══ Hero Banner Slider ══ */
.promo-hero-wrap {
    padding: 8rem 0 0;
    position: relative; z-index: 10;
}
.promo-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    text-align: center;
}
.promo-hero-label {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(74,222,128,.12); border: 1px solid rgba(74,222,128,.25);
    color: #4ade80; padding: .35rem 1rem; border-radius: 999px;
    font-size: .8rem; font-weight: 700; letter-spacing: .05em;
    text-transform: uppercase; margin-bottom: 1.25rem;
}
.promo-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900; color: #fff; line-height: 1.1;
    margin-bottom: .5rem;
}
.promo-hero-title span { color: #4ade80; }
.promo-hero-sub {
    color: rgba(255,255,255,0.55); font-size: 1.1rem; max-width: 500px;
    margin: 0 auto 2.5rem;
}

.banner-slider-wrap {
    position: relative;
    max-width: 1200px; margin: 0 auto;
    padding: 0 1.5rem;
}
.promo-swiper {
    border-radius: 20px;
    overflow: hidden;
    height: 420px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.06);
}
@media(max-width:768px){ .promo-swiper { height: 220px; } }

.promo-swiper .swiper-slide { position: relative; }
.promo-swiper .swiper-slide a { display: block; width: 100%; height: 100%; }
.promo-swiper .swiper-slide img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 6s ease;
}
.promo-swiper .swiper-slide-active img { transform: scale(1.05); }
.slide-gradient {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(6,20,40,.65) 0%, rgba(6,20,40,.1) 50%, transparent 100%);
}
.slide-caption {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 2.5rem 2rem 2rem;
    color: white;
}
.slide-badge {
    display: inline-block;
    background: #ef4444; color: white;
    font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em;
    padding: .3rem .85rem; border-radius: 999px; margin-bottom: .75rem;
}
.slide-title {
    font-family: var(--font-heading);
    font-size: clamp(1.25rem, 3vw, 1.85rem);
    font-weight: 800; line-height: 1.2; margin: 0;
    text-shadow: 0 2px 12px rgba(0,0,0,.4);
    color: white !important;
}
.swiper-button-next, .swiper-button-prev {
    color: white !important;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(6px);
    width: 44px !important; height: 44px !important;
    border-radius: 50%;
}
.swiper-button-next::after, .swiper-button-prev::after { font-size: 1rem !important; font-weight: 900 !important; }
.swiper-pagination-bullet { background: white !important; opacity: .5 !important; }
.swiper-pagination-bullet-active { opacity: 1 !important; width: 24px !important; border-radius: 999px !important; transition: width .3s; }

/* Empty banner state */
.banner-empty {
    background: linear-gradient(135deg, #1e293b, #0f4c35);
    border-radius: 20px; overflow: hidden;
    height: 280px; display: flex; align-items: center; justify-content: center;
    text-align: center; color: white;
}

/* ══ Section below hero ══ */
.promo-content { padding: 3rem 0 4rem; }

/* ══ Tour Discount Cards ══ */
.disc-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.75rem;
}
.disc-card {
    width: 100%;
    max-width: 360px;
    flex: 1 1 300px;
    background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(15px); border-radius: 18px; overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform .35s cubic-bezier(.23,1,.32,1), box-shadow .35s;
    display: flex; flex-direction: column;
}
.disc-card:hover { transform: translateY(-7px); box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 20px rgba(74,222,128,0.08); border-color: rgba(74,222,128,0.25); background: rgba(255,255,255,0.07); }
.disc-thumb { position: relative; aspect-ratio: 16/9; overflow: hidden; }
.disc-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.disc-card:hover .disc-thumb img { transform: scale(1.06); }
.disc-badge {
    position: absolute; top: .85rem; right: .85rem;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white; font-size: 1rem; font-weight: 900;
    padding: .35rem .9rem; border-radius: 10px;
    box-shadow: 0 4px 12px rgba(239,68,68,.35);
    z-index: 5;
}
.disc-body { padding: 1.25rem 1.5rem; flex: 1; display: flex; flex-direction: column; }
.disc-meta { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: .65rem; }
.disc-chip { font-size: .75rem; color: rgba(255,255,255,0.5); font-weight: 600; display: flex; align-items: center; gap: .3rem; }
.disc-chip i { color: #4ade80; }
.disc-name {
    font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 800;
    color: #fff; margin-bottom: 1rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .2s;
}
.disc-card:hover .disc-name { color: #4ade80; }
.disc-footer {
    margin-top: auto; display: flex; align-items: flex-end; justify-content: space-between;
    padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06);
}
.disc-prices { display: flex; flex-direction: column; }
.price-orig { font-size: .8rem; color: rgba(255,255,255,0.4); text-decoration: line-through; }
.price-sale {
    font-family: 'Outfit', sans-serif; font-size: 1.3rem; font-weight: 900; color: #ef4444;
}
.disc-btn {
    width: 2.5rem; height: 2.5rem; border-radius: 10px;
    background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25);
    display: flex; align-items: center; justify-content: center;
    color: #4ade80; transition: all .3s; text-decoration: none; font-size: .9rem;
}
.disc-card:hover .disc-btn { background: #4ade80; border-color: #4ade80; color: #0f172a; transform: rotate(45deg); box-shadow: 0 0 15px rgba(74,222,128,0.5); }

/* Section header */
.s-header { display: flex; align-items: center; justify-content: center; flex-direction: column; text-align: center; gap: 1rem; margin-bottom: 2.5rem; position: relative; z-index: 10; }
.s-header-text h2 {
    font-family: 'Outfit', sans-serif; font-size: clamp(1.8rem, 4vw, 2.2rem); font-weight: 900;
    color: #fff; margin: 0 0 .5rem;
}
.s-header-text p { font-size: .95rem; color: rgba(255,255,255,0.65); margin: 0; }
.s-icon {
    width: 60px; height: 60px; border-radius: 18px;
    background: rgba(74,222,128,0.15); color: #4ade80;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; flex-shrink: 0; margin: 0 auto; box-shadow: 0 0 20px rgba(74,222,128,0.1);
}

/* Empty state */
.empty-promo {
    text-align: center; padding: 5rem 2rem; position: relative; z-index: 10;
    background: rgba(255,255,255,0.04); backdrop-filter: blur(10px);
    border-radius: 20px; border: 2px dashed rgba(74,222,128,0.3);
}
</style>
@endsection

@section('content')

{{-- ══ HERO + BANNER SLIDER ══ --}}
<div class="promo-hero-wrap">
    <div class="promo-hero-inner">
        <div class="promo-hero-label">
            <i class="fa-solid fa-tags"></i> Ưu Đãi Đặc Biệt
        </div>
        <h1 class="promo-hero-title">Ưu Đãi <span>Hot</span> Nhất Hôm Nay</h1>
        <p class="promo-hero-sub">Khám phá những tour giảm giá cực sốc, tiết kiệm tối đa chi phí chuyến đi!</p>
    </div>

    <div class="banner-slider-wrap">
        @if($banners->isNotEmpty())
        <div class="swiper promo-swiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                <div class="swiper-slide">
                    <a href="{{ $banner->duong_dan ?: '#' }}">
                        <img src="{{ $banner->hinh_anh_url }}" alt="{{ $banner->tieu_de }}" loading="lazy">
                        <div class="slide-gradient"></div>
                        <div class="slide-caption">
                            <div class="slide-badge">🔥 Sự kiện đặc biệt</div>
                            <h2 class="slide-title">{{ $banner->tieu_de }}</h2>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        @else
        <div class="banner-empty">
            <div>
                <div style="font-size:3.5rem;margin-bottom:1rem;">🎉</div>
                <h3 style="font-size:1.25rem;font-weight:800;margin-bottom:.5rem;">VietGo sắp có sự kiện mới!</h3>
                <p style="color:rgba(255,255,255,.65);font-size:.9rem;">Hãy quay lại để không bỏ lỡ những ưu đãi hấp dẫn nhất.</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ══ TOUR KHUYẾN MÃI ══ --}}
<div class="promo-content">
    <div class="container">
        @if($tours_giam_gia->isNotEmpty())
        <div class="s-header">
            <div class="s-icon">🏷️</div>
            <div class="s-header-text">
                <h2>Tour Khuyến Mãi Hấp Dẫn</h2>
                <p>{{ $tours_giam_gia->count() }} tour đang được giảm giá — đặt ngay kẻo hết!</p>
            </div>
        </div>

        <div class="disc-grid">
            @foreach($tours_giam_gia as $tour)
            <article class="disc-card">
                <div class="disc-thumb">
                    <div class="disc-badge">-{{ $tour->phan_tram_giam_gia }}%</div>
                    <img src="{{ $tour->hinh_bia_url }}" alt="{{ $tour->ten_tour }}" loading="lazy">
                </div>
                <div class="disc-body">
                    <div class="disc-meta">
                        <span class="disc-chip"><i class="fa-solid fa-location-dot"></i>{{ $tour->diemDen->ten_diem_den }}</span>
                        <span class="disc-chip"><i class="fa-regular fa-clock"></i>{{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</span>
                    </div>
                    <a href="{{ route('tour.chi-tiet', $tour) }}">
                        <h3 class="disc-name">{{ $tour->ten_tour }}</h3>
                    </a>
                    <div class="disc-footer">
                        <div class="disc-prices">
                            <span class="price-orig">{{ $tour->gia_nguoi_lon_dinh_dang }}</span>
                            <span class="price-sale">{{ $tour->gia_khuyen_mai_nguoi_lon_dinh_dang }}</span>
                        </div>
                        <a href="{{ route('tour.chi-tiet', $tour) }}" class="disc-btn" title="Xem chi tiết">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="empty-promo">
            <div style="font-size:4rem;margin-bottom:1.25rem;">🎫</div>
            <h3 style="font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:.5rem;">Chưa có tour khuyến mãi</h3>
            <p style="color:rgba(255,255,255,0.5);max-width:360px;margin:0 auto 1.5rem;">VietGo đang chuẩn bị những ưu đãi bất ngờ. Hãy quay lại sớm nhé!</p>
            <a href="{{ route('tour.danh-sach') }}" class="btn btn-primary">Xem tất cả tour</a>
        </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
@if($banners->isNotEmpty())
new Swiper('.promo-swiper', {
    loop: {{ $banners->count() > 1 ? 'true' : 'false' }},
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    effect: 'fade',
    fadeEffect: { crossFade: true },
    speed: 800,
});
@endif
</script>
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif
@endsection
