<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\LichKhoiHanh;
use App\Models\Tour;
use Illuminate\Http\Request;

class QuanLyLichKhoiHanhController extends Controller
{
    /**
     * Danh sách lịch khởi hành của 1 tour
     */
    public function danhSach(Tour $tour)
    {
        $tour->load(['lichKhoiHanh' => fn($q) => $q->orderBy('ngay_di')]);
        return view('quan-tri.lich-khoi-hanh.danh-sach', compact('tour'));
    }

    /**
     * Lưu lịch khởi hành mới (AJAX-friendly + redirect)
     */
    public function luu(Request $request, Tour $tour)
    {
        $request->validate([
            'ngay_di'       => 'required|date|after_or_equal:today',
            'ngay_ve'       => 'required|date|after:ngay_di',
            'so_cho_toi_da' => 'required|integer|min:1|max:500',
            'gia_nguoi_lon' => 'nullable|numeric|min:0',
            'gia_tre_em'    => 'nullable|numeric|min:0',
            'trang_thai'    => 'required|in:con_cho,het_cho,huy',
        ]);

        LichKhoiHanh::create([
            'tour_id'       => $tour->id,
            'ngay_di'       => $request->ngay_di,
            'ngay_ve'       => $request->ngay_ve,
            'so_cho_toi_da' => $request->so_cho_toi_da,
            'so_cho_con'    => $request->so_cho_toi_da,
            'gia_nguoi_lon' => $request->gia_nguoi_lon ?: null,
            'gia_tre_em'    => $request->gia_tre_em ?: null,
            'trang_thai'    => $request->trang_thai,
        ]);

        return redirect()->route('quan-tri.lich-khoi-hanh.danh-sach', $tour)
            ->with('thanh_cong', 'Đã thêm lịch khởi hành mới!');
    }

    /**
     * Cập nhật lịch khởi hành
     */
    public function capNhat(Request $request, Tour $tour, LichKhoiHanh $lich)
    {
        $request->validate([
            'ngay_di'       => 'required|date',
            'ngay_ve'       => 'required|date|after:ngay_di',
            'so_cho_toi_da' => 'required|integer|min:1',
            'gia_nguoi_lon' => 'nullable|numeric|min:0',
            'gia_tre_em'    => 'nullable|numeric|min:0',
            'trang_thai'    => 'required|in:con_cho,het_cho,huy',
        ]);

        $lich->update([
            'ngay_di'       => $request->ngay_di,
            'ngay_ve'       => $request->ngay_ve,
            'so_cho_toi_da' => $request->so_cho_toi_da,
            'so_cho_con'    => $request->so_cho_con ?? $lich->so_cho_con,
            'gia_nguoi_lon' => $request->gia_nguoi_lon ?: null,
            'gia_tre_em'    => $request->gia_tre_em ?: null,
            'trang_thai'    => $request->trang_thai,
        ]);

        return back()->with('thanh_cong', 'Đã cập nhật lịch khởi hành!');
    }

    /**
     * Xóa lịch khởi hành
     */
    public function xoa(Tour $tour, LichKhoiHanh $lich)
    {
        if ($lich->datTour()->exists()) {
            return back()->with('loi', 'Không thể xóa vì đã có đơn đặt tour cho lịch này!');
        }
        $lich->delete();
        return back()->with('thanh_cong', 'Đã xóa lịch khởi hành!');
    }
}
