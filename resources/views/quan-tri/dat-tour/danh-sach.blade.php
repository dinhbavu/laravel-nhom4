@extends('layouts.quan-tri')
@section('title', 'Quản Lý Booking - VietGo Admin')
@section('page-title', 'Quản Lý Đặt Tour')

@section('css')
<style>
@media (max-width: 768px) {
    .quick-stats-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
}
@media (max-width: 480px) {
    .quick-stats-grid { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }
    .quick-stats-grid > div { padding: 12px 10px !important; }
    .quick-stats-grid > div > div:first-child { width: 34px !important; height: 34px !important; font-size: 15px !important; }
    .quick-stats-grid > div > div:last-child > div:last-child { font-size: 20px !important; }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Danh Sách Đặt Tour</h2>
        <p>Quản lý và xử lý tất cả các đơn đặt tour của khách hàng</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="quick-stats-grid" style="display:grid; grid-template-columns: repeat(4,1fr); gap:14px; margin-bottom:22px;">
    @php
        $cho_duyet = $danh_sach_don->where('trang_thai','cho_duyet')->count() ?? 0;
    @endphp
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #fef3c7; display:flex; align-items:center; gap:12px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
        <div style="width:40px; height:40px; border-radius:10px; background:#fef3c7; display:flex; align-items:center; justify-content:center; font-size:18px; color:#d97706; flex-shrink:0;"><i class="fa-solid fa-clock"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Chờ Duyệt</div>
        <div style="font-size:24px; font-weight:800; color:#d97706; line-height:1.1;">{{ \App\Models\DatTour::where('trang_thai','cho_duyet')->count() }}</div></div>
    </div>
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #dbeafe; display:flex; align-items:center; gap:12px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
        <div style="width:40px; height:40px; border-radius:10px; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:18px; color:#2563eb; flex-shrink:0;"><i class="fa-solid fa-check"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Đã Duyệt</div>
        <div style="font-size:24px; font-weight:800; color:#2563eb; line-height:1.1;">{{ \App\Models\DatTour::where('trang_thai','da_duyet')->count() }}</div></div>
    </div>
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #d1fae5; display:flex; align-items:center; gap:12px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
        <div style="width:40px; height:40px; border-radius:10px; background:#d1fae5; display:flex; align-items:center; justify-content:center; font-size:18px; color:#059669; flex-shrink:0;"><i class="fa-solid fa-flag-checkered"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Hoàn Thành</div>
        <div style="font-size:24px; font-weight:800; color:#059669; line-height:1.1;">{{ \App\Models\DatTour::where('trang_thai','hoan_thanh')->count() }}</div></div>
    </div>
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #fee2e2; display:flex; align-items:center; gap:12px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
        <div style="width:40px; height:40px; border-radius:10px; background:#fee2e2; display:flex; align-items:center; justify-content:center; font-size:18px; color:#dc2626; flex-shrink:0;"><i class="fa-solid fa-ban"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Đã Hủy</div>
        <div style="font-size:24px; font-weight:800; color:#dc2626; line-height:1.1;">{{ \App\Models\DatTour::where('trang_thai','da_huy')->count() }}</div></div>
    </div>
</div>

<div class="table-card">
    <!-- Filter -->
    <form action="{{ route('quan-tri.dat-tour.danh-sach') }}" method="GET">
        <div class="filter-bar">
            <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="🔍  Mã đơn, tên khách..." class="form-control" style="min-width:200px;">
            <select name="trang_thai" class="form-control">
                <option value="">Tất cả trạng thái</option>
                <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ Duyệt</option>
                <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã Duyệt</option>
                <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn Thành</option>
                <option value="da_huy" {{ request('trang_thai') == 'da_huy' ? 'selected' : '' }}>Đã Hủy</option>
            </select>
            <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}" class="form-control" style="min-width:140px;" title="Từ ngày">
            <input type="date" name="den_ngay" value="{{ request('den_ngay') }}" class="form-control" style="min-width:140px;" title="Đến ngày">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Lọc</button>
            @if(request()->hasAny(['tu_khoa','trang_thai','tu_ngay','den_ngay']))
            <a href="{{ route('quan-tri.dat-tour.danh-sach') }}" class="btn btn-secondary">Xóa lọc</a>
            @endif
        </div>
    </form>

    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Tour</th>
                    <th>Ngày KH</th>
                    <th>Số Khách</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danh_sach_don as $don)
                <tr>
                    <td>
                        <span style="font-weight:700; color:#10b981; font-size:13px;">#{{ $don->ma_dat_tour }}</span>
                        <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $don->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; font-size:13.5px;">{{ $don->khachHang->ho_ten ?? 'N/A' }}</div>
                        <div style="font-size:12px; color:#94a3b8;">{{ $don->khachHang->email ?? '' }}</div>
                    </td>
                    <td style="max-width:180px;">
                        <div style="font-size:13.5px; font-weight:500; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ Str::limit($don->lichKhoiHanh->tour->ten_tour ?? 'N/A', 35) }}
                        </div>
                    </td>
                    <td>
                        @if($don->lichKhoiHanh && $don->lichKhoiHanh->ngay_di)
                        <span style="font-size:13px; font-weight:600;">{{ $don->lichKhoiHanh->ngay_di->format('d/m/Y') }}</span>
                        @else
                        <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px;">
                            <span style="background:#dbeafe; color:#1d4ed8; border-radius:6px; padding:2px 8px; font-weight:600; font-size:12px;">{{ $don->so_nguoi_lon ?? 0 }} NL</span>
                            @if(($don->so_tre_em ?? 0) > 0)
                            <span style="background:#fef3c7; color:#92400e; border-radius:6px; padding:2px 8px; font-weight:600; font-size:12px; margin-left:3px;">{{ $don->so_tre_em }} TE</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-weight:800; color:#059669; font-size:14px;">{{ $don->tong_tien_dinh_dang }}</td>
                    <td><span class="badge badge-{{ $don->trang_thai_mau }}">{{ $don->trang_thai_ten }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px; align-items:center;">
                            <a href="{{ route('quan-tri.dat-tour.chi-tiet', $don) }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-eye"></i> Xem
                            </a>
                            @if(in_array($don->trang_thai, ['hoan_thanh', 'done', 'da_xac_nhan']))
                            <a href="{{ route('quan-tri.dat-tour.hoa-don', $don) }}" target="_blank" class="btn btn-warning btn-sm" title="In hóa đơn">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="empty-row">
                    <i class="fa-solid fa-inbox" style="font-size:32px; display:block; margin-bottom:8px; opacity:0.3;"></i>
                    Không có đơn đặt tour nào
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $danh_sach_don->links() }}</div>
</div>
@endsection
