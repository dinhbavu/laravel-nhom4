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
                    <th style="width: 25%;">Khách Hàng</th>
                    <th style="width: 20%;">Địa Chỉ IP</th>
                    <th style="width: 30%;">Thông Tin Trình Duyệt / Thiết Bị</th>
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
                    <td><span class="badge badge-secondary" style="font-size:12px;"><i class="fa-solid fa-globe"></i> {{ $ls->ip_address ?? 'Không xác định' }}</span></td>
                    <td><div style="font-size:12px; color:#475569; word-break: break-all; line-height: 1.4;">{{ $ls->user_agent }}</div></td>
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
@endsection
