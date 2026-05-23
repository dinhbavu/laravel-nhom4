<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VietGo - Khám phá Việt Nam')</title>
    
    <!-- SEO & Social Media Meta Tags -->
    <meta name="description" content="VietGo - Nền tảng đặt tour du lịch hàng đầu Việt Nam. Hành trình tận hưởng và khám phá vẻ đẹp vô tận của đất nước.">
    <meta property="og:title" content="@yield('title', 'VietGo - Khám phá Việt Nam')">
    <meta property="og:description" content="VietGo - Nền tảng đặt tour du lịch hàng đầu Việt Nam. Hành trình tận hưởng và khám phá vẻ đẹp vô tận của đất nước.">
    <meta property="og:image" content="{{ asset('images/og-vietgo-2025.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'VietGo - Khám phá Việt Nam')">
    <meta name="twitter:description" content="VietGo - Nền tảng đặt tour du lịch hàng đầu Việt Nam.">
    <meta name="twitter:image" content="{{ asset('images/og-vietgo-2025.png') }}">
    
    <!-- Schema.org markup for Zalo, Google+ -->
    <meta itemprop="name" content="@yield('title', 'VietGo - Khám phá Việt Nam')">
    <meta itemprop="description" content="VietGo - Nền tảng đặt tour du lịch hàng đầu Việt Nam. Hành trình tận hưởng và khám phá vẻ đẹp vô tận của đất nước.">
    <meta itemprop="image" content="{{ asset('images/og-vietgo-2025.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
    <script>window.APP_ASSET_URL = "{{ rtrim(asset(''), '/') }}/";</script>
    <style>
        :root {
            --glass-bg: rgba(15, 23, 42, 0.4);
            --glass-border: rgba(255, 255, 255, 0.08);
            --primary-accent: #4ade80;
        }
        
        body {
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            margin: 0; padding: 0;
            overflow-x: hidden;
        }

        /* Canvas 3D Background */
        #webgl-container {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1; overflow: hidden; pointer-events: none;
            background: #0f172a url('{{ asset('images/sapa_3d.jpg') }}') center/cover no-repeat;
        }

        /* Glassmorphism Overrides */
        /* ========== GLASS NAVBAR SYNC ========== */
        .glass-nav {
            position: fixed; top: 0; width: 100%; z-index: 100;
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(25px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(25px) saturate(180%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 1rem 0; transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        .glass-nav .container {
            display: flex; justify-content: space-between; align-items: center;
            max-width: 1280px; margin: 0 auto; padding: 0 2rem;
        }
        .glass-brand {
            font-family: 'Outfit', sans-serif !important; font-size: 1.75rem !important; font-weight: 900 !important;
            color: #fff !important; text-decoration: none !important; display: flex !important; align-items: center !important;
            gap: 10px; text-transform: uppercase; letter-spacing: 2px;
        }
        .glass-brand span { color: #4ade80 !important; text-shadow: 0 0 20px rgba(74,222,128,0.4) !important; }

        .glass-links { display: flex; gap: 2rem; }
        .glass-links a {
            color: rgba(255,255,255,0.7) !important; text-decoration: none !important; font-weight: 600 !important;
            font-size: 1rem !important; transition: all 0.3s; position: relative;
        }
        .glass-links a:hover { color: #4ade80 !important; }
        .glass-links a::after {
            content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 3px;
            background: #4ade80; transition: width 0.3s ease; box-shadow: 0 0 10px rgba(74,222,128,0.5);
        }
        .glass-links a:hover::after { width: 100%; }

        .glass-actions { display: flex; align-items: center; gap: 15px; }
        .glass-actions a {
            padding: 8px 24px; border-radius: 99px; font-weight: 600;
            text-decoration: none; transition: all 0.3s;
        }
        .btn-glass {
            background: rgba(255,255,255,0.06) !important; color: rgba(255,255,255,0.85) !important;
            border: 1px solid rgba(255,255,255,0.12) !important; backdrop-filter: blur(5px) !important;
        }
        .btn-glass:hover { background: rgba(255,255,255,0.12) !important; color: #fff !important; }
        .btn-neon {
            background: linear-gradient(135deg, #4ade80, #10b981) !important;
            color: #0f172a !important; font-weight: 700 !important;
            box-shadow: 0 5px 20px rgba(74,222,128,0.35) !important;
            border: none !important;
        }
        .btn-neon:hover { box-shadow: 0 5px 30px rgba(74,222,128,0.6) !important; transform: translateY(-2px); }
        
        /* Auto-Darkify Content & Border Cleanup */
        .page-header { background: transparent !important; color: #fff !important; border-bottom: none !important; }
        .page-header h1, .page-header-title { color: #fff !important; }
        .page-header p, .page-header-sub { color: rgba(255, 255, 255, 0.7) !important; }
        .page-header::after { display: none !important; } /* Remove old background decorations */
        .page-header-title i { color: var(--primary-accent) !important; }
        
        .section-card, .profile-card, .card, .bg-white {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(25px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #e2e8f0 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important;
        }

        .section-card-title {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
        }

        .form-control, .form-select, select, input, textarea {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }
        .form-control:focus {
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2) !important;
        }
        .form-control:disabled { background: rgba(0,0,0,0.2) !important; }

        .form-label { color: rgba(255, 255, 255, 0.7) !important; font-weight: 600 !important; }

        .profile-nav-item { color: rgba(255, 255, 255, 0.6) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important; }
        .profile-nav-item i { color: var(--primary-accent) !important; }
        .profile-nav-item:hover, .profile-nav-item.active { 
            background: rgba(74, 222, 128, 0.1) !important; 
            color: var(--primary-accent) !important; 
        }
        
        /* Tour Card V2 Glass Overrides */
        .tour-card-v2 {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(15px) !important;
        }
        .tour-title-v2 { color: #fff !important; }
        .tour-footer-v2 { border-top: 1px solid rgba(255, 255, 255, 0.05) !important; }
        
        /* Empty State Cleanup */
        .empty-state {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 2px dashed rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .empty-state h4 { color: #fff !important; }

        /* ========== PAGINATION 3D GLASSMORPHISM ========== */
        .pagination { display: flex; padding-left: 0; list-style: none; gap: 0.75rem; margin: 0; flex-wrap: wrap; justify-content: center; }
        .page-item .page-link { position: relative; display: flex; align-items: center; justify-content: center; min-width: 3rem; height: 3rem; padding: 0 1rem; color: #fff; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1); backdrop-filter: blur(10px); }
        .page-item:not(.disabled):not(.active) .page-link:hover { background: rgba(74, 222, 128, 0.15); border-color: #4ade80; color: #4ade80; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(74, 222, 128, 0.25); }
        .page-item.active .page-link, .page-item.active span.page-link { background: linear-gradient(135deg, #4ade80, #10b981); border-color: #4ade80; color: #0f172a; box-shadow: 0 0 20px rgba(74, 222, 128, 0.4); z-index: 2; }
        .page-item.disabled .page-link, .page-item.disabled span.page-link { color: rgba(255, 255, 255, 0.2); cursor: not-allowed; background: rgba(255, 255, 255, 0.02); border-color: rgba(255, 255, 255, 0.05); box-shadow: none; transform: none; }

        /* Global Text Contrast for 3D Background */
        .order-list-page h1, .order-list-page h2, .order-list-page h3,
        .profile-container h1, .profile-container h2, .profile-container h3 {
            color: #fff !important;
        }
        .order-list-page p, .profile-container p {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .footer { background: rgba(15, 23, 42, 0.9) !important; backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,0.06); }
        .footer-logo span { color: var(--primary-accent); }

        /* User Dropdown Glassmorphism */
        .user-menu { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 15px);
            right: 0;
            width: 260px;
            background: rgba(15, 23, 42, 0.9) !important;
            backdrop-filter: blur(40px) saturate(200%) !important;
            -webkit-backdrop-filter: blur(40px) saturate(200%) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 20px !important;
            padding: 12px !important;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6) !important;
            z-index: 1000;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            pointer-events: none;
        }
        .user-menu.active .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }

        /* ========== HAMBURGER BUTTON ========== */
        .hamburger {
            display: none; background: none; border: none; cursor: pointer;
            width: 36px; height: 36px; position: relative; z-index: 110;
            flex-direction: column; justify-content: center; align-items: center; gap: 5px;
        }
        .hamburger span {
            display: block; width: 24px; height: 2px; background: #fff;
            border-radius: 2px; transition: all 0.3s ease;
        }
        .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

        /* ========== MOBILE DRAWER ========== */
        .mobile-drawer {
            display: none; position: fixed; top: 0; right: -100%; width: 300px; height: 100vh;
            background: rgba(15,23,42,0.4); backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            z-index: 200; padding: 100px 24px 40px;
            transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            border-left: 1px solid rgba(255,255,255,0.1);
            flex-direction: column; gap: 0;
            overflow-y: auto;
            box-shadow: -10px 0 30px rgba(0,0,0,0.5);
        }
        .mobile-drawer.open { right: 0; }
        .mobile-drawer a {
            display: block; color: rgba(255,255,255,0.8); text-decoration: none;
            font-weight: 600; font-size: 1.15rem; padding: 18px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06); transition: all 0.3s;
            font-family: 'Outfit';
        }
        .mobile-drawer a:hover { color: #4ade80; padding-left: 12px; }
        .mobile-drawer .drawer-actions {
            display: flex; flex-direction: column; gap: 15px; margin-top: 35px;
        }
        .mobile-drawer .drawer-actions a {
            text-align: center; border-bottom: none; padding: 14px; border-radius: 16px;
            font-size: 1rem;
        }
        .mobile-overlay {
            display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 150; opacity: 0; transition: opacity 0.3s;
        }
        .mobile-overlay.show { display: block; opacity: 1; }

        /* ========== RESPONSIVE NAVBAR ========== */
        @media (max-width: 1024px) {
            .glass-links { display: none !important; }
            .glass-actions .user-name { display: none !important; }
            .glass-actions .btn-glass, .glass-actions .btn-neon { display: none !important; }
            .hamburger { display: flex !important; }
            .mobile-drawer { display: flex; }
            .glass-nav .container { padding: 0 15px; }
            .glass-brand { font-size: 1.45rem !important; gap: 8px; }
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px !important;
            color: rgba(255, 255, 255, 0.8) !important;
            text-decoration: none !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
            border: none !important;
            background: transparent !important;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
        }
        .dropdown-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            color: var(--primary-accent) !important;
            transition: transform 0.3s;
        }
        .dropdown-item:hover {
            background: rgba(74, 222, 128, 0.1) !important;
            color: var(--primary-accent) !important;
            padding-left: 22px !important;
        }
        .dropdown-item:hover i {
            transform: scale(1.2);
        }
        .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 10px 0;
        }
        
        .user-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 6px 16px;
            border-radius: 99px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-btn:hover {
            background: rgba(255,255,255,0.1);
            border-color: var(--primary-accent);
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-accent);
        }
    </style>
</head>
<body>
    <!-- Preloader to sync 3D and Text -->
    <div id="preloader-3d" style="position: fixed; inset: 0; background: #0f172a; z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div style="font-family: 'Outfit', sans-serif; font-size: 3.5rem; font-weight: 900; color: white; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="fa-solid fa-plane-departure" style="color: #4ade80;"></i>
            Viet<span style="color: #4ade80;">Go</span>
        </div>
        <div style="width: 40px; height: 40px; border: 3px solid rgba(74, 222, 128, 0.2); border-top-color: #4ade80; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <div style="color: rgba(255,255,255,0.5); margin-top: 15px; font-size: 0.9rem; font-weight: 500;">Đang thiết lập không gian...</div>
    </div>
    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
    <script>
        // Fallback safety to remove preloader if 3D fails to load
        setTimeout(function() {
            const preloader = document.getElementById('preloader-3d');
            if (preloader) {
                preloader.style.transition = 'opacity 0.5s';
                preloader.style.opacity = '0';
                setTimeout(() => preloader.remove(), 500);
            }
        }, 5000); // Tự động ẩn sau 5 giây nếu lỗi
    </script>

    <!-- WebGL Background -->
    <div id="webgl-container"></div>
<!-- ===== GLASS NAVBAR ===== -->
<nav class="glass-nav" id="mainNavbar">
    <div class="container">
        <a href="{{ route('trang-chu') }}" class="glass-brand">
            <i class="fa-solid fa-plane-departure"></i>
            Viet<span>Go</span>
        </a>

        <div class="glass-links">
            <a href="{{ route('trang-chu') }}">Trang Chủ</a>
            <a href="{{ route('tour.danh-sach') }}">Tour Du Lịch</a>
            <a href="{{ route('khuyen-mai') }}">Khuyến Mãi</a>
            <a href="{{ route('cam-nang.danh-sach') }}">Cẩm Nang</a>
            <a href="#footer">Liên Hệ</a>
        </div>

            @if(auth()->guard('khach_hang')->check())
                <div class="user-menu">
                    <button class="user-btn">
                        <img src="{{ auth()->guard('khach_hang')->user()->anh_dai_dien_url }}" alt="Avatar" class="user-avatar">
                        <span class="user-name">{{ auth()->guard('khach_hang')->user()->ho_ten }}</span>
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('khach-hang.ho-so') }}" class="dropdown-item">
                            <i class="fa-regular fa-user"></i> Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="dropdown-item">
                            <i class="fa-solid fa-clipboard-list"></i> Lịch sử đặt tour
                        </a>
                        <a href="{{ route('khach-hang.dat-tour.thanh-toan') }}" class="dropdown-item">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử thanh toán
                        </a>
                        <a href="{{ route('khach-hang.yeu-thich') }}" class="dropdown-item">
                            <i class="fa-regular fa-heart"></i> Tour yêu thích
                        </a>
                        <a href="{{ route('khach-hang.thong-bao') }}" class="dropdown-item">
                            <i class="fa-regular fa-bell"></i> Thông báo
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('dang-xuat') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @elseif(auth()->guard('quan_tri')->check())
                <a href="{{ route('quan-tri.dashboard') }}" class="btn-neon">Quản Trị</a>
            @else
                <a href="{{ route('dang-nhap') }}" class="btn-glass">Đăng Nhập</a>
                <a href="{{ route('dang-ky') }}" class="btn-neon">Đăng Ký</a>
            @endif

            <!-- Hamburger (mobile only) -->
            <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer">
    <a href="{{ route('trang-chu') }}"><i class="fa-solid fa-house" style="margin-right:10px;color:#4ade80;"></i> Trang Chủ</a>
    <a href="{{ route('tour.danh-sach') }}"><i class="fa-solid fa-map-location-dot" style="margin-right:10px;color:#4ade80;"></i> Tour Du Lịch</a>
    <a href="{{ route('khuyen-mai') }}"><i class="fa-solid fa-tags" style="margin-right:10px;color:#4ade80;"></i> Khuyến Mãi</a>
    <a href="{{ route('cam-nang.danh-sach') }}"><i class="fa-solid fa-book" style="margin-right:10px;color:#4ade80;"></i> Cẩm Nang</a>
    <a href="#footer"><i class="fa-solid fa-headset" style="margin-right:10px;color:#4ade80;"></i> Liên Hệ</a>
    <div class="drawer-actions">
        @if(auth()->guard('khach_hang')->check())
            <a href="{{ route('khach-hang.ho-so') }}" class="btn-glass" style="background:rgba(74,222,128,0.1);border-color:rgba(74,222,128,0.25);color:#4ade80;">
                <i class="fa-solid fa-user"></i> {{ auth()->guard('khach_hang')->user()->ho_ten }}
            </a>
        @elseif(auth()->guard('quan_tri')->check())
            <a href="{{ route('quan-tri.dashboard') }}" class="btn-neon">Quản Trị</a>
        @else
            <a href="{{ route('dang-nhap') }}" class="btn-glass">Đăng Nhập</a>
            <a href="{{ route('dang-ky') }}" class="btn-neon">Đăng Ký</a>
        @endif
    </div>
</div>


<!-- ===== MAIN CONTENT ===== -->
<main>
    @if(session('thanh_cong') || session('loi') || $errors->any())
        <!-- Giao diện Modal Thông báo Mới -->
        <div id="systemAlertModal" style="position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:99999;display:flex;align-items:center;justify-content:center;opacity:0;animation:fadeIn 0.2s forwards;">
            <!-- Modal Box -->
            <div style="background:white;width:90%;max-width:400px;border-radius:16px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);transform:scale(0.95);animation:scaleIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;text-align:center;padding:1.5rem;overflow:hidden;position:relative;">
                
                @if(session('thanh_cong'))
                    <div style="width:60px;height:60px;border-radius:50%;background:#d1fae5;color:#10b981;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;font-family:var(--font-heading, 'Outfit', sans-serif);">Thành Công</h3>
                    <p style="color:#475569;font-size:0.95rem;line-height:1.5;margin-bottom:1.5rem;">{{ session('thanh_cong') }}</p>
                    <button onclick="document.getElementById('systemAlertModal').remove()" style="background:linear-gradient(135deg, #10b981, #059669);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;font-size:0.95rem;cursor:pointer;width:100%;transition:transform 0.1s;box-shadow:0 4px 12px rgba(16,185,129,0.3);" onmousedown="this.style.transform='scale(0.97)'" onmouseup="this.style.transform='scale(1)'">Xác nhận</button>
                @endif
                
                @if(session('loi') || $errors->any())
                    <div style="width:60px;height:60px;border-radius:50%;background:#fee2e2;color:#ef4444;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;font-family:var(--font-heading, 'Outfit', sans-serif);">Thông báo</h3>
                    <div style="color:#475569;font-size:0.95rem;line-height:1.5;margin-bottom:1.5rem;">
                        @if(session('loi'))<p style="margin-bottom:4px;">{{ session('loi') }}</p>@endif
                        @foreach ($errors->all() as $error)
                            <p style="margin-bottom:4px;">{{ $error }}</p>
                        @endforeach
                    </div>
                    <button onclick="document.getElementById('systemAlertModal').remove()" style="background:linear-gradient(135deg, #ef4444, #dc2626);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;font-size:0.95rem;cursor:pointer;width:100%;transition:transform 0.1s;box-shadow:0 4px 12px rgba(239,68,68,0.3);" onmousedown="this.style.transform='scale(0.97)'" onmouseup="this.style.transform='scale(1)'">Đóng lại</button>
                @endif
            </div>
        </div>
        <style>
            @keyframes fadeIn { to { opacity: 1; } }
            @keyframes scaleIn { to { transform: scale(1); } }
        </style>
    @endif

    @yield('content')
</main>

<!-- ===== FOOTER ===== -->
<footer id="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <i class="fa-solid fa-plane-departure"></i>
                    Viet<span>Go</span>
                </div>
                <p class="footer-desc">Đồng hành cùng bạn trên mọi nẻo đường khám phá vẻ đẹp Việt Nam. Uy tín, chất lượng và tận tâm.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <h4 class="footer-title">Điểm Đến</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('tour.danh-sach', ['vung_mien' => 'mien_bac']) }}">Miền Bắc</a></li>
                    <li><a href="{{ route('tour.danh-sach', ['vung_mien' => 'mien_trung']) }}">Miền Trung</a></li>
                    <li><a href="{{ route('tour.danh-sach', ['vung_mien' => 'mien_nam']) }}">Miền Nam</a></li>
                    <li><a href="{{ route('tour.danh-sach') }}">Tất cả điểm đến</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Chính Sách</h4>
                <ul class="footer-links">
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Chính sách hoàn tiền</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Liên Hệ</h4>
                <ul class="footer-links">
                    <li><i class="fa-solid fa-location-dot"></i><span>123 Đường Du Lịch, TP.HCM</span></li>
                    <li><i class="fa-solid fa-phone"></i><span>1900 1234</span></li>
                    <li><i class="fa-solid fa-envelope"></i><span>hotro@vietgo.com</span></li>
                    <li><i class="fa-regular fa-clock"></i><span>8:00 - 22:00 mỗi ngày</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} VietGo. Đồ án quản lý Tour Du Lịch.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept form onsubmit confirm
    document.querySelectorAll('form').forEach(form => {
        const onsubmitAttr = form.getAttribute('onsubmit');
        if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
            const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                const message = match[1];
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        background: 'rgba(30, 41, 59, 0.95)',
                        color: '#ffffff',
                        iconColor: '#fbbf24',
                        backdrop: 'rgba(15, 23, 42, 0.6)',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-700'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        }
    });

    // Intercept element onclick confirm
    document.querySelectorAll('[onclick*="confirm("]').forEach(el => {
        const onclickAttr = el.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes('confirm(')) {
            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                const message = match[1];
                el.removeAttribute('onclick');
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        background: 'rgba(30, 41, 59, 0.95)',
                        color: '#ffffff',
                        iconColor: '#fbbf24',
                        backdrop: 'rgba(15, 23, 42, 0.6)',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-700'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (el.tagName === 'A') {
                                window.location.href = el.href;
                            } else {
                                const form = el.closest('form');
                                if (form) {
                                    form.submit();
                                }
                            }
                        }
                    });
                });
            }
        }
    });
});
</script>

@yield('js')

    @include('partials.background-3d-script')
    @include('partials.chatbot-ai')
    @include('partials.gps-prompt')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const mobileDrawer = document.getElementById('mobileDrawer');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (hamburgerBtn && mobileDrawer && mobileOverlay) {
                function toggleMenu() {
                    hamburgerBtn.classList.toggle('active');
                    mobileDrawer.classList.toggle('open');
                    mobileOverlay.classList.toggle('show');
                    document.body.style.overflow = mobileDrawer.classList.contains('open') ? 'hidden' : '';
                }

                hamburgerBtn.addEventListener('click', toggleMenu);
                mobileOverlay.addEventListener('click', toggleMenu);

                const drawerLinks = mobileDrawer.querySelectorAll('a');
                drawerLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        hamburgerBtn.classList.remove('active');
                        mobileDrawer.classList.remove('open');
                        mobileOverlay.classList.remove('show');
                        document.body.style.overflow = '';
                    });
                });
            }

            // ── User Dropdown Toggle (Click instead of Hover) ──
            const userMenu = document.querySelector('.user-menu');
            const userBtn = document.querySelector('.user-btn');

            if (userMenu && userBtn) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>
