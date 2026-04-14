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

            if (!$dat_tour->thanhToan || $dat_tour->thanhToan->trang_thai !== 'cho_xu_ly') {
                Log::info("ℹ️ Đơn {$ma_dat_tour} đã được xử lý hoặc không hợp lệ");
                return response()->json(['success' => true, 'message' => 'Đã xử lý trước đó']);
            }

            // ── Kiểm tra số tiền ──
            if ($amountIn >= $dat_tour->tong_tien_thanh_toan) {
                \Illuminate\Support\Facades\DB::beginTransaction();
                try {
                    // 1. Cập nhật Thanh Toán
                    $dat_tour->thanhToan->update([
                        'trang_thai'   => 'thanh_cong',
                        'ma_giao_dich' => $referenceCode ?: ('MB_' . now()->format('YmdHis')),
                        'ghi_chu'      => 'Tự động xác nhận qua MB Bank API. Nhận ' . number_format($amountIn) . 'đ.',
                    ]);

                    // 2. Cập nhật Đơn Tour
                    $dat_tour->update(['trang_thai' => 'da_duyet']);

                    // 3. Thông báo cho khách
                    ThongBao::create([
                        'nguoi_nhan_id' => $dat_tour->khach_hang_id,
                        'tieu_de'       => '✅ Thanh toán MB Bank thành công!',
                        'noi_dung'      => 'Hệ thống đã nhận ' . number_format($amountIn) . 'đ cho đơn ' . $ma_dat_tour . '. Đơn tour đã được XÁC NHẬN tự động. Chúc bạn có chuyến đi vui vẻ!',
                        'loai'          => 'dat_tour',
                        'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
                    ]);

                    \Illuminate\Support\Facades\DB::commit();
                    Log::info("✅ Webhook: Duyệt tự động {$ma_dat_tour} thành công qua MB Bank");
                    return response()->json(['success' => true, 'message' => "Duyệt {$ma_dat_tour} thành công"]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    Log::error("❌ Lỗi xử lý Webhook: " . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Lỗi server'], 500);
                }
            } else {
                Log::warning("⚠️ Số tiền thiếu: nhận {$amountIn}, cần {$dat_tour->tong_tien_thanh_toan}");
                return response()->json(['success' => false, 'message' => 'Số tiền không đủ']);
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
            'transferAmount' => $dat_tour->tong_tien_thanh_toan,
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
        // Nếu đơn đang chờ, chủ động kéo dữ liệu từ SePay API thay vì đợi webhook
        if ($dt && $dt->thanhToan && in_array($dt->thanhToan->trang_thai, ['cho_xu_ly', 'chua_thanh_toan'])) {
            $this->pullSePayTransactions($dt);
            $dt->refresh();
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
     * (Giải quyết vấn đề InfinityFree chặn webhook POST đến)
     * 
     * SePay API docs: https://docs.sepay.vn/api-giao-dich.html
     * Token: Bearer API_TOKEN (tạo tại my.sepay.vn → Cấu hình → API Access)
     * Fallback: dùng SEPAY_SECRET_KEY nếu chưa có API Token riêng
     */
    private function pullSePayTransactions($dat_tour)
    {
        try {
            // Lấy token: ưu tiên SEPAY_API_TOKEN, fallback sang SEPAY_SECRET_KEY
            $token = config('services.sepay.api_token') ?: config('services.sepay.secret_key');
            if (!$token) {
                Log::warning('[SePay Pull] Không có API token - bỏ qua polling');
                return;
            }

            $bankAccount = config('services.payment.bank_account', '0363102985');

            // Gọi SePay API lấy 20 giao dịch gần nhất
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->timeout(8)
                ->get('https://my.sepay.vn/userapi/transactions/list', [
                    'account_number' => $bankAccount,
                    'limit' => 20,
                ]);

            if (!$response->successful()) {
                Log::warning('[SePay Pull] API trả HTTP ' . $response->status() . ' - Body: ' . substr($response->body(), 0, 200));
                return;
            }

            $data = $response->json();

            // SePay API trả: { "status": 200, "transactions": [...] }
            $transactions = $data['transactions'] ?? [];
            if (!is_array($transactions) || empty($transactions)) {
                Log::info('[SePay Pull] Không có giao dịch nào');
                return;
            }

            $maDon = strtoupper($dat_tour->ma_dat_tour);

            foreach ($transactions as $tx) {
                // Theo docs SePay: field là "transaction_content", "amount_in", "reference_number"
                $content = strtoupper(trim($tx['transaction_content'] ?? ''));
                $amountIn = floatval($tx['amount_in'] ?? 0);
                $refCode = $tx['reference_number'] ?? '';

                // Kiểm tra nội dung CK có chứa mã đơn tour không
                if (str_contains($content, $maDon) && $amountIn > 0) {
                    Log::info("[SePay Pull] ✅ Tìm thấy GD khớp: {$content} | Số tiền: {$amountIn} | Ref: {$refCode}");

                    // Tạo giả request webhook để tái sử dụng logic xử lý
                    $mockRequest = new Request([
                        'content'        => $content,
                        'transferAmount' => $amountIn,
                        'referenceCode'  => $refCode,
                        'gateway'        => 'MB',
                        'transferType'   => 'in',
                    ]);

                    $this->receivePayment($mockRequest);
                    return; // Đã tìm thấy, dừng lại
                }
            }

            Log::info("[SePay Pull] Không tìm thấy GD chứa mã {$maDon} trong {$bankAccount}");
        } catch (\Exception $e) {
            Log::error('[SePay Pull] Exception: ' . $e->getMessage());
        }
    }
}
