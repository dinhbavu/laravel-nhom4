@extends('layouts.3d-app')
@section('title', 'Cẩm Nang Du Lịch - VietGo')

@section('css')
<style>
    .cam-nang-hero { padding: 8rem 0 4rem; text-align: center; color: #fff; margin-bottom: 3rem; position: relative; z-index: 10; }
    .cam-nang-hero h1 { font-family: 'Outfit', sans-serif; font-size: clamp(2.5rem, 4vw, 4rem); font-weight: 900; margin-bottom: 1rem; }
    .cam-nang-hero h1 span { color: #4ade80; }
    .cam-nang-hero p { font-size: 1.1rem; color: rgba(255,255,255,0.55); max-width: 600px; margin: 0 auto; line-height: 1.7; }
    
    .category-tabs { display: flex; justify-content: center; gap: 10px; margin-bottom: 3rem; flex-wrap: wrap; position: relative; z-index: 10; }
    .cat-tab { padding: 10px 24px; border-radius: 50px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); font-weight: 700; font-size: 0.95rem; transition: all 0.3s; backdrop-filter: blur(5px); text-decoration: none; }
    .cat-tab:hover, .cat-tab.active { background: #4ade80; color: #0f172a; border-color: #4ade80; transform: translateY(-2px); box-shadow: 0 5px 20px rgba(74,222,128,0.35); }
    
    .cn-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 4rem; position: relative; z-index: 10; }
    @media (max-width: 768px) { .cn-grid { grid-template-columns: 1fr; } }
    
    .cn-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s; height: 100%; display: flex; flex-direction: column; }
    .cn-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 20px rgba(74,222,128,0.08); border-color: rgba(74,222,128,0.25); background: rgba(255,255,255,0.07); }
    .cn-card-img { height: 220px; position: relative; overflow: hidden; }
    .cn-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .cn-card:hover .cn-card-img img { transform: scale(1.1); }
    .cn-badge { position: absolute; top: 15px; left: 15px; background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.1); padding: 4px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; z-index: 2; }
    
    .cn-card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .cn-card-meta { display: flex; gap: 15px; font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-bottom: 1rem; }
    .cn-card-meta span { display: flex; align-items: center; gap: 5px; }
    .cn-card-title { font-size: 1.25rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 3.5rem; text-decoration: none; }
    .cn-card:hover .cn-card-title { color: #4ade80; }
    .cn-card-text { font-size: 0.95rem; color: rgba(255,255,255,0.5); line-height: 1.7; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 1.5rem; }
    .cn-card-link { margin-top: auto; display: flex; align-items: center; gap: 8px; font-weight: 800; color: #4ade80; font-size: 0.95rem; text-decoration: none; }
    .cn-card-link i { transition: transform 0.3s; }
    .cn-card:hover .cn-card-link i { transform: translateX(5px); }
</style>
@endsection

@section('content')
<div class="cam-nang-hero animate-fade-in">
    <div class="container">
        <h1>Cẩm Nang <span>Du Lịch</span></h1>
        <p>Khám phá bí kíp du lịch, ẩm thực vùng miền và những điểm đến tuyệt vời nhất cùng VietGo.</p>
    </div>
</div>

<div class="container">
    <div class="category-tabs">
        <a href="{{ route('cam-nang.danh-sach') }}" class="cat-tab {{ !request('chuyen_muc') ? 'active' : '' }}">Tất cả</a>
        <a href="{{ route('cam-nang.danh-sach', ['chuyen_muc' => 'meo_du_lich']) }}" class="cat-tab {{ request('chuyen_muc') == 'meo_du_lich' ? 'active' : '' }}">Mẹo Du Lịch</a>
        <a href="{{ route('cam-nang.danh-sach', ['chuyen_muc' => 'diem_den']) }}" class="cat-tab {{ request('chuyen_muc') == 'diem_den' ? 'active' : '' }}">Điểm Đến</a>
        <a href="{{ route('cam-nang.danh-sach', ['chuyen_muc' => 'am_thuc']) }}" class="cat-tab {{ request('chuyen_muc') == 'am_thuc' ? 'active' : '' }}">Ẩm Thực</a>
    </div>

    <div class="cn-grid">
        @forelse($danh_sach as $item)
        <div class="cn-card">
            <div class="cn-card-img">
                <div class="cn-badge">{{ $item->chuyen_muc_ten }}</div>
                <img src="{{ $item->hinh_anh_url }}" alt="{{ $item->tieu_de }}">
            </div>
            <div class="cn-card-body">
                <div class="cn-card-meta">
                    <span><i class="fa-regular fa-calendar"></i> {{ $item->ngay_dang }}</span>
                    <span><i class="fa-regular fa-eye"></i> {{ $item->luot_xem }} lượt xem</span>
                </div>
                <a href="{{ route('cam-nang.chi-tiet', $item->slug) }}">
                    <h3 class="cn-card-title">{{ $item->tieu_de }}</h3>
                </a>
                <p class="cn-card-text">{{ $item->tom_tat ?? Str::limit(strip_tags($item->noi_dung), 120) }}</p>
                <a href="{{ route('cam-nang.chi-tiet', $item->slug) }}" class="cn-card-link">
                    Đọc chi tiết <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @empty
            <div style="grid-column: 1/-1; padding: 4rem 1rem; text-align: center; background: rgba(255,255,255,0.04); backdrop-filter: blur(10px); border-radius: 20px; border: 1px dashed rgba(74,222,128,0.3); color: rgba(255,255,255,0.5);">
                <i class="fa-solid fa-feather-pointed" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p style="font-size: 1.1rem; font-weight: 600;">Chưa có bài viết nào trong chuyên mục này.</p>
            </div>
        @endforelse
    </div>

    @if($danh_sach->hasPages())
    <div style="margin-bottom: 5rem; position: relative; z-index: 10;">
        {{ $danh_sach->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection

@section('js')
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif
@endsection
