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
        if (auth()->check() && auth()->user()->laKhachHang()) {
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

        Auth::login($nguoi_dung, $request->boolean('ghi_nho'));

        // Lưu lịch sử đăng nhập (bọc try-catch để không crash nếu bảng chưa tạo)
        try {
            \App\Models\LichSuDangNhap::create([
                'khach_hang_id' => $nguoi_dung->id,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->header('User-Agent'),
            ]);
        } catch (\Exception $e) {
            // Không crash nếu bảng chưa tồn tại trên hosting
        }

        $response = redirect()->intended(route('trang-chu'))
            ->with('thanh_cong', 'Chào mừng ' . $nguoi_dung->ho_ten . ' quay trở lại!');

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
        if (auth()->check() && auth()->user()->laKhachHang()) {
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
        ]);

        Auth::login($nguoi_dung);

        return redirect()->route('trang-chu')
            ->with('thanh_cong', 'Đăng ký thành công! Chào mừng ' . $nguoi_dung->ho_ten . '!');
    }

    // Đăng xuất
    public function dangXuat(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dang-nhap')->with('thanh_cong', 'Bạn đã đăng xuất thành công.');
    }
}
