<?php

namespace App\Http\Controllers\QuanTri;

use App\Http\Controllers\Controller;
use App\Models\LichSuDangNhap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LichSuDangNhapController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = LichSuDangNhap::with('khachHang')->latest();

        if ($request->filled('email')) {
            $query->whereHas('khachHang', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        $danh_sach = $query->paginate(20)->withQueryString();

        return view('quan-tri.lich-su-dang-nhap.index', compact('danh_sach'));
    }

    /**
     * Proxy định vị IP qua server-side để tránh lỗi CORS / Mixed Content trên HTTPS.
     * Browser gọi route này (HTTPS), Laravel gọi ip-api.com (HTTP) từ server.
     */
    public function locateIp(Request $request)
    {
        $ip = $request->query('ip');

        // Validate định dạng IP cơ bản
        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return response()->json(['status' => 'fail', 'message' => 'IP không hợp lệ.'], 400);
        }

        try {
            $response = Http::timeout(5)
                ->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,message,lat,lon,city,regionName,country',
                ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => 'Không thể kết nối dịch vụ định vị.'], 500);
        }
    }

    /**
     * Lưu tọa độ GPS thực tế từ browser vào bảng lịch sử đăng nhập
     */
    public function luuGps(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'history_id' => 'required|integer|exists:lich_su_dang_nhap,id'
        ]);

        $ls = LichSuDangNhap::find($request->history_id);
        
        // Kiểm tra xem bản ghi này có thuộc về user đang đăng nhập không (bảo mật)
        if ($ls && $ls->khach_hang_id == auth()->id()) {
            $ls->update([
                'latitude' => $request->lat,
                'longitude' => $request->lon
            ]);
            
            // Đã lưu thành công, xóa flag trong session để không hỏi lại
            session()->forget('login_history_id');
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}
