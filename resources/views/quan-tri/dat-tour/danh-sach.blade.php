@extends('layouts.quan-tri')
@section('title', 'Quản Lý Booking - VietGo Admin')
@section('page-title', 'Quản Lý Đặt Tour')

@section('css')
<style>
.quick-stats-grid {
    grid-template-columns: repeat(6, 1fr) !important;
}
.stat-icon.purple {
    background: #ede9fe !important;
    color: #7c3aed !important;
}
.stat-icon.cyan {
    background: #e0f7fa !important;
    color: #00838f !important;
}
@media (max-width: 1200px) {
    .quick-stats-grid { grid-template-columns: repeat(3, 1fr) !important; gap: 12px !important; }
}
@media (max-width: 768px) {
    .quick-stats-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
}
@media (max-width: 480px) {
    .quick-stats-grid { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }
    .quick-stats-grid > a { padding: 12px 10px !important; }
    .quick-stats-grid > a > div:first-child { width: 34px !important; height: 34px !important; font-size: 15px !important; }
    .quick-stats-grid > a > div:last-child > div:last-child { font-size: 20px !important; }
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
<div class="quick-stats-grid" style="display:grid; grid-template-columns: repeat(6,1fr); gap:14px; margin-bottom:22px;">
@php
    $cho_duyet = $counts['cho_duyet'] ?? 0;
    $da_duyet = $counts['da_duyet'] ?? 0;
    $hoan_thanh = $counts['hoan_thanh'] ?? 0;
    $da_huy = $counts['da_huy'] ?? 0;
    $yeu_cau_hoan_tien = $counts['yeu_cau_hoan_tien'] ?? 0;
    $da_hoan_tien = $counts['da_hoan_tien'] ?? 0;
@endphp
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'cho_duyet']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'cho_duyet' ? '2px solid #d97706' : '1px solid #e2e8f0' }}">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <h4>Chờ Duyệt</h4>
            <div style="font-size:24px; font-weight:800; color:#d97706; line-height:1.1;">{{ $cho_duyet }}</div>
        </div>
    </a>
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'da_duyet']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'da_duyet' ? '2px solid #10b981' : '1px solid #e2e8f0' }}">
        <div class="stat-icon green"><i class="fa-solid fa-check-double"></i></div>
        <div class="stat-info">
            <h4>Đã Duyệt</h4>
            <div style="font-size:24px; font-weight:800; color:#10b981; line-height:1.1;">{{ $da_duyet }}</div>
        </div>
    </a>
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'hoan_thanh']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'hoan_thanh' ? '2px solid #3b82f6' : '1px solid #e2e8f0' }}">
        <div class="stat-icon blue"><i class="fa-solid fa-flag-checkered"></i></div>
        <div class="stat-info">
            <h4>Hoàn Thành</h4>
            <div style="font-size:24px; font-weight:800; color:#3b82f6; line-height:1.1;">{{ $hoan_thanh }}</div>
        </div>
    </a>
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'yeu_cau_hoan_tien']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'yeu_cau_hoan_tien' ? '2px solid #7c3aed' : '1px solid #e2e8f0' }}">
        <div class="stat-icon purple"><i class="fa-solid fa-hand-holding-dollar"></i></div>
        <div class="stat-info">
            <h4>Chờ Hoàn Tiền</h4>
            <div style="font-size:24px; font-weight:800; color:#7c3aed; line-height:1.1;">{{ $yeu_cau_hoan_tien }}</div>
        </div>
    </a>
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'da_hoan_tien']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'da_hoan_tien' ? '2px solid #00838f' : '1px solid #e2e8f0' }}">
        <div class="stat-icon cyan"><i class="fa-solid fa-money-bill-transfer"></i></div>
        <div class="stat-info">
            <h4>Đã Hoàn Tiền</h4>
            <div style="font-size:24px; font-weight:800; color:#00838f; line-height:1.1;">{{ $da_hoan_tien }}</div>
        </div>
    </a>
    <a href="{{ route('quan-tri.dat-tour.danh-sach', ['trang_thai' => 'da_huy']) }}" class="stat-card" style="text-decoration:none; border:{{ request('trang_thai') == 'da_huy' ? '2px solid #ef4444' : '1px solid #e2e8f0' }}">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div class="stat-info">
            <h4>Đã Hủy</h4>
            <div style="font-size:24px; font-weight:800; color:#ef4444; line-height:1.1;">{{ $da_huy }}</div>
        </div>
    </a>
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
                <option value="yeu_cau_hoan_tien" {{ request('trang_thai') == 'yeu_cau_hoan_tien' ? 'selected' : '' }}>Chờ Hoàn Tiền</option>
                <option value="da_hoan_tien" {{ request('trang_thai') == 'da_hoan_tien' ? 'selected' : '' }}>Đã Hoàn Tiền</option>
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
        <table class="data-table" style="min-width: 1150px;">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Tour</th>
                    <th>Ngày KH</th>
                    <th>Số Khách</th>
                    <th>Tổng Tiền</th>
                    <th>Thanh Toán</th>
                    <th>Trạng Thái</th>
                    <th style="min-width: 240px;">Thao Tác</th>
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
                    <td style="font-weight:800; color:#1e293b; font-size:14px;">{{ $don->tong_tien_dinh_dang }}</td>
                    <td style="min-width:180px;">
                        @php
                            $paid = $don->tong_tien_da_thanh_toan ?? 0;
                            $total = $don->tong_tien_thanh_toan ?? 0;
                            $percent = $total > 0 ? ($paid / $total) * 100 : 0;
                            $method = $don->thanhToan->phuong_thuc ?? 'tien_mat';
                            
                            $method_name = match($method) {
                                'vnpay' => 'VNPay',
                                'momo' => 'Ví MoMo',
                                'chuyen_khoan' => 'Chuyển Khoản',
                                'tien_mat' => 'Tiền Mặt',
                                'dat_coc' => 'Đặt Cọc (30%)',
                                default => 'Khác'
                            };

                            $expected = $total;
                            if ($method === 'dat_coc' && $paid == 0) {
                                $expected = $total * 0.3;
                            }

                            $status_color = $percent >= 100 ? '#10b981' : ($percent > 0 ? '#3b82f6' : ($don->trang_thai == 'cho_duyet' ? '#f59e0b' : '#ef4444'));
                        @endphp
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600;">
                                <span style="color:#64748b;">{{ $method_name }}</span>
                                <span style="color:{{ $status_color }}">
                                    @if($paid > 0)
                                        {{ number_format($paid, 0, ',', '.') }}đ
                                    @else
                                        <span style="font-size:10px; opacity:0.8;">Cần thu:</span> {{ number_format($expected, 0, ',', '.') }}đ
                                    @endif
                                </span>
                            </div>
                            <div style="height:6px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                                <div style="width:{{ $percent }}%; height:100%; background:{{ $status_color }}; border-radius:10px;"></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-{{ $don->trang_thai_mau }}">{{ $don->trang_thai_ten }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px; align-items:center;">
                            <a href="{{ route('quan-tri.dat-tour.chi-tiet', $don) }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-eye"></i> Xem
                            </a>

                            {{-- Nút Duyệt cho đơn mới --}}
                            @if($don->trang_thai === 'cho_duyet')
                            <form action="{{ route('quan-tri.dat-tour.duyet', $don) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận duyệt đơn và gửi thông báo cho khách?')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-check"></i> Duyệt
                                </button>
                            </form>
                            @endif

                            {{-- Nút Hoàn thành khi tour kết thúc --}}
                            @if($don->trang_thai === 'hoan_thanh')
                            <form action="{{ route('quan-tri.dat-tour.duyet-hoan-thanh', $don) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận tour đã kết thúc và thanh toán đủ?')">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-flag-checkered"></i> Hoàn thành
                                </button>
                            </form>
                            @endif

                            {{-- Nút Duyệt hoàn tiền --}}
                            @if($don->trang_thai === 'yeu_cau_hoan_tien')
                            <form action="{{ route('quan-tri.dat-tour.duyet-hoan-tien', $don) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận đã chuyển khoản hoàn tiền cho khách hàng thành công?')">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-money-bill-transfer"></i> Hoàn tiền
                                </button>
                            </form>
                            @endif

                            @if(in_array($don->trang_thai, ['hoan_thanh', 'done', 'da_xac_nhan', 'da_duyet']))
                            <a href="{{ route('quan-tri.dat-tour.hoa-don', $don) }}" target="_blank" class="btn btn-warning btn-sm" title="In hóa đơn">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="empty-row">
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
