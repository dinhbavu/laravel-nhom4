@extends('layouts.quan-tri')
@section('title', 'Quản Lý Khuyến Mãi - VietGo Admin')
@section('page-title', 'Quản Lý Khuyến Mãi')

@section('content')

{{-- Success / Error alerts --}}
@if(session('thanh_cong'))
<div class="alert alert-success mb-4" style="background:#ecfdf5;border:1px solid #6ee7b7;color:#065f46;padding:1rem 1.5rem;border-radius:12px;display:flex;align-items:center;gap:.75rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('thanh_cong') }}
</div>
@endif

{{-- Tab Navigation --}}
<div style="display:flex; gap:.5rem; margin-bottom:1.5rem; border-bottom:2px solid #e2e8f0; padding-bottom:0;">
    <button id="tab-banner-btn" onclick="switchTab('banner')" style="padding:.75rem 1.5rem; font-weight:700; border:none; cursor:pointer; border-radius:8px 8px 0 0; background:var(--primary); color:white; font-size:.9rem; transition: all .25s;">
        <i class="fa-solid fa-image mr-2"></i> Banner Sự Kiện
    </button>
    <button id="tab-tour-btn" onclick="switchTab('tour')" style="padding:.75rem 1.5rem; font-weight:700; border:none; cursor:pointer; border-radius:8px 8px 0 0; background:#f1f5f9; color:#64748b; font-size:.9rem; transition: all .25s;">
        <i class="fa-solid fa-percent mr-2"></i> Tour Giảm Giá
    </button>
</div>

{{-- =========================================== TAB BANNER =========================================== --}}
<div id="tab-banner">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <form method="GET" class="filter-bar d-flex gap-2">
            <input type="hidden" name="tab" value="banner">
            <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" class="form-control" placeholder="Tìm theo tiêu đề..." style="width:250px;">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i> Lọc</button>
        </form>
        <a href="{{ route('quan-tri.khuyen-mai.tao-moi') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Thêm Banner Mới
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th>Ảnh Banner</th>
                        <th>Tiêu Đề</th>
                        <th>Đường Dẫn (Link)</th>
                        <th>Trạng Thái</th>
                        <th style="min-width: 120px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($danh_sach as $banner)
                    <tr>
                        <td style="width:180px;">
                            <img src="{{ $banner->hinh_anh_url }}" alt="Banner" style="width:100%;height:70px;object-fit:cover;border-radius:8px;">
                        </td>
                        <td>
                            <div class="font-bold text-primary" style="font-size:15px;">{{ $banner->tieu_de }}</div>
                        </td>
                        <td>
                            @if($banner->duong_dan)
                                <a href="{{ $banner->duong_dan }}" target="_blank" class="text-info" style="font-size:13px;">{{ \Illuminate\Support\Str::limit($banner->duong_dan, 40) }}</a>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            @if($banner->trang_thai)
                                <span class="badge badge-success">Đang hiển thị</span>
                            @else
                                <span class="badge badge-danger">Đã ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('quan-tri.khuyen-mai.chinh-sua', $banner) }}" class="btn btn-info btn-sm btn-icon" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('quan-tri.khuyen-mai.xoa', $banner) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa Banner này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">Không tìm thấy Banner nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            {{ $danh_sach->links() }}
        </div>
    </div>
</div>

