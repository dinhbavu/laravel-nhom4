@extends('layouts.3d-app')
@section('title', $bai_viet->tieu_de . ' - Cẩm Nang VietGo')

@section('css')
<style>
    .article-container { padding: 4rem 0 6rem; position: relative; z-index: 10; margin-top: 50px; }
    .article-grid { display: grid; grid-template-columns: 1fr 320px; gap: 2.5rem; }
    @media (max-width: 1024px) { .article-grid { grid-template-columns: 1fr; } }
    
    .article-main { background: rgba(255,255,255,0.04); backdrop-filter: blur(15px); border-radius: 24px; padding: 2.5rem; border: 1px solid rgba(255,255,255,0.06); }
    .article-header { margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 1.5rem; }
    .article-badge { display: inline-block; padding: 5px 14px; background: rgba(74,222,128,0.12); color: #4ade80; border-radius: 50px; font-weight: 700; font-size: 0.85rem; margin-bottom: 1rem; }
    .article-title { font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 1.5rem; }
    .article-meta { display: flex; gap: 20px; color: rgba(255,255,255,0.4); font-size: 0.9rem; font-weight: 500; }
    .article-meta span { display: flex; align-items: center; gap: 7px; }
    
    .article-img { width: 100%; height: 450px; object-fit: cover; border-radius: 20px; margin-bottom: 2.5rem; }
    
    /* Content styling from editor */
    .article-content { font-size: 1.15rem; line-height: 1.8; color: rgba(255,255,255,0.7); }
    .article-content h2 { font-size: 1.75rem; font-weight: 800; color: #fff; margin: 2rem 0 1rem; }
    .article-content h3 { font-size: 1.4rem; font-weight: 800; color: #fff; margin: 1.5rem 0 1rem; }
    .article-content p { margin-bottom: 1.5rem; }
    .article-content img { max-width: 100%; height: auto; border-radius: 12px; margin: 1.5rem 0; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
    .article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .article-content li { margin-bottom: 0.5rem; }
    
    .sidebar-widget { background: rgba(255,255,255,0.04); backdrop-filter: blur(15px); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 2rem; }
    .widget-title { font-size: 1.1rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 10px; }
    .widget-title i { color: #4ade80; }
    
    .related-item { display: flex; gap: 12px; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px dashed rgba(255,255,255,0.06); }
    .related-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .related-thumb { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
    .related-item-title { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.8); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .related-item-meta { font-size: 0.75rem; color: rgba(255,255,255,0.35); margin-top: 4px; }
</style>
@endsection

@section('content')
<div class="article-container">
    <div class="container">
        <div class="article-grid">
            <div class="article-main animate-fade-in">
                <div class="article-header">
                    <span class="article-badge">{{ $bai_viet->chuyen_muc_ten }}</span>
                    <h1 class="article-title">{{ $bai_viet->tieu_de }}</h1>
                    <div class="article-meta">
                        <span><i class="fa-regular fa-calendar"></i> {{ $bai_viet->ngay_dang }}</span>
                        <span><i class="fa-regular fa-user"></i> Bởi {{ $bai_viet->nguoiDang->ho_ten }}</span>
                        <span><i class="fa-regular fa-eye"></i> {{ $bai_viet->luot_xem }} lượt xem</span>
                    </div>
                </div>

                @if($bai_viet->hinh_anh)
                <img src="{{ $bai_viet->hinh_anh_url }}" alt="{{ $bai_viet->tieu_de }}" class="article-img">
                @endif

                <div class="article-content">
                    {!! $bai_viet->noi_dung !!}
                </div>

                <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center;">
                    <div style="color: rgba(255,255,255,0.5); font-weight: 600;">Chia sẻ bài viết:</div>
                    <div style="display: flex; gap: 10px;">
                        <a href="#" style="width:40px;height:40px;border-radius:50%;background:#1877f2;color:white;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,0.1);color:white;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" style="width:40px;height:40px;border-radius:50%;background:#e4405f;color:white;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <aside>
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fa-solid fa-fire"></i> Bài viết liên quan</h3>
                    <div class="related-list">
                        @forelse($tin_lien_quan as $tin)
                        <div class="related-item">
                            <img src="{{ $tin->hinh_anh_url }}" alt="Thumb" class="related-thumb">
                            <div class="related-info">
                                <a href="{{ route('cam-nang.chi-tiet', $tin->slug) }}">
                                    <h4 class="related-item-title">{{ $tin->tieu_de }}</h4>
                                </a>
                                <div class="related-item-meta">{{ $tin->ngay_dang }}</div>
                            </div>
                        </div>
                        @empty
                        <p style="font-size:0.9rem; color:rgba(255,255,255,0.35);">Không có bài viết liên quan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="sidebar-widget" style="background: linear-gradient(135deg, #059669, #047857); color: white; border: none;">
                    <h3 class="widget-title" style="color: white;"><i class="fa-solid fa-gift"></i> Nhận ưu đãi mới</h3>
                    <p style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 1.25rem;">Đăng ký nhận thông tin về các tour khuyến mãi và cẩm nang du lịch mới nhất.</p>
                    <input type="email" placeholder="Email của bạn..." style="width:100%; padding:10px; border-radius:10px; border:none; margin-bottom:10px; background:rgba(255,255,255,0.15); color:white;">
                    <button class="btn btn-primary btn-full" style="background: white; color: #059669; box-shadow: none;">Đăng ký ngay</button>
                </div>
            </aside>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif
@endsection
