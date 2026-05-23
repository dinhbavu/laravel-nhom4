@extends('layouts.quan-tri')
@section('title', 'Viết Bài Cẩm Nang Mới')
@section('page-title', 'Viết Bài Cẩm Nang Mới')

@section('css')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<style>
    .img-preview { width: 100%; height: 200px; border-radius: 12px; object-fit: cover; border: 2px dashed #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 13px; margin-bottom: 10px; }
    .img-preview img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
    /* Hide CKEditor security warning overlay */
    .cke_notification_warning { display: none !important; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Viết Bài Mới</h2>
        <p>Chia sẻ kiến thức và kinh nghiệm du lịch bổ ích</p>
    </div>
    <a href="{{ route('quan-tri.cam-nang.danh-sach') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('quan-tri.cam-nang.luu') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid-detail">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-pen-nib"></i> Nội dung bài viết</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tiêu đề bài viết <span class="req">*</span></label>
                    <input type="text" name="tieu_de" class="form-control" placeholder="Ví dụ: Kinh nghiệm du lịch Mũi Né 3 ngày 2 đêm" value="{{ old('tieu_de') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tóm tắt ngắn gọn</label>
                    <textarea name="tom_tat" class="form-control" rows="3" placeholder="Mô tả ngắn để hiển thị ở danh sách bài viết...">{{ old('tom_tat') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội dung chi tiết <span class="req">*</span></label>
                    <textarea name="noi_dung" id="editor" required>{{ old('noi_dung') }}</textarea>
                </div>
            </div>
        </div>

        <div class="sticky-box">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-gear"></i> Thiết lập</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Chuyên mục <span class="req">*</span></label>
                        <select name="chuyen_muc" class="form-control" required>
                            <option value="meo_du_lich" {{ old('chuyen_muc') == 'meo_du_lich' ? 'selected' : '' }}>Mẹo Du Lịch</option>
                            <option value="am_thuc" {{ old('chuyen_muc') == 'am_thuc' ? 'selected' : '' }}>Ẩm Thực & Văn Hóa</option>
                            <option value="diem_den" {{ old('chuyen_muc') == 'diem_den' ? 'selected' : '' }}>Điểm Đến Hấp Dẫn</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh bìa bài viết</label>
                        <input type="file" name="hinh_anh" id="imgInput" class="form-control" accept="image/*">
                        <div class="form-hint">Dung lượng tối đa 2MB. Cần ảnh đẹp để thu hút khách.</div>
                        <div class="img-preview" id="previewContainer">
                            <span>Chưa chọn ảnh</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="1" {{ old('trang_thai', '1') == '1' ? 'selected' : '' }}>Hiển thị công khai</option>
                            <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Lưu tạm (Ẩn)</option>
                        </select>
                    </div>

                    <div class="divider"></div>
                    
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Đăng Bài Ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script>
    CKEDITOR.replace('editor', {
        height: 500,
        removeButtons: 'PasteFromWord',
        // Update textarea before form submits
        on: {
            instanceReady: function(evt) {
                this.dataProcessor.writer.setRules('p', {
                    indent: false,
                    breakBeforeOpen: true,
                    breakAfterOpen: false,
                    breakBeforeClose: false,
                    breakAfterClose: true
                });
            }
        }
    });

    // Sync CKEditor data to textarea before submission
    document.querySelector('form').onsubmit = () => {
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
    };

    document.getElementById('imgInput').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            const container = document.getElementById('previewContainer');
            container.innerHTML = `<img src="${URL.createObjectURL(file)}">`;
        }
    }
</script>
@endsection
