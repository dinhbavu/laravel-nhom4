<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatTour;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * ═══════════════════════════════════════════════════
     * WEBHOOK CHÍNH: Nhận thông báo giao dịch từ SePay.vn
     * ═══════════════════════════════════════════════════
     * 
     * SePay sẽ gửi POST JSON mỗi khi tài khoản MB Bank nhận tiền.
     * Format JSON chuẩn SePay:
     * {
     *   "gateway": "MB",
     *   "content": "DT2604120001 THANH TOAN TOUR",
     *   "transferAmount": 10000,
     *   "referenceCode": "FT24194...",
     *   ...
     * }
     */
    public function receivePayment(Request $request)
    {
        Log::info("=== SEPAY WEBHOOK INCOMING ===", $request->all());

        // 1. Nếu là SePay Gateway/Checkout (Dùng IPN cấu hình trong Mã đơn vị)
        if ($request->has('orderInvoiceNumber') || $request->has('order_invoice_number')) {
            $content = $request->input('orderInvoiceNumber') ?? $request->input('order_invoice_number');
            $amountIn = $request->input('orderAmount') ?? $request->input('amount') ?? 0;
            $referenceCode = $request->input('transactionId') ?? $request->input('referenceNumber') ?? '';
        }
        // 2. Nếu là SePay Webhook Biến động số dư (Dùng Webhook quét nội dung)
        elseif ($request->has('content') || $request->has('transferAmount')) {
            $content = $request->input('content', '');
            $amountIn = $request->input('transferAmount', 0);
            $referenceCode = $request->input('referenceCode', '');
        }
        // 3. Fallback Casso / Khác
        elseif ($request->has('data') && is_array($request->input('data'))) {
            $transactions = $request->input('data', []);
            if (!empty($transactions)) {
                $tx = $transactions[0];
                $content = $tx['description'] ?? '';
                $amountIn = $tx['amount'] ?? 0;
                $referenceCode = $tx['tid'] ?? '';
            }
        }
        else {
            $content = $request->input('transactionContent') ?? $request->input('description') ?? '';
            $amountIn = $request->input('amountIn') ?? $request->input('amount') ?? 0;
        }

        $content = strtoupper(trim((string) $content));
        $amountIn = (float) $amountIn;

        Log::info("Webhook Parsed: [Code: {$content}] [Amount: {$amountIn}] [Ref: {$referenceCode}]");

        // ── Tìm mã đặt tour (DTxxxxxx) ──
        if (preg_match('/DT\d{6,}/', $content, $matches)) {
            $ma_dat_tour = $matches[0];
            Log::info("Đang xử lý đơn: {$ma_dat_tour}");

            $dat_tour = DatTour::with('thanhToan')
                ->where('ma_dat_tour', $ma_dat_tour)
                ->first();

            if (!$dat_tour) {
                Log::warning("⚠️ Webhook: Không tìm thấy đơn {$ma_dat_tour}");
                return response()->json(['success' => false, 'message' => "Đơn {$ma_dat_tour} không tồn tại"]);
            }

            // Cho phép xử lý cả đơn chờ lần đầu (cho_xu_ly) và đơn đã cọc chờ trả nốt (da_dat_coc)
            if (!$dat_tour->thanhToan || !in_array($dat_tour->thanhToan->trang_thai, ['cho_xu_ly', 'da_dat_coc'])) {
                Log::info("ℹ️ Đơn {$ma_dat_tour} đã được xử lý hoặc không hợp lệ (status: " . ($dat_tour->thanhToan->trang_thai ?? 'null') . ')');
                return response()->json(['success' => true, 'message' => 'Đã xử lý trước đó']);
            }

            // ── Xử lý thanh toán (Hỗ trợ đặt cọc & thanh toán đủ) ──
            if ($amountIn > 0) {
                \Illuminate\Support\Facades\DB::beginTransaction();
                try {
                    // 1. Cập nhật số tiền đã thanh toán
                    $dat_tour->increment('tong_tien_da_thanh_toan', $amountIn);
                    $dat_tour->refresh();

                    $thanh_toan_status = $dat_tour->isFullyPaid() ? 'thanh_cong' : 'da_dat_coc';

                    // 2. Cập nhật Thanh Toán
                    $dat_tour->thanhToan->update([
                        'trang_thai'   => $thanh_toan_status,
                        'ma_giao_dich' => $referenceCode ?: ('MB_' . now()->format('YmdHis')),
                        'ghi_chu'      => 'Tự động qua MB Bank. Nhận ' . number_format($amountIn) . 'đ. Tổng đã trả: ' . number_format($dat_tour->tong_tien_da_thanh_toan) . 'đ.',
                    ]);

                    // 3. Cập nhật trạng thái Đơn Tour theo luồng:
                    //    - Chuyển sang 'hoan_thanh' (khởi hành) nếu thanh toán đủ 100%, hoặc 'da_duyet' nếu chỉ thanh toán 30%
                    $target_status = $dat_tour->isFullyPaid() ? 'hoan_thanh' : 'da_duyet';
                    if (in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet', 'da_xac_nhan'])) {
                        $dat_tour->update(['trang_thai' => $target_status]);
                    }

                    // 4. Thông báo cho khách
                    $isFull = $dat_tour->isFullyPaid();
                    ThongBao::create([
                        'nguoi_nhan_id' => $dat_tour->khach_hang_id,
                        'tieu_de'       => $isFull ? '✅ Thanh toán MB Bank thành công!' : '💸 Đặt cọc MB Bank thành công!',
                        'noi_dung'      => $isFull 
                            ? 'Hệ thống đã nhận đủ tiền cho đơn ' . $ma_dat_tour . '. Đơn tour đã được XÁC NHẬN. Chúc bạn có chuyến đi vui vẻ!'
                            : 'Hệ thống đã nhận ' . number_format($amountIn) . 'đ cho đơn ' . $ma_dat_tour . '. Đơn đã được ghi nhận đặt cọc.',
                        'loai'          => 'dat_tour',
                        'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
                    ]);

                    \Illuminate\Support\Facades\DB::commit();
                    Log::info("✅ Webhook: Xử lý {$ma_dat_tour} thành công. Loại: " . ($isFull ? "Đủ" : "Cọc"));
                    return response()->json(['success' => true, 'message' => "Xử lý {$ma_dat_tour} thành công"]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    Log::error("❌ Lỗi xử lý Webhook: " . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Lỗi server'], 500);
                }
            } else {
                Log::warning("⚠️ Webhook: Số tiền nhận được không hợp lệ: {$amountIn}");
                return response()->json(['success' => false, 'message' => 'Số tiền không hợp lệ']);
            }
        }

        Log::info("Webhook: Không tìm thấy mã đơn DT trong nội dung: {$content}");
        return response()->json(['success' => false, 'message' => 'Không tìm thấy mã đơn hàng']);
    }

    /**
     * API GIẢ LẬP: Dùng để test khi chạy localhost
     * Gọi: GET /hook/payment/test-webhook/{ma_dat_tour}
     */
    public function simulatePayment(Request $request, $ma_dat_tour)
    {
        $ma_dat_tour = strtoupper($ma_dat_tour);
        $dat_tour = DatTour::where('ma_dat_tour', $ma_dat_tour)->firstOrFail();

        // Giả lập payload SePay với tài khoản MB Bank
        $mockRequest = new Request([
            'content'        => $ma_dat_tour . ' THANH TOAN TOUR',
            'transferAmount' => $dat_tour->tong_tien_thanh_toan - $dat_tour->tong_tien_da_thanh_toan,
            'referenceCode'  => 'SIM_MB_' . now()->format('YmdHis'),
            'gateway'        => 'MB',
            'transferType'   => 'in',
        ]);

        Log::info("--- STARTING MB BANK SIMULATION FOR {$ma_dat_tour} ---");
        return $this->receivePayment($mockRequest);
    }

    /**
     * AJAX POLLING: Frontend check status mỗi 5s
     */
    public function checkStatus($ma_dat_tour)
    {
        $dt = DatTour::with('thanhToan')
            ->where('ma_dat_tour', strtoupper($ma_dat_tour))
            ->first();

        // ── AUTO PULL (Bypass webhook block trên Hosting) ──
        // Quét SePay khi đơn chờ thanh toán lần đầu (cho_xu_ly) HOẶC chờ trả nốt 70% (da_dat_coc)
        if ($dt && $dt->thanhToan && in_array($dt->thanhToan->trang_thai, ['cho_xu_ly', 'chua_thanh_toan', 'da_dat_coc']) && !$dt->isFullyPaid()) {
            try {
                $this->pullSePayTransactions($dt);
                $dt->refresh();
                $dt->load('thanhToan');
            } catch (\Exception $e) {
                Log::error('[SePay Pull] Outer exception: ' . $e->getMessage());
            }
        }

        if ($dt && $dt->thanhToan) {
            return response()->json([
                'thanh_toan_status' => $dt->thanhToan->trang_thai,
                'tour_status'       => $dt->trang_thai,
            ]);
        }

        return response()->json(['thanh_toan_status' => 'cho_xu_ly', 'tour_status' => 'cho_duyet']);
    }

    /**
     * Chủ động gọi API SePay để kiểm tra giao dịch
     * Dùng file_get_contents thay Http facade để tránh lỗi SSL trên XAMPP
     */
    private function pullSePayTransactions($dat_tour)
    {
        // Lấy token
        $token = config('services.sepay.api_token') ?: config('services.sepay.secret_key');
        if (!$token) {
            Log::warning('[SePay Pull] Không có API token - bỏ qua polling');
            return;
        }

        $bankAccount = config('services.payment.bank_account', '0363102985');
        $maDon = strtoupper($dat_tour->ma_dat_tour);

        Log::info("[SePay Pull] Bắt đầu quét cho {$maDon} trên TK {$bankAccount}");

        try {
            // Dùng cURL trực tiếp thay vì Http facade để tránh vấn đề SSL
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://my.sepay.vn/userapi/transactions/list?account_number=' . urlencode($bankAccount) . '&limit=20',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            $responseBody = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error("[SePay Pull] cURL Error: {$curlError}");
                return;
            }

            if ($httpCode !== 200) {
                Log::warning("[SePay Pull] API trả HTTP {$httpCode} - Body: " . substr($responseBody, 0, 300));
                return;
            }

            $data = json_decode($responseBody, true);
            if (!$data || !isset($data['transactions'])) {
                Log::warning("[SePay Pull] Response không hợp lệ: " . substr($responseBody, 0, 300));
                return;
            }

            $transactions = $data['transactions'];
            Log::info("[SePay Pull] Nhận được " . count($transactions) . " giao dịch");

            foreach ($transactions as $tx) {
                $content = strtoupper(trim($tx['transaction_content'] ?? ''));
                $amountIn = floatval($tx['amount_in'] ?? 0);
                $refCode = $tx['reference_number'] ?? '';

                // Kiểm tra nội dung CK có chứa mã đơn tour không
                if (str_contains($content, $maDon) && $amountIn > 0) {
                    // Chống trùng: kiểm tra ref code đã xử lý chưa
                    $existingRef = $dat_tour->thanhToan->ma_giao_dich ?? '';
                    if ($refCode && $existingRef === $refCode) {
                        Log::info("[SePay Pull] Bỏ qua GD đã xử lý: {$refCode}");
                        continue;
                    }

                    Log::info("[SePay Pull] ✅ Tìm thấy GD khớp: {$content} | Số tiền: {$amountIn} | Ref: {$refCode}");

                    // Xử lý thanh toán trực tiếp
                    \Illuminate\Support\Facades\DB::beginTransaction();
                    try {
                        $dat_tour->increment('tong_tien_da_thanh_toan', $amountIn);
                        $dat_tour->refresh();

                        $thanh_toan_status = $dat_tour->isFullyPaid() ? 'thanh_cong' : 'da_dat_coc';

                        $dat_tour->thanhToan->update([
                            'trang_thai'   => $thanh_toan_status,
                            'ma_giao_dich' => $refCode ?: ('MB_' . now()->format('YmdHis')),
                            'ghi_chu'      => 'Tự động qua SePay API. Nhận ' . number_format($amountIn) . 'đ. Tổng đã trả: ' . number_format($dat_tour->tong_tien_da_thanh_toan) . 'đ.',
                        ]);

                        // Chuyển trạng thái đơn tour sang 'hoan_thanh' (khởi hành) nếu thanh toán đủ 100%, hoặc 'da_duyet' nếu chỉ đặt cọc
                        $target_status = $dat_tour->isFullyPaid() ? 'hoan_thanh' : 'da_duyet';
                        if (in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet', 'da_xac_nhan'])) {
                            $dat_tour->update(['trang_thai' => $target_status]);
                        }

                        // Thông báo
                        $isFull = $dat_tour->isFullyPaid();
                        \App\Models\ThongBao::create([
                            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
                            'tieu_de'       => $isFull ? '✅ Thanh toán đủ 100% thành công!' : '💸 Đặt cọc 30% thành công!',
                            'noi_dung'      => $isFull
                                ? 'Đơn ' . $maDon . ' đã thanh toán đủ. Tour đã sẵn sàng khởi hành!'
                                : 'Đơn ' . $maDon . ' đã đặt cọc ' . number_format($amountIn) . 'đ. Tour sẵn sàng khởi hành!',
                            'loai'          => 'dat_tour',
                            'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
                        ]);

                        \Illuminate\Support\Facades\DB::commit();
                        Log::info("[SePay Pull] ✅ Xử lý {$maDon} thành công! TT: {$thanh_toan_status} | Tour: " . $dat_tour->trang_thai);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\DB::rollBack();
                        Log::error("[SePay Pull] DB Error: " . $e->getMessage());
                    }
                    return;
                }
            }

            Log::info("[SePay Pull] Không tìm thấy GD chứa mã {$maDon} trong {$bankAccount}");
        } catch (\Exception $e) {
            Log::error('[SePay Pull] Exception: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        }
    }
}
