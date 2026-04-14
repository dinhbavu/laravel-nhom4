<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\DatTour;
use App\Models\NguoiDung;
use App\Models\DiemDen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $thong_ke = [
            'tong_tour'       => Tour::count(),
            'tong_khach_hang' => NguoiDung::where('vai_tro', 'khach_hang')->count(),
            'tong_dat_tour'   => DatTour::count(),
            'doanh_thu_thang' => DatTour::where('trang_thai', 'hoan_thanh')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('tong_tien_thanh_toan'),
            'cho_duyet'       => DatTour::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet'        => DatTour::where('trang_thai', 'da_duyet')->count(),
        ];

        // Biểu đồ doanh thu 6 tháng gần nhất
        $doanh_thu_6_thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $thang = now()->subMonths($i);
            $doanh_thu_6_thang[] = [
                'thang'     => $thang->format('m/Y'),
                'doanh_thu' => DatTour::where('trang_thai', 'hoan_thanh')
                    ->whereMonth('created_at', $thang->month)
                    ->whereYear('created_at', $thang->year)
                    ->sum('tong_tien_thanh_toan'),
            ];
        }

        // Đặt tour gần đây
        $dat_tour_gan_day = DatTour::with(['khachHang', 'lichKhoiHanh.tour'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Tour nổi bật
        $tour_noi_bat = Tour::with('diemDen')
            ->orderByDesc('luot_xem')
            ->limit(5)
            ->get();

        return view('quan-tri.dashboard', compact(
            'thong_ke',
            'doanh_thu_6_thang',
            'dat_tour_gan_day',
            'tour_noi_bat'
        ));
    }

    public function thongKe()
    {
        $thong_ke = [
            'doanh_thu_nam' => DatTour::where('trang_thai', 'hoan_thanh')
                ->whereYear('created_at', now()->year)->sum('tong_tien_thanh_toan'),
            'tong_dat_tour' => DatTour::count(),
            'tong_khach'    => NguoiDung::where('vai_tro', 'khach_hang')->count(),
            'tong_tour'     => Tour::count(),
            'cho_duyet'     => DatTour::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet'      => DatTour::where('trang_thai', 'da_duyet')->count(),
            'hoan_thanh'    => DatTour::where('trang_thai', 'hoan_thanh')->count(),
            'da_huy'        => DatTour::where('trang_thai', 'da_huy')->count(),
        ];

        // Revenue last 6 months
        $doanh_thu_6_thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $thang = now()->subMonths($i);
            $doanh_thu_6_thang[] = [
                'thang'     => $thang->format('m/Y'),
                'doanh_thu' => DatTour::where('trang_thai', 'hoan_thanh')
                    ->whereMonth('created_at', $thang->month)
                    ->whereYear('created_at', $thang->year)
                    ->sum('tong_tien_thanh_toan'),
            ];
        }

        // Top 5 tours by revenue
        $top_tour = Tour::with('diemDen')
            ->withCount(['datTour as so_don' => fn($q) => $q->where('dat_tour.trang_thai', 'hoan_thanh')])
            ->withSum(['datTour as tong_doanh_thu' => fn($q) => $q->where('dat_tour.trang_thai', 'hoan_thanh')], 'tong_tien_thanh_toan')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5)->get();

        return view('quan-tri.thong-ke', compact('thong_ke', 'doanh_thu_6_thang', 'top_tour'));
    }
}
