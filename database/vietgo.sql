-- ============================================================
-- VietGo Database Schema
-- Website Quản Lý Đặt Tour Du Lịch Xuyên Việt
-- ============================================================

CREATE DATABASE IF NOT EXISTS `vietgo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vietgo`;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- Bảng: nguoi_dung (Người dùng hệ thống)
-- vai_tro: khach_hang | nhan_vien | admin
-- ============================================================
DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE `nguoi_dung` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ho_ten` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `mat_khau` VARCHAR(255) NOT NULL,
    `so_dien_thoai` VARCHAR(20) NULL,
    `dia_chi` TEXT NULL,
    `anh_dai_dien` VARCHAR(255) NULL DEFAULT 'default-avatar.png',
    `vai_tro` ENUM('khach_hang','nhan_vien','admin') NOT NULL DEFAULT 'khach_hang',
    `trang_thai` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=hoat dong, 0=khoa',
    `remember_token` VARCHAR(100) NULL,
    `email_verified_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: diem_den (Điểm đến / Danh mục địa điểm du lịch)
-- ============================================================
DROP TABLE IF EXISTS `diem_den`;
CREATE TABLE `diem_den` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ten_diem_den` VARCHAR(150) NOT NULL,
    `tinh_thanh` VARCHAR(100) NOT NULL,
    `vung_mien` ENUM('mien_bac','mien_trung','mien_nam') NOT NULL,
    `mo_ta` TEXT NULL,
    `hinh_anh` VARCHAR(255) NULL,
    `trang_thai` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: tour (Thông tin tour du lịch)
-- ============================================================
DROP TABLE IF EXISTS `tour`;
CREATE TABLE `tour` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ma_tour` VARCHAR(20) NOT NULL UNIQUE,
    `ten_tour` VARCHAR(200) NOT NULL,
    `diem_den_id` BIGINT UNSIGNED NOT NULL,
    `loai_tour` ENUM('trong_nuoc','quoc_te','mao_hiem','nghi_duong','van_hoa') NOT NULL DEFAULT 'trong_nuoc',
    `so_ngay` TINYINT UNSIGNED NOT NULL,
    `so_dem` TINYINT UNSIGNED NOT NULL,
    `mo_ta_ngan` TEXT NULL,
    `mo_ta_day_du` LONGTEXT NULL,
    `gia_nguoi_lon` DECIMAL(12,0) NOT NULL,
    `gia_tre_em` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `gia_em_be` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `hinh_bia` VARCHAR(255) NULL,
    `luot_xem` INT UNSIGNED NOT NULL DEFAULT 0,
    `danh_gia_trung_binh` DECIMAL(3,1) NOT NULL DEFAULT 0,
    `tong_danh_gia` INT UNSIGNED NOT NULL DEFAULT 0,
    `noi_bat` TINYINT(1) NOT NULL DEFAULT 0,
    `trang_thai` ENUM('con_cho','hoat_dong','ngung') NOT NULL DEFAULT 'hoat_dong',
    `nguoi_tao_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`diem_den_id`) REFERENCES `diem_den`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: hinh_anh_tour (Gallery ảnh tour)
-- ============================================================
DROP TABLE IF EXISTS `hinh_anh_tour`;
CREATE TABLE `hinh_anh_tour` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tour_id` BIGINT UNSIGNED NOT NULL,
    `duong_dan` VARCHAR(255) NOT NULL,
    `thu_tu` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES `tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: lich_trinh_tour (Chi tiết lịch trình từng ngày)
-- ============================================================
DROP TABLE IF EXISTS `lich_trinh_tour`;
CREATE TABLE `lich_trinh_tour` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tour_id` BIGINT UNSIGNED NOT NULL,
    `ngay_thu` TINYINT UNSIGNED NOT NULL,
    `tieu_de` VARCHAR(200) NOT NULL,
    `noi_dung` TEXT NOT NULL,
    `bua_an` VARCHAR(200) NULL COMMENT 'VD: Sáng, Trưa, Tối',
    `khach_san` VARCHAR(200) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES `tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: lich_khoi_hanh (Ngày khởi hành + số chỗ)
-- ============================================================
DROP TABLE IF EXISTS `lich_khoi_hanh`;
CREATE TABLE `lich_khoi_hanh` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tour_id` BIGINT UNSIGNED NOT NULL,
    `ngay_di` DATE NOT NULL,
    `ngay_ve` DATE NOT NULL,
    `so_cho_toi_da` SMALLINT UNSIGNED NOT NULL,
    `so_cho_con` SMALLINT UNSIGNED NOT NULL,
    `gia_nguoi_lon` DECIMAL(12,0) NULL COMMENT 'Giá override cho lịch này',
    `gia_tre_em` DECIMAL(12,0) NULL,
    `trang_thai` ENUM('con_cho','het_cho','ngung') NOT NULL DEFAULT 'con_cho',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES `tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: ma_khuyen_mai (Voucher / Coupon)
-- ============================================================
DROP TABLE IF EXISTS `ma_khuyen_mai`;
CREATE TABLE `ma_khuyen_mai` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ma` VARCHAR(50) NOT NULL UNIQUE,
    `loai` ENUM('phan_tram','so_tien') NOT NULL DEFAULT 'so_tien',
    `gia_tri` DECIMAL(12,0) NOT NULL,
    `gia_tri_toi_da` DECIMAL(12,0) NULL,
    `dieu_kien_toi_thieu` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `so_luot_su_dung` INT UNSIGNED NOT NULL DEFAULT 0,
    `so_luot_toi_da` INT UNSIGNED NOT NULL DEFAULT 1,
    `ngay_bat_dau` DATE NOT NULL,
    `ngay_ket_thuc` DATE NOT NULL,
    `trang_thai` TINYINT(1) NOT NULL DEFAULT 1,
    `mo_ta` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: dat_tour (Đơn đặt tour)
