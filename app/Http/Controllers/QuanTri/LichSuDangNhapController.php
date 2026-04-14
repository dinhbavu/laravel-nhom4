<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\LichSuDangNhap;
use Illuminate\Http\Request;

class LichSuDangNhapController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = LichSuDangNhap::with('khachHang')->latest();

        if ($request->filled('email')) {
            $query->whereHas('khachHang', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        $danh_sach = $query->paginate(20)->withQueryString();

        return view('quan-tri.lich-su-dang-nhap.index', compact('danh_sach'));
    }
}
