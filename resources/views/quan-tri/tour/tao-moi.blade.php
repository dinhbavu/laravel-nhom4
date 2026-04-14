@extends('layouts.quan-tri')
@section('title', 'Thêm Tour Mới - VietGo Admin')
@section('page-title', 'Thêm Tour Mới')

@section('css')
<style>
@media (max-width: 768px) {
    .tour-img-preview { height: 160px !important; }
}
</style>
@endsection

@section('content')
<div class="back-header">
    <a href="{{ route('quan-tri.tour.danh-sach') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    <h2 style="font-size:20px; font-weight:800; color:#0f172a;">Tạo Tour Du Lịch Mới</h2>
</div>

<form action="{{ route('quan-tri.tour.luu') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid-detail">

        <!-- Main Info -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div class="card">
                <div class="card-header"><h3><i class="fa-solid fa-info-circle"></i> Thông Tin Cơ Bản</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Tên Tour <span class="req">*</span></label>
                        <input type="text" name="ten_tour" value="{{ old('ten_tour') }}" class="form-control" placeholder="VD: Tour Hà Nội - Sapa 3N2Đ" required>
                        @error('ten_tour')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Vùng Miền <span class="req">*</span></label>
                            <select name="vung_mien" class="form-control" required>
                                <option value="">-- Chọn vùng miền --</option>
                                <option value="mien_bac" {{ old('vung_mien') == 'mien_bac' ? 'selected' : '' }}>Miền Bắc</option>
                                <option value="mien_trung" {{ old('vung_mien') == 'mien_trung' ? 'selected' : '' }}>Miền Trung</option>
                                <option value="mien_nam" {{ old('vung_mien') == 'mien_nam' ? 'selected' : '' }}>Miền Nam</option>
                            </select>
                            @error('vung_mien')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Địa Điểm Du Lịch <span class="req">*</span></label>
                            <input type="text" name="nhap_diem_den" value="{{ old('nhap_diem_den') }}" class="form-control" placeholder="VD: Vịnh Hạ Long, Sapa..." required>
                            @error('nhap_diem_den')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Loại Tour</label>
                        <select name="loai_tour" class="form-control">
                                <option value="trong_nuoc" {{ old('loai_tour')=='trong_nuoc' ? 'selected' : '' }}>🇻🇳 Trong Nước</option>
                                <option value="quoc_te" {{ old('loai_tour')=='quoc_te' ? 'selected' : '' }}>✈️ Quốc Tế</option>
                                <option value="nghi_duong" {{ old('loai_tour')=='nghi_duong' ? 'selected' : '' }}>🏖 Nghỉ Dưỡng</option>
                                <option value="mao_hiem" {{ old('loai_tour')=='mao_hiem' ? 'selected' : '' }}>⛰ Mạo Hiểm</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Số Ngày <span class="req">*</span></label>
                            <input type="number" name="so_ngay" value="{{ old('so_ngay', 3) }}" min="1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số Đêm <span class="req">*</span></label>
                            <input type="number" name="so_dem" value="{{ old('so_dem', 2) }}" min="0" class="form-control" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Giá Người Lớn (VNĐ) <span class="req">*</span></label>
                            <input type="number" name="gia_nguoi_lon" value="{{ old('gia_nguoi_lon') }}" min="0" class="form-control" placeholder="3500000" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giá Trẻ Em (VNĐ)</label>
                            <input type="number" name="gia_tre_em" value="{{ old('gia_tre_em') }}" min="0" class="form-control" placeholder="2000000">
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Phần Trăm Giảm Giá (%)</label>
                            <input type="number" name="phan_tram_giam_gia" value="{{ old('phan_tram_giam_gia', 0) }}" min="0" max="100" class="form-control" placeholder="Vd: 10">
                            <small class="text-muted" style="display:block;margin-top:4px;">Nhập 0 để không áp dụng giảm giá.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3><i class="fa-solid fa-align-left"></i> Mô Tả Tour</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Mô Tả Ngắn</label>
                        <textarea name="mo_ta_ngan" rows="3" class="form-control" placeholder="Tóm tắt hấp dẫn về tour (hiển thị trên danh sách)...">{{ old('mo_ta_ngan') }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Mô Tả Chi Tiết</label>
                        <textarea name="mo_ta_day_du" rows="8" class="form-control" placeholder="Mô tả đầy đủ về lịch trình, dịch vụ bao gồm, quy định...">{{ old('mo_ta_day_du') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div class="card">
                <div class="card-header"><h3><i class="fa-solid fa-image"></i> Hình Ảnh Bìa</h3></div>
                <div class="card-body">
                    <div id="preview-wrap" style="display:none; margin-bottom:12px;">
                        <img id="img-preview" style="width:100%; height:160px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;">
                    </div>
                    <label style="display:flex; flex-direction:column; align-items:center; justify-content:center; border: 2px dashed #d1d5db; border-radius:10px; padding:30px 20px; cursor:pointer; color:#94a3b8; text-align:center; gap:8px; transition:all 0.2s;" onmouseover="this.style.borderColor='#10b981'; this.style.color='#10b981';" onmouseout="this.style.borderColor='#d1d5db'; this.style.color='#94a3b8';">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px;"></i>
                        <span style="font-size:13px; font-weight:600;">Chọn ảnh bìa</span>
                        <span style="font-size:12px;">JPG, PNG, WebP — tối đa 2MB</span>
                        <input type="file" name="hinh_bia" accept="image/*" style="display:none;" onchange="previewImg(this)">
                    </label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3><i class="fa-solid fa-sliders"></i> Cài Đặt</h3></div>
                <div class="card-body">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px; background:#f8fafc; border-radius:9px; margin-bottom:10px;">
                        <input type="checkbox" name="noi_bat" value="1" {{ old('noi_bat') ? 'checked' : '' }} style="width:16px; height:16px; accent-color:#f59e0b;">
                        <div>
                            <div style="font-size:13.5px; font-weight:600; color:#1e293b;">⭐ Tour Nổi Bật</div>
                            <div style="font-size:12px; color:#64748b;">Hiển thị ở vị trí ưu tiên trang chủ</div>
                        </div>
                    </label>
                    <div class="form-group" style="margin-bottom:0; margin-top:4px;">
                        <label class="form-label">Trạng Thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="hoat_dong" selected>✅ Hoạt động (Hiển thị)</option>
                            <option value="con_cho">⏳ Chờ (Chưa hiển thị)</option>
                            <option value="ngung">🚫 Ngưng hoạt động</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="font-size:15px; padding:13px;">
                <i class="fa-solid fa-plus"></i> Tạo Tour Mới
            </button>
            <a href="{{ route('quan-tri.tour.danh-sach') }}" class="btn btn-secondary btn-full">Hủy Bỏ</a>
        </div>
    </div>
</form>

@section('js')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('img-preview').src = e.target.result;
            document.getElementById('preview-wrap').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
@endsection
