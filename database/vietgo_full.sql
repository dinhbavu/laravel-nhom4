SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` bigint(20) unsigned NOT NULL,
  `hanh_dong` varchar(100) NOT NULL,
  `doi_tuong` varchar(100) DEFAULT NULL,
  `doi_tuong_id` bigint(20) unsigned DEFAULT NULL,
  `du_lieu_cu` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `du_lieu_moi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `dia_chi_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tieu_de` varchar(255) NOT NULL,
  `hinh_anh_url` varchar(2000) NOT NULL,
  `duong_dan` varchar(2000) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cam_nang`;
CREATE TABLE `cam_nang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tieu_de` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `chuyen_muc` enum('meo_du_lich','am_thuc','diem_den') NOT NULL,
  `tom_tat` text DEFAULT NULL,
  `noi_dung` longtext NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `luot_xem` int(10) unsigned DEFAULT 0,
  `nguoi_dang_id` bigint(20) unsigned NOT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `nguoi_dang_id` (`nguoi_dang_id`),
  CONSTRAINT `cam_nang_ibfk_1` FOREIGN KEY (`nguoi_dang_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `chuong_trinh_khuyen_mai`;
CREATE TABLE `chuong_trinh_khuyen_mai` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ten_chuong_trinh` varchar(200) NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `danh_gia`;
CREATE TABLE `danh_gia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` bigint(20) unsigned NOT NULL,
  `khach_hang_id` bigint(20) unsigned NOT NULL,
  `dat_tour_id` bigint(20) unsigned NOT NULL,
  `diem_so` tinyint(3) unsigned NOT NULL DEFAULT 5 COMMENT '1-5',
  `tieu_de` varchar(200) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `trang_thai` enum('cho_duyet','da_duyet','an') NOT NULL DEFAULT 'cho_duyet',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `khach_hang_id` (`khach_hang_id`),
  KEY `dat_tour_id` (`dat_tour_id`),
  CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung` (`id`),
  CONSTRAINT `danh_gia_ibfk_3` FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `dat_tour`;
CREATE TABLE `dat_tour` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ma_dat_tour` varchar(20) NOT NULL,
  `khach_hang_id` bigint(20) unsigned NOT NULL,
  `lich_khoi_hanh_id` bigint(20) unsigned NOT NULL,
  `ma_khuyen_mai_id` bigint(20) unsigned DEFAULT NULL,
  `so_nguoi_lon` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `so_tre_em` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `so_em_be` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `tong_tien_goc` decimal(14,0) NOT NULL,
  `giam_gia` decimal(14,0) NOT NULL DEFAULT 0,
  `tong_tien_thanh_toan` decimal(14,0) NOT NULL,
  `tong_tien_da_thanh_toan` decimal(14,0) NOT NULL DEFAULT 0,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` enum('cho_duyet','da_duyet','da_xac_nhan','da_huy','hoan_thanh','done') NOT NULL DEFAULT 'cho_duyet',
  `ly_do_huy` text DEFAULT NULL,
  `nguoi_duyet_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ma_dat_tour` (`ma_dat_tour`),
  KEY `khach_hang_id` (`khach_hang_id`),
  KEY `lich_khoi_hanh_id` (`lich_khoi_hanh_id`),
  KEY `ma_khuyen_mai_id` (`ma_khuyen_mai_id`),
  KEY `nguoi_duyet_id` (`nguoi_duyet_id`),
  CONSTRAINT `dat_tour_ibfk_1` FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung` (`id`),
  CONSTRAINT `dat_tour_ibfk_2` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`),
  CONSTRAINT `dat_tour_ibfk_3` FOREIGN KEY (`ma_khuyen_mai_id`) REFERENCES `ma_khuyen_mai` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dat_tour_ibfk_4` FOREIGN KEY (`nguoi_duyet_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `diem_den`;
CREATE TABLE `diem_den` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ten_diem_den` varchar(150) NOT NULL,
  `tinh_thanh` varchar(100) DEFAULT NULL,
  `vung_mien` enum('mien_bac','mien_trung','mien_nam') NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hanh_khach`;
