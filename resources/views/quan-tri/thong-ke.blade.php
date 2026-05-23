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
        <p style="font-size:13px; color:#64748b; margin:0;">Số liệu thống kê: {{ $label }}</p>
    </div>
    <button onclick="window.print()" class="btn btn-warning btn-print-report" style="box-shadow:0 4px 12px rgba(245,158,11,0.3);">
        <i class="fa-solid fa-print"></i> In Báo Cáo Thống Kê
    </button>
</div>

<!-- Filter Section -->
<div class="card fade-up" style="margin-bottom:24px; padding:20px; background:white; border:1px solid #e2e8f0; border-radius:16px;">
    <form action="{{ route('quan-tri.thong-ke') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:20px; align-items:flex-end;" id="filterForm">
        <div style="flex:1; min-width:140px;">
            <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Loại Báo Cáo</label>
            <select name="type" id="filterType" class="form-select" onchange="toggleFilterInputs()" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
                <option value="ngay" {{ request('type') == 'ngay' ? 'selected' : '' }}>Theo Ngày</option>
                <option value="tuan" {{ request('type') == 'tuan' ? 'selected' : '' }}>Theo Tuần</option>
                <option value="thang" {{ request('type', 'thang') == 'thang' ? 'selected' : '' }}>Theo Tháng</option>
                <option value="quy" {{ request('type') == 'quy' ? 'selected' : '' }}>Theo Quý</option>
                <option value="nam" {{ request('type') == 'nam' ? 'selected' : '' }}>Theo Năm</option>
            </select>
        </div>
        
        <div id="inputNgay" class="filter-input" style="flex:1; min-width:140px; display:none;">
            <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Chọn Ngày</label>
            <input type="date" name="date" class="form-control" value="{{ request('date', now()->format('Y-m-d')) }}" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
        </div>

        <div id="inputTuan" class="filter-input" style="flex:1; min-width:140px; display:none;">
            <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Chọn Tuần</label>
            <input type="week" name="week" class="form-control" value="{{ request('week', now()->format('Y-\WW')) }}" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
        </div>

        <div id="inputThang" class="filter-input" style="flex:1; min-width:140px; display:none;">
            <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Chọn Tháng</label>
            <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
        </div>

        <div id="inputQuy" class="filter-input" style="flex:2; min-width:280px; display:none; gap:10px;">
            <div style="flex:1;">
                <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Chọn Quý</label>
                <select name="quarter" class="form-select" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
                    @for($q = 1; $q <= 4; $q++)
                        <option value="{{ $q }}" {{ request('quarter', now()->quarter) == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                    @endfor
                </select>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Năm</label>
                <select name="year" class="form-select" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div id="inputNam" class="filter-input" style="flex:1; min-width:140px; display:none;">
            <label style="display:block; font-size:12px; color:#64748b; margin-bottom:8px; font-weight:700; text-transform:uppercase; letter-spacing:0.025em;">Chọn Năm</label>
            <select name="year" class="form-select" style="border-radius:10px; border-color:#e2e8f0; font-weight:600;">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                @endfor
            </select>
        </div>

        <div style="flex:0; display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary" style="border-radius:10px; padding:10px 20px; font-weight:600; background:#0f172a; border-color:#0f172a;">
                <i class="fa-solid fa-filter"></i> Lọc
            </button>
            <a href="{{ route('quan-tri.thong-ke') }}" class="btn btn-outline-secondary" style="border-radius:10px; padding:10px 20px; font-weight:600;">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Overview Stats (Month, Quarter, Year) -->
<div class="tk-stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-icon green"><i class="fa-solid fa-calendar-day"></i></div>
        <div class="stat-info">
            <h4>Doanh Thu Tháng {{ now()->month }}</h4>
            <p style="font-size:20px;">{{ number_format($thong_ke['doanh_thu_thang'] / 1000000, 1) }}M</p>
            <div class="sub">{{ number_format($thong_ke['doanh_thu_thang'], 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-week"></i></div>
        <div class="stat-info">
            <h4>Doanh Thu Quý {{ ceil(now()->month / 3) }}</h4>
            <p style="font-size:20px;">{{ number_format($thong_ke['doanh_thu_quy'] / 1000000, 1) }}M</p>
            <div class="sub">{{ number_format($thong_ke['doanh_thu_quy'], 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon orange"><i class="fa-solid fa-filter"></i></div>
        <div class="stat-info">
            <h4>Doanh Thu Kỳ Lọc</h4>
            <p style="font-size:20px;">{{ number_format($thong_ke['doanh_thu_loc'] / 1000000, 1) }}M</p>
            <div class="sub">{{ number_format($thong_ke['doanh_thu_loc'], 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-icon purple"><i class="fa-solid fa-check-double"></i></div>
        <div class="stat-info">
            <h4>Đơn Hoàn Thành</h4>
            <p>{{ $thong_ke['hoan_thanh'] }}</p>
            <div class="sub">Trên tổng {{ $thong_ke['tong_dat_tour'] }} đơn</div>
        </div>
    </div>
</div>

<div class="grid-detail" style="margin-bottom:20px;">

    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line"></i> Biểu Đồ Doanh Thu ({{ $label }})</h3>
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

<!-- Regional Stats Breakdown -->
<div style="margin-bottom:20px;">
    <!-- Regional Revenue Chart/Table -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-earth-asia"></i> Doanh Thu Theo 3 Miền</h3>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction:column; gap:16px;">
                @foreach($doanh_thu_mien as $mien)
                @php
                    $label = match($mien->vung_mien) {
                        'mien_bac' => 'Miền Bắc',
                        'mien_trung' => 'Miền Trung',
                        'mien_nam' => 'Miền Nam',
                        default => 'Khác'
                    };
                    $color = match($mien->vung_mien) {
                        'mien_bac' => '#ef4444',
                        'mien_trung' => '#fbbf24',
                        'mien_nam' => '#3b82f6',
                        default => '#64748b'
                    };
                    $percent = $thong_ke['doanh_thu_loc'] > 0 ? ($mien->total_revenue / $thong_ke['doanh_thu_loc']) * 100 : 0;
                    $region_tours = $tours_by_mien[$mien->vung_mien] ?? collect();
                @endphp
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; transition:all 0.3s; box-shadow:0 1px 2px rgba(0,0,0,0.02);">
                    <div style="padding:16px; cursor:pointer; background:#fff;" onclick="toggleRegionTours('{{ $mien->vung_mien }}')" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; align-items:center;">
                            <span style="font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                                {{ $label }} 
                                <i id="icon-{{ $mien->vung_mien }}" class="fa-solid fa-chevron-down" style="font-size:11px; color:#94a3b8; transition:transform 0.3s;"></i>
                            </span>
                            <strong style="color:{{ $color }}; font-size:15px;">{{ number_format($mien->total_revenue, 0, ',', '.') }}đ</strong>
                        </div>
                        <div style="height:10px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                            <div style="width:{{ $percent }}%; height:100%; background:{{ $color }}; border-radius:10px;"></div>
                        </div>
                    </div>
                    
                    <div id="tours-{{ $mien->vung_mien }}" style="display:none; border-top:1px solid #e2e8f0; background:#f8fafc; max-height: 400px; overflow-y: auto;">
                        <div style="padding:0;">
                            @forelse($region_tours as $t)
                            <a href="{{ route('quan-tri.tour.chinh-sua', $t->id) }}" style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-bottom:1px solid #f1f5f9; text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div style="width:24px; height:24px; border-radius:50%; background:{{ $loop->first ? '#fef3c7' : ($loop->iteration == 2 ? '#f1f5f9' : '#f0fdf4') }}; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; color:{{ $loop->first ? '#d97706' : '#64748b' }}; flex-shrink:0;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <img src="{{ $t->hinh_bia_url }}" style="width:48px; height:36px; object-fit:cover; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                    <div>
                                        <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:2px;">{{ Str::limit($t->ten_tour, 60) }}</div>
                                        <div style="font-size:12px; color:#10b981; font-weight:600;"><i class="fa-solid fa-money-bill-wave" style="font-size:10px; margin-right:3px;"></i>{{ number_format($t->tong_doanh_thu, 0, ',', '.') }}đ</div>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-right" style="color:#cbd5e1; font-size:12px;"></i>
                            </a>
                            @empty
                            <div style="padding:30px; text-align:center; color:#94a3b8; font-size:13px;">Chưa có tour nào phát sinh doanh thu</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@section('js')
<script>
function toggleFilterInputs() {
    document.querySelectorAll('.filter-input').forEach(el => {
        el.style.display = 'none';
        el.querySelectorAll('input, select').forEach(input => input.disabled = true);
    });
    const type = document.getElementById('filterType').value;
    const activeInput = document.getElementById('input' + type.charAt(0).toUpperCase() + type.slice(1));
    if (activeInput) {
        if (type === 'quy') {
            activeInput.style.display = 'flex';
        } else {
            activeInput.style.display = 'block';
        }
        activeInput.querySelectorAll('input, select').forEach(input => input.disabled = false);
    }
}
document.addEventListener('DOMContentLoaded', toggleFilterInputs);

function toggleRegionTours(regionId) {
    const listDiv = document.getElementById('tours-' + regionId);
    const icon = document.getElementById('icon-' + regionId);
    if (listDiv.style.display === 'none') {
        listDiv.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        listDiv.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

const labels = @json(array_column($doanh_thu_bieu_do, 'label'));
const revenues = @json(array_column($doanh_thu_bieu_do, 'doanh_thu'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: labels,
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
