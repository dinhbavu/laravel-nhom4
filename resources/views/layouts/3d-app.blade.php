<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VietGo 3D - Khám Phá Trải Nghiệm Mới')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/app-DAXCIxn9.css') }}">
        <script src="{{ asset('build/assets/app-BbzB21r_.js') }}" defer></script>
    @endif
    @yield('css')

    <script>window.APP_ASSET_URL = "{{ rtrim(asset(''), '/') }}/";</script>

    <style>
        :root {
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        
        html { scroll-behavior: smooth; }
        
        body {
            margin: 0; padding: 0;
            background-color: #0f172a; color: #e2e8f0;
            font-family: 'Inter', sans-serif; overflow-x: hidden;
        }

        /* 3D Canvas Background */
        #webgl-container {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1; overflow: hidden; pointer-events: none;
            background: #0f172a url('{{ asset('images/sapa_3d.jpg') }}') center/cover no-repeat;
        }

        /* ========== NAVBAR ========== */
        .glass-nav {
            position: fixed; top: 0; width: 100%; z-index: 100;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px) saturate(180%); -webkit-backdrop-filter: blur(25px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0; transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .glass-nav .container {
            display: flex; justify-content: space-between; align-items: center;
            max-width: 1280px; margin: 0 auto; padding: 0 2rem;
        }
        .glass-brand {
            font-family: 'Outfit', sans-serif; font-size: 1.75rem; font-weight: 900;
            color: #fff; text-decoration: none; display: flex; align-items: center;
            gap: 10px; text-transform: uppercase; letter-spacing: 2px;
        }
        .glass-brand span { color: #4ade80; text-shadow: 0 0 20px rgba(74,222,128,0.4); }

        .glass-links { display: flex; gap: 2rem; }
        .glass-links a {
            color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600;
            font-size: 1rem; transition: all 0.3s; position: relative;
        }
        .glass-links a:hover { color: #4ade80; }
        .glass-links a::after {
            content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 3px;
            background: #4ade80; transition: width 0.3s ease; box-shadow: 0 0 10px rgba(74,222,128,0.5);
        }
        .glass-links a:hover::after { width: 100%; }

        .glass-actions a {
            padding: 8px 24px; border-radius: 99px; font-weight: 600;
            text-decoration: none; transition: all 0.3s;
        }
        .btn-glass {
            background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(5px);
        }
        .btn-glass:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .btn-neon {
            background: linear-gradient(135deg, #4ade80, #10b981);
            color: #0f172a !important; font-weight: 700;
            box-shadow: 0 5px 20px rgba(74,222,128,0.35);
        }
        .btn-neon:hover { box-shadow: 0 5px 30px rgba(74,222,128,0.6); transform: translateY(-2px); }

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
            color: #4ade80 !important;
            transition: transform 0.3s;
        }
        .dropdown-item:hover {
            background: rgba(74, 222, 128, 0.1) !important;
            color: #4ade80 !important;
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
            color: #fff;
            font-family: inherit;
        }
        .user-btn:hover {
            background: rgba(255,255,255,0.1);
            border-color: #4ade80;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4ade80;
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

        /* Main Wrapper */
        main.glass-main { position: relative; z-index: 10; }

        /* ========== RESPONSIVE NAVBAR ========== */
        @media (max-width: 1024px) {
            .glass-links { display: none !important; }
            .glass-actions { display: none !important; }
            .hamburger { display: flex !important; }
            .mobile-drawer { display: flex; }
            .glass-nav .container { padding: 0 15px; }
            .glass-brand { font-size: 1.4rem; gap: 8px; }
        }
        @media (min-width: 769px) and (max-width: 1024px) {
            .glass-links { gap: 1rem; }
            .glass-links a { font-size: 0.9rem; }
            .glass-actions a { padding: 6px 16px; font-size: 0.85rem; }
        }

        /* ========== FOOTER ========== */
        .glass-footer {
            position: relative; z-index: 10;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 60px 20px 20px; margin-top: 0;
            color: rgba(255,255,255,0.6);
        }
        .footer-grid {
            max-width: 1280px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 40px; margin-bottom: 40px;
        }
        @media(max-width:1024px) { .footer-grid { grid-template-columns: repeat(2, 1fr); gap: 30px; } }
        @media(max-width:600px) { .footer-grid { grid-template-columns: 1fr; gap: 25px; } .glass-footer { padding: 40px 15px 15px; } }
        
        .footer-col h4 { font-family: 'Outfit'; font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 20px; }
        .footer-col p { font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px; color: rgba(255,255,255,0.55); }
        .footer-list { list-style: none; padding: 0; margin: 0; }
        .footer-list li { margin-bottom: 12px; color: rgba(255,255,255,0.55); }
        .footer-list a { color: rgba(255,255,255,0.55); text-decoration: none; transition: 0.3s; font-size: 0.95rem; }
        .footer-list a:hover { color: #4ade80; padding-left: 5px; }
        .footer-socials { display: flex; gap: 15px; margin-top: 20px; }
        .footer-socials a {
            width: 40px; height: 40px; background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7); transition: 0.3s;
        }
        .footer-socials a:hover { background: #4ade80; color: #0f172a; border-color: #4ade80; transform: translateY(-3px); box-shadow: 0 5px 20px rgba(74,222,128,0.4); }
        
        /* ========== PAGINATION 3D GLASSMORPHISM ========== */
        .pagination { display: flex; padding-left: 0; list-style: none; gap: 0.75rem; margin: 0; flex-wrap: wrap; justify-content: center; }
        .page-item .page-link { position: relative; display: flex; align-items: center; justify-content: center; min-width: 3rem; height: 3rem; padding: 0 1rem; color: #fff; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1); backdrop-filter: blur(10px); }
        .page-item:not(.disabled):not(.active) .page-link:hover { background: rgba(74, 222, 128, 0.15); border-color: #4ade80; color: #4ade80; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(74, 222, 128, 0.25); }
        .page-item.active .page-link, .page-item.active span.page-link { background: linear-gradient(135deg, #4ade80, #10b981); border-color: #4ade80; color: #0f172a; box-shadow: 0 0 20px rgba(74, 222, 128, 0.4); z-index: 2; }
        .page-item.disabled .page-link, .page-item.disabled span.page-link { color: rgba(255, 255, 255, 0.2); cursor: not-allowed; background: rgba(255, 255, 255, 0.02); border-color: rgba(255, 255, 255, 0.05); box-shadow: none; transform: none; }

        /* ========== PRELOADER ========== */
        #preloader-3d {
            position: fixed; inset: 0; z-index: 9999;
            background: #0f172a; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
        }
        .loader-spinner {
            width: 60px; height: 60px;
            border: 4px solid rgba(74, 222, 128, 0.2);
            border-top-color: #4ade80;
            border-radius: 50%;
            animation: preloader-spin 1s linear infinite;
            box-shadow: 0 0 20px rgba(74,222,128,0.2);
        }
        .loader-text {
            margin-top: 25px; font-family: 'Outfit', sans-serif;
            color: #4ade80; font-weight: 700; letter-spacing: 3px;
            font-size: 1.1rem;
            animation: preloader-pulse 2s ease-in-out infinite;
        }
        @keyframes preloader-spin { to { transform: rotate(360deg); } }
        @keyframes preloader-pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; text-shadow: 0 0 20px rgba(74,222,128,0.6); }
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
    
    <div id="content-wrapper" style="opacity: 0;">

    <!-- Glass Navbar -->
    <nav class="glass-nav">
        <div class="container">
            <a href="{{ route('trang-chu') }}" class="glass-brand">
                <i class="fa-solid fa-cube"></i> Viet<span>Go</span>
            </a>
            <div class="glass-links">
                <a href="{{ route('trang-chu') }}">Trang Chủ</a>
                <a href="{{ route('tour.danh-sach') }}">Tour Du Lịch</a>
                <a href="{{ route('khuyen-mai') }}">Khuyến Mãi</a>
                <a href="{{ route('cam-nang.danh-sach') }}">Cẩm Nang</a>
                <a href="#footer">Liên Hệ</a>
            </div>
            <div class="glass-actions" style="display:flex; gap:10px; align-items:center;">
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
                            <form action="{{ route('dang-xuat') }}" method="POST" style="margin:0; padding:0;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width:100%; border:none; cursor:pointer;">
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
            </div>
            <!-- Hamburger (mobile only) -->
            <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
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

    <!-- Main Content -->
    <main class="glass-main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="glass-footer" id="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="{{ route('trang-chu') }}" class="glass-brand" style="margin-bottom:20px;">
                    <i class="fa-solid fa-cube"></i> Viet<span>Go</span>
                </a>
                <p>Nền tảng đặt tour du lịch 3D tương tác hàng đầu Việt Nam. Khám phá vẻ đẹp đất nước qua lăng kính hoàn toàn mới.</p>
                <div class="footer-socials">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Khám Phá</h4>
                <ul class="footer-list">
                    <li><a href="{{ route('tour.danh-sach') }}">Tất cả Tour</a></li>
                    <li><a href="{{ route('khuyen-mai') }}">Khuyến mãi</a></li>
                    <li><a href="{{ route('cam-nang.danh-sach') }}">Cẩm nang du lịch</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Hỗ Trợ</h4>
                <ul class="footer-list">
                    <li><a href="#">Trung tâm trợ giúp</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Liên Hệ</h4>
                <ul class="footer-list">
                    <li><i class="fa-solid fa-location-dot" style="margin-right:10px; color:#4ade80;"></i> 123 Đường Du Lịch, Hà Nội</li>
                    <li><i class="fa-solid fa-phone" style="margin-right:10px; color:#4ade80;"></i> 1900 1234</li>
                    <li><i class="fa-solid fa-envelope" style="margin-right:10px; color:#4ade80;"></i> hotro@vietgo.com</li>
                </ul>
            </div>
        </div>
        <div style="text-align:center; padding-top:20px; border-top:1px solid rgba(255,255,255,0.06); font-size:0.9rem; max-width:1280px; margin:0 auto; color:rgba(255,255,255,0.4);">
            &copy; 2026 VietGo Tour. All rights reserved. Built with 3D Experience.
        </div>
    </footer>
    </div>

    @include('partials.background-3d-script')
    @yield('js')
    @include('partials.chatbot-ai')
    @include('partials.gps-prompt')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const mobileDrawer = document.getElementById('mobileDrawer');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (hamburgerBtn && mobileDrawer && mobileOverlay) {
                // Toggle Menu
                function toggleMenu() {
                    hamburgerBtn.classList.toggle('active');
                    mobileDrawer.classList.toggle('open');
                    mobileOverlay.classList.toggle('show');
                    document.body.style.overflow = mobileDrawer.classList.contains('open') ? 'hidden' : '';
                }

                hamburgerBtn.addEventListener('click', toggleMenu);
                mobileOverlay.addEventListener('click', toggleMenu);

                // Close menu when a link is clicked
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

                // Close dropdown when clicking outside
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
