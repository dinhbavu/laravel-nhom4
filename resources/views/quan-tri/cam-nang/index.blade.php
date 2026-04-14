@extends('layouts.quan-tri')
@section('title', 'Quản Lý Cẩm Nang')
@section('page-title', 'Quản Lý Cẩm Nang')

@section('content')
<div class="page-header">
    <div>
        <h2>Quản Lý Cẩm Nang</h2>
        <p>Thêm, sửa, xóa các bài viết chia sẻ kinh nghiệm du lịch</p>
    </div>
    <a href="{{ route('quan-tri.cam-nang.tao-moi') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Viết Bài Mới
    </a>
</div>

<div class="table-card fade-up">
    <div class="filter-bar">
        <form action="{{ route('quan-tri.cam-nang.danh-sach') }}" method="GET" style="display:flex; gap:10px; width:100%;">
            <input type="text" name="tu_khoa" class="form-control" placeholder="Tìm tiêu đề bài viết..." value="{{ request('tu_khoa') }}" style="flex:1;">
            <select name="chuyen_muc" class="form-control">
                <option value="">Tất cả chuyên mục</option>
                <option value="meo_du_lich" {{ request('chuyen_muc') == 'meo_du_lich' ? 'selected' : '' }}>Mẹo Du Lịch</option>
                <option value="am_thuc" {{ request('chuyen_muc') == 'am_thuc' ? 'selected' : '' }}>Ẩm Thực & Văn Hóa</option>
                <option value="diem_den" {{ request('chuyen_muc') == 'diem_den' ? 'selected' : '' }}>Điểm Đến Hấp Dẫn</option>
            </select>
            <button type="submit" class="btn btn-secondary">
                <i class="fa-solid fa-magnifying-glass"></i> Lọc
            </button>
            <a href="{{ route('quan-tri.cam-nang.danh-sach') }}" class="btn btn-secondary">Xóa Lọc</a>
        </form>
    </div>

    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th width="80">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th>Người đăng</th>
                    <th>Lượt xem</th>
                    <th>Ngày đăng</th>
                    <th width="120" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danh_sach as $item)
                <tr>
                    <td>#{{ $item->id }}</td>
                    <td>
                        <img src="{{ $item->hinh_anh_url }}" class="tour-thumb" alt="Thumb">
                    </td>
                    <td>
                        <div style="font-weight:700; color:#1e293b; margin-bottom:4px;">{{ $item->tieu_de }}</div>
                        <div style="font-size:11px; color:#94a3b8;">{{ $item->slug }}</div>
                    </td>
                    <td>
                        <span class="badge badge-purple">{{ $item->chuyen_muc_ten }}</span>
                    </td>
                    <td>
                        <div class="status-dot status-active">{{ $item->nguoiDang->ho_ten }}</div>
                    </td>
                    <td><i class="fa-solid fa-eye mr-1" style="color:#94a3b8;"></i> {{ $item->luot_xem }}</td>
                    <td>{{ $item->ngay_dang }}</td>
                    <td>
                        <div class="action-group" style="justify-content:center;">
                            <a href="{{ route('quan-tri.cam-nang.chinh-sua', $item) }}" class="btn btn-sm btn-icon btn-secondary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('quan-tri.cam-nang.xoa', $item) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Xóa">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="8">
                        <i class="fa-solid fa-folder-open" style="font-size:32px; color:#e2e8f0; margin-bottom:12px; display:block;"></i>
                        Chưa có bài viết cẩm nang nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($danh_sach->hasPages())
    <div class="pagination-wrap">
        {{ $danh_sach->links() }}
    </div>
    @endif
</div>
@endsection
