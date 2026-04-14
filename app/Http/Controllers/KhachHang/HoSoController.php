<?php

namespace App\Http\Controllers\KhachHang;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ThongBao;
use App\Models\YeuThich;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HoSoController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function index()
    {
        $user = auth()->user();
        return view('khach-hang.ho-so.index', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function capNhatProfile(Request $request)
    {
        /** @var NguoiDung $user */
        $user = auth()->user();

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:500',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
        ]);

        $user->update([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
        ]);

        return back()->with('thanh_cong', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Cập nhật ảnh đại diện
     */
    public function capNhatAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
            'avatar.image' => 'Tệp được chọn phải là ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Dung lượng ảnh tối đa là 2MB.',
        ]);

        /** @var NguoiDung $user */
        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu không phải ảnh mặc định
            if ($user->anh_dai_dien && $user->anh_dai_dien !== 'default-avatar.png') {
                Storage::disk('public')->delete('avatars/' . $user->anh_dai_dien);
            }

            $fileName = time() . '_' . $user->id . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $fileName, 'public');

            $user->update(['anh_dai_dien' => $fileName]);

            return back()->with('thanh_cong', 'Cập nhật ảnh đại diện thành công!');
        }

        return back()->with('loi', 'Có lỗi xảy ra khi tải ảnh lên.');
    }

    /**
     * Đổi mật khẩu
     */
    public function doiMatKhau(Request $request)
    {
        $request->validate([
            'mat_khau_cu' => 'required',
            'mat_khau_moi' => 'required|min:6|confirmed',
        ], [
            'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu cũ.',
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min' => 'Mật khẩu mới tối thiểu 6 ký tự.',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        /** @var NguoiDung $user */
        $user = auth()->user();

        if (!Hash::check($request->mat_khau_cu, $user->mat_khau)) {
            return back()->withErrors(['mat_khau_cu' => 'Mật khẩu cũ không chính xác.']);
        }

        $user->update([
            'mat_khau' => Hash::make($request->mat_khau_moi)
        ]);

        return back()->with('thanh_cong', 'Đổi mật khẩu thành công!');
    }

    /**
     * Trang tour yêu thích
     */
    public function yeuThich()
    {
        $user = auth()->user();
        $danh_sach_yeu_thich = $user->tourYeuThich()->paginate(10);
        return view('khach-hang.ho-so.yeu-thich', compact('danh_sach_yeu_thich'));
    }

    /**
     * Trang thông báo
     */
    public function thongBao()
    {
        $user = auth()->user();
        $danh_sach_thong_bao = ThongBao::where('nguoi_nhan_id', $user->id)
            ->orderByDesc('id')
            ->paginate(15);

        return view('khach-hang.ho-so.thong-bao', compact('danh_sach_thong_bao'));
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function danhDauDoc(ThongBao $thong_bao)
    {
        if ($thong_bao->nguoi_nhan_id !== auth()->id()) {
            abort(403);
        }

        $thong_bao->update(['da_doc' => true]);

        if ($thong_bao->duong_dan) {
            return redirect($thong_bao->duong_dan);
        }

        return back();
    }

    /**
     * Thêm tour vào yêu thích
     */
    public function themYeuThich(Tour $tour)
    {
        $user = auth()->user();
        
        // Kiểm tra xem đã có trong wishlist chưa
        $exists = YeuThich::where('khach_hang_id', $user->id)
            ->where('tour_id', $tour->id)
            ->exists();

        if (!$exists) {
            YeuThich::create([
                'khach_hang_id' => $user->id,
                'tour_id' => $tour->id
            ]);
        }

        return back()->with('thanh_cong', 'Đã thêm tour vào danh sách yêu thích!');
    }

    /**
     * Xóa tour khỏi yêu thích
     */
    public function xoaYeuThich(Tour $tour)
    {
        $user = auth()->user();
        
        YeuThich::where('khach_hang_id', $user->id)
            ->where('tour_id', $tour->id)
            ->delete();

        return back()->with('thanh_cong', 'Đã xóa tour khỏi danh sách yêu thích!');
    }
}
