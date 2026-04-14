@extends('layouts.3d-app')

@section('title', 'VietGo 3D - Không Gian Du Lịch Đa Chiều')

@section('css')
<style>
    /* =================== HERO SECTION =================== */
    .hero-section {
        height: 100vh; display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
    }
    .hero-overlay {
        text-align: center; max-width: 900px; padding: 0 20px; z-index: 10; pointer-events: none;
    }
    .hero-title {
        font-family: 'Outfit', sans-serif; font-size: clamp(3rem, 5vw, 5.5rem); font-weight: 900;
        line-height: 1.15; margin-bottom: 25px; text-transform: uppercase; letter-spacing: -1px;
        background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 20px rgba(74,222,128,0.15));
    }
    .hero-subtitle {
        font-size: clamp(1.1rem, 2vw, 1.4rem); color: rgba(255,255,255,0.75); margin-bottom: 40px;
        font-weight: 400; line-height: 1.7;
    }
    .hero-actions { display: flex; gap: 20px; justify-content: center; pointer-events: auto; flex-wrap: wrap; }
    
    .btn-3d {
        padding: 15px 40px; border-radius: 99px; font-size: 1.1rem; font-weight: 800; text-decoration: none;
        text-transform: uppercase; letter-spacing: 1px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-3d-primary { background: linear-gradient(135deg, #4ade80, #10b981); color: #0f172a; box-shadow: 0 10px 30px rgba(74,222,128,0.35); }
    .btn-3d-primary:hover { transform: translateY(-5px) scale(1.05); box-shadow: 0 15px 40px rgba(74,222,128,0.55); }
    .btn-3d-outline { background: rgba(255,255,255,0.04); color: #fff; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(10px); }
    .btn-3d-outline:hover { background: rgba(255,255,255,0.1); transform: translateY(-5px); border-color: rgba(255,255,255,0.35); }

    /* Carousel Controls at bottom */
    .tour-controls {
        position: absolute; bottom: 50px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; z-index: 10; pointer-events: auto;
    }
    .tour-slide-btn {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.8); padding: 10px 20px; border-radius: 30px;
        cursor: pointer; font-family: 'Outfit'; font-weight: 600; backdrop-filter: blur(5px); transition: all 0.3s;
    }
    .tour-slide-btn:hover { background: rgba(255,255,255,0.12); color: #fff; }
    .active-slide { background: #4ade80; color: #0f172a; border-color: #4ade80; box-shadow: 0 0 20px rgba(74,222,128,0.5); }

    /* =================== SCROLLING SECTIONS =================== */
    .glass-section {
        position: relative; z-index: 10; padding: 100px 20px;
    }
    .section-title {
        font-family: 'Outfit'; font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff;
        text-align: center; margin-bottom: 20px; text-transform: uppercase;
    }
    .section-title span { color: #4ade80; }
    .section-subtitle { text-align: center; color: rgba(255,255,255,0.55); max-width: 600px; margin: 0 auto 60px; font-size: 1.1rem; line-height: 1.7; }

    .grid-container { max-width: 1280px; margin: 0 auto; }
    .tours-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    @media(max-width:1024px) { .tours-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width:768px) { .tours-grid { grid-template-columns: 1fr; } }

    /* =================== WHY CHOOSE US =================== */
    .features-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;
    }
    @media(max-width:1024px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width:768px) { .features-grid { grid-template-columns: 1fr; } }

    .feature-card {
        background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 40px 30px;
        transition: all 0.4s ease;
    }
    .feature-card:hover {
        transform: translateY(-10px); border-color: rgba(74,222,128,0.35);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 25px rgba(74,222,128,0.08);
        background: rgba(255, 255, 255, 0.07);
    }
    .feature-icon {
        width: 60px; height: 60px; border-radius: 15px; margin-bottom: 25px;
        display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
    }
    .feature-card h3 { color: #fff; font-size: 1.3rem; font-weight: 800; font-family: 'Outfit'; margin-bottom: 15px; }
    .feature-card p { color: rgba(255,255,255,0.55); font-size: 0.95rem; line-height: 1.7; }

    /* =================== CARDS (Featured Tours) =================== */
    .card-3d {
        background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border-radius: 25px; overflow: hidden; transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid rgba(255, 255, 255, 0.06); display: flex; flex-direction: column;
        transform-style: preserve-3d; perspective: 1000px;
    }
    .card-3d:hover {
        transform: translateY(-15px) rotateX(5deg) scale(1.02);
        box-shadow: 0 30px 60px rgba(0,0,0,0.3), 0 0 30px rgba(74,222,128,0.08);
        border-color: rgba(74,222,128,0.25); background: rgba(255, 255, 255, 0.07);
    }
    .card-thumb { position: relative; aspect-ratio: 16/10; overflow: hidden; border-radius: 25px 25px 0 0; }
    .card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease; }
    .card-3d:hover .card-thumb img { transform: scale(1.15); }
    .card-thumb-glow { position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,1) 0%, transparent 70%); }
    .card-content { padding: 25px; display: flex; flex-direction: column; flex: 1; }
    
    .card-title {
        font-family: 'Outfit'; font-size: 1.4rem; font-weight: 800; color: #fff;
        margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;
    }
    .card-3d:hover .card-title { color: #4ade80; }
    .card-meta { display: flex; justify-content: space-between; color: rgba(255,255,255,0.5); font-size: 0.9rem; margin-bottom: 15px; font-weight: 600; }
    .card-meta i { color: #4ade80; margin-right: 5px; }

    /* =================== CTA BOTTOM =================== */
    .cta-banner {
        background: linear-gradient(135deg, rgba(74,222,128,0.1) 0%, rgba(59,130,246,0.08) 100%);
        border: 1px solid rgba(255,255,255,0.06); border-radius: 30px; padding: 80px 40px;
        text-align: center; position: relative; overflow: hidden; backdrop-filter: blur(20px);
    }
    .cta-banner::before {
        content:''; position:absolute; top:-50%; left:-50%; width:200%; height:200%;
        background: radial-gradient(circle, rgba(74,222,128,0.08) 0%, transparent 60%); pointer-events:none;
    }

    /* ========== MOBILE RESPONSIVE ========== */
    @media (max-width: 768px) {
        .hero-section { 
            height: 100vh; 
            padding: 0 25px; 
            display: flex;
            align-items: center; 
            justify-content: flex-start;
        }
        .hero-overlay { 
            text-align: left; 
            max-width: 100%; 
            padding: 0;
            margin-top: -80px; /* Offset to make room for bottom bar */
        }
        .hero-title { 
            font-size: 2.5rem !important; 
            letter-spacing: -1px; 
            margin-bottom: 15px;
            text-align: left;
            background: #fff;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle { 
            text-align: left !important;
            font-size: 0.95rem !important; 
            line-height: 1.6;
            margin-bottom: 30px; 
            color: rgba(255,255,255,0.7);
            padding-right: 10%;
        }
        .hero-actions { 
            flex-direction: column; 
            gap: 12px; 
            align-items: flex-start; 
            width: 100%;
        }
        .btn-3d { 
            padding: 14px 25px; 
            font-size: 0.95rem; 
            width: 100%; 
            max-width: 280px; 
            text-align: center; 
        }

        /* BALANCED BOTTOM BAR FOR DESTINATIONS */
        .tour-controls { 
            position: absolute; 
            bottom: 40px; 
            left: 50%; 
            transform: translateX(-50%); 
            width: calc(100% - 40px);
            max-width: 450px;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 6px; 
            border-radius: 100px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            z-index: 50;
        }
        .tour-slide-btn { 
            padding: 10px 5px; 
            font-size: 0.75rem; 
            border-radius: 100px;
            border: none;
            background: transparent;
            color: rgba(255,255,255,0.6);
            white-space: nowrap;
            text-align: center;
        }
        .active-slide {
            background: #4ade80 !important;
            color: #0f172a !important;
            box-shadow: 0 4px 15px rgba(74, 222, 128, 0.3);
        }

        .glass-section { padding: 60px 15px; }
        .section-title { font-size: 2.2rem !important; }
        .section-subtitle { font-size: 0.95rem; margin-bottom: 30px; }
        .feature-card { padding: 25px 20px; }
        .card-content { padding: 18px; }
        .card-title { font-size: 1.15rem; }
        .cta-banner { padding: 50px 20px; border-radius: 20px; }
    }

    @media (max-width: 480px) {
        .hero-title { font-size: 1.8rem !important; }
        .btn-3d { font-size: 0.85rem; padding: 12px 20px; }
        .tour-controls { gap: 6px; }
        .tour-slide-btn { padding: 6px 12px; font-size: 0.75rem; }
    }

    /* Tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
        .hero-title { font-size: clamp(2.5rem, 5vw, 3.5rem) !important; }
        .glass-section { padding: 80px 20px; }
        .cta-banner { padding: 60px 30px; }
    }
</style>
@endsection

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay">
        <h1 class="hero-title">Trải Nghiệm Du Lịch<br>Đỉnh Cao Cùng VietGo</h1>
        <p class="hero-subtitle">Bước vào thế giới của những chuyến đi không giới hạn. Tận hưởng vẻ đẹp hùng vĩ của thiên nhiên Việt Nam qua lăng kính 3D sống động trước khi xách balo lên và đi.</p>
        <div class="hero-actions">
            <a href="{{ route('tour.danh-sach') }}" class="btn-3d btn-3d-primary"><i class="fa-solid fa-map-location-dot"></i> Khám Phá Tour Chào Hè</a>
            <a href="#why-us" class="btn-3d btn-3d-outline">Lý Do Chọn VietGo</a>
        </div>
    </div>
    <div class="tour-controls">
        <button class="tour-slide-btn active-slide" data-index="0">Sapa sương mù</button>
        <button class="tour-slide-btn" data-index="1">Vịnh Hạ Long</button>
        <button class="tour-slide-btn" data-index="2">Hội An về đêm</button>
    </div>
</section>

<!-- Why Choose Us -->
<section id="why-us" class="glass-section" style="padding-top:40px;">
    <div class="grid-container">
        <div style="text-align:center; margin-bottom:15px; color:#4ade80; font-weight:800; text-transform:uppercase; font-size:0.9rem; letter-spacing:2px;" class="gs-reveal"><i class="fa-solid fa-gem"></i> Giá Trị Cốt Lõi</div>
        <h2 class="section-title gs-reveal">Lý Do Chọn <span>VietGo</span></h2>
        <p class="section-subtitle gs-reveal">Chúng tôi không chỉ cung cấp các hành trình du lịch, chúng tôi mang tới cho bạn những trải nghiệm đẳng cấp với công nghệ 3D hiện đại nhất.</p>

        <div class="features-grid">
            <div class="feature-card gs-slide-left">
                <div class="feature-icon" style="background:rgba(59,130,246,0.15); color:#60a5fa;">
                    <i class="fa-solid fa-vr-cardboard"></i>
                </div>
                <h3>Trải Nghiệm 3D Chân Thực</h3>
                <p>VietGo là nền tảng tiên phong áp dụng công nghệ đồ họa không gian 3D, giúp bạn "đi du lịch qua màn hình" trước khi quyết định đặt chuyến.</p>
            </div>
            
            <div class="feature-card gs-slide-up">
                <div class="feature-icon" style="background:rgba(74,222,128,0.15); color:#4ade80;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3>Giá Cả Minh Bạch & Ưu Đãi</h3>
                <p>Hệ thống tự động đồng bộ giá vé mới nhất. Luôn có nhiều Flash Sale và Voucher độc quyền được cập nhật thường xuyên trên nền tảng.</p>
            </div>
            
            <div class="feature-card gs-slide-right">
                <div class="feature-icon" style="background:rgba(239,68,68,0.15); color:#f87171;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3>AI Hỗ Trợ Khách Hàng 24/7</h3>
                <p>Khác biệt hoàn toàn! Chatbot thông minh của chúng tôi được trang bị AI sẵn sàng giải đáp mọi thắc mắc của bạn về lịch trình trong tích tắc.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Tours Section -->
<section class="glass-section" style="padding-top:40px;">
    <div class="grid-container">
        <div style="text-align:center; margin-bottom:15px; color:#4ade80; font-weight:800; text-transform:uppercase; font-size:0.9rem; letter-spacing:2px;" class="gs-reveal"><i class="fa-solid fa-bolt"></i> Mới Cập Nhật</div>
        <h2 class="section-title gs-reveal">Tour <span>Mới Nhất</span></h2>
        <p class="section-subtitle gs-reveal">Các chương trình du lịch vừa được chúng tôi cập nhật, mở ra nhưng hành trình đầy thú vị.</p>
        
        <div class="tours-grid">
            @foreach($tour_moi->take(3) as $tour)
            <a href="{{ route('tour.chi-tiet', $tour) }}" style="text-decoration:none;">
                <div class="card-3d gs-lift">
                    <div class="card-thumb">
                        <img src="{{ $tour->hinh_bia_url }}" alt="{{ $tour->ten_tour }}">
                        <div class="card-thumb-glow"></div>
                        <div style="position:absolute; top:20px; left:20px; background:rgba(59,130,246,0.85); color:white; font-size:0.8rem; font-weight:800; padding:5px 15px; border-radius:30px; text-transform:uppercase;">Mới</div>
                    </div>
                    <div class="card-content">
                        <div class="card-meta">
                            <div><i class="fa-solid fa-location-dot"></i> {{ $tour->diemDen->ten_diem_den }}</div>
                            <div><i class="fa-regular fa-clock"></i> {{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</div>
                        </div>
                        <h3 class="card-title">{{ $tour->ten_tour }}</h3>
                        <div style="margin-top:auto; padding-top:20px; border-top:1px solid rgba(255,255,255,0.06); display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:#4ade80; font-family:'Outfit'; font-weight:900; font-size:1.4rem;">{{ $tour->gia_nguoi_lon_dinh_dang }}</span>
                            <div style="background:#4ade80; color:#0f172a; font-weight:800; padding:8px 15px; border-radius:20px; font-size:0.8rem;">Chi tiết <i class="fa-solid fa-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Tours Section -->
<section class="glass-section" style="padding-top:40px;">
    <div class="grid-container">
        <div style="text-align:center; margin-bottom:15px; color:#4ade80; font-weight:800; text-transform:uppercase; font-size:0.9rem; letter-spacing:2px;" class="gs-reveal"><i class="fa-solid fa-fire"></i> Được Yêu Thích Nhất</div>
        <h2 class="section-title gs-reveal">Tour <span>Nổi Bật</span></h2>
        <p class="section-subtitle gs-reveal">Những hành trình được khách hàng lựa chọn và yêu thích nhiều nhất khi đến với VietGo.</p>
        
        <div class="tours-grid">
            @foreach($tour_noi_bat as $tour)
            <a href="{{ route('tour.chi-tiet', $tour) }}" style="text-decoration:none;">
                <div class="card-3d gs-lift">
                    <div class="card-thumb">
                        <img src="{{ $tour->hinh_bia_url }}" alt="{{ $tour->ten_tour }}">
                        <div class="card-thumb-glow"></div>
                        <div style="position:absolute; top:20px; left:20px; background:rgba(239,68,68,0.85); color:white; font-size:0.8rem; font-weight:800; padding:5px 15px; border-radius:30px; text-transform:uppercase;">🔥 Bán chạy</div>
                    </div>
                    <div class="card-content">
                        <div class="card-meta">
                            <div><i class="fa-solid fa-location-dot"></i> {{ $tour->diemDen->ten_diem_den }}</div>
                            <div><i class="fa-regular fa-clock"></i> {{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</div>
                        </div>
                        <h3 class="card-title">{{ $tour->ten_tour }}</h3>
                        <div style="margin-top:auto; padding-top:20px; border-top:1px solid rgba(255,255,255,0.06); display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:#ef4444; font-family:'Outfit'; font-weight:900; font-size:1.4rem;">{{ $tour->gia_nguoi_lon_dinh_dang }}</span>
                            <div style="width:40px; height:40px; border-radius:50%; background:rgba(74,222,128,0.1); display:flex; align-items:center; justify-content:center; color:#4ade80;"><i class="fa-solid fa-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div style="text-align:center; margin-top:50px;">
            <a href="{{ route('tour.danh-sach') }}" class="btn-3d btn-3d-outline gs-reveal">Cửa Hàng Tour <i class="fa-solid fa-store" style="margin-left:10px;"></i></a>
        </div>
    </div>
</section>

<!-- Bottom CTA -->
<section class="glass-section" style="padding-bottom:80px; padding-top:60px;">
    <div class="grid-container">
        <div class="cta-banner gs-reveal">
            <h2 style="font-family:'Outfit'; font-size:clamp(2rem,4vw,3.5rem); font-weight:900; color:white; line-height:1.2; margin-bottom:20px;">SẴN SÀNG CHO CHUYẾN ĐI<br><span style="color:#4ade80;">ĐÁNG NHỚ CỦA BẠN?</span></h2>
            <p style="color:rgba(255,255,255,0.6); font-size:1.2rem; max-width:600px; margin:0 auto 40px; line-height:1.7;">Hàng trăm lịch trình hấp dẫn đang chờ bạn khám phá. Đặt ngay hôm nay để nhận ưu đãi tốt nhất từ hệ thống VietGo!</p>
            
            <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:50px;">
                <a href="{{ route('tour.danh-sach') }}" class="btn-3d btn-3d-primary" style="font-size:1.1rem; padding:15px 40px;"><i class="fa-solid fa-binoculars"></i> Xem Tour Ngay</a>
                <a href="{{ route('khuyen-mai') }}" class="btn-3d btn-3d-outline" style="font-size:1.1rem; padding:15px 40px;"><i class="fa-solid fa-tag"></i> Lấy Khuyến Mãi</a>
            </div>

            <!-- Stats inside CTA -->
            <div style="display:flex; justify-content:center; gap:30px; flex-wrap:wrap; border-top:1px solid rgba(255,255,255,0.08); padding-top:30px;">
                <div style="display:flex; align-items:center; gap:10px; color:rgba(255,255,255,0.85); font-weight:700;">
                    <div style="width:40px;height:40px;background:rgba(74,222,128,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-map-location" style="color:#4ade80;"></i></div>
                    {{ $thong_ke['tong_tour'] }}+ Lựa chọn
                </div>
                <div style="display:flex; align-items:center; gap:10px; color:rgba(255,255,255,0.85); font-weight:700;">
                    <div style="width:40px;height:40px;background:rgba(96,165,250,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-users" style="color:#60a5fa;"></i></div>
                    {{ number_format($thong_ke['tong_khach']) }}+ Tin dùng
                </div>
                <div style="display:flex; align-items:center; gap:10px; color:rgba(255,255,255,0.85); font-weight:700;">
                    <div style="width:40px;height:40px;background:rgba(250,204,21,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-star" style="color:#facc15;"></i></div>
                    99% Khách hàng hài lòng
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif

<!-- Extra 3D Effects CSS -->
<style>
    /* ===== CUSTOM CURSOR GLOW ===== */
    .cursor-glow {
        position: fixed; width: 30px; height: 30px; border-radius: 50%;
        background: radial-gradient(circle, rgba(74,222,128,0.6) 0%, transparent 70%);
        pointer-events: none; z-index: 9998; transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s, opacity 0.3s;
        mix-blend-mode: screen;
    }
    .cursor-ring {
        position: fixed; width: 50px; height: 50px; border-radius: 50%;
        border: 2px solid rgba(74,222,128,0.3); pointer-events: none; z-index: 9997;
        transform: translate(-50%, -50%); transition: width 0.2s, height 0.2s, border-color 0.3s;
    }
    .cursor-glow.hovering { width: 60px; height: 60px; }
    .cursor-ring.hovering { width: 80px; height: 80px; border-color: rgba(74,222,128,0.6); }

    /* ===== FLOATING SHAPES ===== */
    .floating-shapes {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        pointer-events: none; z-index: 1; overflow: hidden;
    }
    .shape {
        position: absolute; border: 1px solid rgba(74,222,128,0.12);
        animation: floatShape linear infinite;
        opacity: 0.4;
    }
    .shape-circle { border-radius: 50%; }
    .shape-square { border-radius: 4px; }
    .shape-triangle {
        width: 0 !important; height: 0 !important; border: none !important;
        border-left: 15px solid transparent !important;
        border-right: 15px solid transparent !important;
        border-bottom: 26px solid rgba(74,222,128,0.1) !important;
    }
    @keyframes floatShape {
        0% { transform: translateY(110vh) rotate(0deg); opacity: 0; }
        10% { opacity: 0.4; }
        90% { opacity: 0.4; }
        100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
    }

    /* ===== TILT 3D CARD SHINE ===== */
    .card-3d { position: relative; overflow: hidden; }
    .card-3d .card-shine {
        position: absolute; inset: 0; pointer-events: none; z-index: 5;
        background: radial-gradient(600px circle at var(--mx) var(--my), rgba(74,222,128,0.08), transparent 40%);
        opacity: 0; transition: opacity 0.3s;
    }
    .card-3d:hover .card-shine { opacity: 1; }

    /* ===== SECTION TITLE GLOW ===== */
    .section-title.glow-active span {
        text-shadow: 0 0 30px rgba(74,222,128,0.5), 0 0 60px rgba(74,222,128,0.25);
        transition: text-shadow 0.5s;
    }

    /* ===== MAGNETIC BUTTON ===== */
    .btn-3d { position: relative; display: inline-block; }

    /* ===== SCROLL PROGRESS BAR ===== */
    .scroll-progress {
        position: fixed; top: 0; left: 0; height: 3px; z-index: 200;
        background: linear-gradient(90deg, #4ade80, #3b82f6, #a855f7);
        width: 0%; transition: width 0.05s linear;
        box-shadow: 0 0 10px rgba(74,222,128,0.5);
    }
</style>

<!-- All 3D Effect Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    // =============================================
    // 1. CUSTOM CURSOR GLOW
    // =============================================
    const cursorGlow = document.createElement('div');
    cursorGlow.className = 'cursor-glow';
    const cursorRing = document.createElement('div');
    cursorRing.className = 'cursor-ring';
    document.body.appendChild(cursorGlow);
    document.body.appendChild(cursorRing);

    let cx = 0, cy = 0, rx = 0, ry = 0;
    document.addEventListener('mousemove', (e) => {
        cx = e.clientX; cy = e.clientY;
        cursorGlow.style.left = cx + 'px';
        cursorGlow.style.top = cy + 'px';
    });
    // Smooth ring follow
    function animateRing() {
        rx += (cx - rx) * 0.12;
        ry += (cy - ry) * 0.12;
        cursorRing.style.left = rx + 'px';
        cursorRing.style.top = ry + 'px';
        requestAnimationFrame(animateRing);
    }
    animateRing();

    // Cursor enlarge on hover interactive elements
    document.querySelectorAll('a, button, .card-3d, .feature-card').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorGlow.classList.add('hovering');
            cursorRing.classList.add('hovering');
        });
        el.addEventListener('mouseleave', () => {
            cursorGlow.classList.remove('hovering');
            cursorRing.classList.remove('hovering');
        });
    });

    // Hide on mobile
    if(window.innerWidth < 768) {
        cursorGlow.style.display = 'none';
        cursorRing.style.display = 'none';
    }

    // =============================================
    // 2. FLOATING GEOMETRIC SHAPES
    // =============================================
    const shapesContainer = document.createElement('div');
    shapesContainer.className = 'floating-shapes';
    document.body.appendChild(shapesContainer);

    const shapeTypes = ['shape-circle', 'shape-square', 'shape-triangle'];
    for(let i = 0; i < 15; i++) {
        const shape = document.createElement('div');
        const type = shapeTypes[Math.floor(Math.random() * shapeTypes.length)];
        const size = Math.random() * 25 + 10;
        shape.className = 'shape ' + type;
        shape.style.width = size + 'px';
        shape.style.height = size + 'px';
        shape.style.left = Math.random() * 100 + '%';
        shape.style.animationDuration = (Math.random() * 20 + 15) + 's';
        shape.style.animationDelay = (Math.random() * 15) + 's';
        shapesContainer.appendChild(shape);
    }

    // =============================================
    // 3. TILT 3D ON CARDS (Mouse Tracking)
    // =============================================
    document.querySelectorAll('.card-3d').forEach(card => {
        // Add shine overlay
        const shine = document.createElement('div');
        shine.className = 'card-shine';
        card.appendChild(shine);

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 15;
            const rotateY = (centerX - x) / 15;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
            card.style.setProperty('--mx', x + 'px');
            card.style.setProperty('--my', y + 'px');
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            card.style.transition = 'transform 0.6s cubic-bezier(0.23, 1, 0.32, 1)';
        });

        card.addEventListener('mouseenter', () => {
            card.style.transition = 'none';
        });
    });

    // Also tilt feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) translateY(0)';
            card.style.transition = 'transform 0.5s ease';
        });
        card.addEventListener('mouseenter', () => { card.style.transition = 'none'; });
    });

    // =============================================
    // 4. MAGNETIC BUTTONS
    // =============================================
    document.querySelectorAll('.btn-3d').forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0, 0)';
            btn.style.transition = 'transform 0.4s cubic-bezier(0.23, 1, 0.32, 1)';
        });
        btn.addEventListener('mouseenter', () => { btn.style.transition = 'none'; });
    });

    // =============================================
    // 5. SCROLL PROGRESS BAR
    // =============================================
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = (scrollTop / docHeight) * 100;
        progressBar.style.width = progress + '%';
    });

    // =============================================
    // 6. GSAP SCROLL ANIMATIONS + TITLE GLOW
    // =============================================
    if(window.gsap && window.ScrollTrigger) {
        
        // Fade In Up for headers
        gsap.utils.toArray('.gs-reveal').forEach(function(elem) {
            gsap.fromTo(elem, { y: 40, opacity: 0 }, {
                scrollTrigger: { trigger: elem, start: "top 90%" },
                y: 0, opacity: 1, duration: 1, ease: "power3.out"
            });
        });

        // Slide Left / Right
        gsap.utils.toArray('.gs-slide-left').forEach(function(elem) {
            gsap.fromTo(elem, { x: -80, opacity: 0 }, {
                scrollTrigger: { trigger: elem, start: "top 85%" },
                x: 0, opacity: 1, duration: 1, ease: "power3.out"
            });
        });
        gsap.utils.toArray('.gs-slide-right').forEach(function(elem) {
            gsap.fromTo(elem, { x: 80, opacity: 0 }, {
                scrollTrigger: { trigger: elem, start: "top 85%" },
                x: 0, opacity: 1, duration: 1, ease: "power3.out"
            });
        });
        gsap.utils.toArray('.gs-slide-up').forEach(function(elem) {
            gsap.fromTo(elem, { y: 80, opacity: 0 }, {
                scrollTrigger: { trigger: elem, start: "top 85%" },
                y: 0, opacity: 1, duration: 1, ease: "power3.out"
            });
        });

        // Lift Up for Cards (staggered)
        gsap.utils.toArray('.gs-lift').forEach(function(elem, i) {
            gsap.fromTo(elem, { y: 100, opacity: 0 }, {
                scrollTrigger: { trigger: elem, start: "top 90%" },
                y: 0, opacity: 1, duration: 1, delay: i * 0.15, ease: "power3.out"
            });
        });

        // Section Title Glow when scrolled into view
        gsap.utils.toArray('.section-title').forEach(function(title) {
            ScrollTrigger.create({
                trigger: title,
                start: "top 80%",
                onEnter: () => title.classList.add('glow-active'),
            });
        });
    }

});
</script>
@endsection
