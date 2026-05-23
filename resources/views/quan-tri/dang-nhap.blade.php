<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản Trị - VietGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0a0f1e;
            overflow: hidden;
        }

        /* ═══════════════════════════════════════════
           LEFT SIDE — Admin Dashboard Illustration
        ═══════════════════════════════════════════ */
        .admin-showcase {
            flex: 1.2;
            background: linear-gradient(145deg, #0a0f1e 0%, #0f1a2e 30%, #132238 60%, #0d1726 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem;
            overflow: hidden;
        }

        /* Ambient glow effects */
        .admin-showcase::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatGlow 8s ease-in-out infinite;
        }
        .admin-showcase::after {
            content: '';
            position: absolute;
            bottom: -15%;
            right: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatGlow 10s ease-in-out infinite reverse;
        }

        @keyframes floatGlow {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -30px) scale(1.1); }
        }

        /* Grid pattern overlay */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse at center, rgba(0,0,0,0.6) 0%, transparent 75%);
        }

        /* Dashboard mockup cards */
        .showcase-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 520px;
        }

        .showcase-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 3rem;
        }
        .showcase-brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            box-shadow: 0 8px 25px rgba(16,185,129,0.4);
        }
        .showcase-brand-text .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 30px;
            font-weight: 900;
            color: white;
        }
        .showcase-brand-text .brand-name span { color: #10b981; }
        .showcase-brand-text .brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Mock dashboard stats */
        .mock-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 1.5rem;
        }
        .mock-stat {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeSlideUp 0.8s ease both;
        }
        .mock-stat:nth-child(1) { animation-delay: 0.2s; }
        .mock-stat:nth-child(2) { animation-delay: 0.35s; }
        .mock-stat:nth-child(3) { animation-delay: 0.5s; }
        .mock-stat:nth-child(4) { animation-delay: 0.65s; }
        .mock-stat:hover { 
            background: rgba(255,255,255,0.07);
            border-color: rgba(16,185,129,0.2);
            transform: translateY(-2px);
        }
        .mock-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .mock-stat-icon.green { background: rgba(16,185,129,0.15); color: #34d399; }
        .mock-stat-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .mock-stat-icon.amber { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .mock-stat-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .mock-stat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .mock-stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: white;
        }

        /* Mock chart bar */
        .mock-chart {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeSlideUp 0.8s ease 0.8s both;
        }
        .mock-chart-title {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            font-weight: 600;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mock-chart-title i { color: #10b981; font-size: 13px; }
        .mock-bars {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 70px;
        }
        .mock-bar {
            flex: 1;
            border-radius: 6px 6px 0 0;
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .mock-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 6px 6px 0 0;
            background: rgba(255,255,255,0.3);
        }

        /* Tagline */
        .showcase-tagline {
            text-align: center;
            animation: fadeSlideUp 0.8s ease 1s both;
        }
        .showcase-tagline h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .showcase-tagline h2 span { color: #10b981; }
        .showcase-tagline p {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            line-height: 1.6;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(16,185,129,0.3);
            animation: drift linear infinite;
        }
        @keyframes drift {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { transform: translateY(-400px) translateX(60px); opacity: 0; }
        }

        /* ═══════════════════════════════════════════
           RIGHT SIDE — Login Form
        ═══════════════════════════════════════════ */
        .login-panel {
            width: 480px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            background: #ffffff;
            position: relative;
            z-index: 10;
            box-shadow: -20px 0 60px rgba(0,0,0,0.15);
        }

        /* Decorative top accent */
        .login-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669, #10b981);
        }

        .login-form-wrap {
            width: 100%;
            max-width: 360px;
        }

        /* Logo in form */
        .login-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
        }
        .login-logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }
        .login-logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
        }
        .login-logo-text span { color: #10b981; }

        .login-heading {
            margin-bottom: 2rem;
        }
        .login-heading h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .login-heading p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        /* Admin badge */
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #fef3c7, #fff7ed);
            border: 1px solid #fde68a;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #92400e;
            letter-spacing: 0.5px;
            margin-bottom: 1.5rem;
        }
        .admin-badge i { font-size: 12px; }

        /* Alert styles */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }
        .alert-box.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-box.success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-box i { margin-top: 2px; flex-shrink: 0; }

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap > i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            transition: color 0.2s;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 42px 12px 42px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-wrap input::placeholder { color: #94a3b8; }
        .input-wrap input:focus {
            border-color: #10b981;
            background: white;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
        }
        .input-wrap input:focus + i,
        .input-wrap:focus-within > i { color: #10b981; }

        /* Password toggle */
        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pass:hover { color: #475569; }

        /* Checkbox */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #475569;
            cursor: pointer;
        }
        .checkbox-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #10b981;
            cursor: pointer;
        }

        /* Submit */
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16,185,129,0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(16,185,129,0.3);
        }

        /* Footer note */
        .login-footer {
            margin-top: 2.5rem;
            text-align: center;
        }
        .login-footer p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .login-footer i { color: #10b981; }

        /* Security indicators */
        .security-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 1.5rem;
        }
        .security-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: #94a3b8;
        }
        .security-item i { color: #10b981; font-size: 10px; }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) {
            .admin-showcase { display: none; }
            .login-panel {
                width: 100%;
                background: linear-gradient(180deg, #f8fafc 0%, #ffffff 30%);
            }
        }

        @media (max-width: 480px) {
            .login-panel { padding: 2rem 1.5rem; }
            .login-heading h1 { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- LEFT: Admin Dashboard Showcase -->
    <div class="admin-showcase">
        <div class="grid-pattern"></div>
        
        <!-- Floating particles -->
        <div class="particle" style="left:10%; bottom:20%; animation-duration:7s; animation-delay:0s;"></div>
        <div class="particle" style="left:30%; bottom:10%; animation-duration:9s; animation-delay:1s; width:3px; height:3px;"></div>
        <div class="particle" style="left:60%; bottom:15%; animation-duration:8s; animation-delay:2s;"></div>
        <div class="particle" style="left:80%; bottom:25%; animation-duration:11s; animation-delay:0.5s; width:5px; height:5px; background:rgba(59,130,246,0.3);"></div>
        <div class="particle" style="left:45%; bottom:5%; animation-duration:10s; animation-delay:3s; width:3px; height:3px;"></div>
        <div class="particle" style="left:20%; bottom:30%; animation-duration:12s; animation-delay:4s; background:rgba(139,92,246,0.2);"></div>

        <div class="showcase-content">
            <!-- Brand -->
            <div class="showcase-brand">
                <div class="showcase-brand-icon">
                    <i class="fa-solid fa-plane-departure"></i>
                </div>
                <div class="showcase-brand-text">
                    <div class="brand-name">Viet<span>Go</span></div>
                    <div class="brand-sub">Hệ Thống Quản Trị</div>
                </div>
            </div>

            <!-- Mock Dashboard Stats -->
            <div class="mock-stats">
                <div class="mock-stat">
                    <div class="mock-stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
                    <div class="mock-stat-label">Doanh Thu</div>
                    <div class="mock-stat-value">126.5M</div>
                </div>
                <div class="mock-stat">
                    <div class="mock-stat-icon blue"><i class="fa-solid fa-ticket-simple"></i></div>
                    <div class="mock-stat-label">Đơn Đặt</div>
                    <div class="mock-stat-value">1,284</div>
                </div>
                <div class="mock-stat">
                    <div class="mock-stat-icon amber"><i class="fa-solid fa-users"></i></div>
                    <div class="mock-stat-label">Khách Hàng</div>
                    <div class="mock-stat-value">3,892</div>
                </div>
                <div class="mock-stat">
                    <div class="mock-stat-icon purple"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div class="mock-stat-label">Tour</div>
                    <div class="mock-stat-value">156</div>
                </div>
            </div>

            <!-- Mock Chart -->
            <div class="mock-chart">
                <div class="mock-chart-title">
                    <i class="fa-solid fa-chart-line"></i> Doanh Thu 6 Tháng Gần Nhất
                </div>
                <div class="mock-bars">
                    <div class="mock-bar" style="height:45%; background:linear-gradient(to top, rgba(16,185,129,0.3), rgba(16,185,129,0.1));"></div>
                    <div class="mock-bar" style="height:65%; background:linear-gradient(to top, rgba(16,185,129,0.4), rgba(16,185,129,0.15));"></div>
                    <div class="mock-bar" style="height:40%; background:linear-gradient(to top, rgba(16,185,129,0.3), rgba(16,185,129,0.1));"></div>
                    <div class="mock-bar" style="height:80%; background:linear-gradient(to top, rgba(16,185,129,0.5), rgba(16,185,129,0.2));"></div>
                    <div class="mock-bar" style="height:55%; background:linear-gradient(to top, rgba(16,185,129,0.4), rgba(16,185,129,0.15));"></div>
                    <div class="mock-bar" style="height:95%; background:linear-gradient(to top, #10b981, rgba(16,185,129,0.3));"></div>
                    <div class="mock-bar" style="height:70%; background:linear-gradient(to top, rgba(16,185,129,0.45), rgba(16,185,129,0.15));"></div>
                    <div class="mock-bar" style="height:85%; background:linear-gradient(to top, rgba(16,185,129,0.5), rgba(16,185,129,0.2));"></div>
                </div>
            </div>

            <!-- Tagline -->
            <div class="showcase-tagline">
                <h2>Quản lý <span>doanh nghiệp</span><br>chuyên nghiệp & hiệu quả</h2>
                <p>Theo dõi đặt tour, doanh thu, khách hàng và vận hành toàn bộ hệ thống du lịch chỉ với một nền tảng.</p>
            </div>
        </div>
    </div>

    <!-- RIGHT: Login Form -->
    <div class="login-panel">
        <div class="login-form-wrap">
            <!-- Logo (mobile only shows this) -->
            <div class="login-logo" style="display:none;">
                <div class="login-logo-icon"><i class="fa-solid fa-plane-departure"></i></div>
                <div class="login-logo-text">Viet<span>Go</span></div>
            </div>
            <style>
                @media (max-width: 1024px) { .login-logo { display: flex !important; } }
            </style>

            <!-- Badge -->
            <div class="admin-badge">
                <i class="fa-solid fa-shield-halved"></i> QUẢN TRỊ VIÊN
            </div>

            <!-- Heading -->
            <div class="login-heading">
                <h1>Đăng Nhập Quản Trị</h1>
                <p>Vui lòng đăng nhập bằng tài khoản admin hoặc nhân viên để truy cập hệ thống.</p>
            </div>

            <!-- Alerts -->
            @if($errors->any() || session('loi'))
            <div class="alert-box error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    {{ session('loi') }}
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if(session('thanh_cong'))
            <div class="alert-box success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('thanh_cong') }}</span>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('quan-tri.dang-nhap.xu-ly') }}" method="POST" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fa-regular fa-user" style="margin-right:4px; color:#10b981;"></i> Tài khoản
                    </label>
                    <div class="input-wrap">
                        <input type="text" id="email" name="email" placeholder="Nhập email hoặc tên đăng nhập..."
                               value="{{ old('email') }}" required autofocus>
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="mat_khau">
                        <i class="fa-solid fa-lock" style="margin-right:4px; color:#10b981;"></i> Mật khẩu
                    </label>
                    <div class="input-wrap">
                        <input type="password" id="mat_khau" name="mat_khau" placeholder="••••••••" required>
                        <i class="fa-solid fa-key"></i>
                        <button type="button" class="toggle-pass" onclick="togglePassword()" title="Hiện/ẩn mật khẩu">
                            <i id="eyeIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="ghi_nho" value="1">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập Hệ Thống
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <div class="security-row">
                    <span class="security-item"><i class="fa-solid fa-lock"></i> Mã hóa SSL</span>
                    <span class="security-item"><i class="fa-solid fa-shield-halved"></i> Bảo mật 2 lớp</span>
                    <span class="security-item"><i class="fa-solid fa-fingerprint"></i> Xác thực</span>
                </div>
                <p style="margin-top: 1.25rem;">
                    <i class="fa-solid fa-circle-info"></i>
                    Trang đăng nhập chỉ dành cho<br>
                    <strong style="color:#475569;">Admin</strong> và <strong style="color:#475569;">Nhân viên</strong> hệ thống VietGo
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('mat_khau');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-regular fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-regular fa-eye';
            }
        }

        // Animate mock chart bars on load
        document.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('.mock-bar');
            bars.forEach((bar, i) => {
                const finalHeight = bar.style.height;
                bar.style.height = '0%';
                bar.style.transition = `height 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) ${0.3 + i * 0.08}s`;
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        bar.style.height = finalHeight;
                    });
                });
            });
        });
    </script>
</body>
</html>
