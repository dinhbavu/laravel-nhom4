@extends('layouts.khach-hang')
@section('title', 'Nhập Mã Xác Nhận - VietGo')

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
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff; font-size: 1.8rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: 20px; margin: 0 auto 1.5rem;
    box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
}
.auth-logo-text {
    color: #fff; font-family: 'Outfit'; font-size: 2rem;
    font-weight: 800; margin-bottom: 0.5rem; text-align: center;
}
.auth-desc {
    color: rgba(255,255,255,0.7); text-align: center; margin-bottom: 2rem; font-size: 0.95rem;
}

.otp-inputs { display:flex; justify-content:space-between; margin-bottom:2rem; gap:.5rem; }
.otp-inputs input { 
    width:3.2rem; height:3.8rem; text-align:center; font-size:1.6rem; font-weight:700; 
    border:1px solid rgba(255,255,255,0.1); border-radius:15px; 
    background:rgba(255,255,255,0.05); color: #fff;
    outline:none; transition:all 0.3s; 
}
.otp-inputs input:focus { 
    border-color:#10b981; 
    background:rgba(255,255,255,0.1);
    box-shadow:0 0 15px rgba(16,185,129,0.3);
    transform: translateY(-2px);
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
            <div class="auth-logo-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
            <h1 class="auth-logo-text">Xác Nhận Email</h1>
            <p class="auth-desc">Nhập mã 6 chữ số vừa được gửi tới <strong>{{ $email }}</strong></p>
        </div>

        @if(session('thanh_cong'))
        <div class="alert" style="background:rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.9rem;">
            <p style="margin:0;"><i class="fa-solid fa-circle-check"></i> {{ session('thanh_cong') }}</p>
        </div>
        @endif

        @if(session('loi') || $errors->any())
        <div class="alert" style="background:rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.9rem;">
            <div>
                @if(session('loi'))<p style="margin:0;">{{ session('loi') }}</p>@endif
                @foreach($errors->all() as $e)<p style="margin:0;">{{ $e }}</p>@endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('quen-mat-khau.xac-nhan-otp') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" id="realOTP">
            
            <div class="otp-inputs" id="otp-container">
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" autofocus required>
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            </div>

            <button type="submit" id="submitBtn" class="btn-auth w-full" style="width:100%; opacity:0.6; pointer-events:none;">
                Xác Nhận Ngay
            </button>
        </form>

        <div style="border-top:1px solid rgba(255,255,255,0.1); margin-top:2.5rem; padding-top:1.5rem; text-align:center;">
            <p style="font-size:.85rem; color:rgba(255,255,255,0.5); margin-bottom:.75rem;">Bạn chưa nhận được mã?</p>
            <form action="{{ route('quen-mat-khau.gui-otp') }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" style="background:none; border:none; color:#f471b5; font-weight:700; cursor:pointer; font-size:.9rem; transition: 0.3s;" onmouseover="this.style.color='#fbcfe8'" onmouseout="this.style.color='#f471b5'">Gửi lại mã mới</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-inputs input');
    const realOTP = document.getElementById('realOTP');
    const submitBtn = document.getElementById('submitBtn');

    function updateOTP() {
        let otpValue = "";
        inputs.forEach(inp => otpValue += inp.value);
        realOTP.value = otpValue;
        if(otpValue.length === 6) {
            submitBtn.style.opacity = '1';
            submitBtn.style.pointerEvents = 'auto';
        } else {
            submitBtn.style.opacity = '0.6';
            submitBtn.style.pointerEvents = 'none';
        }
    }

    inputs.forEach((input, index) => {
        // Khi nhập 1 ký tự -> tự nhảy sang ô kế tiếp
        input.addEventListener('input', (e) => {
            // Chỉ cho phép số
            input.value = input.value.replace(/\D/g, '').slice(0, 1);
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateOTP();
        });

        // Xử lý phím Backspace -> xóa và lùi về ô trước
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                updateOTP();
            }
        });

        // Xử lý paste toàn bộ mã OTP
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            if(pastedData) {
                for(let i = 0; i < pastedData.length; i++) {
                    if(inputs[i]) inputs[i].value = pastedData[i];
                }
                inputs[Math.min(pastedData.length, 5)].focus();
                updateOTP();
            }
        });

        // Click vào ô -> tự select nội dung để ghi đè
        input.addEventListener('focus', () => input.select());
    });
});
</script>
@endsection
