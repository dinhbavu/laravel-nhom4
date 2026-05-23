<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatTour;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Log;
use SePay\SePayClient;
use SePay\Builders\CheckoutBuilder;

class SePayController extends Controller
{
    /**
     * Khởi tạo SePay Client
     */
    private function getClient(): SePayClient
    {
        $env = config('services.sepay.environment', 'sandbox');
        $environment = ($env === 'production') ? 'production' : 'sandbox';

        return new SePayClient(
            config('services.sepay.merchant_id'),
            config('services.sepay.secret_key'),
            $environment
        );
    }

    /**
     * Tạo trang thanh toán SePay cho đơn đặt tour
     * GET /thanh-toan/sepay/{dat_tour}
     */
    public function checkout(DatTour $dat_tour)
    {
        // Chỉ cho phép chủ đơn thanh toán
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        // Chỉ cho phép đơn đang chờ thanh toán
        if (!$dat_tour->thanhToan || $dat_tour->thanhToan->trang_thai !== 'cho_xu_ly') {
            return redirect()->route('khach-hang.dat-tour.chi-tiet', $dat_tour)
                ->with('loi', 'Đơn này không cần thanh toán hoặc đã thanh toán.');
        }

        $sepay = $this->getClient();

        $baseUrl = url('/');
        
        $phuong_thuc = $dat_tour->thanhToan?->phuong_thuc;
        $tong_tien = (int) $dat_tour->tong_tien_thanh_toan;
        $da_tra = (int) $dat_tour->tong_tien_da_thanh_toan;
        
        $amount = $tong_tien;
        if ($phuong_thuc === 'dat_coc' && $da_tra == 0) {
            $amount = (int) ($tong_tien * 0.3);
        } elseif ($da_tra > 0) {
            $amount = $tong_tien - $da_tra;
        }

        $checkoutData = CheckoutBuilder::make()
            ->paymentMethod('BANK_TRANSFER')
            ->currency('VND')
            ->orderInvoiceNumber($dat_tour->ma_dat_tour)
            ->orderAmount($amount)
            ->operation('PURCHASE')
            ->orderDescription('VietGo - Thanh toan tour ' . $dat_tour->ma_dat_tour)
            ->successUrl(route('khach-hang.thanh-toan.thanh-cong', $dat_tour))
            ->errorUrl(route('khach-hang.dat-tour.chi-tiet', $dat_tour) . '?payment=error')
            ->cancelUrl(route('khach-hang.dat-tour.chi-tiet', $dat_tour) . '?payment=cancel')
            ->build();

        $formHtml = $sepay->checkout()->generateFormHtml($checkoutData);

        return view('khach-hang.dat-tour.sepay-checkout', [
            'dat_tour' => $dat_tour,
            'formHtml' => $formHtml,
        ]);
    }

    /**
     * Trang thanh toán thành công (SePay redirect về)
     * GET /thanh-toan/thanh-cong/{dat_tour}
     */
    public function thanhCong(DatTour $dat_tour)
    {
        return view('khach-hang.dat-tour.thanh-toan-thanh-cong', [
            'dat_tour' => $dat_tour,
        ]);
    }

    /**
     * IPN Webhook - SePay gọi khi thanh toán thành công
     * POST /hook/sepay/ipn
     */
    public function ipn(Request $request)
    {
        Log::info('=== SEPAY IPN RECEIVED ===', $request->all());

        // Lấy dữ liệu từ SePay IPN
        $orderInvoice = $request->input('orderInvoiceNumber') 
                     ?? $request->input('order_invoice_number')
                     ?? $request->input('orderNumber')
                     ?? '';
        $amount = $request->input('orderAmount') 
               ?? $request->input('order_amount') 
               ?? $request->input('amount') 
               ?? 0;
        $status = $request->input('status') 
               ?? $request->input('transactionStatus') 
               ?? '';
        $transactionId = $request->input('transactionId') 
                      ?? $request->input('transaction_id') 
                      ?? $request->input('referenceNumber')
                      ?? '';

        // Tìm đơn đặt tour
        $ma_dat_tour = strtoupper(trim($orderInvoice));
        $dat_tour = DatTour::with('thanhToan')
            ->where('ma_dat_tour', $ma_dat_tour)
            ->first();

        if (!$dat_tour) {
            Log::warning("SePay IPN: Không tìm thấy đơn {$ma_dat_tour}");
            return response()->json(['success' => false, 'message' => 'Đơn không tồn tại'], 200);
        }

        if (!$dat_tour->thanhToan || $dat_tour->thanhToan->trang_thai !== 'cho_xu_ly') {
            Log::info("SePay IPN: Đơn {$ma_dat_tour} đã xử lý trước đó");
            return response()->json(['success' => true, 'message' => 'Đã xử lý'], 200);
        }

        // Xử lý: Cập nhật trạng thái
        $dat_tour->increment('tong_tien_da_thanh_toan', $amount);
        $dat_tour->refresh(); // Refresh to get updated tong_tien_da_thanh_toan

        $thanh_toan_status = $dat_tour->isFullyPaid() ? 'thanh_cong' : 'da_dat_coc';

        $dat_tour->thanhToan->update([
            'trang_thai'   => $thanh_toan_status,
            'ma_giao_dich' => $transactionId ?: ('SEPAY_' . now()->format('YmdHis')),
            'ghi_chu'      => 'Thanh toán qua SePay. Số tiền: ' . number_format($amount) . 'đ. Tổng đã trả: ' . number_format($dat_tour->tong_tien_da_thanh_toan) . 'đ.',
        ]);

        if (in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet', 'da_xac_nhan'])) {
            $status = $dat_tour->isFullyPaid() ? 'hoan_thanh' : 'da_duyet';
            $dat_tour->update(['trang_thai' => $status]);
        }

        // Gửi thông báo
        ThongBao::create([
            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
            'tieu_de'       => '✅ Thanh toán qua SePay thành công!',
            'noi_dung'      => 'Đơn ' . $ma_dat_tour . ' đã thanh toán ' . number_format($amount) . 'đ và được XÁC NHẬN tự động.',
            'loai'          => 'dat_tour',
            'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
        ]);

        Log::info("✅ SePay IPN: Duyệt tự động {$ma_dat_tour} thành công");
        return response()->json(['success' => true], 200);
    }
}
