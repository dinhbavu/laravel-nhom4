<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\DatTour;
use App\Models\ThongBao;
use Illuminate\Http\Request;

class QuanLyDatTourController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = DatTour::with(['khachHang', 'lichKhoiHanh.tour']);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('tu_khoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('ma_dat_tour', 'like', '%' . $request->tu_khoa . '%')
                  ->orWhereHas('khachHang', fn($kh) => $kh->where('ho_ten', 'like', '%' . $request->tu_khoa . '%'));
            });
        }
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        $danh_sach_don = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('quan-tri.dat-tour.danh-sach', compact('danh_sach_don'));
    }

    public function chiTiet(DatTour $dat_tour)
    {
        $dat_tour->load(['khachHang', 'lichKhoiHanh.tour.diemDen', 'hanhKhach', 'thanhToan', 'maKhuyenMai']);
        return view('quan-tri.dat-tour.chi-tiet', compact('dat_tour'));
    }

    public function duyetDatTour(DatTour $dat_tour)
    {
        if ($dat_tour->trang_thai !== 'cho_duyet') {
            return back()->with('loi', 'Đơn đặt tour không ở trạng thái chờ duyệt.');
        }

        $dat_tour->update([
            'trang_thai'    => 'da_duyet',
            'nguoi_duyet_id' => auth()->id(),
        ]);

        ThongBao::create([
            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
            'tieu_de'       => 'Tour đã được xác nhận!',
            'noi_dung'      => 'Đơn đặt tour ' . $dat_tour->ma_dat_tour . ' đã được xác nhận.',
            'loai'          => 'dat_tour',
            'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
        ]);

        return back()->with('thanh_cong', 'Đã duyệt đơn đặt tour!');
    }

    public function tuChoiDatTour(Request $request, DatTour $dat_tour)
    {
        $request->validate(['ly_do_huy' => 'required|string|min:10']);

        $dat_tour->update([
            'trang_thai'    => 'da_huy',
            'ly_do_huy'     => $request->ly_do_huy,
            'nguoi_duyet_id' => auth()->id(),
        ]);

        // Hoàn chỗ
        $dat_tour->lichKhoiHanh->increment('so_cho_con', $dat_tour->so_nguoi_lon + $dat_tour->so_tre_em);

        ThongBao::create([
            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
            'tieu_de'       => 'Đơn đặt tour bị từ chối',
            'noi_dung'      => 'Đơn ' . $dat_tour->ma_dat_tour . ' bị từ chối. Lý do: ' . $request->ly_do_huy,
            'loai'          => 'huy_tour',
        ]);

        return back()->with('thanh_cong', 'Đã từ chối đơn đặt tour!');
    }

    public function hoanThanh(DatTour $dat_tour)
    {
        $dat_tour->update(['trang_thai' => 'hoan_thanh']);
        return back()->with('thanh_cong', 'Đã đánh dấu hoàn thành!');
    }

    public function hoaDon(DatTour $dat_tour)
    {
        $dat_tour->load(['khachHang', 'lichKhoiHanh.tour.diemDen', 'hanhKhach', 'thanhToan']);
        return view('quan-tri.dat-tour.hoa-don', compact('dat_tour'));
    }
}
