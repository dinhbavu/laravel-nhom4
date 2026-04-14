@extends('layouts.3d-app')
@section('title', 'Đăng Ký - VietGo 3D')

@section('css')
<style>
.auth-full {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    position: relative; padding: 5rem 1.25rem 2rem;
    z-index: 10;
}
.auth-box {
    position: relative; width: 100%; max-width: 500px;
    background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-radius: 25px; padding: 3rem 2.5rem;
    border: 1px solid rgba(255,255,255,0.06);
}
@media(max-width:480px) { .auth-box { padding: 2rem 1.5rem; } }

.auth-logo-wrap { text-align: center; margin-bottom: 2rem; }
.auth-logo-icon {
    width: 4rem; height: 4rem; border-radius: 1rem;
    background: rgba(74,222,128,0.1); color: #4ade80;
    font-size: 1.8rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
    box-shadow: 0 0 20px rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2);
}
.auth-logo-text { font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 900; color: #fff; }
.auth-logo-text span { color: #4ade80; text-shadow: 0 0 15px rgba(74,222,128,0.3); }
.auth-welcome { font-size: .875rem; color: rgba(255,255,255,0.5); margin-top: .5rem; }

.form-label { color: rgba(255,255,255,0.8); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; }
.form-control {
    width: 100%; padding: 0.8rem 1rem 0.8rem 2.8rem; border-radius: 12px;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    color: #e2e8f0; outline: none; transition: all 0.3s;
}
.form-control:focus { background: rgba(255,255,255,0.08); border-color: #4ade80; box-shadow: 0 0 15px rgba(74,222,128,0.15); }
.form-control::placeholder { color: rgba(255,255,255,0.3); }
.form-group { margin-bottom: 1.5rem; position: relative; }

.luoi-2-cot { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:500px) { .luoi-2-cot { grid-template-columns: 1fr; gap: 0; } }

.btn-login {
    width: 100%; padding: 0.8rem; border-radius: 12px; margin-top: 10px;
    background: linear-gradient(135deg, #4ade80, #10b981); color: #0f172a;
    font-weight: 800; font-size: 1rem; text-transform: uppercase; border: none; cursor: pointer;
    transition: all 0.3s; box-shadow: 0 5px 20px rgba(74,222,128,0.3);
}
.btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(74,222,128,0.5); }

/* Alert */
.alert-error {
    background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px;
    padding: 1rem; color: #f87171; font-size: .875rem; margin-bottom: 1.5rem; display: flex; gap: .75rem; align-items: flex-start;
}
</style>
@endsection

@section('content')
<div class="auth-full">
    <div class="auth-box">
        <div class="auth-logo-wrap">
            <div class="auth-logo-icon"><i class="fa-solid fa-plane-departure"></i></div>
            <div class="auth-logo-text">Tham Gia <span>VietGo</span></div>
            <p class="auth-welcome">Gia nhập cộng đồng yêu thích du lịch!</p>
        </div>

        @if($errors->any() || session('loi'))
        <div class="alert-error">
            <i class="fa-solid fa-triangle-exclamation" style="margin-top:3px;"></i>
            <div>
                @if(session('loi')){{ session('loi') }}<br>@endif
                @foreach($errors->all() as $e){{ $e }}<br>@endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('dang-ky.xu-ly') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="ho_ten">Họ và tên</label>
                <div style="position:relative;">
                    <i class="fa-regular fa-user" style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#4ade80;"></i>
                    <input type="text" id="ho_ten" name="ho_ten" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('ho_ten') }}" required autofocus maxlength="100">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Địa chỉ Email</label>
                <div style="position:relative;">
                    <i class="fa-regular fa-envelope" style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#4ade80;"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nhap@email.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="so_dien_thoai">Số điện thoại</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-phone" style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#4ade80;"></i>
                    <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-control" placeholder="0901 234 567" value="{{ old('so_dien_thoai') }}" required maxlength="15">
                </div>
            </div>

            <div class="luoi-2-cot">
                <div class="form-group">
                    <label class="form-label" for="mat_khau">Mật khẩu</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-lock" style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#4ade80;"></i>
                        <input type="password" id="mat_khau" name="mat_khau" class="form-control" placeholder="Ít nhất 8 ký tự" required minlength="8">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mat_khau_confirmation">Xác nhận mật khẩu</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-shield-halved" style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#4ade80;"></i>
                        <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-user-plus"></i> Tạo Tài Khoản
            </button>
        </form>

        <div style="text-align:center; margin-top:2rem; padding-top:1.5rem; border-top:1px solid rgba(255,255,255,0.06);">
            <p style="font-size:.875rem;color:rgba(255,255,255,0.5);">
                Đã có tài khoản?
                <a href="{{ route('dang-nhap') }}" style="color:#4ade80;font-weight:700;text-decoration:none;">Đăng nhập ngay</a>
            </p>
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
