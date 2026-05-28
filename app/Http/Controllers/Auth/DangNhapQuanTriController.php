<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DangNhapQuanTriController extends Controller
{
    // Hiển thị trang đăng nhập quản trị
    public function hienThiForm()
    {
        if (Auth::guard('quan_tri')->check() && Auth::guard('quan_tri')->user()->laQuanTri()) {
            return redirect()->route('quan-tri.dashboard');
        }
        return view('quan-tri.dang-nhap');
    }

    // Xử lý đăng nhập admin/nhân viên
    public function dangNhap(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'mat_khau' => 'required',
        ], [
            'email.required'    => 'Vui lòng nhập tài khoản.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $nguoi_dung = NguoiDung::where('email', $request->email)
            ->whereIn('vai_tro', ['admin', 'nhan_vien'])
            ->first();

        if (!$nguoi_dung || !Hash::check($request->mat_khau, $nguoi_dung->mat_khau)) {
            return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
        }

        if (!$nguoi_dung->trang_thai) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
        }

        Auth::guard('quan_tri')->login($nguoi_dung, $request->boolean('ghi_nho'));
        $request->session()->regenerate();

        return redirect()->intended(route('quan-tri.dashboard'))
            ->with('thanh_cong', 'Đăng nhập thành công! Xin chào, ' . $nguoi_dung->ho_ten);
    }

    // Đăng xuất
    public function dangXuat(Request $request)
    {
        Auth::guard('quan_tri')->logout();
        
        if (!Auth::guard('khach_hang')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            $request->session()->regenerate();
        }
        
        return redirect()->route('quan-tri.dang-nhap')
            ->with('thanh_cong', 'Đăng xuất thành công.');
    }
}
