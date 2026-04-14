@extends('layouts.quan-tri')
@section('title', 'Cấu Hình Hệ Thống - VietGo Admin')
@section('page-title', 'Cấu Hình Hệ Thống')

@section('css')
<style>
.sys-info-grid { display:grid; grid-template-columns: repeat(3,1fr); gap:18px; margin-bottom:24px; }
.sys-data-grid { display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px; }
@media (max-width: 1024px) {
    .sys-info-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 768px) {
    .sys-info-grid { grid-template-columns: 1fr; gap:12px; }
    .sys-data-grid { grid-template-columns: 1fr; gap:14px; }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Hệ Thống</h2>
        <p>Thông tin và trạng thái hoạt động của hệ thống VietGo</p>
    </div>
</div>

<!-- System Info Cards -->
<div class="sys-info-grid">
    <div class="card" style="border-left:4px solid #10b981;">
        <div class="card-body">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:10px; background:#d1fae5; display:flex; align-items:center; justify-content:center; color:#059669; font-size:20px;"><i class="fa-solid fa-server"></i></div>
                <div style="font-size:15px; font-weight:700; color:#1e293b;">Máy Chủ</div>
            </div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">PHP</div><div class="detail-value"><span class="badge badge-success">{{ phpversion() }}</span></div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Laravel</div><div class="detail-value"><span class="badge badge-info">{{ app()->version() }}</span></div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Timezone</div><div class="detail-value" style="font-size:13px;">{{ config('app.timezone') }}</div></div>
        </div>
    </div>

    <div class="card" style="border-left:4px solid #3b82f6;">
        <div class="card-body">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:10px; background:#dbeafe; display:flex; align-items:center; justify-content:center; color:#2563eb; font-size:20px;"><i class="fa-solid fa-database"></i></div>
                <div style="font-size:15px; font-weight:700; color:#1e293b;">Cơ Sở Dữ Liệu</div>
            </div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Driver</div><div class="detail-value"><span class="badge badge-info">{{ config('database.default') }}</span></div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Database</div><div class="detail-value" style="font-size:13px;">{{ config('database.connections.' . config('database.default') . '.database') }}</div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Trạng thái</div><div class="detail-value"><span class="status-dot status-active">Kết nối tốt</span></div></div>
        </div>
    </div>

    <div class="card" style="border-left:4px solid #f59e0b;">
        <div class="card-body">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:10px; background:#fef3c7; display:flex; align-items:center; justify-content:center; color:#d97706; font-size:20px;"><i class="fa-solid fa-globe"></i></div>
                <div style="font-size:15px; font-weight:700; color:#1e293b;">Ứng Dụng</div>
            </div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Tên App</div><div class="detail-value" style="font-size:13px; font-weight:600;">{{ config('app.name') }}</div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Môi trường</div><div class="detail-value"><span class="badge {{ app()->environment('production') ? 'badge-danger' : 'badge-warning' }}">{{ app()->environment() }}</span></div></div>
            <div class="detail-row"><div class="detail-label" style="width:110px;">Debug</div><div class="detail-value"><span class="badge {{ config('app.debug') ? 'badge-warning' : 'badge-success' }}">{{ config('app.debug') ? 'Bật' : 'Tắt' }}</span></div></div>
        </div>
    </div>
</div>

<!-- Data Overview -->
<div class="sys-data-grid">
    <div class="card">
        <div class="card-header"><h3><i class="fa-solid fa-chart-bar"></i> Tổng Quan Dữ Liệu</h3></div>
        <div class="card-body" style="padding:12px 22px;">
            @php
                $data_counts = [
                    ['icon' => 'fa-map-location-dot', 'color' => '#2563eb', 'bg' => '#dbeafe', 'label' => 'Tour du lịch', 'count' => \App\Models\Tour::count()],
                    ['icon' => 'fa-ticket-simple', 'color' => '#d97706', 'bg' => '#fef3c7', 'label' => 'Đơn đặt tour', 'count' => \App\Models\DatTour::count()],
                    ['icon' => 'fa-users', 'color' => '#059669', 'bg' => '#d1fae5', 'label' => 'Người dùng', 'count' => \App\Models\NguoiDung::count()],
                    ['icon' => 'fa-location-dot', 'color' => '#7c3aed', 'bg' => '#ede9fe', 'label' => 'Điểm đến', 'count' => \App\Models\DiemDen::count()],
                ];
            @endphp
            @foreach($data_counts as $item)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:36px; height:36px; border-radius:9px; background:{{ $item['bg'] }}; display:flex; align-items:center; justify-content:center; color:{{ $item['color'] }}; font-size:15px;">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                    </div>
                    <span style="font-size:14px; font-weight:500; color:#374151;">{{ $item['label'] }}</span>
                </div>
                <span style="font-size:20px; font-weight:800; color:{{ $item['color'] }};">{{ number_format($item['count']) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fa-solid fa-screwdriver-wrench"></i> Công Cụ Bảo Trì</h3></div>
        <div class="card-body">
            <div style="display:flex; flex-direction:column; gap:12px;">
                <div style="padding:14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
                    <div style="font-size:13.5px; font-weight:600; color:#166534; margin-bottom:4px;"><i class="fa-solid fa-circle-check"></i> Hệ thống đang hoạt động bình thường</div>
                    <div style="font-size:12px; color:#4ade80;">Tất cả các dịch vụ đều online</div>
                </div>

                <div style="padding:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                    <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:8px;">Thông Tin Phiên Đăng Nhập</div>
                    <div style="font-size:12.5px; color:#64748b; line-height:1.8;">
                        <div>👤 Người dùng: <strong>{{ auth()->user()->ho_ten }}</strong></div>
                        <div>🔑 Vai trò: <strong>{{ auth()->user()->ten_vai_tro }}</strong></div>
                        <div>🕐 Thời gian: <strong>{{ now()->format('H:i - d/m/Y') }}</strong></div>
                    </div>
                </div>

                <div style="padding:14px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px;">
                    <div style="font-size:13.5px; font-weight:600; color:#92400e; margin-bottom:4px;"><i class="fa-solid fa-triangle-exclamation"></i> Lưu ý</div>
                    <div style="font-size:12px; color:#78350f; line-height:1.6;">Các tính năng cấu hình nâng cao (Email SMTP, Upload S3, Caching...) sẽ được bổ sung trong phiên bản tiếp theo.</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
