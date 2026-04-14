@extends('layouts.3d-app')

@section('title', 'VietGo 3D - Không Gian Du Lịch Đa Chiều')

@section('css')
<style>
    .hero-section {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        position: relative;
        overflow: hidden;
        padding-left: 8%;
    }

    .hero-overlay {
        text-align: left;
        max-width: 700px;
        padding: 0 20px;
        z-index: 10;
        pointer-events: none; /* Let mouse interact with 3D background */
    }

    .hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: 5rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: -2px;
        color: #fff;
        text-shadow: 0 4px 30px rgba(0,0,0,0.8), 0 2px 10px rgba(0,0,0,0.5);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 40px;
        font-weight: 400;
        line-height: 1.6;
        text-shadow: 0 2px 15px rgba(0,0,0,0.8);
    }

    .hero-actions {
        display: flex;
        gap: 20px;
        justify-content: flex-start;
        pointer-events: auto; /* Buttons need clicks */
    }

    .btn-3d {
        padding: 15px 35px;
        border-radius: 100px;
        font-size: 1.05rem;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .btn-3d-primary {
        background: linear-gradient(135deg, #4ade80, #10b981);
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(74,222,128,0.4);
    }
    .btn-3d-primary:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(74,222,128,0.6);
    }

    .btn-3d-outline {
        background: rgba(15, 23, 42, 0.6);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
    }
    .btn-3d-outline:hover {
        background: rgba(255,255,255,0.1);
        transform: translateY(-5px);
    }

    /* Slick Horizontal Bottom Bar for Destinations */
    .tour-controls {
        position: absolute;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 15px;
        z-index: 10;
        pointer-events: auto;
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        padding: 12px 25px;
        border-radius: 100px;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }

    .tour-slide-btn {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.6);
        padding: 10px 25px;
        border-radius: 100px;
        cursor: pointer;
        font-family: 'Outfit';
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .tour-slide-btn:hover {
         color: #fff;
         background: rgba(255,255,255,0.1);
    }

    .active-slide {
        background: linear-gradient(135deg, #4ade80, #10b981) !important;
        color: #0f172a !important;
        box-shadow: 0 10px 20px rgba(74,222,128,0.4);
    }

    /* Info Section (For scrolling content) */
    .glass-section {
        min-height: 100vh;
        padding: 100px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .glass-panel {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 60px;
        max-width: 1000px;
        width: 90%;
        margin: 0 auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .glass-panel h2 {
        font-family: 'Outfit';
        font-size: 3rem;
        margin-bottom: 20px;
        color: #4ade80;
    }

    /* ========== PREMIUM MOBILE UI ========== */
    @media (max-width: 768px) {
        .hero-section {
            align-items: center;
            justify-content: flex-start;
            padding-top: 150px;
        }

        .hero-overlay {
            padding: 0 20px;
            text-align: center;
            width: 100%;
            z-index: 20;
        }

        .hero-title {
            font-size: 2.4rem;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
            text-transform: uppercase;
            /* Enhance legibility against bright backgrounds */
            text-shadow: 0 4px 20px rgba(0,0,0,0.8), 0 2px 4px rgba(0,0,0,0.4);
            color: #fff;
            background: none;
            -webkit-text-fill-color: initial;
        }

        .hero-subtitle {
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 35px;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            padding: 0 10px;
        }

        .hero-actions {
            flex-direction: column;
            gap: 15px;
            align-items: center;
            width: 100%;
            pointer-events: auto;
        }

        .hero-actions .btn-3d {
            width: 100%;
            max-width: 280px;
            padding: 14px 25px;
            font-size: 1rem;
            border-radius: 99px;
            text-align: center;
        }

        /* App-like Horizontal Scrollbar for Controls */
        .tour-controls {
            bottom: 40px;
            left: 0;
            right: 0;
            transform: none;
            width: 100%;
            padding: 0 20px;
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
            justify-content: flex-start;
        }
        .tour-controls::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .tour-slide-btn {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 12px 24px;
            font-size: 0.9rem;
            border-radius: 99px;
            white-space: nowrap;
            flex-shrink: 0;
            scroll-snap-align: center;
            color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .active-slide {
            background: linear-gradient(135deg, #4ade80, #10b981) !important;
            color: #0f172a !important;
            border: none !important;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(74, 222, 128, 0.4) !important;
        }

        /* Enhance Info Section */
        .glass-section {
            padding: 60px 15px;
            min-height: auto;
        }

        .glass-panel {
            padding: 40px 25px;
            border-radius: 24px;
            width: 100%;
        }

        .glass-panel h2 {
            font-size: 2.2rem;
            text-align: center;
        }
        
        .glass-panel p {
            font-size: 1rem !important;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .glass-panel .btn-3d {
            display: block;
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem !important;
        }
    }
</style>
@endsection

@section('content')

<!-- Home Hero Section -->
<section class="hero-section">
    <div class="hero-overlay">
        <h1 class="hero-title">Khám Phá Thế Giới<br>Theo Cách Mới</h1>
        <p class="hero-subtitle">VietGo mang đến trải nghiệm không gian 3D tương tác. Vuốt chuột để thay đổi góc nhìn, đắm chìm vào phong cảnh hùng vĩ của Việt Nam trước khi xách balo lên và đi.</p>
        
        <div class="hero-actions">
            <a href="{{ route('tour.danh-sach') }}" class="btn-3d btn-3d-primary">Khám Phá Ngay</a>
            <a href="#about" class="btn-3d btn-3d-outline">Tìm Hiểu Thêm</a>
        </div>
    </div>

    <!-- 3D Scene Controls -->
    <div class="tour-controls">
        <button class="tour-slide-btn active-slide" data-index="0">Sapa sương mù</button>
        <button class="tour-slide-btn" data-index="1">Vịnh Hạ Long</button>
        <button class="tour-slide-btn" data-index="2">Hội An về đêm</button>
    </div>
</section>

<!-- Next Section -->
<section id="about" class="glass-section">
    <div class="glass-panel">
        <h2>Công Nghệ Tương Lai</h2>
        <p style="font-size:1.1rem; line-height:1.8; color:#cbd5e1; margin-bottom:20px;">
            Chúng tôi đã loại bỏ giao diện phẳng nhàm chán. Bằng cách tích hợp công nghệ WebGL (Three.js) và GreenSock (GSAP), VietGo biến website thành một hành trình trải nghiệm chứ không chỉ là công cụ đặt vé.
        </p>
        <p style="font-size:1.1rem; line-height:1.8; color:#cbd5e1; margin-bottom:30px;">
            Hệ thống đặt tour và thanh toán an toàn ở backend vẫn được giữ nguyên bản, đảm bảo sự ổn định tuyệt đối dưới lớp áo đồ họa 3D lộng lẫy này.
        </p>
        <a href="{{ route('tour.danh-sach') }}" class="btn-3d btn-3d-outline" style="display:inline-block;">Bắt đầu đặt Tour</a>
    </div>
</section>

@endsection

@endsection
