# VietGo - Nền Tảng Đặt Tour Du Lịch

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Giới thiệu về VietGo

**VietGo** là một ứng dụng web nền tảng đặt tour du lịch trực tuyến hiện đại, thân thiện, được xây dựng và phát triển trên nền tảng Laravel (PHP Framework). VietGo cam kết mang lại trải nghiệm tiện ích, đáng tin cậy và thú vị nhất cho du khách có đam mê xê dịch, giúp họ dễ dàng tìm kiếm, lên kế hoạch và đặt các hành trình khám phá mong muốn. 

Dự án này không chỉ giải quyết bài toán đặt chỗ trực tuyến một cách tối ưu mà còn cung cấp hệ thống quản lý tập trung và chi tiết dành cho quản trị viên và các đơn vị cung cấp dịch vụ lữ hành.

## 🚀 Các tính năng nổi bật (Features)

### Dành cho Khách hàng (User Role)
- **Tìm kiếm và Lọc đa dạng:** Dễ dàng tìm kiếm chuyến đi dựa theo điểm đến, thời gian khởi hành, khoảng giá và các tiện ích kèm theo.
- **Chi tiết tour rõ ràng:** Xem chi tiết lộ trình, giới thiệu điểm đến, đánh giá từ những người dùng trước và bộ sưu tập hình ảnh sống động.
- **Hệ thống giỏ hàng và Đặt tour an toàn:** Thao tác đặt vé nhanh chóng, tiện lợi, tích hợp hỗ trợ cho các cổng thanh toán.
- **Quản lý hành trình:** Đăng ký tài khoản, theo dõi trạng thái đơn đặt tour và đánh giá lại một chuyến đi sau khi trải nghiệm xong. 

### Dành cho Quản trị viên (Admin Role)
- **Quản lý Tour:** Thêm, xem, sửa, và xóa chi tiết các danh mục tour.
- **Quản lý Đơn hàng:** Kiểm duyệt và theo dõi trạng thái các giao dịch đặt chỗ, thay đổi trạng thái và xuất báo cáo.
- **Quản trị Người dùng:** Quản trị danh sách khách hàng và quản trị phân quyền (đại lý du lịch, khách hàng v.v...).
- **Chương trình khuyến mãi và Blog:** Tổ chức và cung cấp tin tức, cẩm nang du lịch và tạo mã giảm giá. 

## 🛠 Công nghệ sử dụng (Tech Stack)

- **Backend:** Laravel Framework (PHP)
- **Frontend:** Blade Template, Bootstrap / TailwindCSS, JavaScript, AJAX...
- **Database:** MySQL / PostgreSQL
- **Môi trường phát triển:** Docker / XAMPP / Composer / Node.js
- **Architecture:** MVC Pattern

## ⚙️ Hướng dẫn Cài đặt & Chạy Dự Án (Getting Started)

Để chạy trơn tru dự án VietGo trên máy tính của bạn, hãy làm theo các bước dưới đây:

**Bước 1: Clone Repository**
```bash
git clone <đường-dẫn-repository-của-bạn>
cd webdattour
```

**Bước 2: Cài đặt Dependencies**
```bash
# Cài đặt PHP dependencies thông qua Composer
composer install

# Cài đặt Node.js dependencies thông qua NPM (nếu cần thiết)
npm install
npm run build
```

**Bước 3: Cấu hình Môi trường**
```bash
cp .env.example .env
```
Mở tệp `.env` vừa được tạo ra bằng trình soạn thảo và cập nhật thông tin kết nối Cơ sở dữ liệu (Database Credentials) của bạn.

**Bước 4: Thiết lập Application Key và Database**
```bash
# Sinh khóa bảo mật mới cho ứng dụng
php artisan key:generate

# Chạy tạo bảng và thêm dữ liệu giả lập (seeding)
php artisan migrate:fresh --seed
```

**Bước 5: Chạy Dự án local**
```bash
php artisan serve
```
Bây giờ, bạn có thể truy cập dự án thông qua trình duyệt ở địa chỉ: `http://localhost:8000`

---
*Phát triển bởi Nhóm 4*
