@extends('layouts.khach-hang')
@section('title', 'Chi Tiết Đơn #' . $dat_tour->ma_dat_tour . ' - VietGo')

@section('css')
<style>
/* ═══ Layout ═══ */
.order-page { padding: 2.5rem 0 5rem; }
.order-grid { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }
@media(max-width: 900px){ .order-grid { grid-template-columns: 1fr; } }

/* ═══ Status timeline ═══ */
.status-bar {
    display: flex; align-items: center; gap: 0;
    background: rgba(255, 255, 255, 0.04); border-radius: 20px; padding: 1.5rem 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);
    margin-bottom: 2rem; overflow-x: auto;
}
.sbar-step { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 80px; position: relative; }
.sbar-step + .sbar-step::before {
    content: ''; position: absolute; left: calc(-50% + 20px); right: calc(50% + 20px);
    top: 20px; height: 2px; background: rgba(255, 255, 255, 0.1); z-index: 0;
}
.sbar-step.done + .sbar-step.done::before,
.sbar-step.done + .sbar-step.active::before { background: var(--primary-accent); }
.sbar-icon {
    width: 40px; height: 40px; border-radius: 50%; z-index: 1;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; background: rgba(255, 255, 255, 0.05); color: rgba(255, 255, 255, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.1); flex-shrink: 0;
    transition: all .3s;
}
.sbar-step.done .sbar-icon { background: #4ade80; color: #000; border-color: #4ade80; }
.sbar-step.active .sbar-icon { background: var(--primary-accent); color: #000; border-color: var(--primary-accent); box-shadow: 0 0 15px rgba(74, 222, 128, 0.4); }
.sbar-step.cancelled .sbar-icon { background: rgba(239, 68, 68, 0.2); color: #f87171; border-color: rgba(239, 68, 68, 0.4); }
.sbar-label { font-size: .72rem; font-weight: 700; color: rgba(255, 255, 255, 0.4); margin-top: .5rem; text-align: center; }
.sbar-step.done .sbar-label, .sbar-step.active .sbar-label { color: #fff; }
.sbar-step.cancelled .sbar-label { color: #f87171; }

/* ═══ Info block ═══ */
.oblock { background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(20px); margin-bottom: 1.5rem; }
.oblock-head { padding: 1.1rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; gap: .65rem; font-weight: 800; font-size: .95rem; color: #fff; }
.oblock-head i { width: 30px; height: 30px; border-radius: 8px; background: rgba(74, 222, 128, 0.1); color: var(--primary-accent); display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
.oblock-body { padding: 1.5rem; }
.orow { display: flex; justify-content: space-between; align-items: center; padding: .65rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: .875rem; }
.orow:last-child { border-bottom: none; }
.orow .ol { color: rgba(255, 255, 255, 0.5); font-weight: 500; }
.orow .ov { font-weight: 700; color: #fff; }

/* ═══ Tour card ═══ */
.tour-snap { display: flex; gap: 1rem; align-items: flex-start; }
.tour-snap img { width: 100px; height: 75px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
.tour-snap-info { flex: 1; }
.tour-snap-name { font-family: var(--font-heading); font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: .5rem; line-height: 1.3; }
.tour-snap-meta { display: flex; flex-direction: column; gap: .3rem; font-size: .8rem; color: rgba(255, 255, 255, 0.5); }
.tour-snap-meta span { display: flex; align-items: center; gap: .4rem; }
.tour-snap-meta i { color: var(--primary-accent); width: 14px; }

/* ═══ Cancel form ═══ */
.cancel-card { background: rgba(239, 68, 68, 0.05); border-radius: 20px; overflow: hidden; border: 1px solid rgba(239, 68, 68, 0.2); backdrop-filter: blur(15px); margin-bottom: 1.5rem; }
.cancel-head { padding: 1.1rem 1.5rem; border-bottom: 1px solid rgba(239, 68, 68, 0.1); display: flex; align-items: center; gap: .65rem; font-weight: 800; font-size: .95rem; color: #f87171; background: rgba(239, 68, 68, 0.1); }
.cancel-body { padding: 1.5rem; }
.cancel-warning { font-size: .85rem; color: #fecaca; background: rgba(239, 68, 68, 0.15); border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1.25rem; display: flex; gap: .6rem; align-items: flex-start; line-height: 1.5; border: 1px solid rgba(239, 68, 68, 0.1); }
.cancel-textarea { width: 100%; padding: .85rem 1rem; border: 2px solid rgba(255,255,255,0.1); border-radius: 12px; font-family: inherit; font-size: .875rem; resize: vertical; outline: none; transition: all .25s; background: rgba(255,255,255,0.05); color: #fff; }
.cancel-textarea:focus { border-color: #ef4444; background: rgba(255,255,255,0.08); box-shadow: 0 0 0 4px rgba(239,68,68,0.15); }
.btn-cancel-tour { width: 100%; padding: .85rem; margin-top: 1rem; background: #ef4444; color: white; border: none; border-radius: 12px; font-weight: 800; font-size: .9rem; cursor: pointer; font-family: inherit; transition: all .3s; display: flex; align-items: center; justify-content: center; gap: .5rem; }
.btn-cancel-tour:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(239,68,68,.3); background: #dc2626; }

/* ═══ Sticky payment summary ═══ */
.pay-sticky { position: sticky; top: 5rem; }
.pay-card { background: rgba(255, 255, 255, 0.03); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(25px); box-shadow: 0 4px 24px rgba(0,0,0,.3); }
.pay-card-head { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-weight: 800; font-size: .95rem; color: #fff; display: flex; align-items: center; gap: .65rem; }
.pay-card-head i { color: var(--primary-accent); }
.pay-body { padding: 1.5rem; }
.pay-method { display: flex; align-items: center; gap: .75rem; padding: .85rem 1rem; background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.2); border-radius: 12px; margin-bottom: 1.25rem; }
.pay-method-icon { font-size: 1.5rem; }
.pay-method-label { font-size: .75rem; color: rgba(255, 255, 255, 0.5); font-weight: 600; }
.pay-method-val { font-size: .9rem; color: #fff; font-weight: 800; }
.pay-rows { display: flex; flex-direction: column; gap: .45rem; }
.pay-row { display: flex; justify-content: space-between; font-size: .85rem; padding: .4rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.pay-row:last-child { border-bottom: none; }
.pay-row .pl { color: rgba(255, 255, 255, 0.5); }
.pay-row .pv { font-weight: 700; color: #fff; }
.pay-row.pdisc .pl, .pay-row.pdisc .pv { color: #f87171; }
.pay-divider { height: 1px; background: rgba(255, 255, 255, 0.05); margin: 1rem 0; }
.pay-total {
    background: linear-gradient(135deg, var(--primary-accent), #22c55e);
    border-radius: 14px; padding: 1.1rem 1.25rem;
    display: flex; justify-content: space-between; align-items: center; color: #000;
    margin-top: 1rem;
}
.pay-total-label { font-size: .8rem; font-weight: 800; opacity: .8; }
.pay-total-num { font-family: var(--font-heading); font-size: 1.5rem; font-weight: 1000; }

/* Status badge custom */
.s-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .35rem 1rem; border-radius: 999px;
    font-size: .82rem; font-weight: 800; border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
}
.s-cho_duyet { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
.s-da_duyet { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
.s-da_xac_nhan { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
.s-da_huy { background: rgba(239, 68, 68, 0.2); color: #f87171; }
.s-hoan_thanh { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
.s-done { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
</style>
@endsection

@section('content')
{{-- Hero header --}}
<div style="padding:4rem 0 3rem; position:relative; z-index:10;">
    <div class="container">
        <a href="{{ route('khach-hang.dat-tour.lich-su') }}" style="display:inline-flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem;margin-bottom:1.5rem;transition:all .2s;background:rgba(255,255,255,0.05);padding:.5rem 1rem;border-radius:10px;border:1px solid rgba(255,255,255,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.color='rgba(255,255,255,.5)'">
            <i class="fa-solid fa-arrow-left"></i> Quay lại lịch sử
        </a>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
            <div>
                <div style="font-size:.8rem;color:rgba(255,255,255,0.4);margin-bottom:.3rem;text-transform:uppercase;letter-spacing:.05em;">Mã đặt tour</div>
                <h1 style="font-family:var(--font-heading);font-size:2rem;font-weight:900;color:white;margin:0 0 .35rem;letter-spacing:.02em;">#{{ $dat_tour->ma_dat_tour }}</h1>
                <div style="font-size:.85rem;color:rgba(255,255,255,0.5);">Đặt vào ngày {{ $dat_tour->created_at->format('d/m/Y') }} • {{ $dat_tour->created_at->format('H:i') }}</div>
            </div>
            @php
                $badges = [
                    'cho_duyet'=>['🕐','Chờ duyệt'],
                    'da_duyet'=>['✅','Đã duyệt'],
                    'da_xac_nhan'=>['🎉','Đã xác nhận'],
                    'da_huy'=>['❌','Đã hủy'],
                    'hoan_thanh'=>['🛫','Khởi hành'],
                    'done'=>['🏆','Hoàn thành']
                ];
                $b = $badges[$dat_tour->trang_thai] ?? ['📋', $dat_tour->trang_thai];
            @endphp
            <span class="s-badge s-{{ $dat_tour->trang_thai }}" style="font-size:.95rem;padding:.6rem 1.5rem;">
                {{ $b[0] }} {{ $b[1] }}
            </span>
        </div>
    </div>
</div>

<div class="container" style="margin-top:-2rem;">
    @if(session('thanh_cong'))
    <div style="background:rgba(74, 222, 128, 0.1); border:1px solid rgba(74, 222, 128, 0.3); backdrop-filter:blur(10px); border-radius:16px; padding:1.25rem; margin-bottom:1.5rem; display:flex; gap:.75rem; align-items:center; color:#4ade80; font-weight:700;">
        <i class="fa-solid fa-circle-check"></i> {{ session('thanh_cong') }}
    </div>
    @endif
    @if(session('loi'))
    <div style="background:rgba(239, 68, 68, 0.1); border:1px solid rgba(239, 68, 68, 0.3); backdrop-filter:blur(10px); border-radius:16px; padding:1.25rem; margin-bottom:1.5rem; display:flex; gap:.75rem; align-items:center; color:#f87171; font-weight:700;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('loi') }}
    </div>
    @endif

        <div class="order-page" id="orderPageContainer">
        {{-- Status Timeline --}}
        <div class="status-bar">
            @php
                $steps = [
                    ['icon'=>'fa-calendar-plus','label'=>'Đặt tour','key'=>'cho_duyet'],
                    ['icon'=>'fa-clipboard-check','label'=>'Chờ duyệt','key'=>'da_duyet'],
                    ['icon'=>'fa-check-double','label'=>'Xác nhận','key'=>'da_xac_nhan'],
                    ['icon'=>'fa-plane-departure','label'=>'Khởi hành','key'=>'hoan_thanh'],
                    ['icon'=>'fa-star','label'=>'Hoàn thành','key'=>'done'],
                ];
                $statusOrder = ['cho_duyet'=>0,'da_duyet'=>1,'da_xac_nhan'=>2,'hoan_thanh'=>3,'done'=>4];
                $currentIdx = $statusOrder[$dat_tour->trang_thai] ?? 0;
                $isCancelled = $dat_tour->trang_thai === 'da_huy';
            @endphp
            @foreach($steps as $i => $step)
            <div class="sbar-step {{ $isCancelled ? 'cancelled' : ($i < $currentIdx ? 'done' : ($i == $currentIdx ? 'active' : '')) }}">
                <div class="sbar-icon">
                    <i class="fa-solid {{ $isCancelled ? 'fa-xmark' : ($i <= $currentIdx ? ($i < $currentIdx ? 'fa-check' : $step['icon']) : $step['icon']) }}"></i>
                </div>
                <div class="sbar-label">{{ $step['label'] }}</div>
            </div>
            @endforeach
        </div>

        <div class="order-grid">
            {{-- ═══ CỘT TRÁI ═══ --}}
            <div>
                {{-- Tour info --}}
                <div class="oblock">
                    <div class="oblock-head"><i class="fa-solid fa-map-location-dot"></i> Thông Tin Tour</div>
                    <div class="oblock-body">
                        <div class="tour-snap">
                            <img src="{{ $dat_tour->lichKhoiHanh->tour->hinh_bia_url }}" alt="Tour">
                            <div class="tour-snap-info">
                                <a href="{{ route('tour.chi-tiet', $dat_tour->lichKhoiHanh->tour) }}" class="tour-snap-name">
                                    {{ $dat_tour->lichKhoiHanh->tour->ten_tour }}
                                </a>
                                <div class="tour-snap-meta">
                                    <span><i class="fa-regular fa-calendar"></i> Khởi hành: <strong>{{ $dat_tour->lichKhoiHanh->ngay_di->format('d/m/Y') }}</strong></span>
                                    <span><i class="fa-regular fa-calendar-check"></i> Về ngày: <strong>{{ $dat_tour->lichKhoiHanh->ngay_ve->format('d/m/Y') }}</strong></span>
                                    <span><i class="fa-regular fa-clock"></i> {{ $dat_tour->lichKhoiHanh->tour->so_ngay }}N{{ $dat_tour->lichKhoiHanh->tour->so_dem }}Đ</span>
                                    <span><i class="fa-solid fa-location-dot"></i> {{ $dat_tour->lichKhoiHanh->tour->diemDen->ten_diem_den }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hành khách --}}
                <div class="oblock">
                    <div class="oblock-head"><i class="fa-solid fa-users"></i> Hành Khách & Liên Hệ</div>
                    <div class="oblock-body">
                        <div class="orow"><span class="ol">Họ và tên</span><span class="ov">{{ $dat_tour->khachHang->ho_ten }}</span></div>
                        <div class="orow"><span class="ol">Email</span><span class="ov">{{ $dat_tour->khachHang->email }}</span></div>
                        <div class="orow"><span class="ol">Điện thoại</span><span class="ov">{{ $dat_tour->khachHang->so_dien_thoai ?? '—' }}</span></div>
                        <div class="orow"><span class="ol">👨 Người lớn</span><span class="ov">{{ $dat_tour->so_nguoi_lon }} người</span></div>
                        @if($dat_tour->so_tre_em > 0)
                        <div class="orow"><span class="ol">🧒 Trẻ em</span><span class="ov">{{ $dat_tour->so_tre_em }} trẻ</span></div>
                        @endif
                        @if($dat_tour->so_em_be > 0)
                        <div class="orow"><span class="ol">👶 Em bé</span><span class="ov">{{ $dat_tour->so_em_be }} em bé</span></div>
                        @endif
                        @if($dat_tour->ghi_chu)
                        <div class="orow" style="flex-direction:column;align-items:flex-start;gap:.5rem;">
                            <span class="ol">Ghi chú đặc biệt</span>
                            <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:.75rem 1rem; width:100%; font-size:.875rem; color:rgba(255,255,255,0.8); border:1px solid rgba(255,255,255,0.08);">{{ $dat_tour->ghi_chu }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Hủy tour (chỉ khi chờ duyệt hoặc đã duyệt) --}}
                @if(in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet']))
                <div class="cancel-card">
                    <div class="cancel-head"><i class="fa-solid fa-triangle-exclamation"></i> Yêu Cầu Hủy Tour</div>
                    <div class="cancel-body">
                        <div class="cancel-warning">
                            <i class="fa-solid fa-circle-info" style="margin-top:.15rem;flex-shrink:0;"></i>
                            Sau khi gửi yêu cầu hủy, chúng tôi sẽ liên hệ để xác nhận và xử lý hoàn tiền theo chính sách. Hành động này không thể hoàn tác.
                        </div>
                        <form action="{{ route('khach-hang.dat-tour.huy', $dat_tour) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt tour này không?')">
                            @csrf
                            <label style="font-size:.8rem;font-weight:800;color:#991b1b;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.5rem;">Lý do hủy *</label>
                            <textarea name="ly_do_huy" rows="3" class="cancel-textarea"
                                placeholder="Nhập lý do hủy tour (tối thiểu 10 ký tự)...">{{ old('ly_do_huy') }}</textarea>
                            @error('ly_do_huy') <div style="color:#ef4444;font-size:.8rem;margin-top:.35rem;">{{ $message }}</div> @enderror
                            <button type="submit" class="btn-cancel-tour">
                                <i class="fa-solid fa-times-circle"></i> Gửi Yêu Cầu Hủy Tour
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Khởi hành MAP and Finish Button --}}
                @if($dat_tour->trang_thai === 'hoan_thanh')
                <div class="oblock">
                    <div class="oblock-head"><i class="fa-solid fa-map-location-dot"></i> Hành Trình & Bản Đồ</div>
                    <div class="oblock-body" style="padding: 1rem;">
                        <div id="tourRoutingMap" style="width: 100%; height: 350px; border-radius: 12px; z-index: 1; border: 1px solid #e2e8f0;"></div>
                        
                        <form action="{{ route('khach-hang.dat-tour.ket-thuc', $dat_tour) }}" method="POST"
                            onsubmit="return confirm('Bạn muốn xác nhận đã hoàn thành chuyến đi này?')">
                            @csrf
                            <button type="submit" class="btn-cancel-tour" style="background: linear-gradient(135deg, #059669, #10b981); margin-top: 1.5rem; box-shadow: 0 4px 15px rgba(16,185,129,.3);">
                                <i class="fa-solid fa-flag-checkered"></i> Kết Thúc Hành Trình Của Bạn
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Đánh giá (chỉ khi hoàn thành) --}}
                @if($dat_tour->trang_thai === 'done' && !$dat_tour->danhGia)
                <div class="oblock">
                    <div class="oblock-head"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> Đánh Giá Trải Nghiệm</div>
                    <div class="oblock-body">
                        <form action="{{ route('khach-hang.dat-tour.danh-gia', $dat_tour) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 1rem;">
                                <label style="font-size:.85rem;font-weight:700;color:#374151;margin-bottom:.5rem;display:block;">Đánh giá của bạn *</label>
                                <select name="diem_so" style="width:100%;padding:.65rem;border-radius:10px;border:1px solid #e2e8f0;outline:none;background:#f8fafc;" required>
                                    <option value="5">⭐⭐⭐⭐⭐ Tuyệt vời</option>
                                    <option value="4">⭐⭐⭐⭐ Tốt</option>
                                    <option value="3">⭐⭐⭐ Bình thường</option>
                                    <option value="2">⭐⭐ Tệ</option>
                                    <option value="1">⭐ Rất tệ</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="font-size:.85rem;font-weight:700;color:#374151;margin-bottom:.5rem;display:block;">Nhận xét chi tiết *</label>
                                <textarea name="noi_dung" rows="4" style="width:100%;padding:.85rem;border-radius:10px;border:1px solid #e2e8f0;outline:none;background:#f8fafc;font-family:inherit;resize:vertical;" placeholder="Chia sẻ trải nghiệm của bạn về chuyến đi..." required></textarea>
                            </div>
                            <button type="submit" class="btn-cancel-tour" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 15px rgba(245,158,11,.3);">
                                <i class="fa-solid fa-paper-plane"></i> Gửi Đánh Giá
                            </button>
                        </form>
                    </div>
                </div>
                @elseif($dat_tour->danhGia)
                <div class="oblock">
                    <div class="oblock-head"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> Đánh Giá Của Bạn</div>
                    <div class="oblock-body">
                        <div style="display:flex;gap:.5rem;margin-bottom:.5rem;color:#f59e0b;font-size:.9rem;">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= $dat_tour->danhGia->diem_so ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p style="font-size:.875rem;color:#374151;line-height:1.6;margin:0;">{{ $dat_tour->danhGia->noi_dung }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ═══ CỘT PHẢI ═══ --}}
            <div class="pay-sticky">
                <div class="pay-card">
                    <div class="pay-card-head"><i class="fa-solid fa-file-invoice-dollar"></i> Chi Tiết Thanh Toán</div>
                    <div class="pay-body">
                        @php
                            $ptMap = [
                                'chuyen_khoan'=>['🏦','Chuyển khoản / Quét QR'],
                                'vnpay'=>['💳','VNPay / Thẻ Visa'],
                                'momo'=>['📱','Ví MoMo'],
                                'zalopay'=>['📲','ZaloPay'],
                                'tien_mat'=>['💵','Tại văn phòng'],
                            ];
                            $pt = $ptMap[$dat_tour->phuong_thuc_thanh_toan ?? 'tien_mat'] ?? ['💰', $dat_tour->phuong_thuc_thanh_toan];
                        @endphp
                        <div class="pay-method">
                            <div class="pay-method-icon">{{ $pt[0] }}</div>
                            <div>
                                <div class="pay-method-label">Phương thức thanh toán</div>
                                <div class="pay-method-val">{{ $pt[1] }}</div>
                            </div>
                        </div>

                        <div class="pay-rows">
                            <div class="pay-row">
                                <span class="pl">👥 Tổng hành khách ({{ $dat_tour->so_nguoi_lon }} NL @if($dat_tour->so_tre_em > 0), {{ $dat_tour->so_tre_em }} TE @endif)</span>
                                <span class="pv">{{ number_format($dat_tour->tong_tien_goc, 0, ',', '.') }}đ</span>
                            </div>
                            @if($dat_tour->giam_gia > 0)
                            <div class="pay-row pdisc">
                                <span class="pl"><i class="fa-solid fa-tag"></i> Giảm giá</span>
                                <span class="pv">-{{ number_format($dat_tour->giam_gia, 0, ',', '.') }}đ</span>
                            </div>
                            @endif
                        </div>

                        <div class="pay-divider"></div>

                        <div class="pay-total">
                            <div>
                                <div class="pay-total-label">Tổng thanh toán</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.6);">Đã bao gồm thuế & phí</div>
                            </div>
                            <div class="pay-total-num">{{ number_format($dat_tour->tong_tien_thanh_toan, 0, ',', '.') }}đ</div>
                        </div>

                        @if($dat_tour->thanhToan)
                        <div style="margin-top:1.25rem; background:rgba(255,255,255,0.03); border-radius:12px; padding:1.25rem; border:1px solid rgba(255,255,255,0.08);">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                                <div style="font-size:.75rem; font-weight:800; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:.05em;">Trạng thái thanh toán</div>
                                @php
                                    $thanhToanStatus = $dat_tour->thanhToan->trang_thai ?? 'cho_xu_ly';
                                    if(in_array($dat_tour->trang_thai, ['da_xac_nhan', 'hoan_thanh', 'done'])) { $thanhToanStatus = 'thanh_cong'; }
                                    $ttMap=['cho_xu_ly'=>['🕐','Chờ xử lý','#fbbf24'],'thanh_cong'=>['✅','Đã thanh toán','#4ade80'],'that_bai'=>['❌','Thất bại','#f87171']];
                                    $tt=$ttMap[$thanhToanStatus]??['📋','Không rõ','#94a3b8'];
                                @endphp
                                <span style="font-weight:800; color:{{ $tt[2] }}; font-size:.85rem; background:rgba(255,255,255,0.05); padding:.3rem .8rem; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">{{ $tt[0] }} {{ $tt[1] }}</span>
                            </div>

                            {{-- HIỂN THỊ MÃ QR TRANH TOÁN ONLINE --}}
                            @if(in_array($dat_tour->phuong_thuc_thanh_toan, ['chuyen_khoan', 'momo', 'zalopay']) && ($dat_tour->thanhToan->trang_thai === 'cho_xu_ly') && !in_array($dat_tour->trang_thai, ['hoan_thanh', 'done']))
                                @php
                                    $pt = $dat_tour->phuong_thuc_thanh_toan;
                                    $amount = $dat_tour->tong_tien_thanh_toan;
                                    $maThanhToan = $dat_tour->ma_dat_tour;
                                @endphp
                                
                                <div id="qr-payment-block" style="margin-top:1.25rem;border-top:1px dashed #cbd5e1;padding-top:1.25rem;text-align:center;">
                                    {{-- Mã thanh toán chung --}}
                                    <div style="background:linear-gradient(135deg,#0f172a,#1e3a5f);border-radius:14px;padding:1rem 1.25rem;margin-bottom:1.25rem;text-align:center;">
                                        <div style="font-size:.7rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.35rem;">Mã xác nhận (vui lòng ghi vào nội dung)</div>
                                        <div style="display:flex;align-items:center;justify-content:center;gap:.6rem;">
                                            <span id="maThanhToan" style="font-family:var(--font-heading);font-weight:900;font-size:1.4rem;color:#34d399;letter-spacing:.1em;">{{ $maThanhToan }}</span>
                                            <button onclick="navigator.clipboard.writeText('{{ $maThanhToan }}');this.innerHTML='<i class=\'fa-solid fa-check\'></i>';setTimeout(()=>this.innerHTML='<i class=\'fa-regular fa-copy\'></i>',2000)" 
                                                style="background:rgba(52,211,153,.15);border:1px solid rgba(52,211,153,.3);color:#34d399;border-radius:8px;padding:.4rem .6rem;cursor:pointer;font-size:.85rem;transition:all .2s;">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if($pt === 'momo')
                                        {{-- GIAO DIỆN MOMO ONLINE --}}
                                        <div style="background:#fff;border:2px solid #db2777;border-radius:16px;padding:1.5rem 1rem;box-shadow:0 10px 30px -5px rgba(219,39,119,0.2);">
                                            <div style="color:#db2777;font-size:2rem;margin-bottom:.5rem;"><i class="fa-solid fa-wallet"></i></div>
                                            <div style="font-family:var(--font-heading);font-weight:800;color:#db2777;font-size:1.1rem;margin-bottom:.5rem;">Thanh Toán Qua MoMo</div>
                                            <p style="font-size:.8rem;color:#64748b;margin-bottom:1rem;line-height:1.5;">Hệ thống hỗ trợ quét QR MoMo chuyển tiền vào MB Bank.<br>Vui lòng ghi đúng <strong>Nội dung chuyển khoản</strong>.</p>
                                            
                                            <div style="background:#fdf2f8;border-radius:12px;padding:1rem;text-align:left;font-size:.85rem;line-height:1.6;border:1px solid #fbcfe8;">
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f9a8d4;padding-bottom:.4rem;margin-bottom:.4rem;">
                                                    <span style="color:#9d174d;">Ngân hàng nhận:</span>
                                                    <strong style="color:#831843;">{{ config('services.payment.bank_id', 'MB') }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f9a8d4;padding-bottom:.4rem;margin-bottom:.4rem;">
                                                    <span style="color:#9d174d;">Số tài khoản:</span>
                                                    <strong style="color:#831843;">{{ config('services.payment.bank_account', '0363102985') }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;">
                                                    <span style="color:#9d174d;">Nội dung CK:</span>
                                                    <strong style="color:#be185d;background:#fce7f3;padding:.1rem .4rem;border-radius:4px;">{{ $maThanhToan }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($pt === 'zalopay')
                                        {{-- GIAO DIỆN ZALOPAY ONLINE --}}
                                        <div style="background:#fff;border:2px solid #2563eb;border-radius:16px;padding:1.5rem 1rem;box-shadow:0 10px 30px -5px rgba(37,99,235,0.2);">
                                            <div style="color:#2563eb;font-size:2rem;margin-bottom:.5rem;"><i class="fa-solid fa-mobile-screen-button"></i></div>
                                            <div style="font-family:var(--font-heading);font-weight:800;color:#2563eb;font-size:1.1rem;margin-bottom:.5rem;">Thanh Toán Qua ZaloPay</div>
                                            <p style="font-size:.8rem;color:#64748b;margin-bottom:1rem;line-height:1.5;">Hệ thống hỗ trợ quét QR ZaloPay chuyển vào MB Bank.<br>Vui lòng ghi đúng <strong>Nội dung chuyển khoản</strong>.</p>
                                            
                                            <div style="background:#eff6ff;border-radius:12px;padding:1rem;text-align:left;font-size:.85rem;line-height:1.6;border:1px solid #bfdbfe;">
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #93c5fd;padding-bottom:.4rem;margin-bottom:.4rem;">
                                                    <span style="color:#1e3a8a;">Ngân hàng nhận:</span>
                                                    <strong style="color:#172554;">{{ config('services.payment.bank_id', 'MB') }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #93c5fd;padding-bottom:.4rem;margin-bottom:.4rem;">
                                                    <span style="color:#1e3a8a;">Số tài khoản:</span>
                                                    <strong style="color:#172554;">{{ config('services.payment.bank_account', '0363102985') }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;">
                                                    <span style="color:#1e3a8a;">Nội dung CK:</span>
                                                    <strong style="color:#1d4ed8;background:#dbeafe;padding:.1rem .4rem;border-radius:4px;">{{ $maThanhToan }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        {{-- GIAO DIỆN CHUYỂN KHOẢN NGÂN HÀNG (MB Bank) --}}
                                        @php
                                            $bankId = config('services.payment.bank_id', 'MB');
                                            $bankAccount = config('services.payment.bank_account', '0363102985');
                                            $bankName = rawurlencode(config('services.payment.bank_name', 'DINH BA VU'));
                                            $addInfo = rawurlencode($maThanhToan);
                                            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$bankAccount}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$bankName}";
                                        @endphp
                                        <div style="background:#fff;border:2px solid #34d399;border-radius:16px;padding:1.5rem 1rem;box-shadow:0 10px 25px -5px rgba(52,211,153,0.2);">
                                            <div style="color:#10b981;font-size:2rem;margin-bottom:.5rem;"><i class="fa-solid fa-building-columns"></i></div>
                                            <div style="font-family:var(--font-heading);font-weight:800;color:#0f172a;font-size:1.1rem;margin-bottom:.5rem;">Chuyển Khoản Ngân Hàng</div>
                                            <p style="font-size:.8rem;color:#64748b;margin-bottom:1rem;line-height:1.5;">Dùng App Ngân Hàng quét mã VietQR bên dưới.</p>
                                            
                                            <div style="background:white;padding:.5rem;border-radius:12px;display:inline-block;border:1px solid #e2e8f0;margin-bottom:1rem;">
                                                <img src="{{ $qrUrl }}" alt="Mã QR Thanh Toán" style="width:220px;height:auto;max-width:100%;">
                                            </div>

                                            <div style="background:#f8fafc;border-radius:8px;padding:.85rem;text-align:left;font-size:.85rem;line-height:1.6;border:1px solid #e2e8f0;">
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:.3rem;margin-bottom:.3rem;">
                                                    <span style="color:#64748b;">Ngân hàng:</span>
                                                    <strong style="color:#0f172a;">{{ $bankId }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:.3rem;margin-bottom:.3rem;">
                                                    <span style="color:#64748b;">Chủ tài khoản:</span>
                                                    <strong style="color:#0f172a;">{{ config('services.payment.bank_name', 'DINH BA VU') }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:.3rem;margin-bottom:.3rem;">
                                                    <span style="color:#64748b;">Số tài khoản:</span>
                                                    <strong style="color:#0f172a;user-select:all;">{{ $bankAccount }}</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:.3rem;margin-bottom:.3rem;">
                                                    <span style="color:#64748b;">Số tiền:</span>
                                                    <strong style="color:#ef4444;">{{ number_format($amount, 0, ',', '.') }} đ</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;">
                                                    <span style="color:#64748b;">Nội dung CK:</span>
                                                    <strong style="color:#059669;">{{ $maThanhToan }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div style="margin-top:1.25rem; font-size:.78rem; color:#4ade80; background:rgba(74, 222, 128, 0.05); padding:1rem; border-radius:12px; border:1px dashed rgba(74, 222, 128, 0.3); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.4rem; line-height:1.4;">
                                        <div style="display:flex; align-items:center; gap:.6rem; font-weight:800; letter-spacing:.02em;">
                                            <span style="display:inline-block; width:8px; height:8px; background:#4ade80; border-radius:50%; box-shadow:0 0 10px rgba(74, 222, 128, 0.5); animation:pulse-green 1.5s infinite;"></span>
                                            ĐANG CHỜ THANH TOÁN...
                                        </div>
                                        <div style="opacity:.7; font-weight:500;">Hệ thống xác nhận tự động ngay sau khi nhận tiền.</div>
                                    </div>
                                    
                                    <style>
                                    @keyframes pulse-green {
                                        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16,185,129, 0.7); }
                                        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16,185,129, 0); }
                                        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16,185,129, 0); }
                                    }
                                    </style>
                                </div>
                            @elseif(in_array($dat_tour->phuong_thuc_thanh_toan, ['chuyen_khoan', 'momo', 'zalopay']) && ($dat_tour->thanhToan->trang_thai === 'thanh_cong'))

                                {{-- Đã thanh toán rồi: hiện checkmark --}}
                                <div style="margin-top:1.25rem; border-top:1px dashed rgba(255,255,255,0.1); padding-top:1.25rem; text-align:center;">
                                    <div style="background:rgba(74, 222, 128, 0.05); border:2px solid rgba(74, 222, 128, 0.2); border-radius:16px; padding:2rem 1rem; backdrop-filter:blur(10px);">
                                        <div style="width:60px; height:60px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; color:#059669; font-size:1.8rem; border:3px solid #4ade80; box-shadow: 0 0 20px rgba(74, 222, 128, 0.2);">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div style="font-family:var(--font-heading); font-weight:800; color:#fff; font-size:1.15rem; margin-bottom:.35rem;">Thanh toán thành công</div>
                                        <div style="font-size:.82rem; color:rgba(255,255,255,0.5);">Mã giao dịch: {{ $dat_tour->thanhToan->ma_giao_dich ?? '—' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @else
                            {{-- TH không có record thanh_toans, fallback --}}
                            <div style="margin-top:1.25rem;background:#f8fafc;border-radius:12px;padding:1rem;border:1px solid #e2e8f0;">
                                <div style="font-size:.75rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.6rem;">Trạng thái thanh toán</div>
                                <span style="font-weight:800;color:#f59e0b;font-size:.9rem;">🕐 Chờ xử lý</span>
                            </div>
                        @endif

                        <div style="margin-top:1.25rem; padding:1.25rem; background:rgba(255,255,255,0.03); border-radius:12px; border:1px solid rgba(255,255,255,0.08);">
                            <div style="font-size:.8rem; color:rgba(255,255,255,0.5); line-height:1.6;">
                                <strong style="color:#fff; display:block; margin-bottom:.35rem;">Hỗ trợ khách hàng:</strong>
                                📞 <a href="tel:19001800" style="color:var(--primary-accent); font-weight:800; text-decoration:none;">1900 1800</a> (8h - 20h)<br>
                                📧 <a href="mailto:hotro@vietgo.vn" style="color:rgba(255,255,255,0.7); text-decoration:none;">hotro@vietgo.vn</a>
                            </div>
                        </div>

                        @php
                            $canPrintInvoice = in_array($dat_tour->trang_thai, ['da_xac_nhan', 'hoan_thanh', 'done'])
                                || optional($dat_tour->thanhToan)->trang_thai === 'thanh_cong';
                        @endphp
                        @if($canPrintInvoice)
                        <a href="{{ route('khach-hang.dat-tour.hoa-don', $dat_tour) }}" target="_blank"
                           style="display:flex; align-items:center; justify-content:center; gap:.6rem; margin-top:1.25rem; padding:1rem; background:linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); border:1px solid rgba(245,158,11,0.3); border-radius:14px; color:#f59e0b; font-weight:800; font-size:.9rem; text-decoration:none; transition:all .3s; backdrop-filter:blur(10px);"
                           onmouseover="this.style.background='rgba(245,158,11,0.25)';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(245,158,11,0.2)'"
                           onmouseout="this.style.background='linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05))';this.style.transform='none';this.style.boxShadow='none'">
                            <i class="fa-solid fa-file-invoice" style="font-size:1.1rem;"></i> In Hóa Đơn
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Banner thành công ảo (chờ JS hiển thị) --}}
<div id="payment-success-banner" style="display:none; text-align:center; padding:3rem 1rem; background:white; border-radius:24px; box-shadow:0 10px 40px rgba(16,185,129,.15); margin: 3rem auto; max-width:600px; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
    <div style="width:80px;height:80px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:#10b981;font-size:2.5rem;border:4px solid #d1fae5;">
        <i class="fa-solid fa-check"></i>
    </div>
    <h2 style="font-family:var(--font-heading);font-weight:900;font-size:1.8rem;color:#064e3b;margin-bottom:.5rem;">Thanh toán thành công!</h2>
    <p style="font-size:1.1rem;color:#64748b;margin-bottom:2rem;line-height:1.6;">Hệ thống đã nhận đủ tiền và tự động xác nhận mã đơn <strong style="color:#0f172a;">{{ $dat_tour->ma_dat_tour }}</strong>. Chúc bạn có một chuyến đi tuyệt vời!</p>
    <div>
        <a href="{{ route('khach-hang.dat-tour.lich-su') }}" class="btn btn-primary" style="padding:1rem 2rem;border-radius:12px;font-size:1.05rem;box-shadow:0 4px 15px rgba(52,211,153,.3);">
            <i class="fa-solid fa-calendar-check"></i> Xem Lịch Khởi Hành
        </a>
    </div>
</div>

<style>
@keyframes slideUp { from { opacity:0; transform:translateY(30px) scale(0.95); } to { opacity:1; transform:translateY(0) scale(1); } }
@keyframes popOut { from { transform:scale(1); opacity:1; } to { transform:scale(0.8); opacity:0; display:none; } }
</style>

@if(in_array($dat_tour->phuong_thuc_thanh_toan, ['chuyen_khoan', 'vnpay', 'momo', 'zalopay']) && optional($dat_tour->thanhToan)->trang_thai === 'cho_xu_ly' && !in_array($dat_tour->trang_thai, ['hoan_thanh', 'done']))
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ★ QUAN TRỌNG: Dùng relative URL để tránh Mixed Content (HTTP trên trang HTTPS)
    // url() helper tạo http://... nhưng InfinityFree phục vụ HTTPS → browser chặn fetch!
    const POLL_URL = '/hook/payment/status/{{ $dat_tour->ma_dat_tour }}';
    const MAX_POLLS = 200;  // Tối đa ~10 phút polling
    let pollCount = 0;
    let checkInterval = null;
    let isProcessing = false;

    const statusEl = document.querySelector('#qr-payment-block .pulse-green')?.closest('div') || 
                     document.querySelector('[style*="ĐANG CHỜ THANH TOÁN"]')?.closest('div');

    const checkPayment = () => {
        if (isProcessing) return;
        pollCount++;

        // Log để debug (xem trong Console DevTools)
        if (pollCount <= 3 || pollCount % 10 === 0) {
            console.log(`[VietGo] Polling #${pollCount}: ${POLL_URL}`);
        }

        // Dừng polling sau MAX_POLLS lần
        if (pollCount > MAX_POLLS) {
            if (checkInterval) clearInterval(checkInterval);
            console.warn('[VietGo] Polling timeout - dừng kiểm tra tự động');
            return;
        }

        fetch(POLL_URL, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            cache: 'no-store'
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log('[VietGo] Status:', data.thanh_toan_status, '| Tour:', data.tour_status);

            if (data.thanh_toan_status === 'thanh_cong' || data.tour_status === 'da_duyet' || data.tour_status === 'hoan_thanh') {
                isProcessing = true;
                if (checkInterval) clearInterval(checkInterval);
                
                const orderPage = document.getElementById('orderPageContainer');
                const successBanner = document.getElementById('payment-success-banner');
                
                if (orderPage && successBanner) {
                    // Hiệu ứng pháo hoa (Confetti)
                    if (typeof confetti === 'function') {
                        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#34d399', '#10b981', '#059669', '#ffffff'] });
                    }

                    orderPage.style.transition = "all 0.6s cubic-bezier(0.4, 0, 0.2, 1)";
                    orderPage.style.opacity = "0";
                    orderPage.style.transform = "scale(0.95)";
                    
                    setTimeout(() => {
                        orderPage.style.display = 'none';
                        successBanner.style.display = 'block';
                        successBanner.style.animation = "slideUp 0.6s ease-out forwards";
                    }, 600);
                } else {
                    // Fallback: reload trang nếu không tìm thấy element
                    window.location.reload();
                }
            }
        })
        .catch(err => {
            console.warn('[VietGo] Polling error:', err.message);
            // Không dừng polling khi lỗi mạng nhất thời — tiếp tục thử
        });
    };

    // Kiểm tra ngay lập tức khi vừa load trang
    setTimeout(checkPayment, 500);

    // Sau đó lặp lại mỗi 3 giây (hợp lý cho InfinityFree)
    checkInterval = setInterval(checkPayment, 3000);
});
</script>
@endif
@if($dat_tour->trang_thai === 'hoan_thanh')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceCoords = {
        "Hà Nội": [21.0285, 105.8542],
        "Hồ Chí Minh": [10.8231, 106.6297],
        "Đà Nẵng": [16.0471, 108.2062],
        "Quảng Ninh": [21.0069, 107.2925],
        "Quảng Nam": [15.5898, 107.9626],
        "Lâm Đồng": [11.9546, 108.4419],
        "Khánh Hòa": [12.2388, 109.1967],
        "Kiên Giang": [10.0135, 105.0809],
        "Lào Cai": [22.4844, 103.9701],
        "Bình Thuận": [11.0877, 108.0694],
        "Ninh Bình": [20.2541, 105.9757],
        "Cần Thơ": [10.0452, 105.7469],
        "Hải Phòng": [20.8561, 106.6822],
        "Thừa Thiên Huế": [16.4674, 107.5905],
        "Đồng Nai": [10.9458, 106.8242],
        "Nghệ An": [18.6656, 105.6791],
        "Thanh Hóa": [19.8058, 105.7725]
    };
    
    let startCoord = provinceCoords["Hà Nội"];
    const userAddress = "{{ $dat_tour->khachHang->dia_chi ?? '' }}";
    for (let province in provinceCoords) {
        if (userAddress.includes(province)) {
            startCoord = provinceCoords[province];
            break;
        }
    }
    
    const destProvince = "{{ $dat_tour->lichKhoiHanh->tour->diemDen->tinh_thanh ?? '' }}";
    let endCoord = startCoord;
    for (let province in provinceCoords) {
        if (destProvince.includes(province) || province.includes(destProvince)) {
            endCoord = provinceCoords[province];
            break;
        }
    }
    
    if (startCoord[0] === endCoord[0] && startCoord[1] === endCoord[1]) {
        endCoord = [endCoord[0] + 0.05, endCoord[1] + 0.05];
    }

    var map = L.map('tourRoutingMap').setView([(startCoord[0]+endCoord[0])/2, (startCoord[1]+endCoord[1])/2], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.Routing.control({
        waypoints: [ L.latLng(startCoord[0], startCoord[1]), L.latLng(endCoord[0], endCoord[1]) ],
        routeWhileDragging: false,
        addWaypoints: false,
        draggableWaypoints: false,
        showAlternatives: false,
        fitSelectedRoutes: true,
        createMarker: function() { return null; }
    }).addTo(map);
    
    L.marker(startCoord, {
        icon: L.divIcon({className: 'custom-div-icon', html: "<div style='background-color:#3b82f6;width:24px;height:24px;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 0 5px rgba(0,0,0,0.5);'><i class='fa-solid fa-location-dot' style='font-size:12px;'></i></div>", iconSize: [24,24]})
    }).addTo(map).bindPopup("<b>Điểm Khởi Hành</b><br>Trụ sở VietGo");
    
    L.marker(endCoord, {
        icon: L.divIcon({className: 'custom-div-icon', html: "<div style='background-color:#10b981;width:24px;height:24px;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 0 5px rgba(0,0,0,0.5);'><i class='fa-solid fa-flag-checkered' style='font-size:12px;'></i></div>", iconSize: [24,24]})
    }).addTo(map).bindPopup("<b>Điểm Đến</b><br>{{ $dat_tour->lichKhoiHanh->tour->diemDen->ten_diem_den ?? 'Đích đến' }}").openPopup();
});
</script>
@endif
@endsection
