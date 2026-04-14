<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\DatTourController;
use App\Http\Controllers\Auth\DangNhapKhachHangController;
use App\Http\Controllers\Auth\DangNhapQuanTriController;
use App\Http\Controllers\QuanTri\DashboardController;
use App\Http\Controllers\QuanTri\QuanLyTourController;
use App\Http\Controllers\QuanTri\QuanLyDatTourController;
use App\Http\Controllers\QuanTri\QuanLyNguoiDungController;
use App\Http\Controllers\QuanTri\QuanLyKhuyenMaiController;
use App\Http\Controllers\QuanTri\QuanLyLichKhoiHanhController;
use App\Http\Controllers\KhuyenMaiController;

/*
|--------------------------------------------------------------------------
| ROUTES CÔNG KHAI - Khách hàng
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [TrangChuController::class, 'index'])->name('trang-chu');

// Danh sách và chi tiết tour (ai cũng xem được)
Route::get('/tour', [TourController::class, 'danhSach'])->name('tour.danh-sach');
Route::get('/tour/{tour}', [TourController::class, 'chiTiet'])->name('tour.chi-tiet');
Route::get('/khuyen-mai', [KhuyenMaiController::class, 'index'])->name('khuyen-mai');

// Cẩm Nang
Route::get('/cam-nang', [\App\Http\Controllers\CamNangController::class, 'danhSach'])->name('cam-nang.danh-sach');
Route::get('/cam-nang/{slug}', [\App\Http\Controllers\CamNangController::class, 'chiTiet'])->name('cam-nang.chi-tiet');

/*
|--------------------------------------------------------------------------
| AUTH KHÁCH HÀNG - Riêng biệt
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [DangNhapKhachHangController::class, 'hienThiForm'])->name('dang-nhap');
    Route::post('/dang-nhap', [DangNhapKhachHangController::class, 'dangNhap'])->name('dang-nhap.xu-ly');
    Route::get('/dang-ky', [DangNhapKhachHangController::class, 'hienThiDangKy'])->name('dang-ky');
    Route::post('/dang-ky', [DangNhapKhachHangController::class, 'dangKy'])->name('dang-ky.xu-ly');
    
    // Quên mật khẩu
    Route::get('/quen-mat-khau', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'hienThiFormEmail'])->name('quen-mat-khau.email');
    Route::post('/quen-mat-khau', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'guiOtp'])->name('quen-mat-khau.gui-otp');
    Route::get('/quen-mat-khau/xac-nhan', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'hienThiFormOtp'])->name('quen-mat-khau.otp');
    Route::post('/quen-mat-khau/xac-nhan', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'xacNhanOtp'])->name('quen-mat-khau.xac-nhan-otp');
    Route::get('/quen-mat-khau/dat-lai', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'hienThiFormDatLai'])->name('quen-mat-khau.dat-lai');
    Route::post('/quen-mat-khau/dat-lai', [\App\Http\Controllers\Auth\QuenMatKhauController::class, 'datLaiMatKhau'])->name('quen-mat-khau.dat-lai-xu-ly');
});

Route::post('/dang-xuat', [DangNhapKhachHangController::class, 'dangXuat'])->name('dang-xuat');

/*
|--------------------------------------------------------------------------
| ROUTES KHÁCH HÀNG ĐÃ ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/
Route::middleware('kiem-tra-khach-hang')->prefix('khach-hang')->name('khach-hang.')->group(function () {

    // Đặt tour
    Route::get('/dat-tour/{lich_khoi_hanh}', [DatTourController::class, 'hienThiForm'])->name('dat-tour.form');
    Route::post('/dat-tour/{lich_khoi_hanh}', [DatTourController::class, 'datTour'])->name('dat-tour.xu-ly');
    Route::get('/lich-su-dat-tour', [DatTourController::class, 'lichSu'])->name('dat-tour.lich-su');
    Route::get('/lich-su-thanh-toan', [DatTourController::class, 'lichSuThanhToan'])->name('dat-tour.thanh-toan');
    Route::post('/dat-tour/{dat_tour}/danh-gia', [DatTourController::class, 'danhGia'])->name('dat-tour.danh-gia');
    Route::get('/dat-tour/{dat_tour}/chi-tiet', [DatTourController::class, 'chiTiet'])->name('dat-tour.chi-tiet');
    Route::get('/dat-tour/{dat_tour}/hoa-don', [DatTourController::class, 'hoaDon'])->name('dat-tour.hoa-don');
    Route::post('/dat-tour/{dat_tour}/huy', [DatTourController::class, 'huyTour'])->name('dat-tour.huy');
    Route::post('/dat-tour/{dat_tour}/ket-thuc', [DatTourController::class, 'ketThuc'])->name('dat-tour.ket-thuc');

    // Thanh toán SePay
    Route::get('/thanh-toan/sepay/{dat_tour}', [\App\Http\Controllers\SePayController::class, 'checkout'])->name('thanh-toan.sepay');
    Route::get('/thanh-toan/thanh-cong/{dat_tour}', [\App\Http\Controllers\SePayController::class, 'thanhCong'])->name('thanh-toan.thanh-cong');

    // Hồ sơ và các tính năng cá nhân
    Route::get('/ho-so', [\App\Http\Controllers\KhachHang\HoSoController::class, 'index'])->name('ho-so');
    Route::post('/ho-so/cap-nhat', [\App\Http\Controllers\KhachHang\HoSoController::class, 'capNhatProfile'])->name('ho-so.cap-nhat');
    Route::post('/ho-so/avatar', [\App\Http\Controllers\KhachHang\HoSoController::class, 'capNhatAvatar'])->name('ho-so.avatar');
    Route::post('/ho-so/doi-mat-khau', [\App\Http\Controllers\KhachHang\HoSoController::class, 'doiMatKhau'])->name('ho-so.doi-mat-khau');
    Route::get('/yeu-thich', [\App\Http\Controllers\KhachHang\HoSoController::class, 'yeuThich'])->name('yeu-thich');
    Route::post('/tour/{tour}/yeu-thich', [\App\Http\Controllers\KhachHang\HoSoController::class, 'themYeuThich'])->name('yeu-thich.them');
    Route::delete('/tour/{tour}/yeu-thich', [\App\Http\Controllers\KhachHang\HoSoController::class, 'xoaYeuThich'])->name('yeu-thich.xoa');
    Route::get('/thong-bao', [\App\Http\Controllers\KhachHang\HoSoController::class, 'thongBao'])->name('thong-bao');
    Route::get('/thong-bao/{thong_bao}/doc', [\App\Http\Controllers\KhachHang\HoSoController::class, 'danhDauDoc'])->name('thong-bao.doc');

});

Route::get('/dang-phat-trien', function() { return view('khach-hang.dang-phat-trien'); })->name('dang-phat-trien');

// Cổng đăng nhập dành riêng cho Admin và Nhân viên
Route::middleware('guest')->group(function () {
    Route::get('/loginadmin', [DangNhapQuanTriController::class, 'hienThiForm'])->name('quan-tri.dang-nhap');
    Route::post('/loginadmin', [DangNhapQuanTriController::class, 'dangNhap'])->name('quan-tri.dang-nhap.xu-ly');
});

/*
|--------------------------------------------------------------------------
| ROUTES QUẢN TRỊ - Admin + Nhân viên
|--------------------------------------------------------------------------
*/
Route::prefix('quan-tri')->name('quan-tri.')->group(function () {

    Route::post('/dang-xuat', [DangNhapQuanTriController::class, 'dangXuat'])->name('dang-xuat');

    // Routes cần đăng nhập quản trị
    Route::middleware('kiem-tra-quan-tri')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Thống Kê
        Route::get('/thong-ke', [DashboardController::class, 'thongKe'])->name('thong-ke');
        
        // Hệ Thống
        Route::get('/he-thong', function () { 
            return view('quan-tri.he-thong'); 
        })->name('he-thong');

        // Quản lý Tour
        Route::prefix('tour')->name('tour.')->group(function () {
            Route::get('/', [QuanLyTourController::class, 'danhSach'])->name('danh-sach');
            Route::get('/tao-moi', [QuanLyTourController::class, 'taoMoi'])->name('tao-moi');
            Route::post('/tao-moi', [QuanLyTourController::class, 'luu'])->name('luu');
            Route::get('/{tour}/chinh-sua', [QuanLyTourController::class, 'chinh_sua'])->name('chinh-sua');
            Route::put('/{tour}', [QuanLyTourController::class, 'capNhat'])->name('cap-nhat');
            Route::delete('/{tour}', [QuanLyTourController::class, 'xoa'])->name('xoa');
            Route::delete('/{tour}/xoa-vinh-vien', [QuanLyTourController::class, 'xoaVinhVien'])->name('xoa-vinh-vien');
        });

        // Quản lý Lịch Khởi Hành
        Route::prefix('tour/{tour}/lich-khoi-hanh')->name('lich-khoi-hanh.')->group(function () {
            Route::get('/', [QuanLyLichKhoiHanhController::class, 'danhSach'])->name('danh-sach');
            Route::post('/', [QuanLyLichKhoiHanhController::class, 'luu'])->name('luu');
            Route::put('/{lich}', [QuanLyLichKhoiHanhController::class, 'capNhat'])->name('cap-nhat');
            Route::delete('/{lich}', [QuanLyLichKhoiHanhController::class, 'xoa'])->name('xoa');
        });

        // Quản lý Đặt Tour
        Route::prefix('dat-tour')->name('dat-tour.')->group(function () {
            Route::get('/', [QuanLyDatTourController::class, 'danhSach'])->name('danh-sach');
            Route::get('/{dat_tour}', [QuanLyDatTourController::class, 'chiTiet'])->name('chi-tiet');
            Route::get('/{dat_tour}/hoa-don', [QuanLyDatTourController::class, 'hoaDon'])->name('hoa-don');
            Route::post('/{dat_tour}/duyet', [QuanLyDatTourController::class, 'duyetDatTour'])->name('duyet');
            Route::post('/{dat_tour}/tu-choi', [QuanLyDatTourController::class, 'tuChoiDatTour'])->name('tu-choi');
            Route::post('/{dat_tour}/hoan-thanh', [QuanLyDatTourController::class, 'hoanThanh'])->name('hoan-thanh');
        });

        // Quản lý Khuyến Mãi
        Route::prefix('khuyen-mai')->name('khuyen-mai.')->group(function () {
            Route::get('/', [QuanLyKhuyenMaiController::class, 'danhSach'])->name('danh-sach');
            Route::get('/tao-moi', [QuanLyKhuyenMaiController::class, 'taoMoi'])->name('tao-moi');
            Route::post('/luu', [QuanLyKhuyenMaiController::class, 'luu'])->name('luu');
            Route::get('/{khuyen_mai}/chinh-sua', [QuanLyKhuyenMaiController::class, 'chinhSua'])->name('chinh-sua');
            Route::put('/{khuyen_mai}/cap-nhat', [QuanLyKhuyenMaiController::class, 'capNhat'])->name('cap-nhat');
            Route::delete('/{khuyen_mai}/xoa', [QuanLyKhuyenMaiController::class, 'xoa'])->name('xoa');
            Route::post('/cap-nhat-giam-gia-tour', [QuanLyKhuyenMaiController::class, 'capNhatGiamGiaTour'])->name('cap-nhat-giam-gia-tour');
        });

        // Quản lý Cẩm Nang
        Route::prefix('cam-nang')->name('cam-nang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'danhSach'])->name('danh-sach');
            Route::get('/tao-moi', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'taoMoi'])->name('tao-moi');
            Route::post('/tao-moi', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'luu'])->name('luu');
            Route::get('/{cam_nang}/chinh-sua', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'chinhSua'])->name('chinh-sua');
            Route::put('/{cam_nang}', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'capNhat'])->name('capNhat');
            Route::delete('/{cam_nang}', [\App\Http\Controllers\QuanTri\QuanLyCamNangController::class, 'xoa'])->name('xoa');
        });

        // Quản lý Người Dùng (chỉ admin)
        Route::prefix('nguoi-dung')->name('nguoi-dung.')->group(function () {
            Route::get('/', [QuanLyNguoiDungController::class, 'danhSach'])->name('danh-sach');
            Route::get('/tao-nhan-vien', [QuanLyNguoiDungController::class, 'taoNhanVien'])->name('tao-nhan-vien');
            Route::post('/tao-nhan-vien', [QuanLyNguoiDungController::class, 'luuNhanVien'])->name('luu-nhan-vien');
            Route::post('/{nguoi_dung}/khoa', [QuanLyNguoiDungController::class, 'khoaTaiKhoan'])->name('khoa');
            Route::get('/{nguoi_dung}/chinh-sua', [QuanLyNguoiDungController::class, 'chinhSua'])->name('chinh-sua');
            Route::put('/{nguoi_dung}', [QuanLyNguoiDungController::class, 'capNhat'])->name('cap-nhat');
            Route::delete('/{nguoi_dung}', [QuanLyNguoiDungController::class, 'xoa'])->name('xoa');
        });

        // Quản lý Lịch Sử Đăng Nhập
        Route::get('/lich-su-dang-nhap', [\App\Http\Controllers\QuanTri\LichSuDangNhapController::class, 'danhSach'])->name('lich-su-dang-nhap.danh-sach');
    });
});

// Chatbot AI gợi ý tour
Route::post('/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');

// Webhooks Thanh Toán (Tích hợp SePay/Casso & Polling)
// Lưu ý: Dùng /hook/ thay vì /api/ vì InfinityFree chặn URL chứa "api" (403 Forbidden)
Route::post('/hook/payment/webhook', [\App\Http\Controllers\WebhookController::class, 'receivePayment']);
Route::get('/hook/payment/status/{ma_dat_tour}', [\App\Http\Controllers\WebhookController::class, 'checkStatus']);
Route::get('/hook/payment/test-webhook/{ma_dat_tour}', [\App\Http\Controllers\WebhookController::class, 'simulatePayment']);
Route::post('/hook/sepay/ipn', [\App\Http\Controllers\SePayController::class, 'ipn']);
