<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\DiemDen;
use Illuminate\Http\Request;

class TrangChuController extends Controller
{
    public function index()
    {
        $tour_noi_bat = Tour::with('diemDen')
            ->hoatDong()
            ->noiBat()
            ->withCount('danhGia')
            ->orderByDesc('luot_xem')
            ->limit(6)
            ->get();

        $tour_moi = Tour::with('diemDen')
            ->hoatDong()
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $diem_den_pho_bien = DiemDen::where('trang_thai', true)
            ->withCount(['tour' => fn($q) => $q->hoatDong()])
            ->orderByDesc('tour_count')
            ->limit(6)
            ->get();

        $thong_ke = [
            'tong_tour'     => Tour::hoatDong()->count(),
            'tong_diem_den' => DiemDen::where('trang_thai', true)->count(),
            'tong_khach'    => \App\Models\NguoiDung::where('vai_tro', 'khach_hang')->count(),
        ];

        return view('khach-hang.trang-chu', compact(
            'tour_noi_bat',
            'tour_moi',
            'diem_den_pho_bien',
            'thong_ke'
        ));
    }
}
