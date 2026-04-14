@extends('layouts.khach-hang')
@section('title', 'Hồ Sơ Cá Nhân - VietGo')

@section('css')
<style>
    .profile-container { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; margin-top: 2rem; }
    @media(max-width:768px){
        .profile-container{grid-template-columns:1fr!important}
        .profile-sidebar{position:static!important}
        .luoi-2-cot{grid-template-columns:1fr!important}
    }
    
    /* Profile specific glass overrides */
    .avatar-upload-wrapper { border: 2px solid rgba(74, 222, 128, 0.3); }
    .profile-name { color: #fff; font-family: 'Outfit'; font-weight: 800; }
    .profile-email { color: rgba(255, 255, 255, 0.5); }
    
    .section-card-title {
        color: var(--primary-accent) !important;
        font-family: 'Outfit';
        font-weight: 800;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="profile-header-section" style="padding:7rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <h1 style="font-family:var(--font-heading);font-size:1.85rem;font-weight:900;color:white;margin:0 0 .3rem;">
            <i class="fa-regular fa-user text-primary mr-2" style="color:#4ade80 !important;"></i> Tài Khoản Của Tôi
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.9rem;">Quản lý thông tin và hoạt động của bạn trên VietGo</p>
    </div>
</div>

<div class="container">
    <div class="profile-container">

        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="avatar-upload-wrapper">
                    <img src="{{ auth()->user()->anh_dai_dien_url }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
                    <label for="avatarInput" class="avatar-edit-btn" title="Đổi ảnh">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>
                <div class="profile-name">{{ auth()->user()->ho_ten }}</div>
                <div class="profile-email">{{ auth()->user()->email }}</div>
                <div style="margin-top:.75rem;">
                    <span class="badge badge-success"><i class="fa-solid fa-check mr-1"></i> Khách hàng</span>
                </div>

                <div class="profile-nav">
                    <a href="{{ route('khach-hang.ho-so') }}" class="profile-nav-item active">
                        <i class="fa-regular fa-user"></i> Hồ sơ cá nhân
                    </a>
                    <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="profile-nav-item">
                        <i class="fa-solid fa-history"></i> Lịch sử đặt tour
                    </a>
                    <a href="{{ route('khach-hang.dat-tour.thanh-toan') }}" class="profile-nav-item">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử thanh toán
                    </a>
                    <a href="{{ route('khach-hang.yeu-thich') }}" class="profile-nav-item">
                        <i class="fa-regular fa-heart"></i> Tour yêu thích
                    </a>
                    <a href="{{ route('khach-hang.thong-bao') }}" class="profile-nav-item">
                        <i class="fa-regular fa-bell"></i> Thông báo
                    </a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div>
            <!-- Cập nhật avatar (ẩn) -->
            <form action="{{ route('khach-hang.ho-so.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <input type="file" id="avatarInput" name="anh_dai_dien" accept="image/*" style="display:none;" onchange="previewAndUpload(this)">
            </form>

            <!-- Thông tin cá nhân -->
            <div class="section-card animate-fade-in">
                <div class="section-card-title">
                    <i class="fa-regular fa-id-card"></i> Thông Tin Cá Nhân
                </div>

                <form action="{{ route('khach-hang.ho-so.cap-nhat') }}" method="POST">
                    @csrf

                    <div class="luoi-2-cot">
                        <div class="form-group">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten', auth()->user()->ho_ten) }}" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled style="background:#f8fafc;color:var(--text-secondary);">
                        </div>
                    </div>

                    <div class="luoi-2-cot">
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai', auth()->user()->so_dien_thoai) }}" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" value="{{ old('ngay_sinh', auth()->user()->ngay_sinh ? \Carbon\Carbon::parse(auth()->user()->ngay_sinh)->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control" value="{{ old('dia_chi', auth()->user()->dia_chi) }}" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <select name="gioi_tinh" class="form-control">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="nam" {{ (old('gioi_tinh', auth()->user()->gioi_tinh) == 'nam') ? 'selected' : '' }}>Nam</option>
                            <option value="nu" {{ (old('gioi_tinh', auth()->user()->gioi_tinh) == 'nu') ? 'selected' : '' }}>Nữ</option>
                            <option value="khac" {{ (old('gioi_tinh', auth()->user()->gioi_tinh) == 'khac') ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Đổi mật khẩu -->
            <div class="section-card animate-fade-in">
                <div class="section-card-title">
                    <i class="fa-solid fa-lock"></i> Đổi Mật Khẩu
                </div>
                <form action="{{ route('khach-hang.ho-so.doi-mat-khau') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="mat_khau_cu" class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
                    </div>
                    <div class="luoi-2-cot">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi" class="form-control" placeholder="Ít nhất 8 ký tự" required minlength="8">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-key"></i> Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function previewAndUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('avatarForm').submit();
    }
}
</script>
@endsection
