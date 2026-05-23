<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\DiemDen;
use App\Models\LichKhoiHanh;
use App\Models\LichTrinhTour;
use App\Models\HinhAnhTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class QuanLyTourController extends Controller
{
    public function danhSach(Request $request)

    {
        $query = Tour::with('diemDen');

        if ($request->filled('tu_khoa')) {
            $query->where('ten_tour', 'like', '%' . $request->tu_khoa . '%');
        }
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('vung_mien')) {
            $query->whereHas('diemDen', function($q) use ($request) {
                $q->where('vung_mien', $request->vung_mien);
            });
        }

        if ($request->filled('diem_den')) {
            $query->where('diem_den_id', $request->diem_den);
        }

        $danh_sach = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $danh_sach_diem_den = DiemDen::where('trang_thai', true)->get();

        return view('quan-tri.tour.danh-sach', compact('danh_sach', 'danh_sach_diem_den'));
    }

    public function taoMoi()
    {
        $danh_sach_diem_den = DiemDen::where('trang_thai', true)->get();
        return view('quan-tri.tour.tao-moi', compact('danh_sach_diem_den'));
    }

    public function luu(Request $request)
    {
        $request->validate([
            'ten_tour'      => 'required|string|max:200',
            'vung_mien'     => 'required|in:mien_bac,mien_trung,mien_nam',
            'nhap_diem_den' => 'required|string|max:255',
            'loai_tour'     => 'required|in:trong_nuoc,quoc_te,mao_hiem,nghi_duong,van_hoa',
            'so_ngay'       => 'required|integer|min:1',
            'so_dem'        => 'required|integer|min:0',
            'gia_nguoi_lon' => 'required|numeric|min:0',
            'gia_tre_em'    => 'required|numeric|min:0',
            'mo_ta_ngan'    => 'nullable|string|max:500',
            'mo_ta_day_du'  => 'nullable|string',
            'phan_tram_giam_gia' => 'nullable|integer|min:0|max:100',
            'hinh_bia'      => 'nullable|image|max:5120',
            'hinh_anh_gallery.*' => 'nullable|image|max:5120',
        ], [
            'ten_tour.required'      => 'Vui lòng nhập tên tour.',
            'vung_mien.required'     => 'Vui lòng chọn vùng miền.',
            'nhap_diem_den.required' => 'Vui lòng nhập tên điểm đến.',
            'gia_nguoi_lon.required' => 'Vui lòng nhập giá người lớn.',
        ]);

        $du_lieu = $request->except(['hinh_bia', '_token', 'hinh_anh_gallery', 'vung_mien', 'nhap_diem_den']);
        
        $diem_den = DiemDen::firstOrCreate(
            ['ten_diem_den' => $request->nhap_diem_den],
            [
                'vung_mien' => $request->vung_mien, 
                'tinh_thanh' => $request->nhap_diem_den, // Dùng tạm tên điểm đến làm tỉnh thành nếu không nhập
                'trang_thai' => 1
            ]
        );
        if($diem_den->vung_mien != $request->vung_mien) {
            $diem_den->update(['vung_mien' => $request->vung_mien]);
        }
        $du_lieu['diem_den_id'] = $diem_den->id;
        
        $du_lieu['ma_tour']      = Tour::taoMaTour();
        $du_lieu['nguoi_tao_id'] = auth()->id();
        $du_lieu['noi_bat']      = $request->has('noi_bat') ? 1 : 0;

        // Lưu hình bia - hỗ trợ InfinityFree
        if ($request->hasFile('hinh_bia')) {
            try {
                $hinh_bia = $request->file('hinh_bia');
                // Tạo tên file an toàn
                $ten_file = time() . '_' . uniqid() . '.' . $hinh_bia->getClientOriginalExtension();
                // Lưu trực tiếp vào public/uploads/tours
                $hinh_bia->move(public_path('uploads/tours'), $ten_file);
                $du_lieu['hinh_bia'] = $ten_file;
            } catch (\Exception $e) {
                \Log::error('Lỗi upload hình bia tour: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('loi', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
            }
        }

        $tour = Tour::create($du_lieu);

        // Upload ảnh gallery
        if ($request->hasFile('hinh_anh_gallery')) {
            foreach ($request->file('hinh_anh_gallery') as $i => $anh) {
                try {
                    $ten_file = time() . '_' . $i . '_' . uniqid() . '.' . $anh->getClientOriginalExtension();
                    $anh->move(public_path('uploads/tours'), $ten_file);
                    HinhAnhTour::create([
                        'tour_id'   => $tour->id,
                        'duong_dan' => $ten_file,
                        'thu_tu'    => $i,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Lỗi upload hình anh tour: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('quan-tri.tour.danh-sach')
            ->with('thanh_cong', 'Tạo tour thành công!');
    }

    public function chinh_sua(Tour $tour)
    {
        $danh_sach_diem_den = DiemDen::where('trang_thai', true)->get();
        $tour->load(['diemDen', 'hinhAnh', 'lichTrinh', 'lichKhoiHanh']);
        return view('quan-tri.tour.chinh-sua', compact('tour', 'danh_sach_diem_den'));
    }

    public function capNhat(Request $request, Tour $tour)
    {
        $request->validate([
            'ten_tour'      => 'required|string|max:200',
            'vung_mien'     => 'required|in:mien_bac,mien_trung,mien_nam',
            'nhap_diem_den' => 'required|string|max:255',
            'gia_nguoi_lon' => 'required|numeric|min:0',
            'phan_tram_giam_gia' => 'nullable|integer|min:0|max:100',
            'hinh_bia'      => 'nullable|image|max:5120',
            'hinh_anh_gallery.*' => 'nullable|image|max:5120',
        ]);

        $du_lieu = $request->except(['hinh_bia', '_token', '_method', 'hinh_anh_gallery', 'vung_mien', 'nhap_diem_den']);
        
        $diem_den = DiemDen::firstOrCreate(
            ['ten_diem_den' => $request->nhap_diem_den],
            ['vung_mien' => $request->vung_mien, 'trang_thai' => 1]
        );
        if($diem_den->vung_mien != $request->vung_mien) {
            $diem_den->update(['vung_mien' => $request->vung_mien]);
        }
        $du_lieu['diem_den_id'] = $diem_den->id;
        
        $du_lieu['noi_bat'] = $request->has('noi_bat') ? 1 : 0;

        // Lưu hình bia - hỗ trợ InfinityFree
        if ($request->hasFile('hinh_bia')) {
            try {
                $hinh_bia = $request->file('hinh_bia');
                // Tạo tên file an toàn
                $ten_file = time() . '_' . uniqid() . '.' . $hinh_bia->getClientOriginalExtension();
                // Lưu trực tiếp vào public/uploads/tours
                $hinh_bia->move(public_path('uploads/tours'), $ten_file);
                $du_lieu['hinh_bia'] = $ten_file;
            } catch (\Exception $e) {
                \Log::error('Lỗi upload hình bia tour: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('loi', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
            }
        }

        $tour->update($du_lieu);

        return redirect()->route('quan-tri.tour.danh-sach')
            ->with('thanh_cong', 'Cập nhật tour thành công!');
    }

    public function xoa(Tour $tour)
    {
        $tour->update(['trang_thai' => 'ngung']);
        return back()->with('thanh_cong', 'Đã ngừng hoạt động tour!');
    }

    public function xoaVinhVien(Tour $tour)
    {
        try {
            DB::beginTransaction();

            // 1. Lấy danh sách ID các lịch khởi hành của tour này
            $lich_khoi_hanh_ids = $tour->lichKhoiHanh()->pluck('id');

            // 2. Tìm tất cả đơn đặt tour liên quan đến các lịch này
            $dat_tour_ids = DB::table('dat_tour')->whereIn('lich_khoi_hanh_id', $lich_khoi_hanh_ids)->pluck('id');

            // 3. Xóa các đánh giá liên quan đến các đơn đặt tour này (để không bị kẹt khóa ngoại)
            DB::table('danh_gia')->whereIn('dat_tour_id', $dat_tour_ids)->delete();

            // 4. Xóa các đơn đặt tour (Hành khách và Thanh toán sẽ tự động xóa do ON DELETE CASCADE trong DB)
            DB::table('dat_tour')->whereIn('id', $dat_tour_ids)->delete();

            // 5. Xóa ảnh bia thực tế trên server
            if ($tour->hinh_bia) {
                $path_bia = public_path('uploads/tours/' . $tour->hinh_bia);
                if (file_exists($path_bia)) {
                    unlink($path_bia);
                }
            }

            // 6. Xóa các ảnh trong gallery thực tế trên server
            foreach ($tour->hinhAnh as $anh) {
                $path_anh = public_path('uploads/tours/' . $anh->duong_dan);
                if (file_exists($path_anh)) {
                    unlink($path_anh);
                }
            }

            // 7. Xóa Tour (Lịch trình, Hình ảnh trong DB, Lịch khởi hành, Yêu thích sẽ tự động xóa do CASCADE)
            $tour->delete();

            DB::commit();

            return redirect()->route('quan-tri.tour.danh-sach')
                ->with('thanh_cong', 'Đã xóa vĩnh viễn tour và toàn bộ dữ liệu liên quan thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi xóa vĩnh viễn tour: ' . $e->getMessage());
            return back()->with('loi', 'Không thể xóa tour. Lỗi: ' . $e->getMessage());
        }
    }
}
