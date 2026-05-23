<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KiemTraKhachHang
{
    /**
     * Chỉ cho phép khách hàng đã đăng nhập
     */
    public function handle(Request $request, Closure $next)
    {
        \Illuminate\Support\Facades\Auth::shouldUse('khach_hang');
        $nguoi_dung = auth()->user();

        if (!$nguoi_dung) {
            return redirect()->route('dang-nhap')
                ->with('loi', 'Vui lòng đăng nhập để tiếp tục.');
        }

        if (!$nguoi_dung->laKhachHang()) {
            return redirect()->route('trang-chu')
                ->with('loi', 'Tài khoản của bạn không phải khách hàng.');
        }

        if (!$nguoi_dung->trang_thai) {
            auth()->logout();
            return redirect()->route('dang-nhap')
                ->with('loi', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ.');
        }

        return $next($request);
    }
}
