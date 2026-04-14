@extends('layouts.quan-tri')
@section('title', 'Quản Lý Tour - VietGo Admin')
@section('page-title', 'Quản Lý Tour Du Lịch')

@section('content')
<div class="page-header">
    <div>
        <h2>Danh Sách Tour</h2>
        <p>Quản lý tất cả các tour du lịch trên hệ thống</p>
    </div>
    <a href="{{ route('quan-tri.tour.tao-moi') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Thêm Tour Mới
    </a>
</div>

<div class="table-card">
    <!-- Filter -->
    <form action="{{ route('quan-tri.tour.danh-sach') }}" method="GET">
        <div class="filter-bar">
            <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="🔍  Tìm tên tour..." class="form-control" style="min-width:220px;">
            <select name="trang_thai" class="form-control">
                <option value="">Tất cả trạng thái</option>
                <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="ngung" {{ request('trang_thai') == 'ngung' ? 'selected' : '' }}>Ngừng hoạt động</option>
                <option value="con_cho" {{ request('trang_thai') == 'con_cho' ? 'selected' : '' }}>Chờ</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Lọc</button>
            @if(request()->hasAny(['tu_khoa','trang_thai','diem_den']))
            <a href="{{ route('quan-tri.tour.danh-sach') }}" class="btn btn-secondary">Xóa lọc</a>
            @endif
        </div>
    </form>

    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Tour</th>
                    <th>Điểm Đến</th>
                    <th>Thời Gian</th>
                    <th>Giá Người Lớn</th>
                    <th>Trạng Thái</th>
                    <th style="width:120px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danh_sach as $tour)
                <tr>
                    <td style="color:#94a3b8; font-weight:600;">#{{ $tour->id }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <img src="{{ $tour->hinh_bia_url }}" alt="" class="tour-thumb">
                            <div>
                                <div style="font-weight:700; font-size:14px; color:#1e293b;">{{ Str::limit($tour->ten_tour, 45) }}</div>
                                <div style="font-size:12px; color:#94a3b8; margin-top:2px;">
                                    <i class="fa-solid fa-eye"></i> {{ number_format($tour->luot_xem) }} lượt xem
                                    &nbsp;·&nbsp;
                                    <i class="fa-solid fa-star" style="color:#f59e0b;"></i> {{ number_format($tour->danh_gia_trung_binh ?? 0, 1) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13.5px; color:#374151;">{{ $tour->diemDen->ten_diem_den ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span style="font-weight:600; color:#374151;">{{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</span>
                    </td>
                    <td>
                        <span style="font-weight:700; color:#059669; font-size:14px;">{{ number_format($tour->gia_nguoi_lon, 0, ',', '.') }}đ</span>
                    </td>
                    <td>
                        @if($tour->trang_thai == 'hoat_dong')
                            <span class="badge badge-success">Hoạt động</span>
                        @elseif($tour->trang_thai == 'ngung')
                            <span class="badge badge-danger">Ngừng</span>
                        @else
                            <span class="badge badge-secondary">Chờ</span>
                        @endif
                        @if($tour->noi_bat)
                            <span class="badge badge-warning" style="margin-left:4px;"><i class="fa-solid fa-fire" style="font-size:10px;"></i> Nổi bật</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('quan-tri.lich-khoi-hanh.danh-sach', $tour) }}" class="btn btn-success btn-sm btn-icon" title="Lịch khởi hành" style="background:#059669;">
                                <i class="fa-solid fa-calendar-days"></i>
                            </a>
                            <a href="{{ route('tour.chi-tiet', $tour) }}" target="_blank" class="btn btn-secondary btn-sm btn-icon" title="Xem trên web">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('quan-tri.tour.chinh-sua', $tour) }}" class="btn btn-info btn-sm btn-icon" title="Chỉnh sửa">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('quan-tri.tour.xoa', $tour) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận ẩn tour này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-warning btn-sm btn-icon" title="Ẩn tour">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </button>
                            </form>
                            <form action="{{ route('quan-tri.tour.xoa-vinh-vien', $tour) }}" method="POST" style="display:inline;" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa vĩnh viễn tour này không?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Xóa vĩnh viễn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-row"><i class="fa-solid fa-inbox" style="font-size:32px; margin-bottom:8px; display:block; opacity:0.3;"></i>Chưa có tour nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
        {{ $danh_sach->links() }}
    </div>
</div>
@endsection