-- ============================================================
DROP TABLE IF EXISTS `dat_tour`;
CREATE TABLE `dat_tour` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ma_dat_tour` VARCHAR(20) NOT NULL UNIQUE,
    `khach_hang_id` BIGINT UNSIGNED NOT NULL,
    `lich_khoi_hanh_id` BIGINT UNSIGNED NOT NULL,
    `ma_khuyen_mai_id` BIGINT UNSIGNED NULL,
    `so_nguoi_lon` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `so_tre_em` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `so_em_be` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `tong_tien_goc` DECIMAL(14,0) NOT NULL,
    `giam_gia` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `tong_tien_thanh_toan` DECIMAL(14,0) NOT NULL,
    `ghi_chu` TEXT NULL,
    `trang_thai` ENUM('cho_duyet','da_duyet','da_huy','hoan_thanh') NOT NULL DEFAULT 'cho_duyet',
    `ly_do_huy` TEXT NULL,
    `nguoi_duyet_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung`(`id`),
    FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh`(`id`),
    FOREIGN KEY (`ma_khuyen_mai_id`) REFERENCES `ma_khuyen_mai`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`nguoi_duyet_id`) REFERENCES `nguoi_dung`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: hanh_khach (Thông tin hành khách trong booking)
-- ============================================================
DROP TABLE IF EXISTS `hanh_khach`;
CREATE TABLE `hanh_khach` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `dat_tour_id` BIGINT UNSIGNED NOT NULL,
    `ho_ten` VARCHAR(100) NOT NULL,
    `ngay_sinh` DATE NOT NULL,
    `gioi_tinh` ENUM('nam','nu') NOT NULL,
    `so_cmnd` VARCHAR(20) NULL,
    `so_dien_thoai` VARCHAR(20) NULL,
    `loai` ENUM('nguoi_lon','tre_em','em_be') NOT NULL DEFAULT 'nguoi_lon',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: thanh_toan (Thông tin thanh toán)
-- ============================================================
DROP TABLE IF EXISTS `thanh_toan`;
CREATE TABLE `thanh_toan` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `dat_tour_id` BIGINT UNSIGNED NOT NULL,
    `so_tien` DECIMAL(14,0) NOT NULL,
    `phuong_thuc` ENUM('vnpay','momo','cod','chuyen_khoan') NOT NULL,
    `ma_giao_dich` VARCHAR(100) NULL,
    `trang_thai` ENUM('cho_xu_ly','thanh_cong','that_bai','hoan_tien') NOT NULL DEFAULT 'cho_xu_ly',
    `ghi_chu` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: danh_gia (Đánh giá tour)
-- ============================================================
DROP TABLE IF EXISTS `danh_gia`;
CREATE TABLE `danh_gia` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tour_id` BIGINT UNSIGNED NOT NULL,
    `khach_hang_id` BIGINT UNSIGNED NOT NULL,
    `dat_tour_id` BIGINT UNSIGNED NOT NULL,
    `diem_so` TINYINT UNSIGNED NOT NULL DEFAULT 5 COMMENT '1-5',
    `tieu_de` VARCHAR(200) NULL,
    `noi_dung` TEXT NULL,
    `hinh_anh` VARCHAR(255) NULL,
    `trang_thai` ENUM('cho_duyet','da_duyet','an') NOT NULL DEFAULT 'cho_duyet',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES `tour`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung`(`id`),
    FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: yeu_thich (Tour yêu thích)
