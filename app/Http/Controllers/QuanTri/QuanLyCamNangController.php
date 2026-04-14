<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\CamNang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QuanLyCamNangController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = CamNang::with('nguoiDang');

        if ($request->tu_khoa) {
            $query->where('tieu_de', 'LIKE', '%' . $request->tu_khoa . '%');
        }

        if ($request->chuyen_muc) {
            $query->where('chuyen_muc', $request->chuyen_muc);
        }

        $danh_sach = $query->orderByDesc('id')->paginate(10);
        return view('quan-tri.cam-nang.index', compact('danh_sach'));
    }

    public function taoMoi()
    {
        return view('quan-tri.cam-nang.tao-moi');
    }

    public function luu(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'chuyen_muc' => 'required|in:meo_du_lich,am_thuc,diem_den',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        $data['nguoi_dang_id'] = auth()->id();

        if ($request->hasFile('hinh_anh')) {
            $fileName = time() . '_' . $request->hinh_anh->getClientOriginalName();
            $request->hinh_anh->storeAs('cam-nang', $fileName, 'public');
            $data['hinh_anh'] = $fileName;
        }

        CamNang::create($data);

        return redirect()->route('quan-tri.cam-nang.danh-sach')->with('thanh_cong', 'Thêm bài viết cẩm nang thành công!');
    }

    public function chinhSua(CamNang $cam_nang)
    {
        return view('quan-tri.cam-nang.chinh-sua', compact('cam_nang'));
    }

    public function capNhat(Request $request, CamNang $cam_nang)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'chuyen_muc' => 'required|in:meo_du_lich,am_thuc,diem_den',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        
        // Chỉ cập nhật slug nếu đổi tiêu đề
        if ($cam_nang->tieu_de !== $request->tieu_de) {
            $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        }

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ
            if ($cam_nang->hinh_anh) {
                Storage::disk('public')->delete('cam-nang/' . $cam_nang->hinh_anh);
            }

            $fileName = time() . '_' . $request->hinh_anh->getClientOriginalName();
            $request->hinh_anh->storeAs('cam-nang', $fileName, 'public');
            $data['hinh_anh'] = $fileName;
        }

        $cam_nang->update($data);

        return redirect()->route('quan-tri.cam-nang.danh-sach')->with('thanh_cong', 'Cập nhật bài viết thành công!');
    }

    public function xoa(CamNang $cam_nang)
    {
        if ($cam_nang->hinh_anh) {
            Storage::disk('public')->delete('cam-nang/' . $cam_nang->hinh_anh);
        }
        $cam_nang->delete();

        return redirect()->route('quan-tri.cam-nang.danh-sach')->with('thanh_cong', 'Xóa bài viết thành công!');
    }
}
