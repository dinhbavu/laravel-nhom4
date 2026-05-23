<?php

namespace App\Http\Controllers;

use App\Models\CamNang;
use Illuminate\Http\Request;

class CamNangController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = CamNang::where('trang_thai', 1);

        if ($request->chuyen_muc) {
            $query->where('chuyen_muc', $request->chuyen_muc);
        }

        $danh_sach = $query->orderByDesc('id')->paginate(12);
        
        // Bài viết xem nhiều
        $tin_hot = CamNang::where('trang_thai', 1)->orderByDesc('luot_xem')->limit(5)->get();

        return view('khach-hang.cam-nang.danh-sach', compact('danh_sach', 'tin_hot'));
    }

    public function chiTiet($slug)
    {
        $bai_viet = CamNang::where('slug', $slug)->where('trang_thai', 1)->firstOrFail();
        
        // Tăng lượt xem
        $bai_viet->increment('luot_xem');

        // Tin liên quan cùng chuyên mục
        $tin_lien_quan = CamNang::where('trang_thai', 1)
            ->where('chuyen_muc', $bai_viet->chuyen_muc)
            ->where('id', '!=', $bai_viet->id)
            ->limit(3)
            ->get();

        return view('khach-hang.cam-nang.chi-tiet', compact('bai_viet', 'tin_lien_quan'));
    }
}