-- ============================================================
DROP TABLE IF EXISTS `yeu_thich`;
CREATE TABLE `yeu_thich` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `khach_hang_id` BIGINT UNSIGNED NOT NULL,
    `tour_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_yeu_thich` (`khach_hang_id`, `tour_id`),
    FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tour_id`) REFERENCES `tour`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: thong_bao (Thông báo)
-- ============================================================
DROP TABLE IF EXISTS `thong_bao`;
CREATE TABLE `thong_bao` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nguoi_nhan_id` BIGINT UNSIGNED NOT NULL,
    `tieu_de` VARCHAR(255) NOT NULL,
    `noi_dung` TEXT NOT NULL,
    `loai` ENUM('dat_tour','huy_tour','thanh_toan','khuyen_mai','he_thong') NOT NULL DEFAULT 'he_thong',
    `da_doc` TINYINT(1) NOT NULL DEFAULT 0,
    `duong_dan` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`nguoi_nhan_id`) REFERENCES `nguoi_dung`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: audit_log (Lịch sử hành động admin)
-- ============================================================
DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nguoi_dung_id` BIGINT UNSIGNED NOT NULL,
    `hanh_dong` VARCHAR(100) NOT NULL,
    `doi_tuong` VARCHAR(100) NULL,
    `doi_tuong_id` BIGINT UNSIGNED NULL,
    `du_lieu_cu` JSON NULL,
    `du_lieu_moi` JSON NULL,
    `dia_chi_ip` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: sessions (Laravel session)
-- ============================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Bảng: cache (Laravel cache)
-- ============================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DỮ LIỆU MẪU
-- ============================================================

-- Tài khoản Admin và Nhân viên mặc định
INSERT INTO `nguoi_dung` (`ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `email_verified_at`) VALUES
('Quản Trị Viên', 'admin@vietgo.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0900000001', 'admin', 1, NOW()),
('Nguyễn Thị Nhân Viên', 'nhanvien@vietgo.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0900000002', 'nhan_vien', 1, NOW()),
('Trần Văn Khách', 'khach@vietgo.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0900000003', 'khach_hang', 1, NOW());

-- Điểm đến
INSERT INTO `diem_den` (`ten_diem_den`, `tinh_thanh`, `vung_mien`, `mo_ta`) VALUES
('Vịnh Hạ Long', 'Quảng Ninh', 'mien_bac', 'Di sản thiên nhiên thế giới với hàng nghìn đảo đá vôi'),
('Phố Cổ Hội An', 'Quảng Nam', 'mien_trung', 'Đô thị cổ được UNESCO công nhận là di sản văn hóa thế giới'),
('Đà Lạt', 'Lâm Đồng', 'mien_nam', 'Thành phố ngàn hoa, khí hậu mát mẻ quanh năm'),
('Nha Trang', 'Khánh Hòa', 'mien_trung', 'Thành phố biển nổi tiếng với bãi biển đẹp và hải sản tươi ngon'),
('Phú Quốc', 'Kiên Giang', 'mien_nam', 'Đảo ngọc thiên đường với bãi biển trong xanh'),
('Sapa', 'Lào Cai', 'mien_bac', 'Cao nguyên mù sương với ruộng bậc thang tuyệt đẹp'),
('Mũi Né', 'Bình Thuận', 'mien_nam', 'Thiên đường cát trắng và lướt ván diều'),
('Ninh Bình', 'Ninh Bình', 'mien_bac', 'Vịnh Hạ Long trên cạn với hang động kỳ vĩ'),
('Đà Nẵng', 'Đà Nẵng', 'mien_trung', 'Thành phố đáng sống với cầu Rồng và bãi biển Mỹ Khê'),
('Cần Thơ', 'Cần Thơ', 'mien_nam', 'Thủ phủ miền Tây với chợ nổi Cái Răng nổi tiếng');

-- Mã khuyến mãi
INSERT INTO `ma_khuyen_mai` (`ma`, `loai`, `gia_tri`, `dieu_kien_toi_thieu`, `so_luot_toi_da`, `ngay_bat_dau`, `ngay_ket_thuc`, `mo_ta`) VALUES
('VIETGO10', 'phan_tram', 10, 2000000, 100, '2026-01-01', '2026-12-31', 'Giảm 10% cho đơn từ 2 triệu'),
('SUMMER50', 'so_tien', 500000, 5000000, 50, '2026-04-01', '2026-08-31', 'Giảm 500k mùa hè'),
('NEWUSER', 'phan_tram', 15, 1000000, 200, '2026-01-01', '2026-12-31', 'Ưu đãi khách hàng mới 15%');
