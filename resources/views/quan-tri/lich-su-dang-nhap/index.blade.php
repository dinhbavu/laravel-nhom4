@extends('layouts.quan-tri')
@section('title', 'Lịch Sử Đăng Nhập - VietGo Admin')
@section('page-title', 'Lịch Sử Đăng Nhập')

@section('content')
<div class="page-header">
    <div>
        <h2>Lịch Sử Đăng Nhập Hệ Thống</h2>
        <p>Theo dõi thời gian, địa chỉ IP và thiết bị đăng nhập của khách hàng.</p>
    </div>
</div>

<!-- Lọc cơ bản -->
<div class="card" style="margin-bottom: 24px;">
    <form action="{{ route('quan-tri.lich-su-dang-nhap.danh-sach') }}" method="GET" class="filter-bar">
        <input type="text" name="email" value="{{ request('email') }}" class="form-control" placeholder="Tìm kiếm theo Email khách hàng..." style="width: 300px !important;">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Lọc kết quả</button>
        @if(request('email'))
            <a href="{{ route('quan-tri.lich-su-dang-nhap.danh-sach') }}" class="btn btn-secondary">Khôi phục</a>
        @endif
    </form>
</div>

<div class="table-card fade-up">
    <div class="table-header">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Danh Sách Hoạt Động ({{ $danh_sach->total() }} lượt)</h3>
    </div>
    
    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 22%;">Khách Hàng</th>
                    <th style="width: 25%;">Địa Chỉ IP & Vị Trí</th>
                    <th style="width: 28%;">Thông Tin Trình Duyệt / Thiết Bị</th>
                    <th style="width: 20%;">Thời Gian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danh_sach as $index => $ls)
                <tr>
                    <td>{{ $danh_sach->firstItem() + $index }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ $ls->khachHang->anh_dai_dien_url ?? asset('images/default-avatar.png') }}" alt="" class="avatar-sm">
                            <div>
                                <div style="font-weight:700; color:#0f172a;">{{ $ls->khachHang?->ho_ten ?? 'N/A' }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $ls->khachHang?->email ?? 'Không có tài khoản' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
                            <span class="badge badge-secondary" style="font-size:12px;">
                                <i class="fa-solid fa-globe"></i> {{ $ls->ip_address ?? 'Không xác định' }}
                            </span>
                            @if(!empty($ls->latitude) && !empty($ls->longitude))
                            <a href="https://www.google.com/maps?q={{ $ls->latitude }},{{ $ls->longitude }}&z=15&t=m"
                               target="_blank"
                               class="btn-map-ip"
                               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); text-decoration:none;"
                               title="Định vị chính xác bằng GPS">
                                <i class="fa-solid fa-location-crosshairs"></i>
                                <span class="btn-map-text">GPS thực tế</span>
                            </a>
                            @elseif(!empty($ls->ip_address) && $ls->ip_address !== 'Không xác định')
                            <button
                                class="btn-map-ip"
                                onclick="openGoogleMap(this, '{{ $ls->ip_address }}')"
                                title="Xem vị trí ước lượng qua IP"
                            >
                                <i class="fa-brands fa-google"></i>
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span class="btn-map-text">Xem trên Maps (IP)</span>
                                <span class="btn-map-spinner" style="display:none;"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                            </button>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px; color:#475569; word-break: break-all; line-height: 1.4; margin-bottom:5px;">{{ $ls->user_agent }}</div>
                        @if($ls->device_id)
                        <div style="font-size:11px; color:#8b5cf6; font-family: monospace; background:rgba(139,92,246,0.1); padding:4px 8px; border-radius:4px; display:inline-block; border: 1px solid rgba(139,92,246,0.2);">
                            <i class="fa-solid fa-mobile-screen" style="margin-right: 4px;"></i> Thiết bị ID: <strong>{{ substr($ls->device_id, 0, 8) }}...{{ substr($ls->device_id, -4) }}</strong>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1e293b;">{{ $ls->created_at->format('d/m/Y') }}</div>
                        <div style="font-size:12px; color:#64748b;">{{ $ls->created_at->format('H:i:s') }}</div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="5">
                        <i class="fa-solid fa-folder-open fa-3x" style="color:#cbd5e1; margin-bottom:15px;"></i>
                        <p>Chưa có lịch sử đăng nhập nào được ghi nhận.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($danh_sach->hasPages())
    <div class="pagination-wrap">
        {{ $danh_sach->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

{{-- Toast thông báo lỗi --}}
<div id="map-toast" style="
    display:none;
    position:fixed;
    bottom:28px;
    right:28px;
    background:#ef4444;
    color:#fff;
    padding:12px 20px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    box-shadow:0 4px 24px rgba(0,0,0,0.18);
    z-index:9999;
    gap:8px;
    align-items:center;
">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span id="map-toast-msg"></span>
</div>

<style>
.btn-map-ip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #4285F4 0%, #34A853 50%, #EA4335 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(66,133,244,0.35);
}
.btn-map-ip:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(66,133,244,0.45);
    filter: brightness(1.08);
}
.btn-map-ip:active {
    transform: translateY(0);
}
.btn-map-ip:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}
</style>

<script>
function openGoogleMap(btn, ip) {
    const textEl = btn.querySelector('.btn-map-text');
    const spinEl = btn.querySelector('.btn-map-spinner');

    if (btn.disabled) return;

    btn.disabled = true;
    textEl.style.display = 'none';
    spinEl.style.display  = 'inline';

    // Kiểm tra IP nội bộ / localhost
    const privateRanges = [
        /^127\./,
        /^10\./,
        /^192\.168\./,
        /^172\.(1[6-9]|2\d|3[01])\./,
        /^::1$/,
        /^localhost$/i
    ];
    if (privateRanges.some(r => r.test(ip))) {
        showToast('IP nội bộ / localhost không thể định vị trên bản đồ.');
        resetBtn(btn, textEl, spinEl);
        return;
    }

    // Gọi proxy Laravel (server-side) → tránh CORS & Mixed Content trên HTTPS
    const proxyUrl = '{{ route("quan-tri.locate.ip") }}' + '?ip=' + encodeURIComponent(ip);
    fetch(proxyUrl)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const lat = data.lat;
                const lon = data.lon;
                const url = `https://www.google.com/maps?q=${lat},${lon}&z=12&t=m`;
                window.open(url, '_blank');
            } else {
                showToast('Không thể định vị IP này: ' + (data.message || 'Không xác định'));
            }
        })
        .catch(() => {
            showToast('Lỗi kết nối. Vui lòng thử lại sau.');
        })
        .finally(() => {
            resetBtn(btn, textEl, spinEl);
        });
}

function resetBtn(btn, textEl, spinEl) {
    btn.disabled = false;
    textEl.style.display = 'inline';
    spinEl.style.display  = 'none';
}

function showToast(msg) {
    const toast = document.getElementById('map-toast');
    document.getElementById('map-toast-msg').textContent = msg;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 4000);
}
</script>
@endsection
