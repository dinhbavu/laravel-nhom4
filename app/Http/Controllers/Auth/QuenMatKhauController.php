<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class QuenMatKhauController extends Controller
{
    /**
     * Bước 1: Hiển thị form nhập Email
     */
    public function hienThiFormEmail()
    {
        return view('khach-hang.quen-mat-khau.nhap-email');
    }

    /**
     * Xử lý gửi OTP tới Email
     */
    public function guiOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $email = $request->email;
        $otp = sprintf("%06d", mt_rand(100000, 999999));

        // Lưu OTP vào Cache cẩn thận, hết hạn sau 5 phút
        Cache::put('otp_reset_' . $email, $otp, now()->addMinutes(5));

        // Gửi qua Google Apps Script để lách giới hạn SMTP của InfinityFree
        $scriptUrl = 'https://script.google.com/macros/s/AKfycbzpsQJv6bb451RUgJHfkSAXfMe2BIIcK-IQfSZjhw4dJywvQtz_oGXSwoGKw1vTCgso/exec';
        
        try {
            $response = Http::withoutVerifying()->post($scriptUrl, [
                'emailAddress' => $email,
                'subject' => 'Mã xác nhận quên mật khẩu - VietGo',
                'body' => "Xin chào,\n\nMã OTP để lấy lại mật khẩu của bạn là: $otp\n\nMã này sẽ hết hạn sau 5 phút.\nNếu bạn không yêu cầu, vui lòng bỏ qua email này.\n\nTrân trọng,\nVietGo"
            ]);            

            return redirect()->route('quen-mat-khau.otp', ['email' => $email])
                ->with('thanh_cong', 'Mã OTP đã được gửi đến email của bạn.');
        } catch (\Exception $e) {
            return back()->with('loi', 'Không thể gửi email lúc này. Hãy thử lại sau!');
        }
    }

    /**
     * Bước 2: Hiển thị form nhập mã OTP
     */
    public function hienThiFormOtp(Request $request)
    {
        $email = $request->query('email');
        if (!$email || !Cache::has('otp_reset_' . $email)) {
            return redirect()->route('quen-mat-khau.email')->with('loi', 'Yêu cầu vượt thời gian hoặc không hợp lệ.');
        }
        return view('khach-hang.quen-mat-khau.nhap-otp', compact('email'));
    }

    /**
     * Xử lý xác nhận OTP
     */
    public function xacNhanOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải có 6 chữ số.',
        ]);

        $email = $request->email;
        $otp = $request->otp;

        $cachedOtp = Cache::get('otp_reset_' . $email);

        if (!$cachedOtp || $cachedOtp != $otp) {
            return back()->with('loi', 'Mã OTP không chính xác hoặc đã hết hạn.');
        }

        // OTP đúng -> Sinh 1 token tạm thời để cho phép đổi pass (15p)
        $token = bin2hex(random_bytes(16));
        Cache::put('reset_token_' . $email, $token, now()->addMinutes(15));
        
        // Xóa OTP khỏi cache
        Cache::forget('otp_reset_' . $email);

        return redirect()->route('quen-mat-khau.dat-lai', ['email' => $email, 'token' => $token]);
    }

    /**
     * Bước 3: Hiển thị form đặt mật khẩu mới
     */
    public function hienThiFormDatLai(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token || Cache::get('reset_token_' . $email) !== $token) {
            return redirect()->route('quen-mat-khau.email')->with('loi', 'Phiên làm việc không hợp lệ hoặc đã quá hạn.');
        }

        return view('khach-hang.quen-mat-khau.dat-lai-mat-khau', compact('email', 'token'));
    }

    /**
     * Xử lý đặt lại mật khẩu
     */
    public function datLaiMatKhau(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'mat_khau' => 'required|min:6|confirmed',
        ], [
            'mat_khau.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $email = $request->email;
        $token = $request->token;

        if (Cache::get('reset_token_' . $email) !== $token) {
            return redirect()->route('quen-mat-khau.email')->with('loi', 'Phiên làm việc đã hết hạn.');
        }

        // Cập nhật mật khẩu
        $nguoi_dung = NguoiDung::where('email', $email)->first();
        if ($nguoi_dung) {
            $nguoi_dung->mat_khau = Hash::make($request->mat_khau);
            $nguoi_dung->save();
        }

        // Xóa token đi
        Cache::forget('reset_token_' . $email);

        // Tự động đăng nhập + ghi nhớ nếu user tick checkbox
        if ($nguoi_dung && $request->boolean('ghi_nho')) {
            Auth::login($nguoi_dung, true);
            return redirect()->route('trang-chu')
                ->with('thanh_cong', 'Mật khẩu đã được thay đổi thành công! Chào mừng ' . $nguoi_dung->ho_ten . '!');
        }

        return redirect()->route('dang-nhap')->with('thanh_cong', 'Mật khẩu của bạn đã được thay đổi thành công! Vui lòng đăng nhập lại.');
    }
}
