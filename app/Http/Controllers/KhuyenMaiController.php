<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Tour;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $banners = Banner::where('trang_thai', 1)->orderByDesc('created_at')->get();
        
        $tours_giam_gia = Tour::where('phan_tram_giam_gia', '>', 0)
            ->where('trang_thai', 'hoat_dong')
            ->orderByDesc('phan_tram_giam_gia')
            ->get();

        return view('khach-hang.khuyen-mai', compact('banners', 'tours_giam_gia'));
    }
}
