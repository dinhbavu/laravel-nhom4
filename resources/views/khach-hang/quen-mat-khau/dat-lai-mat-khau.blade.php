@extends('layouts.khach-hang')
@section('title', 'Đặt Lại Mật Khẩu - VietGo')

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
    background: linear-gradient(135deg, #4ade80, #3b82f6);
    color: #fff; font-size: 1.8rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: 20px; margin: 0 auto 1.5rem;
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
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
.toggle-pass {
    position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,0.4); cursor: pointer; transition: 0.3s;
}
.toggle-pass:hover { color: #4ade80; }
</style>
@endsection

@section('content')
<div class="auth-full">
    <div class="auth-box animate-fade-in">
        <div class="auth-logo-wrap">
            <div class="auth-logo-icon"><i class="fa-solid fa-key"></i></div>
            <h1 class="auth-logo-text">Mật Khẩu Mới</h1>
            <p class="auth-desc">Vui lòng thiết lập mật khẩu mới và bảo mật tuyệt đối.</p>
        </div>

        @if(session('loi') || $errors->any())
        <div class="alert" style="background:rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.9rem;">
            @if(session('loi'))<p style="margin:0;">{{ session('loi') }}</p>@endif
            @foreach($errors->all() as $e)<p style="margin:0;">{{ $e }}</p>@endforeach
        </div>
        @endif

        <form action="{{ route('quen-mat-khau.dat-lai-xu-ly') }}" method="POST" id="resetForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="glass-label" for="mat_khau">Mật khẩu mới</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-lock" style="position:absolute;left:1.2rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);pointer-events:none;"></i>
                    <input type="password" id="mat_khau" name="mat_khau" class="glass-input w-full" style="width:100%;" placeholder="••••••••" required autofocus minlength="6">
                    <i class="fa-regular fa-eye toggle-pass" onclick="togglePass('mat_khau', this)"></i>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="glass-label" for="mat_khau_confirmation">Xác nhận mật khẩu</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-shield-check" style="position:absolute;left:1.2rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);pointer-events:none;"></i>
                    <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation" class="glass-input w-full" style="width:100%;" placeholder="••••••••" required minlength="6">
                    <i class="fa-regular fa-eye toggle-pass" onclick="togglePass('mat_khau_confirmation', this)"></i>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2.5rem; background: rgba(255,255,255,0.05); padding: 12px; border-radius: 12px;">
                <input type="checkbox" id="ghi_nho" name="ghi_nho" value="1" checked style="accent-color:#10b981;width:1.2rem;height:1.2rem; cursor:pointer;">
                <label for="ghi_nho" style="font-size:.9rem;color:rgba(255,255,255,0.7);cursor:pointer; font-weight: 500;">Ghi nhớ đăng nhập mới</label>
            </div>

            <button type="submit" class="btn-auth w-full" id="btnSubmit" style="width:100%;">
                <span id="btnText"><i class="fa-solid fa-check" style="margin-right:8px;"></i> Lưu Mật Khẩu Mới</span>
                <span id="btnLoading" style="display:none;"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang cập nhật...</span>
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', function() {
    document.getElementById('btnSubmit').style.pointerEvents = 'none';
    document.getElementById('btnSubmit').style.opacity = '0.7';
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnLoading').style.display = 'block';
});
</script>
@endsection