CREATE TABLE `hanh_khach` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dat_tour_id` bigint(20) unsigned NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `ngay_sinh` date NOT NULL,
  `gioi_tinh` enum('nam','nu') NOT NULL,
  `so_cmnd` varchar(20) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `loai` enum('nguoi_lon','tre_em','em_be') NOT NULL DEFAULT 'nguoi_lon',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `dat_tour_id` (`dat_tour_id`),
  CONSTRAINT `hanh_khach_ibfk_1` FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hinh_anh_tour`;
CREATE TABLE `hinh_anh_tour` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` bigint(20) unsigned NOT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `thu_tu` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `hinh_anh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `lich_khoi_hanh`;
CREATE TABLE `lich_khoi_hanh` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` bigint(20) unsigned NOT NULL,
  `ngay_di` date NOT NULL,
  `ngay_ve` date NOT NULL,
  `so_cho_toi_da` smallint(5) unsigned NOT NULL,
  `so_cho_con` smallint(5) unsigned NOT NULL,
  `gia_nguoi_lon` decimal(12,0) DEFAULT NULL COMMENT 'Giá override cho lịch này',
  `gia_tre_em` decimal(12,0) DEFAULT NULL,
  `trang_thai` enum('con_cho','het_cho','ngung') NOT NULL DEFAULT 'con_cho',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `lich_khoi_hanh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `lich_trinh_tour`;
CREATE TABLE `lich_trinh_tour` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` bigint(20) unsigned NOT NULL,
  `ngay_thu` tinyint(3) unsigned NOT NULL,
  `tieu_de` varchar(200) NOT NULL,
  `noi_dung` text NOT NULL,
  `bua_an` varchar(200) DEFAULT NULL COMMENT 'VD: Sáng, Trưa, Tối',
  `khach_san` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `lich_trinh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ma_khuyen_mai`;
CREATE TABLE `ma_khuyen_mai` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ma` varchar(50) NOT NULL,
  `loai` enum('phan_tram','so_tien') NOT NULL DEFAULT 'so_tien',
  `gia_tri` decimal(12,0) NOT NULL,
  `gia_tri_toi_da` decimal(12,0) DEFAULT NULL,
  `dieu_kien_toi_thieu` decimal(12,0) NOT NULL DEFAULT 0,
  `so_luot_su_dung` int(10) unsigned NOT NULL DEFAULT 0,
  `so_luot_toi_da` int(10) unsigned NOT NULL DEFAULT 1,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `mo_ta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ma` (`ma`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '0001_01_01_000000_create_users_table', 1),
(4, '0001_01_01_000001_create_cache_table', 1),
(5, '0001_01_01_000002_create_jobs_table', 1),
(6, '2026_04_09_004810_create_banners_table', 1),
(7, '2026_04_09_004812_add_phan_tram_giam_gia_to_tours_table', 1),
(8, '2026_04_09_021316_create_yeu_thich_table', 1),
(9, '2026_04_09_065340_change_banners_string_limits', 1),
(10, '2026_04_12_054713_create_lich_su_dang_nhaps_table', 1),
(11, '2026_04_23_164046_add_gps_to_lich_su_dang_nhap', 1),
(12, '2026_04_23_164558_add_gps_to_lich_su_dang_nhap_table', 1),
(13, '2026_04_24_013021_add_device_id_to_lich_su_dang_nhap_table', 1),
(14, '2026_04_24_024419_update_payment_methods_table', 1),
(15, '2026_05_23_054328_add_bank_fields_and_update_booking_status', 2);

