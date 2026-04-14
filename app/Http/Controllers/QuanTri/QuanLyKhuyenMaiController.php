<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Tour;
use Illuminate\Http\Request;

class QuanLyKhuyenMaiController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('tu_khoa')) {
            $query->where('tieu_de', 'like', '%' . $request->tu_khoa . '%');
        }

        $danh_sach = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        
        // Tours for the discount tab
        $danh_sach_tour = Tour::with('diemDen')->where('trang_thai', 'hoat_dong')->orderBy('ten_tour')->get();
        
        return view('quan-tri.khuyen-mai.index', compact('danh_sach', 'danh_sach_tour'));
    }

    public function taoMoi()
    {
        return view('quan-tri.khuyen-mai.tao-moi');
    }

    public function luu(Request $request)
    {
        // Check if file is missing but request was large (indicates PHP limit hit)
        if (!$request->hasFile('hinh_anh_url') && $request->has('tieu_de')) {
            return back()->withInput()->withErrors(['hinh_anh_url' => 'Không thể tải ảnh này lên. Có thể file quá lớn so với giới hạn của XAMPP (php.ini). Hãy thử cập nhật upload_max_filesize trong php.ini hoặc đổi sang ảnh JPG nhẹ hơn.']);
        }

        $request->validate([
            'tieu_de'       => 'required|max:255',
            'hinh_anh_url'  => 'required|image|max:30720', // Tăng lên 30MB
            'duong_dan'     => 'nullable|url|max:2000',
        ], [
            'hinh_anh_url.image' => 'File phải là định dạng ảnh (jpg, png, webp...).',
            'hinh_anh_url.max'   => 'Dung lượng ảnh vượt quá 30MB.',
            'hinh_anh_url.required' => 'Bạn chưa chọn ảnh hoặc ảnh bị lỗi khi tải lên.',
        ]);

        $data = $request->only(['tieu_de', 'duong_dan']);
        $data['trang_thai'] = $request->has('trang_thai');

        if ($request->hasFile('hinh_anh_url')) {
            $file = $request->file('hinh_anh_url');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('banners', $fileName, 'public');
            $data['hinh_anh_url'] = $fileName;
        }

        Banner::create($data);

        return redirect()->route('quan-tri.khuyen-mai.danh-sach')
            ->with('thanh_cong', 'Thêm Banner thành công!');
    }

    public function chinhSua(Banner $khuyen_mai)
    {
        return view('quan-tri.khuyen-mai.chinh-sua', ['banner' => $khuyen_mai]);
    }

    public function capNhat(Request $request, Banner $khuyen_mai)
    {
        // Check if file is missing but request was large
        if (!$request->hasFile('hinh_anh_url') && $request->has('tieu_de') && $_SERVER['CONTENT_LENGTH'] > 0 && empty($_FILES)) {
            return back()->withInput()->withErrors(['hinh_anh_url' => 'Không thể tải ảnh này lên. Có thể file quá lớn so với giới hạn của máy chủ.']);
        }

        $request->validate([
            'tieu_de'       => 'required|max:255',
            'hinh_anh_url'  => 'nullable|image|max:30720',
            'duong_dan'     => 'nullable|url|max:2000',
        ], [
            'hinh_anh_url.image' => 'File phải là định dạng ảnh.',
            'hinh_anh_url.max'   => 'Dung lượng ảnh vượt quá 30MB.',
        ]);

        $data = $request->only(['tieu_de', 'duong_dan']);
        $data['trang_thai'] = $request->has('trang_thai');

        if ($request->hasFile('hinh_anh_url')) {
            // Delete old file if it's not a remote URL
            if ($khuyen_mai->getRawOriginal('hinh_anh_url') && !filter_var($khuyen_mai->getRawOriginal('hinh_anh_url'), FILTER_VALIDATE_URL)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('banners/' . $khuyen_mai->getRawOriginal('hinh_anh_url'));
            }

            $file = $request->file('hinh_anh_url');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('banners', $fileName, 'public');
            $data['hinh_anh_url'] = $fileName;
        }

        $khuyen_mai->update($data);

        return redirect()->route('quan-tri.khuyen-mai.danh-sach')
            ->with('thanh_cong', 'Cập nhật Banner thành công!');
    }

    public function xoa(Banner $khuyen_mai)
    {
        // Delete local file if exists
        if ($khuyen_mai->getRawOriginal('hinh_anh_url') && !filter_var($khuyen_mai->getRawOriginal('hinh_anh_url'), FILTER_VALIDATE_URL)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('banners/' . $khuyen_mai->getRawOriginal('hinh_anh_url'));
        }

        $khuyen_mai->delete();
        return back()->with('thanh_cong', 'Đã xóa Banner!');
    }

    // --- Tour Discount Methods ---
    public function capNhatGiamGiaTour(Request $request)
    {
        $request->validate([
            'tour_id'            => 'required|exists:tour,id',
            'phan_tram_giam_gia' => 'required|integer|min:0|max:100',
        ]);

        Tour::where('id', $request->tour_id)->update(['phan_tram_giam_gia' => $request->phan_tram_giam_gia]);

        return back()->with('thanh_cong', 'Cập nhật giảm giá tour thành công!');
    }
}
