@extends('layouts.quan-tri')
@section('title', 'Báo Cáo Thống Kê - VietGo Admin')
@section('page-title', 'Báo Cáo & Thống Kê')

@section('css')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
.tk-stats-grid { display:grid; grid-template-columns: repeat(4,1fr); gap:16px; margin-bottom:24px; }
@media (max-width: 1024px) { .tk-stats-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 480px) { .tk-stats-grid { grid-template-columns: 1fr 1fr; gap:10px; } }

/* Print Report Styles */
@media print {
    body { background: white !important; }
    .admin-sidebar, .admin-topbar, .admin-overlay,
    .btn-print-report, #adminSidebarToggle { display: none !important; }
    .admin-main { margin-left: 0 !important; }
    .admin-content { padding: 0 !important; }
    .stat-card, .card, .table-card { box-shadow: none !important; border: 1px solid #d1d5db !important; break-inside: avoid; }
    .stat-icon { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .chart-wrap { height: 200px !important; }
    .print-header { display: block !important; }
}

.print-header {
    display: none; text-align: center; margin-bottom: 24px;
    padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;
}
</style>
@endsection

@section('content')

<!-- Print-only Header -->
<div class="print-header">
    <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:10px;">
        <div style="width:42px; height:42px; border-radius:10px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; color:white; font-size:18px;">
            <i class="fa-solid fa-plane-departure"></i>
        </div>
        <span style="font-family:'Outfit',sans-serif; font-size:26px; font-weight:900; color:#0f172a;">Viet<span style="color:#10b981;">Go</span></span>
    </div>
    <h2 style="font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px;">BÁO CÁO THỐNG KÊ</h2>
    <p style="font-size:13px; color:#64748b; margin:0;">Xuất ngày: {{ now()->format('d/m/Y H:i') }} — Người xuất: {{ auth()->user()->ho_ten ?? 'Admin' }}</p>
</div>

<!-- Print Button -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
    <div>
        <h2 style="font-size:20px; font-weight:800; color:#0f172a; margin:0 0 2px;">Tổng Quan Doanh Nghiệp</h2>
        <p style="font-size:13px; color:#64748b; margin:0;">Số liệu thống kê tổng hợp năm {{ now()->year }}</p>
    </div>
    <button onclick="window.print()" class="btn btn-warning btn-print-report" style="box-shadow:0 4px 12px rgba(245,158,11,0.3);">
        <i class="fa-solid fa-print"></i> In Báo Cáo Thống Kê
    </button>
</div>

<!-- Overview Stats -->
<div class="tk-stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="stat-info">
            <h4>Doanh Thu Năm {{ now()->year }}</h4>
            <p style="font-size:20px;">{{ number_format($thong_ke['doanh_thu_nam'] / 1000000, 1) }}M</p>
            <div class="sub">{{ number_format($thong_ke['doanh_thu_nam'], 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon blue"><i class="fa-solid fa-ticket-simple"></i></div>
        <div class="stat-info">
            <h4>Tổng Đơn Đặt</h4>
            <p>{{ $thong_ke['tong_dat_tour'] }}</p>
            <div class="sub" style="color:#10b981;">{{ $thong_ke['hoan_thanh'] }} hoàn thành</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <h4>Khách Hàng</h4>
            <p>{{ $thong_ke['tong_khach'] }}</p>
            <div class="sub">Đã đăng ký</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon purple"><i class="fa-solid fa-map-location-dot"></i></div>
        <div class="stat-info">
            <h4>Tổng Tour</h4>
            <p>{{ $thong_ke['tong_tour'] }}</p>
            <div class="sub">Đang hoạt động</div>
        </div>
    </div>
</div>

<div class="grid-detail" style="margin-bottom:20px;">

    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line"></i> Doanh Thu 6 Tháng Gần Nhất</h3>
        </div>
        <div class="card-body">
            <div class="chart-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Booking Status Pie -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Phân Bổ Đơn Hàng</h3>
        </div>
        <div class="card-body">
            <div class="chart-wrap" style="height:200px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div style="margin-top:14px; display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="display:flex; align-items:center; gap:6px;"><span style="width:10px; height:10px; border-radius:50%; background:#fbbf24; display:inline-block;"></span>Chờ duyệt</span>
                    <strong>{{ $thong_ke['cho_duyet'] }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="display:flex; align-items:center; gap:6px;"><span style="width:10px; height:10px; border-radius:50%; background:#3b82f6; display:inline-block;"></span>Đã duyệt</span>
                    <strong>{{ $thong_ke['da_duyet'] }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="display:flex; align-items:center; gap:6px;"><span style="width:10px; height:10px; border-radius:50%; background:#10b981; display:inline-block;"></span>Hoàn thành</span>
                    <strong>{{ $thong_ke['hoan_thanh'] }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="display:flex; align-items:center; gap:6px;"><span style="width:10px; height:10px; border-radius:50%; background:#ef4444; display:inline-block;"></span>Đã hủy</span>
                    <strong>{{ $thong_ke['da_huy'] }}</strong>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Top Tours Table -->
<div class="table-card">
    <div class="table-header">
        <h3><i class="fa-solid fa-trophy"></i> Top 5 Tour Có Doanh Thu Cao Nhất</h3>
    </div>
    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Tên Tour</th>
                    <th>Điểm Đến</th>
                    <th>Số Đơn</th>
                    <th>Doanh Thu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($top_tour as $i => $t)
                <tr>
                    <td>
                        @if($i == 0) 🥇 @elseif($i == 1) 🥈 @elseif($i == 2) 🥉 @else {{ $i+1 }} @endif
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ $t->hinh_bia_url }}" style="width:48px; height:34px; object-fit:cover; border-radius:7px; flex-shrink:0;">
                            <span style="font-weight:600; font-size:13.5px;">{{ Str::limit($t->ten_tour, 40) }}</span>
                        </div>
                    </td>
                    <td style="font-size:13px; color:#64748b;">{{ $t->diemDen->ten_diem_den ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-info">{{ $t->so_don }} đơn</span>
                    </td>
                    <td style="font-weight:800; color:#059669; font-size:14px;">{{ number_format($t->tong_doanh_thu, 0, ',', '.') }}đ</td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-row">Chưa có dữ liệu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('js')
<script>
const months = @json(array_column($doanh_thu_6_thang, 'thang'));
const revenues = @json(array_column($doanh_thu_6_thang, 'doanh_thu'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: revenues,
            backgroundColor: 'rgba(16,185,129,0.15)',
            borderColor: '#10b981',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => (v / 1000000).toFixed(1) + 'M',
                    color: '#94a3b8', font: { size: 12 }
                },
                grid: { color: '#f1f5f9' }
            },
            x: { ticks: { color: '#64748b', font: { size: 12 } }, grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Chờ duyệt', 'Đã duyệt', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [{{ $thong_ke['cho_duyet'] }}, {{ $thong_ke['da_duyet'] }}, {{ $thong_ke['hoan_thanh'] }}, {{ $thong_ke['da_huy'] }}],
            backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#ef4444'],
            borderWidth: 0, hoverOffset: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
@endsection
