<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KiemTraVaiTro
{
    /**
     * Kiểm tra vai trò người dùng hiện tại
     */
    public function handle(Request $request, Closure $next, string ...$vai_tro)
    {
        $nguoi_dung = auth()->user();

        if (!$nguoi_dung) {
            return redirect()->route('dang-nhap')->with('loi', 'Bạn cần đăng nhập để tiếp tục.');
        }

        if (!in_array($nguoi_dung->vai_tro, $vai_tro)) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
