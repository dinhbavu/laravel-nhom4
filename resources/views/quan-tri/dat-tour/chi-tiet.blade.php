@extends('layouts.quan-tri')
@section('title', 'Chi Tiết Đơn #' . $dat_tour->ma_dat_tour)
@section('page-title', 'Chi Tiết Đơn Đặt Tour')

@section('css')
<style>
.detail-page-header { display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
.detail-page-header .badge-status { margin-left:auto; }
@media (max-width: 768px) {
    .detail-page-header { gap:8px; }
    .detail-page-header .badge-status { margin-left:0; }
    .tour-preview-img { width:72px !important; height:54px !important; }
    .customer-avatar { width:40px !important; height:40px !important; }
}
</style>
@endsection

@section('content')
<div class="detail-page-header">
    <a href="{{ route('quan-tri.dat-tour.danh-sach') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    <div>
        <h2 style="font-size:20px; font-weight:800; color:#0f172a;">Đơn #{{ $dat_tour->ma_dat_tour }}</h2>
        <div style="font-size:13px; color:#64748b; margin-top:2px;">Đặt lúc {{ $dat_tour->created_at->format('H:i - d/m/Y') }}</div>
    </div>
    <span class="badge badge-{{ $dat_tour->trang_thai_mau }} badge-status" style="font-size:13px; padding:6px 14px;">{{ $dat_tour->trang_thai_ten }}</span>
</div>

<div class="grid-detail">
    <!-- Left: Info columns -->
    <div style="display:flex; flex-direction:column; gap:18px;">

        <!-- Tour Info -->
        <div class="card">
            <div class="card-header"><h3><i class="fa-solid fa-map-location-dot"></i> Thông Tin Tour</h3></div>
            <div class="card-body">
                <div style="display:flex; gap:16px; align-items:flex-start;">
                    @if($dat_tour->lichKhoiHanh && $dat_tour->lichKhoiHanh->tour)
                    <img src="{{ $dat_tour->lichKhoiHanh->tour->hinh_bia_url }}" style="width:100px; height:72px; object-fit:cover; border-radius:10px; flex-shrink:0; border:1px solid #e2e8f0;">
                    @endif
                    <div style="flex:1;">
                        <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:6px;">
                            {{ $dat_tour->lichKhoiHanh->tour->ten_tour ?? 'N/A' }}
                        </div>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <span style="font-size:12px; color:#64748b;"><i class="fa-solid fa-calendar" style="color:#10b981;"></i> Khởi hành: <strong>{{ optional($dat_tour->lichKhoiHanh)->ngay_di ? $dat_tour->lichKhoiHanh->ngay_di->format('d/m/Y') : 'N/A' }}</strong></span>
                            <span style="font-size:12px; color:#64748b;"><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Thời gian: <strong>{{ $dat_tour->lichKhoiHanh->tour->so_ngay ?? 'N/A' }}N{{ $dat_tour->lichKhoiHanh->tour->so_dem ?? '' }}Đ</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <div class="card-header"><h3><i class="fa-solid fa-user"></i> Thông Tin Khách Hàng</h3></div>
            <div class="card-body">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                    <img src="{{ $dat_tour->khachHang->anh_dai_dien_url ?? '' }}" style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #e2e8f0;">
                    <div>
                        <div style="font-size:16px; font-weight:700;">{{ $dat_tour->khachHang->ho_ten ?? 'N/A' }}</div>
                        <div style="font-size:13px; color:#64748b;">{{ $dat_tour->khachHang->email ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-solid fa-phone" style="color:#10b981;"></i> Điện Thoại</div>
                    <div class="detail-value">{{ $dat_tour->khachHang->so_dien_thoai ?? 'Chưa cung cấp' }}</div>
                </div>
                @if($dat_tour->khachHang->ten_ngan_hang)
                <div class="detail-row" style="border-top:1px dashed #e2e8f0; margin-top:8px; padding-top:8px;">
                    <div class="detail-label"><i class="fa-solid fa-building-columns" style="color:#3b82f6;"></i> Ngân Hàng</div>
                    <div class="detail-value">{{ $dat_tour->khachHang->ten_ngan_hang }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-solid fa-credit-card" style="color:#3b82f6;"></i> Số Tài Khoản</div>
                    <div class="detail-value" style="font-family:monospace; font-weight:700;">{{ $dat_tour->khachHang->so_tai_khoan }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-solid fa-signature" style="color:#3b82f6;"></i> Chủ Tài Khoản</div>
                    <div class="detail-value" style="text-transform:uppercase;">{{ $dat_tour->khachHang->ten_tai_khoan }}</div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-solid fa-pen-to-square" style="color:#f59e0b;"></i> Ghi Chú</div>
                    <div class="detail-value" style="color: {{ $dat_tour->ghi_chu ? '#ef4444' : '#94a3b8' }};">{{ $dat_tour->ghi_chu ?: 'Không có ghi chú' }}</div>
                </div>
            </div>
        </div>

        <!-- Passengers -->
        @if($dat_tour->hanhKhach && $dat_tour->hanhKhach->count() > 0)
        <div class="card">
            <div class="card-header"><h3><i class="fa-solid fa-users"></i> Danh Sách Hành Khách ({{ $dat_tour->hanhKhach->count() }} người)</h3></div>
            <div class="overflow-x">
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Họ Tên</th><th>Ngày Sinh</th><th>CCCD/Passport</th><th>Loại</th></tr>
                    </thead>
                    <tbody>
                        @foreach($dat_tour->hanhKhach as $i => $hk)
                        <tr>
                            <td style="color:#94a3b8;">{{ $i+1 }}</td>
                            <td style="font-weight:600;">{{ $hk->ho_ten }}</td>
                            <td>{{ $hk->ngay_sinh ? \Carbon\Carbon::parse($hk->ngay_sinh)->format('d/m/Y') : '—' }}</td>
                            <td style="font-family:monospace;">{{ $hk->so_cmnd ?? '—' }}</td>
                            <td>
                                @if($hk->loai == 'nguoi_lon')
                                    <span class="badge badge-info">Người lớn</span>
                                @elseif($hk->loai == 'tre_em')
                                    <span class="badge badge-warning">Trẻ em</span>
                                @else
                                    <span class="badge badge-secondary">Em bé</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    <!-- Right: Actions & Price -->
    <div style="display:flex; flex-direction:column; gap:18px;">

        <!-- Price Breakdown -->
        <div class="card sticky-box">
            <div class="card-header"><h3><i class="fa-solid fa-receipt"></i> Tóm Tắt Chi Phí</h3></div>
            <div class="card-body">
                <div class="total-row">
                    <span class="label" style="color:#64748b;">Người lớn ({{ $dat_tour->so_nguoi_lon ?? 0 }}x)</span>
                    <span style="font-weight:600;">{{ number_format(($dat_tour->lichKhoiHanh->tour->gia_nguoi_lon ?? 0) * ($dat_tour->so_nguoi_lon ?? 0), 0, ',', '.') }}đ</span>
                </div>
                @if(($dat_tour->so_tre_em ?? 0) > 0)
                <div class="total-row">
                    <span class="label" style="color:#64748b;">Trẻ em ({{ $dat_tour->so_tre_em }}x)</span>
                    <span style="font-weight:600;">{{ number_format(($dat_tour->lichKhoiHanh->tour->gia_tre_em ?? 0) * $dat_tour->so_tre_em, 0, ',', '.') }}đ</span>
                </div>
                @endif
                @if($dat_tour->giam_gia > 0)
                <div class="total-row">
                    <span style="color:#10b981;"><i class="fa-solid fa-tag"></i> Giảm giá</span>
                    <span style="color:#10b981; font-weight:600;">-{{ number_format($dat_tour->giam_gia, 0, ',', '.') }}đ</span>
                </div>
                @endif
                <div class="divider"></div>
                <div class="total-row final">
                    <span class="label">TỔNG CỘNG</span>
                    <span class="value">{{ $dat_tour->tong_tien_dinh_dang }}</span>
                </div>

                <!-- Actions -->
                <div style="margin-top:18px;">
                    @if($dat_tour->trang_thai == 'cho_duyet')
                        <form action="{{ route('quan-tri.dat-tour.duyet', $dat_tour) }}" method="POST" style="margin-bottom:10px;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-full" onclick="return confirm('Xác nhận duyệt đơn này?')">
                                <i class="fa-solid fa-check-circle"></i> Duyệt Đơn
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-full" onclick="document.getElementById('form-tu-choi').style.display='block'; this.style.display='none';">
                            <i class="fa-solid fa-times-circle"></i> Từ Chối Đơn
                        </button>
                        <form id="form-tu-choi" action="{{ route('quan-tri.dat-tour.tu-choi', $dat_tour) }}" method="POST" style="display:none; margin-top:10px;">
                            @csrf
                            <textarea name="ly_do_huy" rows="3" class="form-control" placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..." style="margin-bottom:8px;" required minlength="10"></textarea>
                            <button type="submit" class="btn btn-danger btn-full">Xác Nhận Từ Chối</button>
                        </form>
                    @elseif($dat_tour->trang_thai == 'da_duyet')
                        <form action="{{ route('quan-tri.dat-tour.hoan-thanh', $dat_tour) }}" method="POST" style="margin-bottom:10px;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-full" onclick="return confirm('Đánh dấu tour này đã hoàn thành?')">
                                <i class="fa-solid fa-flag-checkered"></i> Đánh Dấu Hoàn Thành
                            </button>
                        </form>
                    @elseif($dat_tour->trang_thai == 'yeu_cau_hoan_tien')
                        <button type="button" class="btn btn-warning btn-full" style="margin-bottom:10px;" onclick="document.getElementById('form-hoan-tien').style.display='block'; this.style.display='none';">
                            <i class="fa-solid fa-money-bill-transfer"></i> Xem & Duyệt Hoàn Tiền
                        </button>
                        
                        <div id="form-hoan-tien" style="display:none; padding:16px; background:#fff7ed; border-radius:12px; border:1px solid #fed7aa; margin-bottom:10px;">
                            <div style="font-size:13px; font-weight:800; color:#c2410c; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                                <i class="fa-solid fa-building-columns"></i> TÀI KHOẢN HOÀN TIỀN
                            </div>
                            
                            <div style="display:flex; flex-direction:column; gap:8px; font-size:13px; color:#475569; margin-bottom:16px;">
                                <div style="display:flex; justify-content:space-between; border-bottom:1px dashed #fed7aa; padding-bottom:6px;">
                                    <span>Ngân hàng:</span>
                                    <strong style="color:#0f172a;">{{ $dat_tour->khachHang->ten_ngan_hang ?? 'N/A' }}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-bottom:1px dashed #fed7aa; padding-bottom:6px;">
                                    <span>Số tài khoản:</span>
                                    <strong style="color:#0f172a; font-family:monospace;">{{ $dat_tour->khachHang->so_tai_khoan ?? 'N/A' }}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-bottom:1px dashed #fed7aa; padding-bottom:6px;">
                                    <span>Chủ tài khoản:</span>
                                    <strong style="color:#0f172a;">{{ $dat_tour->khachHang->ten_tai_khoan ?? 'N/A' }}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; padding-top:4px;">
                                    <span>Số tiền cần hoàn:</span>
                                    <strong style="color:#ef4444; font-size:15px;">{{ number_format($dat_tour->tong_tien_da_thanh_toan, 0, ',', '.') }}đ</strong>
                                </div>
                            </div>

                            <form action="{{ route('quan-tri.dat-tour.duyet-hoan-tien', $dat_tour) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-full" onclick="return confirm('Xác nhận đã chuyển khoản hoàn tiền cho khách hàng thành công?')">
                                    <i class="fa-solid fa-circle-check"></i> Xác Nhận Đã Hoàn Tiền
                                </button>
                            </form>
                        </div>
                    @else
                        <div style="padding:16px; background:#f8fafc; border-radius:10px; text-align:center; color:#64748b; font-size:13px;">
                            <i class="fa-solid fa-lock" style="font-size:20px; margin-bottom:6px; display:block; opacity:0.4;"></i>
                            Đơn hàng đã được xử lý xong<br><strong>{{ $dat_tour->trang_thai_ten }}</strong>
                        </div>
                    @endif

                    @if(in_array($dat_tour->trang_thai, ['hoan_thanh', 'done', 'da_xac_nhan']))
                    <a href="{{ route('quan-tri.dat-tour.hoa-don', $dat_tour) }}" target="_blank" class="btn btn-warning btn-full" style="margin-top:10px;">
                        <i class="fa-solid fa-file-invoice"></i> In Hóa Đơn
                    </a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
