<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class DangNhapKhachHangController extends Controller
{
    // Hiển thị trang đăng nhập khách hàng
    public function hienThiForm(Request $request)
    {
        if (Auth::guard('khach_hang')->check() && Auth::guard('khach_hang')->user()->laKhachHang()) {
            return redirect()->route('trang-chu');
        }
        $emailDaLuu = $request->cookie('vietgo_remember_email');
        return view('khach-hang.dang-nhap', compact('emailDaLuu'));
    }

    // Xử lý đăng nhập
    public function dangNhap(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'mat_khau' => 'required|min:6',
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min'      => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $nguoi_dung = NguoiDung::where('email', $request->email)
            ->where('vai_tro', 'khach_hang')
            ->first();

        if (!$nguoi_dung || !Hash::check($request->mat_khau, $nguoi_dung->mat_khau)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->withInput();
        }

        if (!$nguoi_dung->trang_thai) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ.']);
        }

        Auth::guard('khach_hang')->login($nguoi_dung, $request->boolean('ghi_nho'));

        // Lấy IP thực (hỗ trợ cho hosting InfinityFree / Cloudflare)
        $ip = $request->server('HTTP_CF_CONNECTING_IP') ?? 
              $request->server('HTTP_X_FORWARDED_FOR') ?? 
              $request->ip();

        // Xử lý Device ID
        $deviceId = $request->cookie('vietgo_device_id');
        $needsNewDeviceCookie = false;
        if (!$deviceId) {
            $deviceId = (string) \Illuminate\Support\Str::uuid();
            $needsNewDeviceCookie = true;
        }

        // Lưu lịch sử đăng nhập (bọc try-catch để không crash nếu bảng chưa tạo)
        try {
            $history = \App\Models\LichSuDangNhap::create([
                'khach_hang_id' => $nguoi_dung->id,
                'device_id'     => $deviceId,
                'ip_address'    => $ip,
                'user_agent'    => $request->header('User-Agent'),
            ]);
            // Lưu id vào session để frontend có thể xin quyền GPS và cập nhật lại
            session(['login_history_id' => $history->id]);
        } catch (\Exception $e) {
            // Không crash nếu bảng chưa tồn tại trên hosting
        }

        $response = redirect()->intended(route('trang-chu'))
            ->with('thanh_cong', 'Chào mừng ' . $nguoi_dung->ho_ten . ' quay trở lại!');

        // Đính kèm cookie device ID nếu tạo mới (sống 10 năm)
        if ($needsNewDeviceCookie) {
            $response->cookie('vietgo_device_id', $deviceId, 60 * 24 * 365 * 10);
        }

        // Lưu hoặc xóa cookie email theo tùy chọn "Ghi nhớ đăng nhập"
        if ($request->boolean('ghi_nho')) {
            $response->withCookie(Cookie::make('vietgo_remember_email', $request->email, 60 * 24 * 30)); // 30 ngày
        } else {
            $response->withCookie(Cookie::forget('vietgo_remember_email'));
        }

        return $response;
    }

    // Hiển thị trang đăng ký
    public function hienThiDangKy()
    {
        if (Auth::guard('khach_hang')->check() && Auth::guard('khach_hang')->user()->laKhachHang()) {
            return redirect()->route('trang-chu');
        }
        return view('khach-hang.dang-ky');
    }

    // Xử lý đăng ký
    public function dangKy(Request $request)
    {
        $request->validate([
            'ho_ten'           => 'required|string|max:100',
            'email'            => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai'    => 'required|string|max:20|unique:nguoi_dung,so_dien_thoai',
            'mat_khau'         => 'required|min:6|confirmed',
        ], [
            'ho_ten.required'         => 'Vui lòng nhập họ tên.',
            'ho_ten.string'           => 'Họ tên không hợp lệ.',
            'ho_ten.max'              => 'Họ tên không được vượt quá 100 ký tự.',
            'email.required'          => 'Vui lòng nhập email.',
            'email.email'             => 'Email không hợp lệ.',
            'email.unique'            => 'Email này đã được sử dụng bởi tài khoản khác.',
            'so_dien_thoai.required'  => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.string'    => 'Số điện thoại không hợp lệ.',
            'so_dien_thoai.max'       => 'Số điện thoại không vượt quá 20 ký tự.',
            'so_dien_thoai.unique'    => 'Số điện thoại này đã được sử dụng bởi tài khoản khác.',
            'mat_khau.required'       => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min'            => 'Mật khẩu tối thiểu 6 ký tự.',
            'mat_khau.confirmed'      => 'Xác nhận mật khẩu không khớp.',
        ]);

        $nguoi_dung = NguoiDung::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau'      => Hash::make($request->mat_khau),
            'vai_tro'       => 'khach_hang',
            'trang_thai'    => 1,
            'anh_dai_dien'  => 'default-avatar.png',
        ]);

        Auth::guard('khach_hang')->login($nguoi_dung);

        // Lấy IP thực (hỗ trợ cho hosting InfinityFree / Cloudflare)
        $ip = $request->server('HTTP_CF_CONNECTING_IP') ?? 
              $request->server('HTTP_X_FORWARDED_FOR') ?? 
              $request->ip();

        // Xử lý Device ID
        $deviceId = $request->cookie('vietgo_device_id');
        $needsNewDeviceCookie = false;
        if (!$deviceId) {
            $deviceId = (string) \Illuminate\Support\Str::uuid();
            $needsNewDeviceCookie = true;
        }

        // Lưu lịch sử đăng nhập khi đăng ký (vì tự động login)
        try {
            $history = \App\Models\LichSuDangNhap::create([
                'khach_hang_id' => $nguoi_dung->id,
                'device_id'     => $deviceId,
                'ip_address'    => $ip,
                'user_agent'    => $request->header('User-Agent'),
            ]);
            // Lưu id vào session để frontend có thể xin quyền GPS
            session(['login_history_id' => $history->id]);
        } catch (\Exception $e) {
            // Không crash nếu bảng chưa tồn tại
        }

        $response = redirect()->route('trang-chu')
            ->with('thanh_cong', 'Đăng ký thành công! Chào mừng ' . $nguoi_dung->ho_ten . '!');

        if ($needsNewDeviceCookie) {
            $response->cookie('vietgo_device_id', $deviceId, 60 * 24 * 365 * 10);
        }

        return $response;
    }

    // Đăng xuất
    public function dangXuat(Request $request)
    {
        Auth::guard('khach_hang')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dang-nhap')->with('thanh_cong', 'Bạn đã đăng xuất thành công.');
    }

    // Lưu GPS thực tế
    public function luuGps(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'history_id' => 'required|integer|exists:lich_su_dang_nhap,id'
        ]);

        $ls = \App\Models\LichSuDangNhap::find($request->history_id);
        
        // Kiểm tra xem bản ghi này có thuộc về user đang đăng nhập không (bảo mật)
        if ($ls && Auth::guard('khach_hang')->check() && $ls->khach_hang_id == Auth::guard('khach_hang')->id()) {
            $ls->update([
                'latitude' => $request->lat,
                'longitude' => $request->lon
            ]);
            
            // Đã lưu thành công, xóa flag trong session để không hỏi lại
            session()->forget('login_history_id');
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}
