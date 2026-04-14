@extends('layouts.quan-tri')
@section('title', 'Cập Nhật Banner - VietGo Admin')
@section('page-title', 'Chỉnh Sửa Banner')

@section('css')
<style>
.banner-form-wrap {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2rem;
    align-items: start;
}
@media(max-width: 1024px) {
    .banner-form-wrap { grid-template-columns: 1fr; }
}
@media(max-width: 768px) {
    .banner-form-wrap { gap: 1rem; }
    .form-panel-head { padding: 1.25rem !important; }
    .form-panel-body { padding: 1.25rem !important; }
    .preview-sticky { position: static !important; }
}
    padding: 1.75rem 2rem;
    background: linear-gradient(135deg, #1e293b, #065f46);
    color: white; display: flex; align-items: center; gap: 1rem;
}
.form-panel-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.form-panel-title { font-size: 1.2rem; font-weight: 800; margin: 0 0 .2rem; }
.form-panel-sub { font-size: .82rem; color: rgba(255,255,255,.65); margin: 0; }
.form-panel-body { padding: 2rem; }
.field-group { margin-bottom: 1.5rem; }
.field-label {
    display: flex; align-items: center; gap: .5rem;
    font-size: .8rem; font-weight: 800; color: #374151;
    text-transform: uppercase; letter-spacing: .06em; margin-bottom: .65rem;
}
.field-label i { color: var(--primary); }
.field-required { color: #ef4444; }
.field-input {
    width: 100%; padding: .85rem 1.1rem;
    border: 2px solid #e2e8f0; border-radius: 12px;
    font-size: .9rem; font-weight: 500; color: #0f172a;
    background: #f8fafc; transition: all .25s; outline: none; font-family: inherit;
}
.field-input:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(5,150,105,.1); }
.field-hint { margin-top: .45rem; font-size: .77rem; color: #64748b; display: flex; align-items: center; gap: .35rem; }
.toggle-wrap {
    display: flex; align-items: center; gap: .75rem;
    padding: 1rem 1.25rem; border-radius: 12px;
    border: 2px solid #e2e8f0; background: #f8fafc;
    cursor: pointer; transition: all .3s;
}
.toggle-wrap:hover { border-color: var(--primary); background: #f0fdf4; }
.toggle-wrap input[type=checkbox] { display: none; }
.toggle-switch {
    width: 44px; height: 24px; border-radius: 99px;
    background: #cbd5e1; position: relative; flex-shrink: 0; transition: background .3s;
}
.toggle-switch::after {
    content: ''; position: absolute; top: 3px; left: 3px;
    width: 18px; height: 18px; border-radius: 50%; background: white;
    transition: transform .3s; box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-checked .toggle-switch { background: var(--primary); }
.toggle-checked .toggle-switch::after { transform: translateX(20px); }
.toggle-text { flex: 1; }
.toggle-text strong { display: block; font-size: .875rem; font-weight: 700; color: #0f172a; }
.toggle-text span { font-size: .78rem; color: #64748b; }
.btn-row { display: flex; gap: .75rem; align-items: center; margin-top: 2rem; }
.btn-save {
    flex: 1; padding: 1rem; border: none; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), #059669);
    color: white; font-weight: 800; font-size: .95rem;
    cursor: pointer; transition: all .3s; font-family: inherit;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    box-shadow: 0 4px 18px rgba(5,150,105,.3);
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(5,150,105,.4); }
.btn-cancel {
    padding: 1rem 1.5rem; border-radius: 12px;
    border: 2px solid #e2e8f0; background: white;
    color: #64748b; font-weight: 700; font-size: .9rem;
    cursor: pointer; transition: all .25s; text-decoration: none;
    display: flex; align-items: center; gap: .5rem;
}
.btn-cancel:hover { border-color: #cbd5e1; background: #f8fafc; color: #374151; }
.preview-panel { position: sticky; top: 1.5rem; }
.preview-card {
    background: white; border-radius: 20px; overflow: hidden;
    box-shadow: 0 4px 24px rgba(15,23,42,.1); border: 1px solid #e2e8f0;
}
.preview-card-head {
    padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: .6rem;
    font-size: .8rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .06em; color: #64748b;
}
.preview-badge { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
.preview-img-wrap { height: 200px; background: #f1f5f9; overflow: hidden; position: relative; }
.preview-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.preview-overlay-demo {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,.75), transparent);
    padding: 2rem 1.25rem 1rem;
}
.preview-title-demo { font-size: 1rem; font-weight: 800; color: white; margin: 0; }
.preview-meta { padding: 1.25rem 1.5rem; }
.preview-meta-row { display: flex; align-items: center; gap: .75rem; padding: .5rem 0; border-bottom: 1px solid #f8fafc; font-size: .82rem; }
.preview-meta-label { color: #94a3b8; font-weight: 600; width: 80px; flex-shrink: 0; }
.preview-meta-val { color: #374151; font-weight: 600; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.75rem;">
    <a href="{{ route('quan-tri.khuyen-mai.danh-sach') }}" style="width:36px;height:36px;border-radius:10px;background:white;border:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none;flex-shrink:0;">
        <i class="fa-solid fa-arrow-left" style="font-size:.85rem;"></i>
    </a>
    <div>
        <h1 style="font-size:1.25rem;font-weight:900;color:#0f172a;margin:0;">Chỉnh Sửa Banner</h1>
        <p style="font-size:.8rem;color:#64748b;margin:.1rem 0 0;">{{ $banner->tieu_de }}</p>
    </div>
</div>

<form action="{{ route('quan-tri.khuyen-mai.cap-nhat', $banner) }}" method="POST" id="bannerForm" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="banner-form-wrap">

        {{-- LEFT --}}
        <div class="form-panel">
            <div class="form-panel-head">
                <div class="form-panel-icon">✏️</div>
                <div>
                    <p class="form-panel-title">Cập Nhật Thông Tin</p>
                    <p class="form-panel-sub">Chỉnh sửa nội dung banner sự kiện</p>
                </div>
            </div>
            <div class="form-panel-body">

                <div class="field-group">
                    <label class="field-label" for="tieu_de">
                        <i class="fa-solid fa-heading"></i> Tiêu đề Banner <span class="field-required">*</span>
                    </label>
                    <input type="text" name="tieu_de" id="tieu_de"
                        class="field-input @error('tieu_de') is-invalid @enderror"
                        value="{{ old('tieu_de', $banner->tieu_de) }}"
                        oninput="updatePreviewTitle(this.value)"
                        required>
                    @error('tieu_de')<div class="field-hint" style="color:#ef4444;"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="hinh_anh_url">
                        <i class="fa-solid fa-image"></i> Thay Đổi Ảnh Banner
                        <span style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:none;letter-spacing:0;">(Để trống nếu giữ nguyên)</span>
                    </label>
                    <input type="file" name="hinh_anh_url" id="hinh_anh_url"
                        class="field-input @error('hinh_anh_url') is-invalid @enderror"
                        accept="image/*"
                        onchange="updatePreviewImage(this)">
                    <div class="field-hint"><i class="fa-solid fa-circle-info"></i> Khuyến nghị kích thước <strong>1200×400px</strong>. Dung lượng tối đa <strong>20MB</strong>.</div>
                    @error('hinh_anh_url')<div class="field-hint" style="color:#ef4444;margin-top:.25rem;"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="duong_dan">
                        <i class="fa-solid fa-link"></i> Đường Dẫn Khi Click
                        <span style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:none;letter-spacing:0;">(Tùy chọn)</span>
                    </label>
                    <input type="url" name="duong_dan" id="duong_dan"
                        class="field-input @error('duong_dan') is-invalid @enderror"
                        value="{{ old('duong_dan', $banner->duong_dan) }}"
                        placeholder="https://...">
                </div>

                <div style="height:1px;background:#f1f5f9;margin:1.5rem 0;"></div>

                <div class="field-group" style="margin-bottom:0;">
                    <label class="toggle-wrap {{ old('trang_thai', $banner->trang_thai) ? 'toggle-checked' : '' }}" id="toggleWrap" for="trang_thai">
                        <input type="checkbox" name="trang_thai" id="trang_thai" {{ old('trang_thai', $banner->trang_thai) ? 'checked' : '' }} onchange="updateToggle(this)">
                        <div class="toggle-switch"></div>
                        <div class="toggle-text">
                            <strong id="toggleLabel">{{ old('trang_thai', $banner->trang_thai) ? 'Đang hiển thị' : 'Đang ẩn' }}</strong>
                            <span id="toggleSub">{{ old('trang_thai', $banner->trang_thai) ? 'Banner đang xuất hiện trên trang Khuyến Mãi.' : 'Banner không hiển thị công khai.' }}</span>
                        </div>
                        <i class="fa-solid {{ old('trang_thai', $banner->trang_thai) ? 'fa-eye' : 'fa-eye-slash' }}" id="toggleIcon" style="color:{{ old('trang_thai', $banner->trang_thai) ? 'var(--primary)' : '#94a3b8' }};font-size:1.1rem;"></i>
                    </label>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Cập Nhật Banner
                    </button>
                    <a href="{{ route('quan-tri.khuyen-mai.danh-sach') }}" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT PREVIEW --}}
        <div class="preview-panel">
            <div class="preview-card">
                <div class="preview-card-head">
                    <div class="preview-badge"></div> Xem Trước Banner
                </div>
                <div class="preview-img-wrap">
                    <img id="live-preview" src="{{ old('hinh_anh_url', $banner->hinh_anh_url) }}" alt="Preview" onerror="previewError()" onload="previewLoaded()">
                    <div class="preview-overlay-demo" id="previewOverlay">
                        <div style="display:inline-block;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-size:.65rem;font-weight:800;padding:.25rem .65rem;border-radius:999px;margin-bottom:.4rem;letter-spacing:.05em;">🔥 SỰ KIỆN</div>
                        <p class="preview-title-demo" id="previewTitleText">{{ old('tieu_de', $banner->tieu_de) }}</p>
                    </div>
                </div>
                <div class="preview-meta">
                    <div class="preview-meta-row">
                        <span class="preview-meta-label">Tiêu đề</span>
                        <span class="preview-meta-val" id="pm-title">{{ $banner->tieu_de }}</span>
                    </div>
                    <div class="preview-meta-row" style="border:none;">
                        <span class="preview-meta-label">Trạng thái</span>
                        <span class="preview-meta-val" id="pm-status">{{ $banner->trang_thai ? '✅ Đang hiển thị' : '⛔ Đang ẩn' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection

@section('js')
<script>
function updatePreviewImage(input) {
    const img = document.getElementById('live-preview');
    const file = input.files[0];
    if (!file) return;
    img.src = URL.createObjectURL(file);
}
function previewLoaded() {
    document.getElementById('previewOverlay').style.display = 'block';
}
function previewError() {
    document.getElementById('previewOverlay').style.display = 'none';
}
function updatePreviewTitle(val) {
    document.getElementById('previewTitleText').textContent = val || '...';
    document.getElementById('pm-title').textContent = val || '—';
}
function updateToggle(cb) {
    const wrap = document.getElementById('toggleWrap');
    const label = document.getElementById('toggleLabel');
    const sub = document.getElementById('toggleSub');
    const icon = document.getElementById('toggleIcon');
    const pmStatus = document.getElementById('pm-status');
    if (cb.checked) {
        wrap.classList.add('toggle-checked');
        label.textContent = 'Đang hiển thị';
        sub.textContent = 'Banner đang xuất hiện trên trang Khuyến Mãi.';
        icon.className = 'fa-solid fa-eye';
        icon.style.color = 'var(--primary)';
        pmStatus.textContent = '✅ Đang hiển thị';
    } else {
        wrap.classList.remove('toggle-checked');
        label.textContent = 'Đang ẩn';
        sub.textContent = 'Banner không hiển thị công khai.';
        icon.className = 'fa-solid fa-eye-slash';
        icon.style.color = '#94a3b8';
        pmStatus.textContent = '⛔ Đang ẩn';
    }
}
</script>
@endsection
