<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $dat_tour->ma_dat_tour }} - VietGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; color: #1e293b; }

        .invoice-wrapper {
            max-width: 800px; margin: 2rem auto; background: white;
            border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            color: white; padding: 2.5rem 3rem; position: relative; overflow: hidden;
        }
        .invoice-header::before {
            content: ''; position: absolute; top: -50%; right: -20%;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .invoice-header::after {
            content: ''; position: absolute; bottom: -40%; left: -10%;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .header-top {
            display: flex; justify-content: space-between; align-items: flex-start;
            position: relative; z-index: 1;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(255,255,255,0.2); display: flex; align-items: center;
            justify-content: center; font-size: 20px; backdrop-filter: blur(10px);
        }
        .brand-name { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; }
        .brand-name span { opacity: 0.8; }
        .brand-sub { font-size: 11px; opacity: 0.7; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px; }

        .invoice-label {
            text-align: right; position: relative; z-index: 1;
        }
        .invoice-label h1 {
            font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 900;
            letter-spacing: 2px; opacity: 0.95;
        }
        .invoice-label .inv-number {
            font-size: 14px; opacity: 0.8; margin-top: 4px; font-weight: 600;
        }

        /* Body */
        .invoice-body { padding: 2.5rem 3rem; }

        /* Info Grid */
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;
            margin-bottom: 2rem; padding-bottom: 2rem;
            border-bottom: 2px dashed #e2e8f0;
        }
        .info-block h3 {
            font-size: 10px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px;
        }
        .info-block p { font-size: 14px; color: #1e293b; line-height: 1.8; }
        .info-block p strong { font-weight: 700; }

        /* Tour Info Card */
        .tour-card {
            background: #f8fafc; border-radius: 12px; padding: 1.5rem;
            border: 1px solid #e2e8f0; margin-bottom: 2rem;
            display: flex; gap: 1.25rem; align-items: flex-start;
        }
        .tour-card img {
            width: 120px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0;
        }
        .tour-card-info h4 {
            font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800;
            color: #0f172a; margin-bottom: 8px;
        }
        .tour-meta-row {
            display: flex; flex-wrap: wrap; gap: 12px; font-size: 12px; color: #64748b;
        }
        .tour-meta-row span { display: flex; align-items: center; gap: 4px; }
        .tour-meta-row i { color: #10b981; font-size: 11px; }

        /* Table */
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        .invoice-table thead th {
            background: #f8fafc; padding: 12px 16px; font-size: 11px;
            font-weight: 700; color: #64748b; text-transform: uppercase;
            letter-spacing: 0.8px; text-align: left; border-bottom: 2px solid #e2e8f0;
        }
        .invoice-table tbody td {
            padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
            font-size: 14px; color: #334155;
        }
        .invoice-table tbody tr:last-child td { border-bottom: 2px solid #e2e8f0; }

        /* Totals */
        .totals-section { display: flex; justify-content: flex-end; margin-bottom: 2rem; }
        .totals-box { width: 320px; }
        .total-row {
            display: flex; justify-content: space-between; padding: 8px 0;
            font-size: 14px; color: #475569;
        }
        .total-row.discount { color: #10b981; }
        .total-row.final {
            border-top: 2px solid #0f172a; margin-top: 8px; padding-top: 12px;
            font-size: 18px; font-weight: 900; color: #0f172a;
        }
        .total-row .label { font-weight: 600; }

        /* Payment Status */
        .payment-status {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;
            padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 12px;
            margin-bottom: 2rem;
        }
        .payment-status.pending {
            background: #fffbeb; border-color: #fde68a;
        }
        .payment-status-icon {
            width: 44px; height: 44px; border-radius: 50%; background: #dcfce7;
            display: flex; align-items: center; justify-content: center;
            color: #16a34a; font-size: 20px; flex-shrink: 0;
        }
        .payment-status.pending .payment-status-icon {
            background: #fef3c7; color: #d97706;
        }
        .payment-status-text h4 { font-size: 14px; font-weight: 700; color: #0f172a; }
        .payment-status-text p { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* Footer */
        .invoice-footer {
            border-top: 2px dashed #e2e8f0; padding-top: 2rem; text-align: center;
        }
        .footer-note {
            font-size: 12px; color: #94a3b8; line-height: 1.8; margin-bottom: 1rem;
        }
        .footer-contact {
            display: flex; justify-content: center; gap: 2rem; font-size: 12px; color: #64748b;
        }
        .footer-contact span { display: flex; align-items: center; gap: 6px; }
        .footer-contact i { color: #10b981; }
        .footer-thanks {
            margin-top: 1.5rem; font-family: 'Outfit', sans-serif;
            font-size: 16px; font-weight: 700; color: #10b981;
        }

        /* Action Buttons (hidden in print) */
        .action-bar {
            display: flex; justify-content: center; gap: 12px;
            padding: 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0;
        }
        .action-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; border-radius: 10px; font-weight: 700;
            font-size: 14px; cursor: pointer; border: none; font-family: inherit;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-print {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16,185,129,0.4); }
        .btn-back {
            background: white; color: #374151; border: 1px solid #d1d5db;
        }
        .btn-back:hover { background: #f9fafb; border-color: #9ca3af; }

        /* Print Styles */
        @media print {
            body { background: white; }
            .invoice-wrapper { box-shadow: none; margin: 0; border-radius: 0; max-width: 100%; }
            .action-bar { display: none !important; }
            .invoice-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .invoice-table thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .payment-status { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .tour-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media (max-width: 640px) {
            .invoice-header { padding: 1.5rem; }
            .header-top { flex-direction: column; gap: 1rem; }
            .invoice-label { text-align: left; }
            .invoice-body { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .tour-card { flex-direction: column; }
            .tour-card img { width: 100%; height: 160px; }
            .totals-box { width: 100%; }
            .footer-contact { flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-top">
                <div class="brand">
                    <div class="brand-icon"><i class="fa-solid fa-plane-departure"></i></div>
                    <div>
                        <div class="brand-name">Viet<span>Go</span></div>
                        <div class="brand-sub">Khám phá Việt Nam cùng bạn</div>
                    </div>
                </div>
                <div class="invoice-label">
                    <h1>HÓA ĐƠN</h1>
                    <div class="inv-number">#{{ $dat_tour->ma_dat_tour }}</div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-block">
                    <h3>Thông Tin Khách Hàng</h3>
                    <p>
                        <strong>{{ $dat_tour->khachHang->ho_ten }}</strong><br>
                        📧 {{ $dat_tour->khachHang->email }}<br>
                        📞 {{ $dat_tour->khachHang->so_dien_thoai ?? 'Chưa cung cấp' }}<br>
                        @if($dat_tour->khachHang->dia_chi)
                        📍 {{ $dat_tour->khachHang->dia_chi }}
                        @endif
                    </p>
                </div>
                <div class="info-block" style="text-align: right;">
                    <h3>Thông Tin Hóa Đơn</h3>
                    <p>
                        <strong>Mã đơn:</strong> #{{ $dat_tour->ma_dat_tour }}<br>
                        <strong>Ngày đặt:</strong> {{ $dat_tour->created_at->format('d/m/Y') }}<br>
                        <strong>Ngày xuất HĐ:</strong> {{ now()->format('d/m/Y H:i') }}<br>
                        <strong>Trạng thái:</strong> {{ $dat_tour->trang_thai_ten }}
                    </p>
                </div>
            </div>

            <!-- Tour Info -->
            <div class="tour-card">
                <img src="{{ $dat_tour->lichKhoiHanh->tour->hinh_bia_url }}" alt="{{ $dat_tour->lichKhoiHanh->tour->ten_tour }}">
                <div class="tour-card-info">
                    <h4>{{ $dat_tour->lichKhoiHanh->tour->ten_tour }}</h4>
                    <div class="tour-meta-row">
                        <span><i class="fa-regular fa-calendar"></i> {{ $dat_tour->lichKhoiHanh->ngay_di->format('d/m/Y') }} - {{ $dat_tour->lichKhoiHanh->ngay_ve->format('d/m/Y') }}</span>
                        <span><i class="fa-regular fa-clock"></i> {{ $dat_tour->lichKhoiHanh->tour->so_ngay }}N{{ $dat_tour->lichKhoiHanh->tour->so_dem }}Đ</span>
                        <span><i class="fa-solid fa-location-dot"></i> {{ $dat_tour->lichKhoiHanh->tour->diemDen->ten_diem_den }}</span>
                    </div>
                </div>
            </div>

            <!-- Invoice Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Mô Tả</th>
                        <th style="text-align: center;">Số Lượng</th>
                        <th style="text-align: right;">Đơn Giá</th>
                        <th style="text-align: right;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <strong>Người lớn</strong><br>
                            <span style="font-size: 12px; color: #64748b;">Tour {{ $dat_tour->lichKhoiHanh->tour->ten_tour }}</span>
                        </td>
                        <td style="text-align: center;">{{ $dat_tour->so_nguoi_lon }}</td>
                        <td style="text-align: right;">{{ number_format($dat_tour->lichKhoiHanh->tour->gia_nguoi_lon ?? 0, 0, ',', '.') }}đ</td>
                        <td style="text-align: right; font-weight: 700;">{{ number_format(($dat_tour->lichKhoiHanh->tour->gia_nguoi_lon ?? 0) * $dat_tour->so_nguoi_lon, 0, ',', '.') }}đ</td>
                    </tr>
                    @if($dat_tour->so_tre_em > 0)
                    <tr>
                        <td>2</td>
                        <td>
                            <strong>Trẻ em</strong><br>
                            <span style="font-size: 12px; color: #64748b;">Tour {{ $dat_tour->lichKhoiHanh->tour->ten_tour }}</span>
                        </td>
                        <td style="text-align: center;">{{ $dat_tour->so_tre_em }}</td>
                        <td style="text-align: right;">{{ number_format($dat_tour->lichKhoiHanh->tour->gia_tre_em ?? 0, 0, ',', '.') }}đ</td>
                        <td style="text-align: right; font-weight: 700;">{{ number_format(($dat_tour->lichKhoiHanh->tour->gia_tre_em ?? 0) * $dat_tour->so_tre_em, 0, ',', '.') }}đ</td>
                    </tr>
                    @endif
                    @if($dat_tour->so_em_be > 0)
                    <tr>
                        <td>{{ $dat_tour->so_tre_em > 0 ? 3 : 2 }}</td>
                        <td>
                            <strong>Em bé</strong><br>
                            <span style="font-size: 12px; color: #64748b;">Miễn phí (dưới 2 tuổi)</span>
                        </td>
                        <td style="text-align: center;">{{ $dat_tour->so_em_be }}</td>
                        <td style="text-align: right;">0đ</td>
                        <td style="text-align: right; font-weight: 700;">0đ</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-section">
                <div class="totals-box">
                    <div class="total-row">
                        <span class="label">Tạm tính</span>
                        <span>{{ number_format($dat_tour->tong_tien_goc, 0, ',', '.') }}đ</span>
                    </div>
                    @if($dat_tour->giam_gia > 0)
                    <div class="total-row discount">
                        <span class="label"><i class="fa-solid fa-tag"></i> Giảm giá</span>
                        <span>-{{ number_format($dat_tour->giam_gia, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    <div class="total-row final">
                        <span class="label">TỔNG CỘNG</span>
                        <span style="color: #059669;">{{ number_format($dat_tour->tong_tien_thanh_toan, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            @php
                $isPaid = in_array($dat_tour->trang_thai, ['da_xac_nhan', 'hoan_thanh', 'done']) || optional($dat_tour->thanhToan)->trang_thai === 'thanh_cong';
                $ptMap = [
                    'chuyen_khoan'=>'Chuyển khoản / Quét QR',
                    'vnpay'=>'VNPay / Thẻ Visa',
                    'momo'=>'Ví MoMo',
                    'zalopay'=>'ZaloPay',
                    'tien_mat'=>'Thanh toán tại văn phòng',
                ];
                $ptName = $ptMap[$dat_tour->phuong_thuc_thanh_toan] ?? 'Thanh toán tại văn phòng';
            @endphp
            <div class="payment-status {{ $isPaid ? '' : 'pending' }}">
                <div class="payment-status-icon">
                    <i class="fa-solid {{ $isPaid ? 'fa-circle-check' : 'fa-clock' }}"></i>
                </div>
                <div class="payment-status-text">
                    <h4>{{ $isPaid ? '✅ Đã Thanh Toán Thành Công' : '🕐 Chờ Thanh Toán' }}</h4>
                    <p>Phương thức: {{ $ptName }}
                        @if($dat_tour->thanhToan && $dat_tour->thanhToan->ma_giao_dich)
                            • Mã GD: {{ $dat_tour->thanhToan->ma_giao_dich }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                <div class="footer-note">
                    Hóa đơn này được tạo tự động bởi hệ thống VietGo.<br>
                    Vui lòng giữ lại hóa đơn để đối chiếu khi cần thiết.
                </div>
                <div class="footer-contact">
                    <span><i class="fa-solid fa-phone"></i> 1900 1800</span>
                    <span><i class="fa-solid fa-envelope"></i> hotro@vietgo.vn</span>
                    <span><i class="fa-solid fa-globe"></i> vietgo.vn</span>
                </div>
                <div class="footer-thanks">Cảm ơn bạn đã tin tưởng VietGo! 🌟</div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <button onclick="window.print()" class="action-btn btn-print">
                <i class="fa-solid fa-print"></i> In Hóa Đơn
            </button>
            <a href="{{ url()->previous() }}" class="action-btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>
</body>
</html>
