<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QuanLyNguoiDungController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = NguoiDung::query();

        if ($request->filled('vai_tro')) {
            $query->where('vai_tro', $request->vai_tro);
        }
        if ($request->filled('tu_khoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->tu_khoa . '%')
                  ->orWhere('email', 'like', '%' . $request->tu_khoa . '%');
            });
        }
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $danh_sach_nguoi_dung = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('quan-tri.nguoi-dung.danh-sach', compact('danh_sach_nguoi_dung'));
    }

    public function taoNhanVien()
    {
        return view('quan-tri.nguoi-dung.tao-nhan-vien');
    }

    public function luuNhanVien(Request $request)
    {
        // Chỉ admin mới tạo được nhân viên
        if (!auth()->user()->laAdmin()) {
            abort(403);
        }

        $request->validate([
            'ho_ten'        => 'required|string|max:100',
            'email'         => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'vai_tro'       => 'required|in:nhan_vien,admin',
            'mat_khau'      => 'required|min:6|confirmed',
        ]);

        NguoiDung::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'vai_tro'       => $request->vai_tro,
            'mat_khau'      => Hash::make($request->mat_khau),
            'trang_thai'    => 1,
        ]);

        return redirect()->route('quan-tri.nguoi-dung.danh-sach')
            ->with('thanh_cong', 'Tạo tài khoản nhân viên thành công!');
    }

    public function khoaTaiKhoan(NguoiDung $nguoi_dung)
    {
        if (!auth()->user()->laAdmin() && $nguoi_dung->vai_tro !== 'khach_hang') {
            abort(403, 'Bạn chỉ có quyền thao tác trên tài khoản Khách Hàng.');
        }

        if ($nguoi_dung->id === auth()->id()) {
            return back()->with('loi', 'Không thể khóa tài khoản của chính mình.');
        }

        $nguoi_dung->update(['trang_thai' => !$nguoi_dung->trang_thai]);
        $trang_thai_moi = $nguoi_dung->fresh()->trang_thai ? 'mở khóa' : 'khóa';

        return back()->with('thanh_cong', "Đã {$trang_thai_moi} tài khoản!");
    }

    public function chinhSua(NguoiDung $nguoi_dung)
    {
        if (!auth()->user()->laAdmin() && $nguoi_dung->vai_tro !== 'khach_hang') {
            abort(403, 'Bạn chỉ có quyền thao tác trên tài khoản Khách Hàng.');
        }
        return view('quan-tri.nguoi-dung.chinh-sua', compact('nguoi_dung'));
    }

    public function capNhat(Request $request, NguoiDung $nguoi_dung)
    {
        if (!auth()->user()->laAdmin() && $nguoi_dung->vai_tro !== 'khach_hang') {
            abort(403, 'Bạn chỉ có quyền thao tác trên tài khoản Khách Hàng.');
        }

        $rule_vai_tro = auth()->user()->laAdmin() ? 'required|in:nhan_vien,admin,khach_hang' : 'required|in:khach_hang';

        $request->validate([
            'ho_ten'        => 'required|string|max:100',
            'email'         => 'required|email|unique:nguoi_dung,email,' . $nguoi_dung->id,
            'so_dien_thoai' => 'nullable|string|max:20',
            'vai_tro'       => $rule_vai_tro,
        ]);

        $du_lieu = [
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'vai_tro'       => $request->vai_tro,
        ];

        if ($request->filled('mat_khau')) {
            $request->validate([
                'mat_khau' => 'min:6|confirmed',
            ]);
            $du_lieu['mat_khau'] = Hash::make($request->mat_khau);
        }

        if ($nguoi_dung->id === auth()->id() && $request->vai_tro !== 'admin' && auth()->user()->laAdmin()) {
            return back()->with('loi', 'Bạn không thể tự tước quyền admin của chính mình.');
        }

        $nguoi_dung->update($du_lieu);

        return redirect()->route('quan-tri.nguoi-dung.danh-sach')
            ->with('thanh_cong', 'Cập nhật thông tin và phân quyền thành công!');
    }

    public function xoa(NguoiDung $nguoi_dung)
    {
        if (!auth()->user()->laAdmin() && $nguoi_dung->vai_tro !== 'khach_hang') {
            abort(403, 'Bạn chỉ có quyền thao tác trên tài khoản Khách Hàng.');
        }

        if ($nguoi_dung->id === auth()->id()) {
            return back()->with('loi', 'Không thể xóa tài khoản của chính mình.');
        }

        // Fix lỗi Foreign Key: Chuyển toàn bộ các Tour do nhân viên này tạo sang cho admin đang thực hiện xóa
        \App\Models\Tour::where('nguoi_tao_id', $nguoi_dung->id)->update(['nguoi_tao_id' => auth()->id()]);

        // Nếu nhân viên này đã từng duyệt đơn, chuyển ID người duyệt về null (vì bảng dat_tour set ON DELETE SET NULL)
        // MySQL sẽ tự động làm nhưng đôi khi gọi qua Eloquent sẽ an toàn hơn.
        
        $nguoi_dung->delete();
        return back()->with('thanh_cong', 'Đã xóa tài khoản!');
    }
}
