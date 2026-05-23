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

    public function thongKe(Request $request)
    {
        if (!auth()->user()->laAdmin()) {
            return redirect()->route('quan-tri.dashboard')->with('loi', 'Bạn không có quyền truy cập Báo Cáo Thống Kê.');
        }

        $type = $request->input('type', 'thang');
        
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        $label = '';

        switch($type) {
            case 'ngay':
                $date = $request->input('date', now()->format('Y-m-d'));
                $startDate = Carbon::parse($date)->startOfDay();
                $endDate = Carbon::parse($date)->endOfDay();
                $label = 'Ngày ' . $startDate->format('d/m/Y');
                break;
            case 'tuan':
                $week = $request->input('week', now()->format('Y-\WW'));
                $startDate = Carbon::parse($week)->startOfWeek();
                $endDate = Carbon::parse($week)->endOfWeek();
                $label = 'Tuần ' . $startDate->format('W') . ' (' . $startDate->format('d/m') . ' - ' . $endDate->format('d/m/Y') . ')';
                break;
            case 'thang':
                $month = $request->input('month', now()->format('Y-m'));
                $startDate = Carbon::parse($month)->startOfMonth();
                $endDate = Carbon::parse($month)->endOfMonth();
                $label = 'Tháng ' . $startDate->format('m/Y');
                break;
            case 'quy':
                $quarter = $request->input('quarter', now()->quarter);
                $year = $request->input('year', now()->year);
                $startDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfQuarter();
                $endDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->endOfQuarter();
                $label = 'Quý ' . $quarter . '/' . $year;
                break;
            case 'nam':
                $year = $request->input('year', now()->year);
                $startDate = Carbon::create($year, 1, 1)->startOfYear();
                $endDate = Carbon::create($year, 1, 1)->endOfYear();
                $label = 'Năm ' . $year;
                break;
        }

        // 1. Thống kê theo thời gian (dựa trên filter)
        $query = DatTour::whereIn('trang_thai', ['hoan_thanh', 'done'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $doanh_thu_thang = DatTour::whereIn('trang_thai', ['hoan_thanh', 'done'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('tong_tien_thanh_toan');

        $doanh_thu_quy = DatTour::whereIn('trang_thai', ['hoan_thanh', 'done'])
            ->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()])
            ->sum('tong_tien_thanh_toan');

        $doanh_thu_loc = $query->sum('tong_tien_thanh_toan');

        $status_query = DatTour::whereBetween('created_at', [$startDate, $endDate]);

        $thong_ke = [
            'doanh_thu_thang' => $doanh_thu_thang,
            'doanh_thu_quy'   => $doanh_thu_quy,
            'doanh_thu_loc'   => $doanh_thu_loc,
            'tong_dat_tour'   => $status_query->count(),
            'cho_duyet'       => (clone $status_query)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet'        => (clone $status_query)->where('trang_thai', 'da_duyet')->count(),
            'hoan_thanh'      => (clone $status_query)->whereIn('trang_thai', ['hoan_thanh', 'done'])->count(),
            'da_huy'          => (clone $status_query)->where('trang_thai', 'da_huy')->count(),
        ];

        // 2. Thống kê theo Miền (áp dụng filter)
        $doanh_thu_mien = \DB::table('dat_tour')
            ->join('lich_khoi_hanh', 'dat_tour.lich_khoi_hanh_id', '=', 'lich_khoi_hanh.id')
            ->join('tour', 'lich_khoi_hanh.tour_id', '=', 'tour.id')
            ->join('diem_den', 'tour.diem_den_id', '=', 'diem_den.id')
            ->whereIn('dat_tour.trang_thai', ['hoan_thanh', 'done'])
            ->whereBetween('dat_tour.created_at', [$startDate, $endDate])
            ->select('diem_den.vung_mien', \DB::raw('SUM(dat_tour.tong_tien_thanh_toan) as total_revenue'))
            ->groupBy('diem_den.vung_mien')
            ->orderByDesc('total_revenue')
            ->get();

        // 3. Tour doanh thu theo từng miền
        $tours_by_mien = [];
        foreach (['mien_bac', 'mien_trung', 'mien_nam'] as $vung) {
            $tours_query = Tour::join('diem_den', 'tour.diem_den_id', '=', 'diem_den.id')
                ->where('diem_den.vung_mien', $vung)
                ->select('tour.*'); // select only tour columns to prevent id overriding
                
            $tours_by_mien[$vung] = $tours_query->withSum(['datTour as tong_doanh_thu' => function($q) use ($startDate, $endDate) {
                    $q->whereIn('dat_tour.trang_thai', ['hoan_thanh', 'done'])
                      ->whereBetween('dat_tour.created_at', [$startDate, $endDate]);
                }], 'tong_tien_thanh_toan')
                ->orderByDesc('tong_doanh_thu')
                ->get()
                ->filter(function($t) { return $t->tong_doanh_thu > 0; })
                ->values();
        }

        // Dữ liệu biểu đồ (tối ưu truy vấn)
        $dat_tour_data = DatTour::whereIn('trang_thai', ['hoan_thanh', 'done'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'tong_tien_thanh_toan']);

        $doanh_thu_bieu_do = [];
        if ($type == 'ngay') {
            for ($h = 0; $h <= 23; $h++) {
                $doanh_thu_bieu_do[] = [
                    'label' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
                    'doanh_thu' => $dat_tour_data->filter(function($item) use ($h) {
                        return $item->created_at->hour == $h;
                    })->sum('tong_tien_thanh_toan'),
                ];
            }
        } elseif ($type == 'tuan') {
            for ($d = 0; $d <= 6; $d++) {
                $day = $startDate->copy()->addDays($d);
                $doanh_thu_bieu_do[] = [
                    'label' => $day->format('d/m'),
                    'doanh_thu' => $dat_tour_data->filter(function($item) use ($day) {
                        return $item->created_at->isSameDay($day);
                    })->sum('tong_tien_thanh_toan'),
                ];
            }
        } elseif ($type == 'thang') {
            for ($d = 1; $d <= $startDate->daysInMonth; $d++) {
                $doanh_thu_bieu_do[] = [
                    'label' => $d . '/' . $startDate->format('m'),
                    'doanh_thu' => $dat_tour_data->filter(function($item) use ($d) {
                        return $item->created_at->day == $d;
                    })->sum('tong_tien_thanh_toan'),
                ];
            }
        } elseif ($type == 'quy') {
            for ($m = 0; $m < 3; $m++) {
                $month = $startDate->copy()->addMonths($m);
                $doanh_thu_bieu_do[] = [
                    'label' => 'Tháng ' . $month->format('m'),
                    'doanh_thu' => $dat_tour_data->filter(function($item) use ($month) {
                        return $item->created_at->month == $month->month;
                    })->sum('tong_tien_thanh_toan'),
                ];
            }
        } else { // nam
            for ($m = 1; $m <= 12; $m++) {
                $doanh_thu_bieu_do[] = [
                    'label' => "Tháng $m",
                    'doanh_thu' => $dat_tour_data->filter(function($item) use ($m) {
                        return $item->created_at->month == $m;
                    })->sum('tong_tien_thanh_toan'),
                ];
            }
        }

        return view('quan-tri.thong-ke', compact(
            'thong_ke', 
            'doanh_thu_bieu_do', 
            'doanh_thu_mien', 
            'tours_by_mien',
            'type',
            'label'
        ));
    }
}