{{-- =========================================== TAB TOUR GIẢM GIÁ =========================================== --}}
<div id="tab-tour" style="display:none;">
    <div class="card">
        <div class="card-header" style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
            <h3 style="font-size:1rem; font-weight:800; margin:0; color:#0f172a;">
                <i class="fa-solid fa-percent mr-2" style="color:var(--primary);"></i>
                Thiết Lập % Giảm Giá Cho Tour
            </h3>
            <p style="font-size:.8rem; color:#64748b; margin:.25rem 0 0;">Nhập hoặc chọn nhanh %, sau đó nhấn Lưu. Nhấn <strong>Xóa</strong> để bỏ giảm giá.</p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên Tour</th>
                        <th>Điểm Đến</th>
                        <th>Giá Gốc</th>
                        <th style="text-align:center;">Trạng Thái</th>
                        <th style="min-width:320px;">Thiết Lập Giảm Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($danh_sach_tour as $tour)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <img src="{{ $tour->hinh_bia_url }}" style="width:52px;height:42px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                                <div>
                                    <div style="font-weight:700;font-size:.875rem;color:#0f172a;">{{ $tour->ten_tour }}</div>
                                    <div style="font-size:.75rem;color:#64748b;">{{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.875rem;">{{ $tour->diemDen->ten_diem_den }}</td>
                        <td style="font-weight:700; font-size:.9rem;">{{ $tour->gia_nguoi_lon_dinh_dang }}</td>
                        <td style="text-align:center;">
                            @if($tour->phan_tram_giam_gia > 0)
                                <div>
                                    <span class="badge badge-danger" style="font-size:.9rem;padding:.4rem .85rem;display:block;margin-bottom:3px;">
                                        -{{ $tour->phan_tram_giam_gia }}%
                                    </span>
                                    <span style="font-size:.8rem;color:#ef4444;font-weight:700;">{{ $tour->gia_khuyen_mai_nguoi_lon_dinh_dang }}</span>
                                </div>
                            @else
                                <span style="font-size:.78rem;color:#94a3b8;font-weight:600;background:#f8fafc;padding:.3rem .75rem;border-radius:6px;border:1px dashed #e2e8f0;">Không giảm</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('quan-tri.khuyen-mai.cap-nhat-giam-gia-tour') }}" method="POST" id="form-tour-{{ $tour->id }}">
                                @csrf
                                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                                    {{-- Quick-pick buttons --}}
                                    <div style="display:flex;gap:.35rem;">
                                        @foreach([5,10,15,20,30,50] as $pct)
                                        <button type="button" onclick="setDiscount({{ $tour->id }}, {{ $pct }})"
                                            style="padding:.3rem .55rem;font-size:.72rem;font-weight:800;border-radius:6px;background:{{ $tour->phan_tram_giam_gia == $pct ? '#dcfce7' : '#f1f5f9' }};color:{{ $tour->phan_tram_giam_gia == $pct ? '#15803d' : '#64748b' }};border:1.5px solid {{ $tour->phan_tram_giam_gia == $pct ? '#86efac' : '#e2e8f0' }};cursor:pointer;transition:all .2s;">
                                            -{{ $pct }}%
                                        </button>
                                        @endforeach
                                    </div>
                                    {{-- Manual input --}}
                                    <input type="number" name="phan_tram_giam_gia" id="input-{{ $tour->id }}"
                                        value="{{ $tour->phan_tram_giam_gia }}" min="0" max="100"
                                        style="width:68px;padding:.4rem .5rem;border:1.5px solid #e2e8f0;border-radius:8px;font-weight:800;text-align:center;font-size:.9rem;"
                                        placeholder="%">
                                    {{-- Save --}}
                                    <button type="submit" class="btn btn-primary btn-sm" style="padding:.45rem .9rem;">
                                        <i class="fa-solid fa-save mr-1"></i>Lưu
                                    </button>
                                    {{-- Delete discount --}}
                                    @if($tour->phan_tram_giam_gia > 0)
                                    <button type="button" onclick="removeDiscount({{ $tour->id }})"
                                        class="btn btn-danger btn-sm" style="padding:.45rem .75rem;" title="Xóa giảm giá">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">Không có tour nào đang hoạt động</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    function switchTab(tab) {
        const bannerTab = document.getElementById('tab-banner');
        const tourTab = document.getElementById('tab-tour');
        const bannerBtn = document.getElementById('tab-banner-btn');
        const tourBtn = document.getElementById('tab-tour-btn');

        if (tab === 'banner') {
            bannerTab.style.display = 'block';
            tourTab.style.display = 'none';
            bannerBtn.style.background = 'var(--primary)';
            bannerBtn.style.color = 'white';
            tourBtn.style.background = '#f1f5f9';
            tourBtn.style.color = '#64748b';
        } else {
            bannerTab.style.display = 'none';
            tourTab.style.display = 'block';
            tourBtn.style.background = 'var(--primary)';
            tourBtn.style.color = 'white';
            bannerBtn.style.background = '#f1f5f9';
            bannerBtn.style.color = '#64748b';
        }
    }

    function setDiscount(tourId, pct) {
        document.getElementById('input-' + tourId).value = pct;
    }

    function removeDiscount(tourId) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa giảm giá cho tour này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ',
            background: '#ffffff',
            color: '#1e293b',
            backdrop: 'rgba(15, 23, 42, 0.4)',
            customClass: {
                popup: 'rounded-2xl border border-slate-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('input-' + tourId).value = 0;
                document.getElementById('form-tour-' + tourId).submit();
            }
        });
    }

    // Auto-switch to the correct tab if redirected after saving tour discount
    @if(session('thanh_cong') && str_contains(session('thanh_cong'), 'tour'))
        switchTab('tour');
    @endif
</script>
@endsection
