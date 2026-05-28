@extends('layouts.quan-tri')
@section('title', 'Báo Cáo Thống Kê - VietGo Admin')
@section('page-title', 'Báo Cáo & Thống Kê')

@section('css')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    /* ===== PREMIUM STATS OVERRIDES ===== */
    .tk-stats-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 20px; 
        margin-bottom: 28px; 
    }
    @media (max-width: 1200px) { .tk-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
    @media (max-width: 576px) { .tk-stats-grid { grid-template-columns: 1fr; gap: 12px; } }

    .stat-card-premium {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 20px;
        border: 1px solid #e8edf5;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.01), 0 10px 30px -5px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.06), 0 22px 40px -15px rgba(15, 23, 42, 0.1);
        border-color: #cbd5e1;
    }
    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: transparent;
    }
    .stat-card-premium.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card-premium.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .stat-card-premium.orange::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-card-premium.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .stat-icon-premium {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .stat-card-premium.green .stat-icon-premium { background: #e6fcf5; color: #0ca678; }
    .stat-card-premium.blue .stat-icon-premium { background: #e7f5ff; color: #1c7ed6; }
    .stat-card-premium.orange .stat-icon-premium { background: #fff9db; color: #f59f00; }
    .stat-card-premium.purple .stat-icon-premium { background: #f3f0ff; color: #748ffc; }

    .stat-card-premium:hover .stat-icon-premium {
        transform: scale(1.1) rotate(4deg);
    }

    .stat-value-premium {
        font-size: 26px;
        font-weight: 850;
        color: #0f172a;
        line-height: 1.1;
        font-family: 'Outfit', sans-serif;
    }
    .stat-label-premium {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-sub-premium {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ===== MODERN CARD STYLING ===== */
    .card-premium {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e8edf5;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.01), 0 10px 30px -5px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        margin-bottom: 28px;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }
    .grid-detail .card-premium {
        height: 100%;
    }
    .card-premium-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #fcfdfe;
        flex-shrink: 0;
    }
    .card-premium-header h3 {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Outfit', sans-serif;
    }
    .card-premium-header h3 i {
        color: #10b981;
    }
    .card-premium-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* ===== SPLIT GRID LAYOUT ===== */
    .region-grid { 
        display: grid; 
        grid-template-columns: 1.1fr 1.9fr; 
        gap: 28px; 
        align-items: start; 
    }
    @media (max-width: 992px) {
        .region-grid { grid-template-columns: 1fr; gap: 24px; }
    }

    /* ===== FORM FILTER CONTROL ===== */
    .filter-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(241, 245, 249, 0.9);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.01), 0 10px 30px -5px rgba(15, 23, 42, 0.02);
    }
    .form-select-premium, .form-control-premium {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        font-weight: 600;
        font-size: 13.5px;
        color: #334155;
        padding: 10px 14px;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
    }
    .form-select-premium:focus, .form-control-premium:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        outline: none;
    }

    /* ===== ACCORDION LIST ===== */
    .region-card-wrapper {
        background: #fff;
        border: 1.5px solid #edf2f7;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.01);
    }
    .region-card-wrapper:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px -5px rgba(15, 23, 42, 0.05);
    }
    .region-card-header {
        padding: 18px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        transition: background 0.25s;
    }
    .region-card-header:hover {
        background: #f8fafc;
    }
    .region-badge-pill {
        font-size: 13px;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 99px;
    }
    .region-badge-pill.mien-bac { background: #fef2f2; color: #ef4444; }
    .region-badge-pill.mien-trung { background: #fffbeb; color: #d97706; }
    .region-badge-pill.mien-nam { background: #eff6ff; color: #2563eb; }

    /* Accordion inner items */
    .tour-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        border-bottom: 1.5px solid #f1f5f9;
        text-decoration: none;
        transition: all 0.2s;
    }
    .tour-list-item:last-child {
        border-bottom: none;
    }
    .tour-list-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }
    .rank-number {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .rank-1 { background: #fef3c7; color: #d97706; border: 1.5px solid #fcd34d; }
    .rank-2 { background: #f1f5f9; color: #475569; border: 1.5px solid #cbd5e1; }
    .rank-3 { background: #ecfdf5; color: #059669; border: 1.5px solid #a7f3d0; }
    .rank-other { background: #f8fafc; color: #94a3b8; border: 1.5px solid #e2e8f0; }

    /* ===== PRINT STYLE ADJUSTMENTS ===== */
    @media print {
        body { background: white !important; color: black !important; }
        .admin-sidebar, .admin-topbar, .admin-overlay,
        .btn-print-report, #adminSidebarToggle, .filter-card { display: none !important; }
        .admin-main { margin-left: 0 !important; }
        .admin-content { padding: 0 !important; }
        .card-premium { box-shadow: none !important; border: 1px solid #cbd5e1 !important; break-inside: avoid; }
        .stat-card-premium { box-shadow: none !important; border: 1px solid #cbd5e1 !important; break-inside: avoid; }
        .chart-wrap { height: 220px !important; }
        .print-header { display: block !important; }
        .region-grid { grid-template-columns: 1fr 1.2fr !important; gap: 15px !important; }
    }

    .print-header {
        display: none; 
        text-align: center; 
        margin-bottom: 28px;
        padding-bottom: 22px; 
        border-bottom: 2px dashed #cbd5e1;
    }
</style>
@endsection

@section('content')

<!-- Print-only Header -->
<div class="print-header">
    <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:12px;">
        <div style="width:46px; height:46px; border-radius:12px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; color:white; font-size:20px; box-shadow: 0 4px 10px rgba(16,185,129,0.2);">
            <i class="fa-solid fa-plane-departure"></i>
        </div>
        <span style="font-family:'Outfit',sans-serif; font-size:28px; font-weight:900; color:#0f172a;">Viet<span style="color:#10b981;">Go</span></span>
    </div>
    <h2 style="font-size:22px; font-weight:850; color:#0f172a; margin:0 0 6px; font-family:'Outfit',sans-serif; letter-spacing:0.5px;">BÁO CÁO THỐNG KÊ DOANH THU</h2>
    <p style="font-size:13.5px; color:#64748b; margin:0; font-weight:500;">Xuất ngày: {{ now()->format('d/m/Y H:i') }} — Người lập báo cáo: {{ auth()->user()->ho_ten ?? 'Quản trị viên' }}</p>
</div>

<!-- Print Button -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
    <div>
        <h2 style="font-size:24px; font-weight:850; color:#0f172a; margin:0 0 4px; font-family:'Outfit',sans-serif;">Báo Cáo Hoạt Động</h2>
        <p style="font-size:14px; color:#64748b; margin:0; font-weight:500; display:flex; align-items:center; gap:6px;">
            <span style="width:8px; height:8px; border-radius:50%; background:#10b981; display:inline-block;"></span>
            Kỳ phân tích dữ liệu: <strong style="color:#0f172a;">{{ $label }}</strong>
        </p>
    </div>
    <button onclick="window.print()" class="btn btn-primary btn-print-report" style="padding:11px 22px; border-radius:12px; font-weight:700; background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:white; box-shadow:0 8px 20px -6px rgba(15,23,42,0.4);">
        <i class="fa-solid fa-print"></i> In Báo Cáo Thống Kê
    </button>
</div>

<!-- Filter Section -->
<div class="filter-card fade-up">
    <form action="{{ route('quan-tri.thong-ke') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:18px; align-items:flex-end;" id="filterForm">
        <div style="flex:1.2; min-width:180px;">
            <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Mốc Thời Gian</label>
            <select name="type" id="filterType" class="form-select form-select-premium" onchange="toggleFilterInputs()" style="width:100%;">
                <option value="ngay" {{ request('type') == 'ngay' ? 'selected' : '' }}>Báo cáo theo Ngày</option>
                <option value="tuan" {{ request('type') == 'tuan' ? 'selected' : '' }}>Báo cáo theo Tuần</option>
                <option value="thang" {{ request('type', 'thang') == 'thang' ? 'selected' : '' }}>Báo cáo theo Tháng</option>
                <option value="quy" {{ request('type') == 'quy' ? 'selected' : '' }}>Báo cáo theo Quý</option>
                <option value="nam" {{ request('type') == 'nam' ? 'selected' : '' }}>Báo cáo theo Năm</option>
            </select>
        </div>
        
        <div id="inputNgay" class="filter-input" style="flex:1; min-width:160px; display:none;">
            <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Chọn Ngày</label>
            <input type="date" name="date" class="form-control form-control-premium" value="{{ request('date', now()->format('Y-m-d')) }}">
        </div>

        <div id="inputTuan" class="filter-input" style="flex:1; min-width:160px; display:none;">
            <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Chọn Tuần</label>
            <input type="week" name="week" class="form-control form-control-premium" value="{{ request('week', now()->format('Y-\WW')) }}">
        </div>

        <div id="inputThang" class="filter-input" style="flex:1; min-width:160px; display:none;">
            <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Chọn Tháng</label>
            <input type="month" name="month" class="form-control form-control-premium" value="{{ request('month', now()->format('Y-m')) }}">
        </div>

        <div id="inputQuy" class="filter-input" style="flex:2; min-width:280px; display:none; gap:10px;">
            <div style="flex:1.2;">
                <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Chọn Quý</label>
                <select name="quarter" class="form-select form-select-premium" style="width:100%;">
                    @for($q = 1; $q <= 4; $q++)
                        <option value="{{ $q }}" {{ request('quarter', now()->quarter) == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                    @endfor
                </select>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Năm</label>
                <select name="year" class="form-select form-select-premium" style="width:100%;">
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div id="inputNam" class="filter-input" style="flex:1; min-width:160px; display:none;">
            <label style="display:block; font-size:11px; color:#64748b; margin-bottom:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.75px;">Chọn Năm</label>
            <select name="year" class="form-select form-select-premium" style="width:100%;">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                @endfor
            </select>
        </div>

        <div style="flex:0; display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary" style="border-radius:12px; padding:10.5px 22px; font-weight:700; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; box-shadow:0 4px 14px rgba(16,185,129,0.25);">
                <i class="fa-solid fa-filter"></i> Áp Dụng
            </button>
            <a href="{{ route('quan-tri.thong-ke') }}" class="btn btn-secondary" style="border-radius:12px; padding:10.5px 15px; border-color:#e2e8f0; display:flex; align-items:center; justify-content:center; transition: all 0.2s;" title="Đặt lại bộ lọc">
                <i class="fa-solid fa-arrow-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Overview Stats Grid -->
<div class="tk-stats-grid">
    <div class="stat-card-premium green fade-up">
        <div class="stat-icon-premium"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info" style="flex:1;">
            <div class="stat-label-premium">Doanh thu tháng {{ now()->month }}</div>
            <div class="stat-value-premium">{{ number_format($thong_ke['doanh_thu_thang'] / 1000000, 1) }}M</div>
            <div class="stat-sub-premium"><i class="fa-solid fa-circle-info" style="font-size:10px;"></i>{{ number_format($thong_ke['doanh_thu_thang'], 0, ',', '.') }} đ</div>
        </div>
    </div>
    <div class="stat-card-premium blue fade-up">
        <div class="stat-icon-premium"><i class="fa-solid fa-chart-line-up"></i></div>
        <div class="stat-info" style="flex:1;">
            <div class="stat-label-premium">Doanh thu Quý {{ ceil(now()->month / 3) }}</div>
            <div class="stat-value-premium">{{ number_format($thong_ke['doanh_thu_quy'] / 1000000, 1) }}M</div>
            <div class="stat-sub-premium"><i class="fa-solid fa-circle-info" style="font-size:10px;"></i>{{ number_format($thong_ke['doanh_thu_quy'], 0, ',', '.') }} đ</div>
        </div>
    </div>
    <div class="stat-card-premium orange fade-up">
        <div class="stat-icon-premium"><i class="fa-solid fa-filter"></i></div>
        <div class="stat-info" style="flex:1;">
            <div class="stat-label-premium">Doanh thu Kỳ Lọc</div>
            <div class="stat-value-premium">{{ number_format($thong_ke['doanh_thu_loc'] / 1000000, 1) }}M</div>
            <div class="stat-sub-premium"><i class="fa-solid fa-circle-info" style="font-size:10px;"></i>{{ number_format($thong_ke['doanh_thu_loc'], 0, ',', '.') }} đ</div>
        </div>
    </div>
    <div class="stat-card-premium purple fade-up">
        <div class="stat-icon-premium"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-info" style="flex:1;">
            <div class="stat-label-premium">Đơn Hoàn Thành</div>
            <div class="stat-value-premium">{{ $thong_ke['hoan_thanh'] }}</div>
            <div class="stat-sub-premium"><i class="fa-solid fa-circle-check" style="font-size:10px; color:#10b981;"></i>Trong tổng {{ $thong_ke['tong_dat_tour'] }} đơn đặt</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid-detail" style="margin-bottom:28px; align-items: stretch;">
    <!-- Revenue Chart Card -->
    <div class="card-premium">
        <div class="card-premium-header">
            <h3><i class="fa-solid fa-chart-column"></i> Biểu Đồ Doanh Thu ({{ $label }})</h3>
        </div>
        <div class="card-premium-body">
            <div class="chart-wrap" style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Booking Status Doughnut Card -->
    <div class="card-premium">
        <div class="card-premium-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Trạng Thái Đơn Hàng</h3>
        </div>
        <div class="card-premium-body" style="justify-content:space-between;">
            <div class="chart-wrap" style="height:180px; position: relative;">
                <canvas id="statusChart"></canvas>
                <!-- Center text overlay -->
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                    <span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Tổng Đơn</span>
                    <h3 style="font-size: 24px; font-weight: 850; color: #0f172a; margin: 2px 0 0; line-height: 1; font-family:'Outfit',sans-serif;">{{ $thong_ke['tong_dat_tour'] }}</h3>
                </div>
            </div>
            
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:10px; margin-top: 15px;">
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:12.5px; padding:6px 10px; background:#fafafa; border-radius:10px;">
                    <span style="display:flex; align-items:center; gap:6px; font-weight:600; color:#64748b;"><span style="width:8px; height:8px; border-radius:50%; background:#fbbf24; display:inline-block;"></span>Chờ duyệt</span>
                    <strong style="color:#334155;">{{ $thong_ke['cho_duyet'] }}</strong>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:12.5px; padding:6px 10px; background:#fafafa; border-radius:10px;">
                    <span style="display:flex; align-items:center; gap:6px; font-weight:600; color:#64748b;"><span style="width:8px; height:8px; border-radius:50%; background:#3b82f6; display:inline-block;"></span>Đã duyệt</span>
                    <strong style="color:#334155;">{{ $thong_ke['da_duyet'] }}</strong>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:12.5px; padding:6px 10px; background:#fafafa; border-radius:10px;">
                    <span style="display:flex; align-items:center; gap:6px; font-weight:600; color:#64748b;"><span style="width:8px; height:8px; border-radius:50%; background:#10b981; display:inline-block;"></span>Đã xong</span>
                    <strong style="color:#334155;">{{ $thong_ke['hoan_thanh'] }}</strong>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:12.5px; padding:6px 10px; background:#fafafa; border-radius:10px;">
                    <span style="display:flex; align-items:center; gap:6px; font-weight:600; color:#64748b;"><span style="width:8px; height:8px; border-radius:50%; background:#ef4444; display:inline-block;"></span>Đã hủy</span>
                    <strong style="color:#334155;">{{ $thong_ke['da_huy'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $chart_labels = [];
    $chart_revenues = [];
    $chart_colors = [];
    foreach($doanh_thu_mien as $mien) {
        $chart_labels[] = match($mien->vung_mien) {
            'mien_bac' => 'Miền Bắc',
            'mien_trung' => 'Miền Trung',
            'mien_nam' => 'Miền Nam',
            default => 'Khác'
        };
        $chart_revenues[] = $mien->total_revenue;
        $chart_colors[] = match($mien->vung_mien) {
            'mien_bac' => '#ef4444',
            'mien_trung' => '#fbbf24',
            'mien_nam' => '#3b82f6',
            default => '#64748b'
        };
    }
@endphp

<!-- Regional Revenue Sharing Breakdown -->
<div class="card-premium fade-up">
    <div class="card-premium-header">
        <h3><i class="fa-solid fa-earth-asia"></i> Thống Kê Thị Phần & Doanh Thu Theo Miền</h3>
    </div>
    <div class="card-premium-body">
        @if(count($doanh_thu_mien) > 0)
        <div class="region-grid">
            <!-- Left Column: Share Doughnut Chart -->
            <div style="display:flex; flex-direction:column; justify-content:center; align-items:center; background:#fcfdfe; border:1px solid #edf2f7; padding: 24px; border-radius:20px;">
                <div class="chart-wrap" style="height: 180px; width:100%; position: relative;">
                    <canvas id="regionChart"></canvas>
                    <!-- Center Overlay text -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                        <span style="font-size: 9.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Kỳ Lọc</span>
                        <h4 style="font-size: 18px; font-weight: 850; color: #0f172a; margin: 2px 0 0; line-height: 1; font-family:'Outfit',sans-serif;">{{ number_format($thong_ke['doanh_thu_loc'] / 1000000, 1) }}M</h4>
                    </div>
                </div>
                
                <div style="margin-top: 20px; width: 100%; display: flex; flex-direction: column; gap: 8px;">
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
                    @endphp
                    <div style="display:flex; justify-content:space-between; font-size:13px; padding: 4px 0; border-bottom: 1px dashed #edf2f7;">
                        <span style="display:flex; align-items:center; gap:8px; font-weight:600; color:#475569;">
                            <span style="width:8px; height:8px; border-radius:50%; background:{{ $color }}; display:inline-block;"></span>
                            {{ $label }}
                        </span>
                        <strong style="color:#0f172a;">{{ number_format($mien->total_revenue, 0, ',', '.') }}đ <span style="color:#64748b; font-weight:500; font-size:11.5px; margin-left:2px;">({{ number_format($percent, 1) }}%)</span></strong>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Accordion Lists of Top Tours -->
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
                <div class="region-card-wrapper">
                    <!-- Header -->
                    <div class="region-card-header" onclick="toggleRegionTours('{{ $mien->vung_mien }}')">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <span class="region-badge-pill {{ str_replace('_', '-', $mien->vung_mien) }}">
                                {{ $label }}
                            </span>
                            <i id="icon-{{ $mien->vung_mien }}" class="fa-solid fa-chevron-down" style="font-size:12px; color:#94a3b8; transition:transform 0.3s;"></i>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size:11px; color:#94a3b8; display:block; font-weight:600; text-transform:uppercase;">Doanh thu</span>
                            <strong style="color:#0f172a; font-size:15px; font-family:'Outfit',sans-serif;">{{ number_format($mien->total_revenue, 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                    
                    <!-- Collapsible Panel -->
                    <div id="tours-{{ $mien->vung_mien }}" style="display:none; border-top:1px solid #f1f5f9; background:#fafbfe; max-height: 320px; overflow-y: auto;">
                        <div style="padding:0;">
                            @forelse($region_tours as $t)
                            @php
                                $rankClass = match($loop->iteration) {
                                    1 => 'rank-1',
                                    2 => 'rank-2',
                                    3 => 'rank-3',
                                    default => 'rank-other'
                                };
                            @endphp
                            <a href="{{ route('quan-tri.tour.chinh-sua', $t->id) }}" class="tour-list-item">
                                <div style="display:flex; align-items:center; gap:14px; min-width:0;">
                                    <div class="rank-number {{ $rankClass }}">
                                        {{ $loop->iteration }}
                                    </div>
                                    <img src="{{ $t->hinh_bia_url }}" style="width:48px; height:36px; object-fit:cover; border-radius:8px; box-shadow:0 3px 8px rgba(0,0,0,0.06); flex-shrink:0;">
                                    <div style="min-width:0;">
                                        <div style="font-size:13px; font-weight:700; color:#1e293b; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-bottom:2px;">{{ $t->ten_tour }}</div>
                                        <div style="font-size:11.5px; color:#94a3b8; font-weight:500;">
                                            <i class="fa-solid fa-eye" style="margin-right:2px;"></i> {{ number_format($t->luot_xem) }} lượt xem
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align:right; flex-shrink:0; margin-left:14px;">
                                    <strong style="font-size:13px; color:#10b981; font-weight:700;">{{ number_format($t->tong_doanh_thu, 0, ',', '.') }}đ</strong>
                                </div>
                            </a>
                            @empty
                            <div style="padding:24px; text-align:center; color:#94a3b8; font-size:13px;">
                                <i class="fa-solid fa-folder-open" style="font-size:24px; margin-bottom:8px; opacity:0.4;"></i>
                                <p style="margin:0;">Chưa có tour nào phát sinh doanh thu trong kỳ</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="text-align:center; padding: 48px 0; color:#94a3b8;">
            <i class="fa-solid fa-earth-asia fa-3x" style="margin-bottom:16px; opacity:0.3;"></i>
            <p style="margin:0; font-size:14px; font-weight:500;">Không tìm thấy dữ liệu doanh thu vùng miền trong kỳ lọc này.</p>
        </div>
        @endif
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

// ===== RENDER PREMIUM CHARTS =====
const labels = @json(array_column($doanh_thu_bieu_do, 'label'));
const revenues = @json(array_column($doanh_thu_bieu_do, 'doanh_thu'));

const revenueCtx = document.getElementById('revenueChart').getContext('2d');
// Create a professional gradient fill for the bars
const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 260);
revGradient.addColorStop(0, 'rgba(16, 185, 129, 0.45)');
revGradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.18)');
revGradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: revenues,
            backgroundColor: revGradient,
            borderColor: '#10b981',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
            hoverBackgroundColor: 'rgba(16, 185, 129, 0.6)'
        }]
    },
    options: {
        responsive: true, 
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#fff',
                bodyColor: '#e2eaec',
                padding: 10,
                borderRadius: 8,
                callbacks: {
                    label: function(context) {
                        return ' ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => (v / 1000000).toFixed(1) + 'M',
                    color: '#94a3b8', 
                    font: { size: 11, weight: '600' }
                },
                grid: { color: '#f1f5f9' }
            },
            x: { 
                ticks: { 
                    color: '#64748b', 
                    font: { size: 11, weight: '600' } 
                }, 
                grid: { display: false } 
            }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Chờ duyệt', 'Đã duyệt', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [{{ $thong_ke['cho_duyet'] }}, {{ $thong_ke['da_duyet'] }}, {{ $thong_ke['hoan_thanh'] }}, {{ $thong_ke['da_huy'] }}],
            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
            borderWidth: 4,
            borderColor: '#ffffff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true, 
        maintainAspectRatio: false, 
        cutout: '72%',
        plugins: { 
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 10,
                borderRadius: 8
            }
        }
    }
});

@if(count($doanh_thu_mien) > 0)
new Chart(document.getElementById('regionChart'), {
    type: 'doughnut',
    data: {
        labels: @json($chart_labels),
        datasets: [{
            data: @json($chart_revenues),
            backgroundColor: @json($chart_colors),
            borderWidth: 4,
            borderColor: '#ffffff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 10,
                borderRadius: 8,
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) label += ': ';
                        if (context.parsed !== null) {
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed);
                        }
                        return label;
                    }
                }
            }
        }
    }
});
@endif
</script>
@endsection
@endsection
