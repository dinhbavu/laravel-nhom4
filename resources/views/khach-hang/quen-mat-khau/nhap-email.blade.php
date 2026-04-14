@extends('layouts.khach-hang')
@section('title', 'Quên Mật Khẩu - VietGo')

@section('css')
<style>
main{padding-top:0!important}
.auth-full {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 2rem 1.25rem;
    z-index: 10;
}
.auth-box {
    position: relative;
    width: 100%;
    max-width: 450px;
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(45px) saturate(200%) !important;
    -webkit-backdrop-filter: blur(45px) saturate(200%) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 30px;
    padding: 3rem;
    box-shadow: 0 40px 100px rgba(0,0,0,0.6);
    animation: authFloat 4s ease-in-out infinite;
}
@keyframes authFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.auth-logo-icon {
    width: 70px; height: 70px;
    background: linear-gradient(135deg, #4ade80, #10b981);
    color: #0f172a; font-size: 1.8rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: 20px; margin: 0 auto 1.5rem;
    box-shadow: 0 10px 20px rgba(74, 222, 128, 0.3);
}
.auth-logo-text {
    color: #fff; font-family: 'Outfit'; font-size: 2rem;
    font-weight: 800; margin-bottom: 0.5rem; text-align: center;
}
.auth-desc {
    color: rgba(255,255,255,0.7); text-align: center; margin-bottom: 2rem; font-size: 0.95rem;
}
.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #fff !important;
    padding: 14px 14px 14px 3rem !important;
    border-radius: 15px !important;
    transition: all 0.3s !important;
}
.glass-input:focus {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: #4ade80 !important;
    box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.2) !important;
}
.glass-label {
    color: rgba(255,255,255,0.8); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;
}
.btn-auth {
    background: linear-gradient(135deg, #4ade80, #10b981);
    color: #0f172a; font-weight: 700; padding: 14px; border-radius: 15px;
    border: none; cursor: pointer; transition: all 0.3s;
    box-shadow: 0 10px 25px rgba(74, 222, 128, 0.3);
}
.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(74, 222, 128, 0.5);
}
</style>
@endsection

@section('content')
<div class="auth-full">
    <div class="auth-box animate-fade-in">
        <div class="auth-logo-wrap">
            <div class="auth-logo-icon"><i class="fa-solid fa-unlock-keyhole"></i></div>
            <h1 class="auth-logo-text">Quên Mật Khẩu</h1>
            <p class="auth-desc">Nhập email đăng ký để nhận mã OTP bảo mật.</p>
        </div>

        @if($errors->any() || session('loi'))
        <div class="alert" style="background:rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.9rem;">
            @if(session('loi'))<p style="margin:0;">{{ session('loi') }}</p>@endif
            @foreach($errors->all() as $e)<p style="margin:0;">{{ $e }}</p>@endforeach
        </div>
        @endif

        <form action="{{ route('quen-mat-khau.gui-otp') }}" method="POST" id="emailForm">
            @csrf
            <div class="form-group" style="margin-bottom:2rem;">
                <label class="glass-label" for="email">Email liên kết với tài khoản</label>
                <div style="position:relative;">
                    <i class="fa-regular fa-envelope" style="position:absolute;left:1.2rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);pointer-events:none;"></i>
                    <input type="email" id="email" name="email" class="glass-input w-full" style="width:100%;" placeholder="nhap@email.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-auth w-full" id="btnSubmit" style="width:100%;">
                <span id="btnText"><i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i> Gửi Mã OTP</span>
                <span id="btnLoading" style="display:none;"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang gửi email...</span>
            </button>
        </form>

        <div style="border-top:1px solid rgba(255,255,255,0.1); margin-top:2.5rem; padding-top:1.5rem; text-align:center;">
            <a href="{{ route('dang-nhap') }}" style="color:rgba(255,255,255,0.6); font-size:.9rem; text-decoration:none; transition: 0.3s;" onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                <i class="fa-solid fa-arrow-left" style="margin-right:8px;"></i> Quay lại đăng nhập
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('emailForm').addEventListener('submit', function() {
    document.getElementById('btnSubmit').style.pointerEvents = 'none';
    document.getElementById('btnSubmit').style.opacity = '0.7';
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnLoading').style.display = 'block';
});
</script>
@endsection
