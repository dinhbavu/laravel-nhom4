<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KiemTraQuanTri
{
    /**
     * Chỉ cho phép admin hoặc nhân viên truy cập khu vực quản trị
     */
    public function handle(Request $request, Closure $next)
    {
        $nguoi_dung = auth()->user();

        if (!$nguoi_dung) {
            return redirect()->route('quan-tri.dang-nhap')
                ->with('loi', 'Vui lòng đăng nhập để truy cập khu vực quản trị.');
        }

        if (!$nguoi_dung->laQuanTri()) {
            auth()->logout();
            return redirect()->route('quan-tri.dang-nhap')
                ->with('loi', 'Bạn không có quyền truy cập khu vực quản trị.');
        }

        if (!$nguoi_dung->trang_thai) {
            auth()->logout();
            return redirect()->route('quan-tri.dang-nhap')
                ->with('loi', 'Tài khoản của bạn đã bị khóa.');
        }

        return $next($request);
    }
}
