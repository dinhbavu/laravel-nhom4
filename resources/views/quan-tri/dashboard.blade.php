@extends('layouts.quan-tri')
@section('title', 'Tổng Quan - VietGo Admin')
@section('page-title', 'Tổng Quan Hệ Thống')

@section('content')

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="stat-info">
            <h4>Doanh Thu Tháng Này</h4>
            <p>{{ number_format($thong_ke['doanh_thu_thang'] / 1000000, 1) }}M</p>
            <div class="sub">{{ number_format($thong_ke['doanh_thu_thang'], 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon blue"><i class="fa-solid fa-map-location-dot"></i></div>
        <div class="stat-info">
            <h4>Tổng Tour</h4>
            <p>{{ $thong_ke['tong_tour'] }}</p>
            <div class="sub">Tour du lịch đang có</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon orange"><i class="fa-solid fa-ticket-simple"></i></div>
        <div class="stat-info">
            <h4>Tổng Đơn Đặt</h4>
            <p>{{ $thong_ke['tong_dat_tour'] }}</p>
            <div class="sub">Tất cả trạng thái</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon red"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="stat-info">
            <h4>Chờ Duyệt</h4>
            <p>{{ $thong_ke['cho_duyet'] }}</p>
            <div class="sub">Cần xử lý ngay</div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="grid-detail">

    <!-- Recent Bookings Table -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Đơn Đặt Tour Gần Đây</h3>
            <a href="{{ route('quan-tri.dat-tour.danh-sach') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-right"></i> Xem tất cả
            </a>
        </div>
        <div class="overflow-x">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Tour</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dat_tour_gan_day as $dt)
                    <tr>
                        <td><strong style="color:#10b981;">#{{ $dt->ma_dat_tour }}</strong></td>
                        <td style="font-weight:600;">{{ $dt->khachHang->ho_ten ?? 'N/A' }}</td>
                        <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $dt->lichKhoiHanh->tour->ten_tour ?? 'N/A' }}
                        </td>
                        <td style="font-weight:700; color:#059669;">{{ $dt->tong_tien_dinh_dang }}</td>
                        <td><span class="badge badge-{{ $dt->trang_thai_mau }}">{{ $dt->trang_thai_ten }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">Chưa có đơn đặt tour nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Tours -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-fire"></i> Tour Xem Nhiều</h3>
        </div>
        <div class="card-body" style="padding: 12px 16px;">
            @forelse($tour_noi_bat as $i => $tour)
            <div style="display:flex; align-items:center; gap:12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                <div style="width:24px; height:24px; border-radius:50%; background: {{ $i==0 ? '#fef3c7' : ($i==1 ? '#f1f5f9' : '#f0fdf4') }}; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; color:{{ $i==0 ? '#d97706' : '#64748b' }}; flex-shrink:0;">
                    {{ $i+1 }}
                </div>
                <img src="{{ $tour->hinh_bia_url }}" alt="" style="width:48px; height:36px; object-fit:cover; border-radius:7px; flex-shrink:0;">
                <div style="min-width:0;">
                    <a href="{{ route('quan-tri.tour.chinh-sua', $tour) }}" style="font-size:13px; font-weight:600; color:#1e293b; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-decoration:none; margin-bottom:2px;">
                        {{ Str::limit($tour->ten_tour, 32) }}
                    </a>
                    <div style="font-size:11.5px; color:#94a3b8;"><i class="fa-solid fa-eye" style="color:#10b981;"></i> {{ number_format($tour->luot_xem) }} lượt xem</div>
                </div>
            </div>
            @empty
            <div style="text-align:center; padding: 30px; color:#94a3b8; font-size:13px;">Chưa có tour nào</div>
            @endforelse
        </div>
    </div>

</div>

@endsection
