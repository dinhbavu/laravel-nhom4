<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\DiemDen;
use Illuminate\Http\Request;

class TourController extends Controller
{
    // Danh sách tour + tìm kiếm + lọc
    public function danhSach(Request $request)
    {
        $query = Tour::with('diemDen')->hoatDong();

        // Tìm kiếm
        if ($request->filled('tu_khoa')) {
            $query->timKiem($request->tu_khoa);
        }

        // Lọc theo điểm đến
        if ($request->filled('diem_den')) {
            $query->where('diem_den_id', $request->diem_den);
        }

        // Lọc theo vùng miền
        if ($request->filled('vung_mien')) {
            $query->whereHas('diemDen', fn($q) => $q->where('vung_mien', $request->vung_mien));
        }

        // Lọc theo loại tour
        if ($request->filled('loai_tour')) {
            $query->where('loai_tour', $request->loai_tour);
        }

        // Lọc theo khoảng giá
        if ($request->filled('gia_tu')) {
            $query->where('gia_nguoi_lon', '>=', $request->gia_tu);
        }
        if ($request->filled('gia_den')) {
            $query->where('gia_nguoi_lon', '<=', $request->gia_den);
        }

        // Lọc theo số ngày
        if ($request->filled('so_ngay')) {
            $query->where('so_ngay', $request->so_ngay);
        }

        // Sắp xếp
        $sap_xep = $request->get('sap_xep', 'moi_nhat');
        match ($sap_xep) {
            'gia_tang'   => $query->orderBy('gia_nguoi_lon'),
            'gia_giam'   => $query->orderByDesc('gia_nguoi_lon'),
            'danh_gia'   => $query->orderByDesc('danh_gia_trung_binh'),
            'pho_bien'   => $query->orderByDesc('luot_xem'),
            default      => $query->orderByDesc('created_at'),
        };

        $danh_sach_tour = $query->paginate(12)->withQueryString();
        $danh_sach_diem_den = DiemDen::where('trang_thai', true)->get();

        return view('khach-hang.tour.danh-sach', compact('danh_sach_tour', 'danh_sach_diem_den'));
    }

    // Chi tiết tour
    public function chiTiet(Tour $tour)
    {
        if ($tour->trang_thai !== 'hoat_dong') {
            abort(404);
        }

        $tour->increment('luot_xem');
        $tour->load(['diemDen', 'hinhAnh', 'lichTrinh', 'danhGia.khachHang']);

        $lich_khoi_hanh = $tour->lichKhoiHanh()
            ->conCho()
            ->where('ngay_di', '>=', now()->toDateString())
            ->orderBy('ngay_di')
            ->get();

        $tour_lien_quan = Tour::with('diemDen')
            ->hoatDong()
            ->where('diem_den_id', $tour->diem_den_id)
            ->where('id', '!=', $tour->id)
            ->limit(4)
            ->get();

        $da_yeu_thich = false;
        if (auth()->check()) {
            $da_yeu_thich = auth()->user()->tourYeuThich()->where('tour_id', $tour->id)->exists();
        }

        return view('khach-hang.tour.chi-tiet', compact(
            'tour', 'lich_khoi_hanh', 'tour_lien_quan', 'da_yeu_thich'
        ));
    }
}
