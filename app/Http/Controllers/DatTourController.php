<?php

namespace App\Http\Controllers;

use App\Models\DatTour;
use App\Models\LichKhoiHanh;
use App\Models\MaKhuyenMai;
use App\Models\HanhKhach;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatTourController extends Controller
{


    // Form đặt tour
    public function hienThiForm(LichKhoiHanh $lich_khoi_hanh)
    {
        $lich_khoi_hanh->load('tour.diemDen');

        if ($lich_khoi_hanh->trang_thai !== 'con_cho' || $lich_khoi_hanh->so_cho_con <= 0) {
            return redirect()->route('tour.chi-tiet', $lich_khoi_hanh->tour)
                ->with('loi', 'Lịch khởi hành này đã hết chỗ.');
        }

        return view('khach-hang.dat-tour.form', compact('lich_khoi_hanh'));
    }

    // Xử lý đặt tour
    public function datTour(Request $request, LichKhoiHanh $lich_khoi_hanh)
    {
        $request->validate([
            'so_nguoi_lon' => 'required|integer|min:1',
            'so_tre_em'    => 'required|integer|min:0',
            'so_em_be'     => 'required|integer|min:0',
            'ghi_chu'      => 'nullable|string|max:500',
            'phuong_thuc_thanh_toan' => 'required|in:chuyen_khoan,tien_mat,dat_coc',
            'dia_diem_hen' => 'required_if:phuong_thuc_thanh_toan,tien_mat',
            'thoi_gian_hen' => 'required_if:phuong_thuc_thanh_toan,tien_mat',
        ], [
            'so_nguoi_lon.required'        => 'Vui lòng nhập số người lớn.',
            'dia_diem_hen.required_if'     => 'Vui lòng chọn trụ sở để đến thanh toán.',
            'thoi_gian_hen.required_if'    => 'Vui lòng chọn thời gian hẹn.',
        ]);

        $tong_nguoi = $request->so_nguoi_lon + $request->so_tre_em;
        if ($tong_nguoi > $lich_khoi_hanh->so_cho_con) {
            return back()->with('loi', 'Số chỗ còn lại không đủ. Chỉ còn ' . $lich_khoi_hanh->so_cho_con . ' chỗ.');
        }

        $gia_nl = $lich_khoi_hanh->gia_nguoi_lon_hien_tai;
        $gia_te = $lich_khoi_hanh->gia_tre_em_hien_tai;

        $tong_tien_goc = ($request->so_nguoi_lon * $gia_nl) + ($request->so_tre_em * $gia_te);

        // Tính giảm giá tự động dựa trên phần trăm giảm giá của tour
        $giam_gia = 0;
        $tour = $lich_khoi_hanh->tour;
        if ($tour->phan_tram_giam_gia > 0) {
            $giam_gia = ($tong_tien_goc * $tour->phan_tram_giam_gia) / 100;
        }

        DB::beginTransaction();
        try {
            $dat_tour = DatTour::create([
                'ma_dat_tour'            => DatTour::taoMaDatTour(),
                'khach_hang_id'          => auth()->id(),
                'lich_khoi_hanh_id'      => $lich_khoi_hanh->id,
                'ma_khuyen_mai_id'       => null,
                'so_nguoi_lon'           => $request->so_nguoi_lon,
                'so_tre_em'              => $request->so_tre_em,
                'so_em_be'               => $request->so_em_be,
                'tong_tien_goc'          => $tong_tien_goc,
                'giam_gia'               => $giam_gia,
                'tong_tien_thanh_toan'   => $tong_tien_goc - $giam_gia,
                'ghi_chu'                => $request->ghi_chu,
                'trang_thai'             => 'cho_duyet',
            ]);

            // Lưu hành khách (Tự động sinh vì form không yêu cầu nhập chi tiết)
            $user_name = auth()->user()->ho_ten;
            
            // Người lớn
            for ($i = 0; $i < $request->so_nguoi_lon; $i++) {
                HanhKhach::create([
                    'dat_tour_id'   => $dat_tour->id,
                    'ho_ten'        => ($i == 0) ? $user_name : "$user_name - Khách người lớn $i",
                    'ngay_sinh'     => now()->subYears(20), // Default
                    'gioi_tinh'     => 'nam',
                    'loai'          => 'nguoi_lon',
                ]);
            }

            // Trẻ em
            for ($i = 1; $i <= $request->so_tre_em; $i++) {
                HanhKhach::create([
                    'dat_tour_id'   => $dat_tour->id,
                    'ho_ten'        => "$user_name - Khách trẻ em $i",
                    'ngay_sinh'     => now()->subYears(8), // Default
                    'gioi_tinh'     => 'nam',
                    'loai'          => 'tre_em',
                ]);
            }

            // Lưu thông tin thanh toán
            $phuong_thuc = $request->phuong_thuc_thanh_toan;
            $tong_tien = $tong_tien_goc - $giam_gia;
            // Nếu đặt cọc thì số tiền của bản ghi thanh toán đầu tiên là 30%
            $so_tien_thanh_toan = ($phuong_thuc === 'dat_coc') ? ($tong_tien * 0.3) : $tong_tien;

            \App\Models\ThanhToan::create([
                'dat_tour_id' => $dat_tour->id,
                'so_tien'     => $so_tien_thanh_toan,
                'phuong_thuc' => $phuong_thuc,
                'trang_thai'  => 'cho_xu_ly',
                'dia_diem_hen' => $request->dia_diem_hen,
                'thoi_gian_hen' => $request->thoi_gian_hen,
            ]);

            // Trừ số chỗ
            $lich_khoi_hanh->decrement('so_cho_con', $tong_nguoi);
            if ($lich_khoi_hanh->so_cho_con <= 0) {
                $lich_khoi_hanh->update(['trang_thai' => 'het_cho']);
            }

            // Bỏ phần tăng lượt dùng mã khuyến mãi

            // Gửi thông báo
            ThongBao::create([
                'nguoi_nhan_id' => auth()->id(),
                'tieu_de'       => 'Đặt tour thành công!',
                'noi_dung'      => 'Mã đặt tour ' . $dat_tour->ma_dat_tour . ' đang chờ xác nhận.',
                'loai'          => 'dat_tour',
                'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đặt tour: ' . $e->getMessage());
            return back()->with('loi', 'Có lỗi xảy ra. Chi tiết: ' . $e->getMessage());
        }

        return redirect()->route('khach-hang.dat-tour.chi-tiet', $dat_tour);
    }

    // Lịch sử đặt tour
    public function lichSu()
    {
        $danh_sach = DatTour::with(['lichKhoiHanh.tour', 'danhGia'])
            ->where('khach_hang_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('khach-hang.dat-tour.lich-su', compact('danh_sach'));
    }

    // Chi tiết đặt tour
    public function chiTiet(DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        $dat_tour->load(['lichKhoiHanh.tour.diemDen', 'hanhKhach', 'thanhToan', 'danhGia']);
        return view('khach-hang.dat-tour.chi-tiet', compact('dat_tour'));
    }

    // Hủy tour
    public function huyTour(Request $request, DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet'])) {
            return back()->with('loi', 'Không thể hủy tour ở trạng thái này.');
        }

        $request->validate(['ly_do_huy' => 'required|string|min:10'], [
            'ly_do_huy.required' => 'Vui lòng nhập lý do hủy.',
            'ly_do_huy.min'      => 'Lý do hủy tối thiểu 10 ký tự.',
        ]);

        $dat_tour->update([
            'trang_thai' => 'da_huy',
            'ly_do_huy'  => $request->ly_do_huy,
        ]);

        // Hoàn chỗ
        $lich = $dat_tour->lichKhoiHanh;
        $lich->increment('so_cho_con', $dat_tour->so_nguoi_lon + $dat_tour->so_tre_em);
        if ($lich->trang_thai === 'het_cho') {
            $lich->update(['trang_thai' => 'con_cho']);
        }

        ThongBao::create([
            'nguoi_nhan_id' => auth()->id(),
            'tieu_de'       => 'Hủy tour thành công',
            'noi_dung'      => 'Tour ' . $dat_tour->ma_dat_tour . ' đã được hủy.',
            'loai'          => 'huy_tour',
        ]);

        return back()->with('thanh_cong', 'Hủy tour thành công.');
    }

    // Yêu cầu hoàn tiền
    public function yeuCauHoanTien(Request $request, DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($dat_tour->trang_thai, ['cho_duyet', 'da_duyet', 'da_xac_nhan', 'hoan_thanh', 'da_huy'])) {
            return back()->with('loi', 'Không thể yêu cầu hoàn tiền ở trạng thái này.');
        }

        if ($dat_tour->tong_tien_da_thanh_toan <= 0) {
            return back()->with('loi', 'Đơn hàng này chưa được thanh toán, vui lòng sử dụng chức năng hủy thông thường.');
        }

        $request->validate([
            'ly_do_huy' => 'required|string|min:10',
        ], [
            'ly_do_huy.required' => 'Vui lòng nhập lý do hoàn tiền.',
            'ly_do_huy.min'      => 'Lý do hoàn tiền tối thiểu 10 ký tự.',
        ]);

        /** @var \App\Models\NguoiDung $user */
        $user = auth()->user();

        // Nếu chưa cập nhật thông tin ngân hàng hoặc có thông tin gửi lên thì validate và lưu
        if ($request->has('ten_ngan_hang') || !$user->ten_ngan_hang || !$user->so_tai_khoan || !$user->ten_tai_khoan) {
            $request->validate([
                'ten_ngan_hang' => 'required|string|max:100',
                'so_tai_khoan'  => 'required|string|max:50',
                'ten_tai_khoan'  => 'required|string|max:150',
            ], [
                'ten_ngan_hang.required' => 'Vui lòng nhập tên ngân hàng nhận tiền hoàn.',
                'so_tai_khoan.required'  => 'Vui lòng nhập số tài khoản nhận tiền hoàn.',
                'ten_tai_khoan.required'  => 'Vui lòng nhập tên chủ tài khoản nhận tiền hoàn.',
            ]);

            $user->update([
                'ten_ngan_hang' => $request->ten_ngan_hang,
                'so_tai_khoan'  => $request->so_tai_khoan,
                'ten_tai_khoan'  => $request->ten_tai_khoan,
            ]);
        }

        $trangThaiCu = $dat_tour->trang_thai;

        DB::beginTransaction();
        try {
            $dat_tour->update([
                'trang_thai' => 'yeu_cau_hoan_tien',
                'ly_do_huy'  => $request->ly_do_huy,
            ]);

            // Chỉ hoàn trả chỗ trống của tour nếu trước đó chưa hoàn trả (tức là trạng thái không phải da_huy)
            if ($trangThaiCu !== 'da_huy') {
                $lich = $dat_tour->lichKhoiHanh;
                $lich->increment('so_cho_con', $dat_tour->so_nguoi_lon + $dat_tour->so_tre_em);
                if ($lich->trang_thai === 'het_cho') {
                    $lich->update(['trang_thai' => 'con_cho']);
                }
            }

            ThongBao::create([
                'nguoi_nhan_id' => auth()->id(),
                'tieu_de'       => 'Gửi yêu cầu hoàn tiền thành công',
                'noi_dung'      => 'Đơn đặt tour ' . $dat_tour->ma_dat_tour . ' đã gửi yêu cầu hoàn tiền thành công. Vui lòng đợi quản trị viên xử lý chuyển khoản.',
                'loai'          => 'dat_tour',
                'duong_dan'     => route('khach-hang.dat-tour.chi-tiet', $dat_tour),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi yêu cầu hoàn tiền: ' . $e->getMessage());
            return back()->with('loi', 'Có lỗi xảy ra khi xử lý: ' . $e->getMessage());
        }

        return back()->with('thanh_cong', 'Gửi yêu cầu hoàn tiền thành công. Admin sẽ kiểm tra và hoàn tiền cho bạn sớm nhất.');
    }

    // Lịch sử thanh toán
    public function lichSuThanhToan()
    {
        $danh_sach = \App\Models\ThanhToan::with(['datTour.lichKhoiHanh.tour'])
            ->whereHas('datTour', function ($query) {
                $query->where('khach_hang_id', auth()->id());
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('khach-hang.dat-tour.lich-su-thanh-toan', compact('danh_sach'));
    }

    // Đánh giá tour
    public function danhGia(Request $request, DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($dat_tour->trang_thai, ['hoan_thanh', 'done'])) {
            return back()->with('loi', 'Chỉ có thể đánh giá tour đã hoàn thành.');
        }

        // Kiểm tra xem đã đánh giá chưa
        if (\App\Models\DanhGia::where('dat_tour_id', $dat_tour->id)->exists()) {
            return back()->with('loi', 'Bạn đã đánh giá tour này rồi.');
        }

        $request->validate([
            'diem_so'  => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string|min:10|max:1000',
        ], [
            'diem_so.required'  => 'Vui lòng chọn số sao đánh giá.',
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.min'      => 'Nội dung đánh giá tối thiểu 10 ký tự.',
        ]);

        \App\Models\DanhGia::create([
            'tour_id'       => $dat_tour->lichKhoiHanh->tour_id,
            'khach_hang_id' => auth()->id(),
            'dat_tour_id'   => $dat_tour->id,
            'diem_so'       => $request->diem_so,
            'tieu_de'       => 'Đánh giá chuyến đi ' . \Carbon\Carbon::parse($dat_tour->lichKhoiHanh->ngay_di)->format('d/m/Y'),
            'noi_dung'      => $request->noi_dung,
            'trang_thai'    => 'da_duyet', // Mặc định hiển thị luôn
        ]);

        // Cập nhật điểm đánh giá trung bình cho Tour (tính trung bình các đánh giá đã duyệt)
        $tour = $dat_tour->lichKhoiHanh->tour;
        $danh_gia_tb = \App\Models\DanhGia::where('tour_id', $tour->id)->where('trang_thai', 'da_duyet')->avg('diem_so');
        $tour->update(['danh_gia_trung_binh' => round((float)$danh_gia_tb, 1)]);

        return back()->with('thanh_cong', 'Cảm ơn bạn đã gửi đánh giá!');
    }

    // In hóa đơn
    public function hoaDon(DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        // Chỉ cho phép in hóa đơn khi tour đã hoàn thành hoặc đã thanh toán
        $isPaid = in_array($dat_tour->trang_thai, ['da_xac_nhan', 'hoan_thanh', 'done'])
                  || optional($dat_tour->thanhToan)->trang_thai === 'thanh_cong';

        if (!$isPaid) {
            return back()->with('loi', 'Chỉ có thể in hóa đơn cho đơn đã hoàn thành hoặc đã thanh toán.');
        }

        $dat_tour->load(['lichKhoiHanh.tour.diemDen', 'khachHang', 'hanhKhach', 'thanhToan']);
        return view('khach-hang.dat-tour.hoa-don', compact('dat_tour'));
    }

    // Kết thúc hành trình
    public function ketThuc(Request $request, DatTour $dat_tour)
    {
        if ($dat_tour->khach_hang_id !== auth()->id()) {
            abort(403);
        }

        if ($dat_tour->trang_thai !== 'hoan_thanh') {
            return back()->with('loi', 'Chuyến đi chưa bắt đầu hoặc đã kết thúc rồi.');
        }

        // Nếu chưa thanh toán đủ, quay lại để view hiện QR
        if (!$dat_tour->isFullyPaid()) {
            return back()->with('thanh_cong', 'Vui lòng thanh toán số tiền còn lại để hoàn tất thủ tục kết thúc tour.');
        }

        // Nếu đã thanh toán đủ, thông báo chờ Admin duyệt
        return back()->with('thanh_cong', 'Bạn đã yêu cầu kết thúc hành trình. Vui lòng chờ quản trị viên xác nhận hoàn thành tour.');
    }
}
