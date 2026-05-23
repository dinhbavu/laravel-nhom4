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

        // Đếm số lượng theo trạng thái (có áp dụng filter trừ filter chính trạng thái)
        $count_query = DatTour::query();
        if ($request->filled('tu_khoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('ma_dat_tour', 'like', '%' . $request->tu_khoa . '%')
                  ->orWhereHas('khachHang', fn($kh) => $kh->where('ho_ten', 'like', '%' . $request->tu_khoa . '%'));
            });
            $count_query->where(function ($q) use ($request) {
                $q->where('ma_dat_tour', 'like', '%' . $request->tu_khoa . '%')
                  ->orWhereHas('khachHang', fn($kh) => $kh->where('ho_ten', 'like', '%' . $request->tu_khoa . '%'));
            });
        }
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
            $count_query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
            $count_query->whereDate('created_at', '<=', $request->den_ngay);
        }

        $counts = [
            'cho_duyet'  => (clone $count_query)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet'   => (clone $count_query)->where('trang_thai', 'da_duyet')->count(),
            'hoan_thanh' => (clone $count_query)->where('trang_thai', 'hoan_thanh')->count(),
            'da_huy'     => (clone $count_query)->where('trang_thai', 'da_huy')->count(),
            'yeu_cau_hoan_tien' => (clone $count_query)->where('trang_thai', 'yeu_cau_hoan_tien')->count(),
            'da_hoan_tien'      => (clone $count_query)->where('trang_thai', 'da_hoan_tien')->count(),
        ];

        $danh_sach_don = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('quan-tri.dat-tour.danh-sach', compact('danh_sach_don', 'counts'));
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

        // Nếu admin duyệt thủ công, coi như đã thanh toán (ít nhất là số tiền cần thiết để duyệt)
        if ($dat_tour->thanhToan && $dat_tour->thanhToan->trang_thai !== 'thanh_cong') {
            $amountToPay = $dat_tour->tong_tien_thanh_toan;
            if ($dat_tour->thanhToan->phuong_thuc === 'dat_coc') {
                $amountToPay = $dat_tour->tong_tien_thanh_toan * 0.3;
            }
            
            if ($dat_tour->tong_tien_da_thanh_toan < $amountToPay) {
                $dat_tour->update(['tong_tien_da_thanh_toan' => $amountToPay]);
            }
            
            $thanh_toan_status = $dat_tour->isFullyPaid() ? 'thanh_cong' : 'da_dat_coc';
            $dat_tour->thanhToan->update(['trang_thai' => $thanh_toan_status]);
        }

        // Nếu thanh toán 100% (isFullyPaid()) thì sang trạng thái khởi hành ('hoan_thanh'), ngược lại là 'da_duyet'
        $trang_thai = $dat_tour->isFullyPaid() ? 'hoan_thanh' : 'da_duyet';

        $dat_tour->update([
            'trang_thai'    => $trang_thai,
            'nguoi_duyet_id' => auth()->id(),
        ]);

        ThongBao::create([
            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
            'tieu_de'       => $trang_thai === 'hoan_thanh' ? 'Tour đã sẵn sàng khởi hành!' : 'Tour đã được xác nhận!',
            'noi_dung'      => $trang_thai === 'hoan_thanh' 
                ? 'Đơn đặt tour ' . $dat_tour->ma_dat_tour . ' đã sẵn sàng khởi hành.'
                : 'Đơn đặt tour ' . $dat_tour->ma_dat_tour . ' đã được xác nhận.',
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
        // Chuyển sang trạng thái đang đi / đã xong (nhưng chưa thanh toán nốt)
        $dat_tour->update(['trang_thai' => 'hoan_thanh']);
        return back()->with('thanh_cong', 'Đã chuyển đơn sang trạng thái Khởi Hành!');
    }

    public function duyetHoanThanh(DatTour $dat_tour)
    {
        // Kiểm tra thanh toán trước khi cho phép hoàn thành 100%
        if (!$dat_tour->isFullyPaid()) {
            return back()->with('loi', 'Đơn hàng chưa được thanh toán đủ 100%. Không thể duyệt hoàn thành.');
        }

        $dat_tour->update(['trang_thai' => 'done']);

        ThongBao::create([
            'nguoi_nhan_id' => $dat_tour->khach_hang_id,
            'tieu_de'       => 'Tour đã hoàn thành xuất sắc!',
            'noi_dung'      => 'Đơn ' . $dat_tour->ma_dat_tour . ' đã được xác nhận hoàn thành 100%. Cảm ơn bạn!',
            'loai'          => 'dat_tour',
        ]);

        return back()->with('thanh_cong', 'Đã duyệt hoàn thành tour!');
    }

    public function hoaDon(DatTour $dat_tour)
    {
        $dat_tour->load(['khachHang', 'lichKhoiHanh.tour.diemDen', 'hanhKhach', 'thanhToan']);
        return view('quan-tri.dat-tour.hoa-don', compact('dat_tour'));
    }

    public function duyetHoanTien(Request $request, DatTour $dat_tour)
    {
        if ($dat_tour->trang_thai !== 'yeu_cau_hoan_tien') {
            return back()->with('loi', 'Đơn đặt tour không ở trạng thái yêu cầu hoàn tiền.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $dat_tour->update([
                'trang_thai'     => 'da_hoan_tien',
                'nguoi_duyet_id' => auth()->id(),
            ]);

            // Cập nhật trạng thái thanh toán thành hoàn tiền
            if ($dat_tour->thanhToan) {
                $dat_tour->thanhToan->update(['trang_thai' => 'hoan_tien']);
            }

            ThongBao::create([
                'nguoi_nhan_id' => $dat_tour->khach_hang_id,
                'tieu_de'       => 'Yêu cầu hoàn tiền đã được duyệt!',
                'noi_dung'      => 'Đơn đặt tour ' . $dat_tour->ma_dat_tour . ' đã được duyệt hoàn tiền thành công. Số tiền đã được chuyển khoản hoàn lại tài khoản ngân hàng của bạn.',
                'loai'          => 'dat_tour',
                'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
            ]);

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Lỗi duyệt hoàn tiền: ' . $e->getMessage());
            return back()->with('loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }

        return back()->with('thanh_cong', 'Đã xác nhận hoàn tiền thành công và cập nhật trạng thái đơn hàng!');
    }
}
