-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: vietgo
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` bigint(20) unsigned NOT NULL,
  `hanh_dong` varchar(100) NOT NULL,
  `doi_tuong` varchar(100) DEFAULT NULL,
  `doi_tuong_id` bigint(20) unsigned DEFAULT NULL,
  `du_lieu_cu` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`du_lieu_cu`)),
  `du_lieu_moi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`du_lieu_moi`)),
  `dia_chi_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (2,'đồ ăn','1775717384_bannermin.png',NULL,1,'2026-04-08 23:49:44','2026-04-08 23:49:44');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cam_nang`
--

DROP TABLE IF EXISTS `cam_nang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cam_nang`
--

LOCK TABLES `cam_nang` WRITE;
/*!40000 ALTER TABLE `cam_nang` DISABLE KEYS */;
/*!40000 ALTER TABLE `cam_nang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chuong_trinh_khuyen_mai`
--

DROP TABLE IF EXISTS `chuong_trinh_khuyen_mai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chuong_trinh_khuyen_mai`
--

LOCK TABLES `chuong_trinh_khuyen_mai` WRITE;
/*!40000 ALTER TABLE `chuong_trinh_khuyen_mai` DISABLE KEYS */;
/*!40000 ALTER TABLE `chuong_trinh_khuyen_mai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `danh_gia`
--

DROP TABLE IF EXISTS `danh_gia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `danh_gia`
--

LOCK TABLES `danh_gia` WRITE;
/*!40000 ALTER TABLE `danh_gia` DISABLE KEYS */;
/*!40000 ALTER TABLE `danh_gia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dat_tour`
--

DROP TABLE IF EXISTS `dat_tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dat_tour`
--

LOCK TABLES `dat_tour` WRITE;
/*!40000 ALTER TABLE `dat_tour` DISABLE KEYS */;
INSERT INTO `dat_tour` VALUES (1,'DT2604090001',5,11,NULL,1,0,0,2700000,0,2700000,NULL,'hoan_thanh',NULL,1,'2026-04-08 20:01:09','2026-04-08 20:03:04'),(2,'DT2604090002',5,11,NULL,3,2,0,10900000,0,10900000,NULL,'da_huy','tôi muốn huy tour',NULL,'2026-04-08 20:19:04','2026-04-08 20:19:57'),(3,'DT2604090003',5,11,NULL,1,0,0,2700000,0,2700000,NULL,'da_huy','tôi muốn hủy điwn',NULL,'2026-04-08 20:20:59','2026-04-08 20:21:35'),(4,'DT2604090004',5,11,NULL,1,0,0,2700000,0,2700000,NULL,'da_huy','tôi muốn hủy',NULL,'2026-04-08 20:26:50','2026-04-08 20:27:06'),(5,'DT2604090005',5,11,NULL,1,0,0,2700000,0,2700000,NULL,'da_huy','tôi muốn hủy đơn',NULL,'2026-04-08 20:28:12','2026-04-08 20:33:18'),(6,'DT2604090006',5,11,NULL,1,0,0,10000,0,10000,NULL,'hoan_thanh',NULL,1,'2026-04-08 20:33:25','2026-04-08 20:34:27'),(7,'DT2604090007',5,11,NULL,1,0,0,10000,0,10000,NULL,'da_huy','tôi muốn hủy',NULL,'2026-04-08 20:43:24','2026-04-08 20:55:06'),(8,'DT2604090008',5,11,NULL,1,0,0,10000,0,10000,NULL,'da_huy','hủy thanh toắn',NULL,'2026-04-08 20:55:22','2026-04-08 21:01:03'),(9,'DT2604090009',5,11,NULL,1,0,0,10000,0,10000,NULL,'da_huy','fsdgsdgsdgsdg',NULL,'2026-04-08 21:01:10','2026-04-08 21:01:25'),(10,'DT2604090010',5,11,NULL,1,0,0,10000,0,10000,NULL,'cho_duyet',NULL,NULL,'2026-04-08 22:10:38','2026-04-08 22:10:38'),(13,'DT2604090011',5,11,NULL,1,0,0,10000,0,10000,NULL,'hoan_thanh',NULL,1,'2026-04-08 22:18:52','2026-04-08 22:19:20'),(14,'DT2604090012',5,11,NULL,1,0,0,10000,0,10000,NULL,'hoan_thanh',NULL,1,'2026-04-08 22:26:18','2026-04-08 22:26:36'),(15,'DT2604090013',5,11,NULL,1,0,0,10000,0,10000,NULL,'hoan_thanh',NULL,1,'2026-04-08 22:35:38','2026-04-08 22:37:22');
/*!40000 ALTER TABLE `dat_tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diem_den`
--

DROP TABLE IF EXISTS `diem_den`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diem_den`
--

LOCK TABLES `diem_den` WRITE;
/*!40000 ALTER TABLE `diem_den` DISABLE KEYS */;
INSERT INTO `diem_den` VALUES (1,'Vịnh Hạ Long','Quảng Ninh','mien_bac','Di sản thiên nhiên thế giới với hàng nghìn đảo đá vôi',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(2,'Phố Cổ Hội An','Quảng Nam','mien_trung','Đô thị cổ được UNESCO công nhận là di sản văn hóa thế giới',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(3,'Đà Lạt','Lâm Đồng','mien_nam','Thành phố ngàn hoa, khí hậu mát mẻ quanh năm',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(4,'Nha Trang','Khánh Hòa','mien_trung','Thành phố biển nổi tiếng với bãi biển đẹp và hải sản tươi ngon',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(5,'Phú Quốc','Kiên Giang','mien_nam','Đảo ngọc thiên đường với bãi biển trong xanh',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(6,'Sapa','Lào Cai','mien_bac','Cao nguyên mù sương với ruộng bậc thang tuyệt đẹp',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(7,'Mũi Né','Bình Thuận','mien_nam','Thiên đường cát trắng và lướt ván diều',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(8,'Ninh Bình','Ninh Bình','mien_bac','Vịnh Hạ Long trên cạn với hang động kỳ vĩ',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(9,'Đà Nẵng','Đà Nẵng','mien_trung','Thành phố đáng sống với cầu Rồng và bãi biển Mỹ Khê',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56'),(10,'Cần Thơ','Cần Thơ','mien_nam','Thủ phủ miền Tây với chợ nổi Cái Răng nổi tiếng',NULL,1,'2026-04-07 11:47:56','2026-04-07 11:47:56');
/*!40000 ALTER TABLE `diem_den` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hanh_khach`
--

DROP TABLE IF EXISTS `hanh_khach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hanh_khach`
--

LOCK TABLES `hanh_khach` WRITE;
/*!40000 ALTER TABLE `hanh_khach` DISABLE KEYS */;
INSERT INTO `hanh_khach` VALUES (1,1,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:01:09'),(2,2,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:19:04'),(3,2,'hoàng văn thượng - Khách người lớn 1','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:19:04'),(4,2,'hoàng văn thượng - Khách người lớn 2','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:19:04'),(5,2,'hoàng văn thượng - Khách trẻ em 1','2018-04-09','nam',NULL,NULL,'tre_em','2026-04-09 03:19:04'),(6,2,'hoàng văn thượng - Khách trẻ em 2','2018-04-09','nam',NULL,NULL,'tre_em','2026-04-09 03:19:04'),(7,3,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:20:59'),(8,4,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:26:50'),(9,5,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:28:12'),(10,6,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:33:25'),(11,7,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:43:24'),(12,8,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 03:55:22'),(13,9,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 04:01:10'),(14,10,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 05:10:38'),(17,13,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 05:18:52'),(18,14,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 05:26:18'),(19,15,'hoàng văn thượng','2006-04-09','nam',NULL,NULL,'nguoi_lon','2026-04-09 05:35:38');
/*!40000 ALTER TABLE `hanh_khach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hinh_anh_tour`
--

DROP TABLE IF EXISTS `hinh_anh_tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hinh_anh_tour`
--

LOCK TABLES `hinh_anh_tour` WRITE;
/*!40000 ALTER TABLE `hinh_anh_tour` DISABLE KEYS */;
/*!40000 ALTER TABLE `hinh_anh_tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lich_khoi_hanh`
--

DROP TABLE IF EXISTS `lich_khoi_hanh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lich_khoi_hanh`
--

LOCK TABLES `lich_khoi_hanh` WRITE;
/*!40000 ALTER TABLE `lich_khoi_hanh` DISABLE KEYS */;
INSERT INTO `lich_khoi_hanh` VALUES (11,9,'2026-10-05','2026-12-05',50,44,NULL,NULL,'con_cho','2026-04-08 18:54:20','2026-04-08 22:35:38');
/*!40000 ALTER TABLE `lich_khoi_hanh` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lich_trinh_tour`
--

DROP TABLE IF EXISTS `lich_trinh_tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lich_trinh_tour`
--

LOCK TABLES `lich_trinh_tour` WRITE;
/*!40000 ALTER TABLE `lich_trinh_tour` DISABLE KEYS */;
/*!40000 ALTER TABLE `lich_trinh_tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ma_khuyen_mai`
--

DROP TABLE IF EXISTS `ma_khuyen_mai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ma_khuyen_mai`
--

LOCK TABLES `ma_khuyen_mai` WRITE;
/*!40000 ALTER TABLE `ma_khuyen_mai` DISABLE KEYS */;
/*!40000 ALTER TABLE `ma_khuyen_mai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_04_09_004810_create_banners_table',1),(2,'2026_04_09_065340_change_banners_string_limits',2),(3,'2026_04_12_054713_create_lich_su_dang_nhaps_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lich_su_dang_nhap`
--

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


--
-- Table structure for table `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nguoi_dung` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nguoi_dung`
--

LOCK TABLES `nguoi_dung` WRITE;
/*!40000 ALTER TABLE `nguoi_dung` DISABLE KEYS */;
INSERT INTO `nguoi_dung` VALUES (1,'Administrator Mặc Định','admin','$2y$12$pbRd9ZXHa3kABQE2z5Ny4uBxut55DLHTmUaDvyuWHZ4CHBsRmpp.6','0999999999',NULL,'default-avatar.png','admin',1,NULL,'2026-04-07 11:47:56','2026-04-07 11:47:56','2026-04-07 11:58:25'),(4,'đinh bá vũ','dinhbavu2k4@gmail.com','$2y$12$ooRS0Z4pR0OCVUcw2Q8cNeefC9z6l.TlIJ9nl.Qxg0mRzpBwKH7DK','0363102985',NULL,'default-avatar.png','nhan_vien',1,NULL,NULL,'2026-04-07 05:54:44','2026-04-07 05:54:44'),(5,'hoàng văn thượng','hoangthuong@gmail.com','$2y$12$CTK.RHGxm6XrUCW9URVWr.27Gmrv5TWYPm41r/zFoE5oNAV1BD7SS','0363102985',NULL,'1775627975_5.jpg','khach_hang',1,NULL,NULL,'2026-04-07 07:01:57','2026-04-07 22:59:36');
/*!40000 ALTER TABLE `nguoi_dung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thanh_toan`
--

DROP TABLE IF EXISTS `thanh_toan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thanh_toan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dat_tour_id` bigint(20) unsigned NOT NULL,
  `so_tien` decimal(14,0) NOT NULL,
  `phuong_thuc` enum('vnpay','momo','cod','chuyen_khoan','zalopay','tien_mat') NOT NULL,
  `ma_giao_dich` varchar(100) DEFAULT NULL,
  `trang_thai` enum('cho_xu_ly','thanh_cong','that_bai','hoan_tien','da_thanh_toan') NOT NULL DEFAULT 'cho_xu_ly',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `dat_tour_id` (`dat_tour_id`),
  CONSTRAINT `thanh_toan_ibfk_1` FOREIGN KEY (`dat_tour_id`) REFERENCES `dat_tour` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thanh_toan`
--

LOCK TABLES `thanh_toan` WRITE;
/*!40000 ALTER TABLE `thanh_toan` DISABLE KEYS */;
INSERT INTO `thanh_toan` VALUES (1,1,2700000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:01:09','2026-04-08 20:01:09'),(2,2,10900000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:19:04','2026-04-08 20:19:04'),(3,3,2700000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:20:59','2026-04-08 20:20:59'),(4,4,2700000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:26:50','2026-04-08 20:26:50'),(5,5,2700000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:28:12','2026-04-08 20:28:12'),(6,6,10000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:33:25','2026-04-08 20:33:25'),(7,7,10000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:43:24','2026-04-08 20:43:24'),(8,8,10000,'chuyen_khoan',NULL,'cho_xu_ly',NULL,'2026-04-08 20:55:22','2026-04-08 20:55:22'),(9,9,10000,'momo',NULL,'cho_xu_ly',NULL,'2026-04-08 21:01:10','2026-04-08 21:01:10'),(10,10,10000,'momo',NULL,'cho_xu_ly',NULL,'2026-04-08 22:10:38','2026-04-08 22:10:38'),(11,13,10000,'momo','DEMO_20260409052203','da_thanh_toan','Tự động xác nhận qua SePay webhook. Nhận 10,000đ.','2026-04-08 22:18:52','2026-04-08 22:22:03'),(12,14,10000,'chuyen_khoan','DEMO_20260409052702','da_thanh_toan','Tự động xác nhận qua SePay webhook. Nhận 10,000đ.','2026-04-08 22:26:18','2026-04-08 22:27:02'),(13,15,10000,'chuyen_khoan','DEMO_20260409053612','thanh_cong','Tự động xác nhận qua SePay webhook. Nhận 10,000đ.','2026-04-08 22:35:38','2026-04-08 22:36:12');
/*!40000 ALTER TABLE `thanh_toan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thong_bao`
--

DROP TABLE IF EXISTS `thong_bao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thong_bao`
--

LOCK TABLES `thong_bao` WRITE;
/*!40000 ALTER TABLE `thong_bao` DISABLE KEYS */;
INSERT INTO `thong_bao` VALUES (1,5,'Đặt tour thành công!','Mã đặt tour DT2604090001 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/1/chi-tiet','2026-04-09 03:01:09'),(2,5,'Tour đã được xác nhận!','Đơn đặt tour DT2604090001 đã được xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/1/chi-tiet','2026-04-09 03:02:32'),(3,5,'Đặt tour thành công!','Mã đặt tour DT2604090002 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/2/chi-tiet','2026-04-09 03:19:04'),(4,5,'Hủy tour thành công','Tour DT2604090002 đã được hủy.','huy_tour',0,NULL,'2026-04-09 03:19:57'),(5,5,'Đặt tour thành công!','Mã đặt tour DT2604090003 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/3/chi-tiet','2026-04-09 03:20:59'),(6,5,'Hủy tour thành công','Tour DT2604090003 đã được hủy.','huy_tour',0,NULL,'2026-04-09 03:21:35'),(7,5,'Đặt tour thành công!','Mã đặt tour DT2604090004 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/4/chi-tiet','2026-04-09 03:26:50'),(8,5,'Hủy tour thành công','Tour DT2604090004 đã được hủy.','huy_tour',0,NULL,'2026-04-09 03:27:06'),(9,5,'Đặt tour thành công!','Mã đặt tour DT2604090005 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/5/chi-tiet','2026-04-09 03:28:12'),(10,5,'Hủy tour thành công','Tour DT2604090005 đã được hủy.','huy_tour',0,NULL,'2026-04-09 03:33:18'),(11,5,'Đặt tour thành công!','Mã đặt tour DT2604090006 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/6/chi-tiet','2026-04-09 03:33:25'),(12,5,'Tour đã được xác nhận!','Đơn đặt tour DT2604090006 đã được xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/6/chi-tiet','2026-04-09 03:34:11'),(13,5,'Đặt tour thành công!','Mã đặt tour DT2604090007 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/7/chi-tiet','2026-04-09 03:43:24'),(14,5,'Hủy tour thành công','Tour DT2604090007 đã được hủy.','huy_tour',0,NULL,'2026-04-09 03:55:06'),(15,5,'Đặt tour thành công!','Mã đặt tour DT2604090008 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/8/chi-tiet','2026-04-09 03:55:22'),(16,5,'Hủy tour thành công','Tour DT2604090008 đã được hủy.','huy_tour',0,NULL,'2026-04-09 04:01:03'),(17,5,'Đặt tour thành công!','Mã đặt tour DT2604090009 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/9/chi-tiet','2026-04-09 04:01:10'),(18,5,'Hủy tour thành công','Tour DT2604090009 đã được hủy.','huy_tour',0,NULL,'2026-04-09 04:01:25'),(19,5,'Đặt tour thành công!','Mã đặt tour DT2604090010 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/10/chi-tiet','2026-04-09 05:10:38'),(20,5,'Đặt tour thành công!','Mã đặt tour DT2604090011 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/13/chi-tiet','2026-04-09 05:18:52'),(21,5,'Tour đã được xác nhận!','Đơn đặt tour DT2604090011 đã được xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/13/chi-tiet','2026-04-09 05:19:18'),(22,5,'Đặt tour thành công!','Mã đặt tour DT2604090012 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/14/chi-tiet','2026-04-09 05:26:18'),(23,5,'Tour đã được xác nhận!','Đơn đặt tour DT2604090012 đã được xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/14/chi-tiet','2026-04-09 05:26:34'),(24,5,'Đặt tour thành công!','Mã đặt tour DT2604090013 đang chờ xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/15/chi-tiet','2026-04-09 05:35:38'),(25,5,'Tour đã được xác nhận!','Đơn đặt tour DT2604090013 đã được xác nhận.','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/15/chi-tiet','2026-04-09 05:35:50'),(26,5,'✅ Thanh toán thành công!','Hệ thống đã nhận 10,000đ cho đơn DT2604090013. Đơn tour đã được XÁC NHẬN tự động. Chúc bạn có chuyến đi vui vẻ!','dat_tour',0,'http://localhost/VietGo/public/index.php/khach-hang/dat-tour/15/chi-tiet','2026-04-09 05:36:12');
/*!40000 ALTER TABLE `thong_bao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour`
--

DROP TABLE IF EXISTS `tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour`
--

LOCK TABLES `tour` WRITE;
/*!40000 ALTER TABLE `tour` DISABLE KEYS */;
INSERT INTO `tour` VALUES (9,'VG-MN09','Nắng Gió Mũi Né',7,'trong_nuoc',3,2,'Trượt cát, tắm biển, tham quan suối Tiên','Đón bình minh tại đồi cát Bàu Trắng, lội nước ở Suối Tiên, tham quan tháp chàm Poshanư, Làng chài Mũi Né. Ăn hải sản bờ kè tươi ngon giá rẻ.',10000,10000,0,'dTIHllGH1lhBixrVBRYb7ZqoP9wdbiE78gM1H6S8.jpg',935,4.5,18,0,0,'hoat_dong',1,'2026-04-07 11:47:56','2026-04-08 22:35:34');
/*!40000 ALTER TABLE `tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `yeu_thich`
--

DROP TABLE IF EXISTS `yeu_thich`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `yeu_thich`
--

LOCK TABLES `yeu_thich` WRITE;
/*!40000 ALTER TABLE `yeu_thich` DISABLE KEYS */;
/*!40000 ALTER TABLE `yeu_thich` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-09 13:55:27