DROP TABLE IF EXISTS `lich_su_dang_nhap`;
CREATE TABLE `lich_su_dang_nhap` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `khach_hang_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `khach_hang_id` (`khach_hang_id`),
  CONSTRAINT `lich_su_dang_nhap_ibfk_1` FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE `nguoi_dung` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `ten_ngan_hang` varchar(100) DEFAULT NULL,
  `so_tai_khoan` varchar(50) DEFAULT NULL,
  `ten_tai_khoan` varchar(150) DEFAULT NULL,
  `anh_dai_dien` varchar(255) DEFAULT 'default-avatar.png',
  `vai_tro` enum('khach_hang','nhan_vien','admin') NOT NULL DEFAULT 'khach_hang',
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hoat dong, 0=khoa',
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`email`) REFERENCES `nguoi_dung` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `thanh_toan`;
CREATE TABLE `thanh_toan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dat_tour_id` bigint(20) unsigned NOT NULL,
  `so_tien` decimal(14,0) NOT NULL,
  `phuong_thuc` enum('vnpay','momo','cod','chuyen_khoan','zalopay','tien_mat','dat_coc') NOT NULL,
  `dia_diem_hen` varchar(255) DEFAULT NULL,
  `thoi_gian_hen` datetime DEFAULT NULL,
  `ma_giao_dich` varchar(100) DEFAULT NULL,
  `trang_thai` enum('cho_xu_ly','thanh_cong','that_bai','hoan_tien','da_thanh_toan','da_dat_coc') NOT NULL DEFAULT 'cho_xu_ly',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `dat_tour_id` (`dat_tour_id`),
  CONSTRAINT `thanh_toan_ibfk_1` FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `thong_bao`;
CREATE TABLE `thong_bao` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nguoi_nhan_id` bigint(20) unsigned NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `loai` varchar(50) NOT NULL DEFAULT 'he_thong',
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `duong_dan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `nguoi_nhan_id` (`nguoi_nhan_id`),
  CONSTRAINT `thong_bao_ibfk_1` FOREIGN KEY (`nguoi_nhan_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tour`;
CREATE TABLE `tour` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ma_tour` varchar(20) NOT NULL,
  `ten_tour` varchar(200) NOT NULL,
  `diem_den_id` bigint(20) unsigned NOT NULL,
  `loai_tour` enum('trong_nuoc','quoc_te','mao_hiem','nghi_duong','van_hoa') NOT NULL DEFAULT 'trong_nuoc',
  `so_ngay` tinyint(3) unsigned NOT NULL,
  `so_dem` tinyint(3) unsigned NOT NULL,
  `mo_ta_ngan` text DEFAULT NULL,
  `mo_ta_day_du` longtext DEFAULT NULL,
  `gia_nguoi_lon` decimal(12,0) NOT NULL,
  `gia_tre_em` decimal(12,0) NOT NULL DEFAULT 0,
  `gia_em_be` decimal(12,0) NOT NULL DEFAULT 0,
  `hinh_bia` varchar(255) DEFAULT NULL,
  `luot_xem` int(10) unsigned NOT NULL DEFAULT 0,
  `danh_gia_trung_binh` decimal(3,1) NOT NULL DEFAULT 0.0,
  `tong_danh_gia` int(10) unsigned NOT NULL DEFAULT 0,
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0,
  `phan_tram_giam_gia` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `trang_thai` enum('con_cho','hoat_dong','ngung') NOT NULL DEFAULT 'hoat_dong',
  `nguoi_tao_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ma_tour` (`ma_tour`),
  KEY `diem_den_id` (`diem_den_id`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`diem_den_id`) REFERENCES `diem_den` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tour_ibfk_2` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `yeu_thich`;
CREATE TABLE `yeu_thich` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `khach_hang_id` bigint(20) unsigned NOT NULL,
  `tour_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_yeu_thich` (`khach_hang_id`,`tour_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `yeu_thich_ibfk_1` FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  CONSTRAINT `yeu_thich_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nguoi_dung` (`ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `email_verified_at`) VALUES ('Quản Trị Viên', 'admin', '$2y$10$luEvEHpjwLq2qgaCDbWOGOu8ncVvaExao1EChE5sdAjQ3SDMTMdiG', '0900000001', 'admin', 1, NOW());

ALTER TABLE `lich_su_dang_nhap` ADD `device_id` VARCHAR(255) NULL DEFAULT NULL AFTER `khach_hang_id`;
ALTER TABLE `lich_su_dang_nhap` ADD `latitude` DECIMAL(10,8) NULL DEFAULT NULL AFTER `ip_address`;
ALTER TABLE `lich_su_dang_nhap` ADD `longitude` DECIMAL(11,8) NULL DEFAULT NULL AFTER `latitude`;

SET FOREIGN_KEY_CHECKS = 1;
