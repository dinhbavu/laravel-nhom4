@extends('layouts.quan-tri')
@section('title', 'Tạo Nhân Viên - VietGo Admin')
@section('page-title', 'Tạo Tài Khoản Nhân Viên')

@section('content')
<div class="back-header">
    <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    <h2 style="font-size:20px; font-weight:800; color:#0f172a;">Cấp Tài Khoản Nhân Viên</h2>
</div>

<div style="max-width: 620px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-user-shield"></i> Thông Tin Tài Khoản Mới</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('quan-tri.nguoi-dung.luu-nhan-vien') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Họ và Tên Đầy Đủ <span class="req">*</span></label>
                    <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" class="form-control" placeholder="VD: Nguyễn Văn An" required>
                    @error('ho_ten')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Địa Chỉ Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nhanvien@vietgo.vn" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số Điện Thoại</label>
                    <input type="tel" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" class="form-control" placeholder="0912 345 678">
                </div>

                <div class="divider"></div>

                <div class="form-group">
                    <label class="form-label">Vai Trò <span class="req">*</span></label>
                    <select name="vai_tro" class="form-control" required>
                        <option value="nhan_vien" {{ old('vai_tro') == 'nhan_vien' ? 'selected' : '' }}>👨‍💼 Nhân Viên</option>
                        <option value="admin" {{ old('vai_tro') == 'admin' ? 'selected' : '' }}>🛡 Quản Trị Viên</option>
                    </select>
                    <div class="form-hint">Xác định quyền truy cập vào hệ thống</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật Khẩu Tạm Thời <span class="req">*</span></label>
                    <input type="text" name="mat_khau" value="{{ old('mat_khau', 'VietGo@123') }}" class="form-control" required minlength="6">
                    <div class="form-hint"><i class="fa-solid fa-info-circle" style="color:#3b82f6;"></i> Yêu cầu nhân viên đổi mật khẩu sau lần đăng nhập đầu tiên</div>
                    @error('mat_khau')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Xác Nhận Mật Khẩu <span class="req">*</span></label>
                    <input type="text" name="mat_khau_confirmation" class="form-control" required minlength="6">
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:24px;">
                    <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="btn btn-secondary">Hủy Bỏ</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Tạo Tài Khoản
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info box -->
    <div style="margin-top:16px; padding:16px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px;">
        <div style="font-size:13px; color:#1d4ed8; font-weight:600; margin-bottom:6px;"><i class="fa-solid fa-circle-info"></i> Lưu ý khi tạo tài khoản:</div>
        <ul style="font-size:13px; color:#374151; padding-left:18px; line-height:1.8;">
            <li>Email phải duy nhất, không trùng với tài khoản khách hàng</li>
            <li>Mật khẩu tối thiểu 6 ký tự</li>
            <li>Nhân viên có thể quản lý Tour và Booking nhưng <strong>không thể</strong> xem mục Nhân Lực</li>
            <li>Chỉ Admin mới tạo được tài khoản nhân viên khác</li>
        </ul>
    </div>
</div>
@endsection
