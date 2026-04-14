@extends('layouts.quan-tri')
@section('title', 'Sửa Người Dùng - VietGo Admin')
@section('page-title', 'Chỉnh Sửa Thông Tin & Phân Quyền')

@section('content')
<div class="back-header">
    <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    <h2 style="font-size:20px; font-weight:800; color:#0f172a;">Chỉnh Sửa Người Dùng</h2>
</div>

<div style="max-width: 620px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-user-pen"></i> Chỉnh Sửa Phân Quyền</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('quan-tri.nguoi-dung.cap-nhat', $nguoi_dung) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Họ và Tên Đầy Đủ <span class="req">*</span></label>
                    <input type="text" name="ho_ten" value="{{ old('ho_ten', $nguoi_dung->ho_ten) }}" class="form-control" required>
                    @error('ho_ten')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Địa Chỉ Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $nguoi_dung->email) }}" class="form-control" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số Điện Thoại</label>
                    <input type="tel" name="so_dien_thoai" value="{{ old('so_dien_thoai', $nguoi_dung->so_dien_thoai) }}" class="form-control">
                </div>

                <div class="divider"></div>

                <div class="form-group">
                    <label class="form-label">Vai Trò <span class="req">*</span></label>
                    <select name="vai_tro" class="form-control" required>
                        <option value="khach_hang" {{ old('vai_tro', $nguoi_dung->vai_tro) == 'khach_hang' ? 'selected' : '' }}>👤 Khách Hàng</option>
                        <option value="nhan_vien" {{ old('vai_tro', $nguoi_dung->vai_tro) == 'nhan_vien' ? 'selected' : '' }}>👨‍💼 Nhân Viên</option>
                        <option value="admin" {{ old('vai_tro', $nguoi_dung->vai_tro) == 'admin' ? 'selected' : '' }}>🛡 Quản Trị Viên</option>
                    </select>
                    <div class="form-hint">Lưu ý: Thay đổi quyền sẽ áp dụng ngay trong lần truy cập tiếp theo của họ.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Đổi mật khẩu (Bỏ trống nếu giữ nguyên)</label>
                    <input type="text" name="mat_khau" class="form-control" minlength="6">
                    <div class="form-hint"><i class="fa-solid fa-info-circle" style="color:#b5c4df;"></i> Nhập mật khẩu mới nếu muốn cấp lại mật khẩu</div>
                    @error('mat_khau')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Xác Nhận Mật Khẩu</label>
                    <input type="text" name="mat_khau_confirmation" class="form-control" minlength="6">
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:24px;">
                    <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="btn btn-secondary">Hủy Bỏ</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
