-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 05:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lvtn`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cham_diem_huong_dan`
--

CREATE TABLE `cham_diem_huong_dan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `giangvien_id` bigint(20) UNSIGNED NOT NULL,
  `phan_tich` decimal(5,2) NOT NULL DEFAULT 0.00,
  `thiet_ke` decimal(5,2) NOT NULL DEFAULT 0.00,
  `hien_thuc` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bao_cao` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tong_phan_tram` decimal(5,2) NOT NULL DEFAULT 0.00,
  `diem_10` decimal(4,2) NOT NULL DEFAULT 0.00,
  `ghi_chu` text DEFAULT NULL,
  `noi_dung_dieu_chinh` text DEFAULT NULL,
  `nhan_xet_tong_quat` text DEFAULT NULL,
  `thuyet_minh` enum('dat','khong_dat') DEFAULT NULL,
  `uu_diem` text DEFAULT NULL,
  `thieu_sot` text DEFAULT NULL,
  `cau_hoi` text DEFAULT NULL,
  `de_nghi` enum('duoc_bao_ve','khong_bao_ve','bo_sung') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cham_diem_huong_dan`
--

INSERT INTO `cham_diem_huong_dan` (`id`, `detai_id`, `sinhvien_id`, `giangvien_id`, `phan_tich`, `thiet_ke`, `hien_thuc`, `bao_cao`, `tong_phan_tram`, `diem_10`, `ghi_chu`, `noi_dung_dieu_chinh`, `nhan_xet_tong_quat`, `thuyet_minh`, `uu_diem`, `thieu_sot`, `cau_hoi`, `de_nghi`, `created_at`, `updated_at`) VALUES
(17, 2, 181, 25, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, 'khong_dat', NULL, NULL, NULL, 'duoc_bao_ve', '2026-01-01 07:27:24', '2026-01-01 07:27:24'),
(18, 2, 189, 25, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, 'khong_dat', NULL, NULL, NULL, 'duoc_bao_ve', '2026-01-01 07:27:24', '2026-01-01 07:27:24'),
(19, 1, 174, 41, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-04 19:34:03', '2026-01-04 19:34:03'),
(20, 1, 183, 41, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-04 19:34:03', '2026-01-04 19:34:03'),
(21, 2, 181, 41, 10.00, 10.00, 10.00, 10.00, 40.00, 4.00, NULL, NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve', '2026-01-04 20:15:17', '2026-01-04 20:15:17'),
(22, 2, 189, 41, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve', '2026-01-04 20:15:17', '2026-01-04 20:15:17'),
(23, 3, 180, 36, 20.00, 20.00, 25.00, 10.00, 75.00, 7.50, NULL, NULL, NULL, 'dat', NULL, NULL, NULL, 'bo_sung', '2026-01-04 20:51:28', '2026-01-04 20:51:28'),
(24, 3, 178, 36, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, NULL, NULL, 'dat', NULL, NULL, NULL, 'bo_sung', '2026-01-04 20:51:28', '2026-01-04 20:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `cham_diem_phan_bien`
--

CREATE TABLE `cham_diem_phan_bien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `giangvien_id` bigint(20) UNSIGNED NOT NULL,
  `phan_tich` decimal(5,2) NOT NULL DEFAULT 0.00,
  `thiet_ke` decimal(5,2) NOT NULL DEFAULT 0.00,
  `hien_thuc` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bao_cao` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tong_phan_tram` decimal(5,2) NOT NULL DEFAULT 0.00,
  `diem_10` decimal(4,2) NOT NULL DEFAULT 0.00,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `noi_dung_dieu_chinh` text DEFAULT NULL,
  `nhan_xet_tong_quat` text DEFAULT NULL,
  `thuyet_minh` enum('dat','khong_dat') DEFAULT NULL,
  `uu_diem` text DEFAULT NULL,
  `thieu_sot` text DEFAULT NULL,
  `cau_hoi` text DEFAULT NULL,
  `de_nghi` enum('duoc_bao_ve','khong_bao_ve','bo_sung') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cham_diem_phan_bien`
--

INSERT INTO `cham_diem_phan_bien` (`id`, `detai_id`, `sinhvien_id`, `giangvien_id`, `phan_tich`, `thiet_ke`, `hien_thuc`, `bao_cao`, `tong_phan_tram`, `diem_10`, `ghi_chu`, `created_at`, `updated_at`, `noi_dung_dieu_chinh`, `nhan_xet_tong_quat`, `thuyet_minh`, `uu_diem`, `thieu_sot`, `cau_hoi`, `de_nghi`) VALUES
(10, 1, 174, 25, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, '2026-01-01 05:30:33', '2026-01-01 05:30:33', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(11, 1, 183, 25, 20.00, 20.00, 20.00, 20.00, 80.00, 8.00, NULL, '2026-01-01 05:30:33', '2026-01-01 05:30:33', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(12, 1, 174, 36, 20.00, 20.00, 20.00, 10.00, 70.00, 7.00, NULL, '2026-01-04 20:20:28', '2026-01-04 20:20:28', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(13, 1, 183, 36, 20.00, 20.00, 10.00, 20.00, 70.00, 7.00, NULL, '2026-01-04 20:20:28', '2026-01-04 20:20:28', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(14, 2, 181, 36, 20.00, 10.00, 10.00, 20.00, 60.00, 6.00, NULL, '2026-01-04 20:21:03', '2026-01-04 20:21:03', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(15, 2, 189, 36, 10.00, 20.00, 20.00, 10.00, 60.00, 6.00, NULL, '2026-01-04 20:21:03', '2026-01-04 20:21:03', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(16, 3, 180, 41, 25.00, 20.00, 10.00, 13.00, 68.00, 6.80, NULL, '2026-01-04 20:31:03', '2026-01-04 20:31:03', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve'),
(17, 3, 178, 41, 20.00, 20.00, 20.00, 10.00, 70.00, 7.00, NULL, '2026-01-04 20:31:03', '2026-01-04 20:31:03', NULL, NULL, 'dat', NULL, NULL, NULL, 'duoc_bao_ve');

-- --------------------------------------------------------

--
-- Table structure for table `detai`
--

CREATE TABLE `detai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_detai` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `giangvien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `giangvien_phanbien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhom_sinhvien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hoi_dong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` enum('ca_nhan','nhom') NOT NULL DEFAULT 'ca_nhan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detai`
--

INSERT INTO `detai` (`id`, `ten_detai`, `mo_ta`, `giangvien_id`, `giangvien_phanbien_id`, `nhom_sinhvien_id`, `hoi_dong_id`, `loai`, `created_at`, `updated_at`) VALUES
(1, 'Quản lí danh sách sinh viên', 'Đề tài được cập nhật từ form phân công', 41, 36, 1, 8, 'nhom', '2025-10-13 04:41:39', '2026-01-04 20:23:36'),
(2, 'Web bán giày', 'Đề tài được cập nhật từ form phân công', 41, 36, 2, 9, 'ca_nhan', '2025-10-13 04:41:39', '2026-01-04 20:23:40'),
(3, 'Đề tài Nhóm 4', 'Đề tài được tạo tự động khi phân công GVHD', 36, 41, 4, 8, 'nhom', '2025-12-05 09:00:09', '2026-01-04 20:31:32'),
(4, 'Todo List', 'Đề tài được cập nhật từ form phân công', 41, 58, 3, NULL, 'nhom', '2025-12-07 18:34:34', '2026-01-04 19:08:05'),
(5, 'Đề tài Nhóm 5', 'Đề tài được tạo tự động khi phân công GVHD', 36, NULL, 5, NULL, 'nhom', '2025-12-07 18:39:15', '2026-01-04 19:08:41'),
(6, 'Đề tài Nhóm 6', 'Đề tài được tạo tự động khi phân công GVHD', 58, 41, 6, NULL, 'nhom', '2025-12-07 18:42:48', '2026-01-04 20:43:34'),
(7, 'Đề tài Nhóm 7', 'Đề tài được tạo tự động khi phân công GVHD', 33, 36, 7, NULL, 'nhom', '2025-12-07 18:44:10', '2026-01-04 20:43:41'),
(8, 'Kinh doanh quảng cáo', 'Đề tài được cập nhật từ form phân công', 41, 36, 8, NULL, 'nhom', '2025-12-07 18:50:26', '2026-01-04 20:17:56'),
(9, 'Quản lí phòng trọ', 'Đề tài được cập nhật từ form phân công', 41, NULL, 9, NULL, 'nhom', '2025-12-24 00:13:34', '2025-12-31 18:47:37'),
(10, 'Chưa cập nhật tên đề tài', 'Đề tài tạm được tạo khi phân công giảng viên trước', 34, NULL, 10, NULL, 'nhom', '2025-12-31 18:47:41', '2025-12-31 18:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `diem_bao_ve`
--

CREATE TABLE `diem_bao_ve` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `diem_bao_ve` decimal(5,2) DEFAULT NULL COMMENT 'Điểm bảo vệ nhập vào (chưa nhân 0.6)',
  `diem_tong` decimal(5,2) DEFAULT NULL COMMENT 'Điểm tổng sau khi tính toán',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giangvien`
--

CREATE TABLE `giangvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nguoidung_id` bigint(20) UNSIGNED DEFAULT NULL,
  `magv` varchar(20) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `bo_mon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giangvien`
--

INSERT INTO `giangvien` (`id`, `nguoidung_id`, `magv`, `hoten`, `email`, `sdt`, `bo_mon`, `created_at`, `updated_at`) VALUES
(25, 1, 'GV0001', 'Admin Khoa CNTT', 'admin.cntt@stu.edu.vn', '0900000000', 'CNTT', '2025-12-24 00:16:39', '2025-12-24 00:16:39'),
(33, 9, 'GV001', 'Ngô Xuân Bách', 'ngoxuanbach@gmail.com', '0933535435', NULL, '2025-12-31 03:10:27', '2025-12-31 03:10:27'),
(34, 10, 'GV002', 'Nguyễn Lạc An Thư', 'thu.nguyenlacan@stu.edu.vn', NULL, NULL, '2025-12-31 03:13:27', '2025-12-31 03:13:27'),
(35, 11, 'GV003', 'Trần Thị Hồng Vân', 'van.tranthihong@stu.edu.vn', NULL, NULL, '2025-12-31 03:15:08', '2025-12-31 03:15:08'),
(36, 12, 'GV004', 'Lương An Vinh', 'vinh.luongan@stu.edu.vn', NULL, NULL, '2025-12-31 03:15:26', '2025-12-31 03:15:26'),
(37, 13, 'GV005', 'Trần Văn Hùng', 'hung.tranvan@stu.edu.vn', NULL, NULL, '2025-12-31 03:15:47', '2025-12-31 03:15:47'),
(38, 14, 'GV006', 'Nguyễn Văn An', 'an.nguyenvan@stu.edu.vn', NULL, NULL, '2025-12-31 03:16:06', '2025-12-31 03:16:06'),
(39, 15, 'GV007', 'Trần Thị Bình', 'binh.tranthi@stu.edu.vn', NULL, NULL, '2025-12-31 03:16:24', '2025-12-31 03:16:24'),
(40, 16, 'GV008', 'Lê Cường', 'cuong.le@stu.edu.vn', NULL, NULL, '2025-12-31 03:16:41', '2025-12-31 03:16:41'),
(41, 17, 'GV009', 'Bùi Nhật Bằng', 'bang.buinhat@stu.edu.vn', NULL, NULL, '2025-12-31 03:17:06', '2025-12-31 03:17:06'),
(42, 18, 'GV011', 'Đoàn Trình Dục', 'duc.doantrinh@stu.edu.vn', NULL, NULL, '2025-12-31 03:18:00', '2025-12-31 03:18:00'),
(43, 19, 'GV012', 'Lê Thị Mỹ Dung', 'dung.lethimy@stu.edu.vn', NULL, NULL, '2025-12-31 03:18:15', '2025-12-31 03:18:15'),
(44, 20, 'GV013', 'Trịnh Thanh Duy', 'duy.trinhthanh@stu.edu.vn', NULL, NULL, '2025-12-31 03:18:29', '2025-12-31 03:18:29'),
(45, 21, 'GV014', 'Dương Văn Đeo', 'deo.duongvan@stu.edu.vn', NULL, NULL, '2025-12-31 03:18:44', '2025-12-31 03:18:44'),
(46, 22, 'GV015', 'Huỳnh Quang Đức', 'duc.huynhquang@stu.edu.vn', NULL, NULL, '2025-12-31 03:19:20', '2025-12-31 03:19:20'),
(47, 23, 'GV016', 'Lê Triệu Ngọc Đức', 'duc.letrieungoc@stu.edu.vn', NULL, NULL, '2025-12-31 03:19:41', '2025-12-31 03:19:41'),
(48, 24, 'GV017', 'Nguyễn Thị Ngân Hà', 'ha.nguyenthingan@stu.edu.vn', NULL, NULL, '2025-12-31 03:20:01', '2025-12-31 03:20:01'),
(49, 25, 'GV019', 'Hồ Đình Khả', 'kha.hodinh@stu.edu.vn', NULL, NULL, '2025-12-31 03:20:25', '2025-12-31 03:20:25'),
(50, 26, 'GV020', 'Nguyễn Thường Kiệt', 'kiet.nguyenthuong@stu.edu.vn', NULL, NULL, '2025-12-31 03:20:42', '2025-12-31 03:20:42'),
(51, 27, 'GV021', 'Khuất Bá Duy Lâm', 'lam.khuatbaduy@stu.edu.vn', NULL, NULL, '2025-12-31 03:21:02', '2025-12-31 03:21:02'),
(52, 28, 'GV022', 'Nguyễn Ngọc Lâm', 'lam.nguyenngoc@stu.edu.vn', NULL, NULL, '2025-12-31 03:21:12', '2025-12-31 03:21:12'),
(53, 29, 'GV023', 'Nguyễn Hồng Bửu Long', 'long.nguyenhongbuu@stu.edu.vn', NULL, NULL, '2025-12-31 03:21:25', '2025-12-31 03:21:25'),
(54, 30, 'GV024', 'Nguyễn Trọng Nghĩa', 'nghia.nguyentrong@stu.edu.vn', NULL, NULL, '2025-12-31 03:21:44', '2025-12-31 03:21:44'),
(55, 31, 'GV025', 'Nguyễn Minh Sang', 'sang.nguyenminh@stu.edu.vn', NULL, NULL, '2025-12-31 03:22:08', '2025-12-31 03:22:08'),
(56, 32, 'GV026', 'Nguyễn Trần Phúc Thịnh', 'thinh.nguyentranphuc@stu.edu.vn', NULL, NULL, '2025-12-31 03:22:27', '2025-12-31 03:22:27'),
(57, 33, 'GV027', 'Trần Quốc Trường', 'truong.tranquoc@stu.edu.vn', NULL, NULL, '2025-12-31 03:22:40', '2025-12-31 03:22:40'),
(58, 34, 'GV028', 'Nguyễn Thanh Tùng', 'tung.nguyenthanh@stu.edu.vn', NULL, NULL, '2025-12-31 03:23:01', '2025-12-31 03:23:01'),
(59, 35, 'GV029', 'Trần Vũ Hoàng Ưng', 'ung.tranvuhoang@stu.edu.vn', NULL, NULL, '2025-12-31 03:23:23', '2025-12-31 03:23:23'),
(60, 36, 'GV031', 'Hà Anh Vũ', 'vu.haanh@stu.edu.vn', NULL, NULL, '2025-12-31 03:23:33', '2025-12-31 03:23:33'),
(61, 37, 'GV032', 'Mai Vân Phương Vũ', 'vu.maivanphuong@stu.edu.vn', NULL, NULL, '2025-12-31 03:23:53', '2025-12-31 03:23:53'),
(62, 38, 'NHK07', 'Nguyễn Hữu Kiên', 'kien113214@gmail.com', '0392965734', NULL, '2026-01-01 05:13:07', '2026-01-01 05:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `hoi_dong`
--

CREATE TABLE `hoi_dong` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_hoi_dong` varchar(255) NOT NULL,
  `ngay_bao_ve` date NOT NULL,
  `phong_bao_ve` varchar(100) DEFAULT NULL,
  `chu_tich_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thu_ky_id` bigint(20) UNSIGNED NOT NULL,
  `uy_vien_1_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uy_vien_2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hoi_dong`
--

INSERT INTO `hoi_dong` (`id`, `ten_hoi_dong`, `ngay_bao_ve`, `phong_bao_ve`, `chu_tich_id`, `thu_ky_id`, `uy_vien_1_id`, `uy_vien_2_id`, `created_at`, `updated_at`) VALUES
(8, 'Hội đồng 1', '2026-01-08', 'C703', 46, 45, 55, 36, '2026-01-04 20:23:12', '2026-01-04 20:23:12'),
(9, 'Hội đồng 2', '2026-01-17', 'C703', 34, 55, 33, 62, '2026-01-04 20:23:22', '2026-01-04 20:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_09_23_071242_create_personal_access_tokens_table', 1),
(4, '2025_10_05_060529_create_nguoidung_table', 1),
(5, '2025_10_05_060530_create_giangvien_table', 1),
(6, '2025_10_05_060531_create_sinhvien_table', 1),
(7, '2025_10_05_060532_create_detai_table', 1),
(8, '2025_10_05_060533_create_moc_thoi_gians_table', 1),
(9, '2025_10_05_060534_create_phancong_table', 1),
(10, '2025_10_13_063526_create_phieu_danh_gia_table', 1),
(11, '2025_10_13_064215_create_nhom_sinhvien_table', 1),
(12, '2025_10_13_064241_create_nhom_sinhvien_chitiet_table', 1),
(13, '2025_12_09_000002_add_giangvien_phanbien_to_detai_table', 2),
(14, '2025_12_09_000003_create_cham_diem_huong_dan_table', 3),
(15, '2025_12_15_015252_create_hoi_dong_table', 4),
(16, '2025_12_15_020535_add_hoi_dong_id_to_detai_table', 5),
(17, '2025_12_15_030000_create_diem_bao_ve_table', 6),
(18, '2025_12_21_033311_add_nhan_xet_fields_to_cham_diem_huong_dan_table', 7),
(19, '2025_12_21_041354_create_nhap_diem_bao_ve_table', 8),
(20, '2025_12_09_000004_create_cham_diem_phan_bien_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `moc_thoi_gian`
--

CREATE TABLE `moc_thoi_gian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_moc` varchar(255) NOT NULL,
  `ngay_batdau` date NOT NULL,
  `ngay_ketthuc` date NOT NULL,
  `mota` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `moc_thoi_gian`
--

INSERT INTO `moc_thoi_gian` (`id`, `ten_moc`, `ngay_batdau`, `ngay_ketthuc`, `mota`, `created_at`, `updated_at`) VALUES
(1, 'Nhập đề tài', '2025-03-01', '2025-03-15', 'Sinh viên nhập đề tài và đăng ký nhóm', NULL, NULL),
(2, 'Phân công giảng viên hướng dẫn', '2025-03-16', '2025-03-25', 'Admin và khoa phân công GVHD cho sinh viên', NULL, NULL),
(3, 'Báo cáo tiến độ lần 1', '2025-04-01', '2025-04-10', 'Sinh viên nộp báo cáo tiến độ đầu tiên', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `sdt` varchar(255) DEFAULT NULL,
  `vaitro` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `hoten`, `email`, `matkhau`, `remember_token`, `sdt`, `vaitro`, `created_at`, `updated_at`) VALUES
(1, 'Admin Khoa CNTT', 'admin.cntt@stu.edu.vn', '$2y$12$Owi8r6KXr2pJoJAKK0btNOQtBLDMuSLlBnjbHGWzQlTcd/gMIUhWa', 'QaZxYUMPOnCkeUUoKho7pXPBcqB35zGnkQatnlV8l2d8eaSdngL5ofH1FK2U', '0900000000', 'admin', NULL, NULL),
(9, 'Ngô Xuân Bách', 'ngoxuanbach@gmail.com', '$2y$12$7EFRKlUSM.PT5cC5bniXcO63FUuBmhyswAy10gmd1Wj7Z23IZIFSi', NULL, '0933535435', 'giangvien', '2025-12-31 03:10:27', '2025-12-31 03:10:27'),
(10, 'Nguyễn Lạc An Thư', 'thu.nguyenlacan@stu.edu.vn', '$2y$12$xORXQqMO/c3B0m62tJ74oufpxlj3rMxmosDyyTLtYvxsA6vjSuLxC', NULL, NULL, 'giangvien', '2025-12-31 03:13:27', '2025-12-31 03:13:27'),
(11, 'Trần Thị Hồng Vân', 'van.tranthihong@stu.edu.vn', '$2y$12$zJ6r0EcoMuy6FbWw/f91fOERQGfE/TViDkaQ2HC0LIxwKYPxWcwya', NULL, NULL, 'giangvien', '2025-12-31 03:15:08', '2025-12-31 03:15:08'),
(12, 'Lương An Vinh', 'vinh.luongan@stu.edu.vn', '$2y$12$h1FTgc0IY7VJWyJmH0uuHOnkAwgCyaZrnsVOTEOEKds9EnqPUU.Pm', NULL, NULL, 'giangvien', '2025-12-31 03:15:26', '2025-12-31 03:15:26'),
(13, 'Trần Văn Hùng', 'hung.tranvan@stu.edu.vn', '$2y$12$WDb2JnzKy0SjDoCQVZFKOOWHnv0UjHQBZEietT7Hb7giFW7Epo.U.', NULL, NULL, 'giangvien', '2025-12-31 03:15:47', '2025-12-31 03:15:47'),
(14, 'Nguyễn Văn An', 'an.nguyenvan@stu.edu.vn', '$2y$12$Hwhadv6rL2i9CdJ8ESOnDeFfKrxqCHHM4sTUAUZb6.TgSyijW.yB6', NULL, NULL, 'giangvien', '2025-12-31 03:16:06', '2025-12-31 03:16:06'),
(15, 'Trần Thị Bình', 'binh.tranthi@stu.edu.vn', '$2y$12$y2lmoBB80OVAqy2KUcqBtO6YWvA/Ym4vieUy.kchBJyqaq/EbkhRC', NULL, NULL, 'giangvien', '2025-12-31 03:16:24', '2025-12-31 03:16:24'),
(16, 'Lê Cường', 'cuong.le@stu.edu.vn', '$2y$12$MNVTMU4et4YyKOLK6NVKve90Sl66F0knDxnEUWvIjOJo9rpfAHHHm', NULL, NULL, 'giangvien', '2025-12-31 03:16:41', '2025-12-31 03:16:41'),
(17, 'Bùi Nhật Bằng', 'bang.buinhat@stu.edu.vn', '$2y$12$dPFLNwwKk8MY5gvt0/WJLO.acl.Dk8rRAgLs6RXKe6DSM/fViclcG', NULL, NULL, 'giangvien', '2025-12-31 03:17:06', '2025-12-31 03:17:06'),
(18, 'Đoàn Trình Dục', 'duc.doantrinh@stu.edu.vn', '$2y$12$gGFNTRNwHlHGC2O6xeMAR.QWqIlr0FfpIKL.udFCKGo1lhUtm7JYa', NULL, NULL, 'giangvien', '2025-12-31 03:18:00', '2025-12-31 03:18:00'),
(19, 'Lê Thị Mỹ Dung', 'dung.lethimy@stu.edu.vn', '$2y$12$/n0Gvpyiy/q4Ym.36TraZOsaAGkujNRkvDPriScdJDezGEbyyI3La', NULL, NULL, 'giangvien', '2025-12-31 03:18:15', '2025-12-31 03:18:15'),
(20, 'Trịnh Thanh Duy', 'duy.trinhthanh@stu.edu.vn', '$2y$12$obttwdf1sxqWM2vId8ZN8uoMxDp2yfZUyU.IlGnVcySiWgTY4QpPS', NULL, NULL, 'giangvien', '2025-12-31 03:18:29', '2025-12-31 03:18:29'),
(21, 'Dương Văn Đeo', 'deo.duongvan@stu.edu.vn', '$2y$12$76diVc.GD3LnJUef6zMnF.eZIETaj53P1nO4hiP7ecR5oiTJBQSES', NULL, NULL, 'giangvien', '2025-12-31 03:18:44', '2025-12-31 03:18:44'),
(22, 'Huỳnh Quang Đức', 'duc.huynhquang@stu.edu.vn', '$2y$12$rfgqXSqxAA8Qcb3GBhcZtel774hnCfNKJYQqcS91Nl6CBz7Zpsy72', NULL, NULL, 'giangvien', '2025-12-31 03:19:20', '2025-12-31 03:19:20'),
(23, 'Lê Triệu Ngọc Đức', 'duc.letrieungoc@stu.edu.vn', '$2y$12$MyO03j8TMuOmWwfEY.c4QejTYoLYUvT1iwMfRNPMbQR1eZ.XN0aJi', NULL, NULL, 'giangvien', '2025-12-31 03:19:41', '2025-12-31 03:19:41'),
(24, 'Nguyễn Thị Ngân Hà', 'ha.nguyenthingan@stu.edu.vn', '$2y$12$xDz.sIqRHK3ZIA5V66LqvOkfzMJWccVQKhTVlMtkCRdHAtZ/VSVjO', NULL, NULL, 'giangvien', '2025-12-31 03:20:01', '2025-12-31 03:20:01'),
(25, 'Hồ Đình Khả', 'kha.hodinh@stu.edu.vn', '$2y$12$7w0sancWduYHpv8P8l.X.uQUyBXLkFPrQEsM..9UkVfmYkhoaBRAy', NULL, NULL, 'giangvien', '2025-12-31 03:20:25', '2025-12-31 03:20:25'),
(26, 'Nguyễn Thường Kiệt', 'kiet.nguyenthuong@stu.edu.vn', '$2y$12$WbLU91rgYoLaEfknUOrAf.qvGkMsnfXBS0tw.dUau8H1eR3mpXLJm', NULL, NULL, 'giangvien', '2025-12-31 03:20:42', '2025-12-31 03:20:42'),
(27, 'Khuất Bá Duy Lâm', 'lam.khuatbaduy@stu.edu.vn', '$2y$12$CVAaAmBeopTUibhwK3gmb.PU5KzAip5iepAEPgW9tCvbpsW6b5evG', NULL, NULL, 'giangvien', '2025-12-31 03:21:02', '2025-12-31 03:21:02'),
(28, 'Nguyễn Ngọc Lâm', 'lam.nguyenngoc@stu.edu.vn', '$2y$12$vbccWYI1sFG2fPaYfC71FeZdRZ8ethqZA4dJNV2.UA85GxYRNjlAO', NULL, NULL, 'giangvien', '2025-12-31 03:21:12', '2025-12-31 03:21:12'),
(29, 'Nguyễn Hồng Bửu Long', 'long.nguyenhongbuu@stu.edu.vn', '$2y$12$md7eqvb71gSpurOQfb2tW.hXV8TapW/Yw8hBARW.iyRzXWRjFKBkq', NULL, NULL, 'giangvien', '2025-12-31 03:21:25', '2025-12-31 03:21:25'),
(30, 'Nguyễn Trọng Nghĩa', 'nghia.nguyentrong@stu.edu.vn', '$2y$12$4pJfNrlcHvdO16l5Zyf9VuUeQJkf2iB3ZYiJ9z9UaCPUkxySUyzUm', NULL, NULL, 'giangvien', '2025-12-31 03:21:44', '2025-12-31 03:21:44'),
(31, 'Nguyễn Minh Sang', 'sang.nguyenminh@stu.edu.vn', '$2y$12$HtS7kBPHPHSGVoroyytAteLDPmd68raz5NPt/VBngoDCu0ZbelFwm', NULL, NULL, 'giangvien', '2025-12-31 03:22:08', '2025-12-31 03:22:08'),
(32, 'Nguyễn Trần Phúc Thịnh', 'thinh.nguyentranphuc@stu.edu.vn', '$2y$12$L1HrXJOdjaGp5Yg/MFGeX.brMCxWO2..IM9Lmx/57vtyyEeeb0DNi', NULL, NULL, 'giangvien', '2025-12-31 03:22:27', '2025-12-31 03:22:27'),
(33, 'Trần Quốc Trường', 'truong.tranquoc@stu.edu.vn', '$2y$12$JyYNjpctwDB.oA/xBz4F8OUeSvfsNBfrjP6E4hnZgpoah2yJff3Jm', NULL, NULL, 'giangvien', '2025-12-31 03:22:40', '2025-12-31 03:22:40'),
(34, 'Nguyễn Thanh Tùng', 'tung.nguyenthanh@stu.edu.vn', '$2y$12$5O9OKbms2eZnnLuZDMl8PeWVDH7oiPnTCPVaq2TfKCh.hBqz/XCPi', NULL, NULL, 'giangvien', '2025-12-31 03:23:01', '2025-12-31 03:23:01'),
(35, 'Trần Vũ Hoàng Ưng', 'ung.tranvuhoang@stu.edu.vn', '$2y$12$OKip1FeKS5GHoVgU.d9/oetyu6IKn.IVJjnzD1ur1ImYvOseMBtea', NULL, NULL, 'giangvien', '2025-12-31 03:23:23', '2025-12-31 03:23:23'),
(36, 'Hà Anh Vũ', 'vu.haanh@stu.edu.vn', '$2y$12$N.M4y2AplAm2gCN39PDNV.IUN6GMlUfZK8Xkbme/R4jFkekJtzU2K', NULL, NULL, 'giangvien', '2025-12-31 03:23:33', '2025-12-31 03:23:33'),
(37, 'Mai Vân Phương Vũ', 'vu.maivanphuong@stu.edu.vn', '$2y$12$n/HgQp1SRbxb8I7lONjNnOHqz27YgvJJs4oC5XChS.WsyWRQLT5Du', NULL, NULL, 'giangvien', '2025-12-31 03:23:53', '2025-12-31 03:23:53'),
(38, 'Nguyễn Hữu Kiên', 'kien113214@gmail.com', '$2y$12$R3pzrqoOCvgVz1ituZi9K.VL6Rw7jCAjeqVfNq3Fa66kP8/pd7SBS', NULL, '0392965734', 'giangvien', '2026-01-01 05:13:07', '2026-01-01 05:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `nhap_diem_bao_ve`
--

CREATE TABLE `nhap_diem_bao_ve` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `diem_bao_ve` decimal(4,2) NOT NULL DEFAULT 0.00,
  `diem_gv` decimal(4,2) NOT NULL DEFAULT 0.00,
  `diem_tong` decimal(4,2) NOT NULL DEFAULT 0.00,
  `trang_thai` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhom_sinhvien`
--

CREATE TABLE `nhom_sinhvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_nhom` varchar(255) NOT NULL,
  `truong_nhom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhom_sinhvien`
--

INSERT INTO `nhom_sinhvien` (`id`, `ten_nhom`, `truong_nhom_id`, `created_at`, `updated_at`) VALUES
(1, 'Nhóm 1', 174, NULL, NULL),
(2, 'Nhóm 2', 181, NULL, NULL),
(3, 'Nhóm 3', 182, '2025-12-05 08:59:51', '2025-12-05 08:59:51'),
(4, 'Nhóm 4', 180, '2025-12-05 08:59:59', '2025-12-05 08:59:59'),
(5, 'Nhóm 5', 179, '2025-12-07 18:39:10', '2025-12-07 18:39:10'),
(6, 'Nhóm 6', 175, '2025-12-07 18:42:45', '2025-12-07 18:42:45'),
(7, 'Nhóm 7', 185, '2025-12-07 18:44:07', '2025-12-07 18:44:07'),
(8, 'Nhóm 8', 188, '2025-12-07 18:50:08', '2025-12-07 18:50:08'),
(9, 'Nhóm 9', 184, '2025-12-24 00:13:25', '2025-12-24 00:13:25'),
(10, 'Nhóm 10', 192, '2025-12-31 18:47:26', '2025-12-31 18:47:26');

-- --------------------------------------------------------

--
-- Table structure for table `nhom_sinhvien_chitiet`
--

CREATE TABLE `nhom_sinhvien_chitiet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhom_sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `vai_tro` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhom_sinhvien_chitiet`
--

INSERT INTO `nhom_sinhvien_chitiet` (`id`, `nhom_sinhvien_id`, `sinhvien_id`, `vai_tro`, `created_at`, `updated_at`) VALUES
(50, 1, 174, 'truong_nhom', '2025-12-31 03:26:26', '2025-12-31 03:26:26'),
(51, 1, 183, 'thanh_vien', '2025-12-31 03:26:26', '2025-12-31 03:26:26'),
(52, 2, 181, 'truong_nhom', '2025-12-31 03:26:36', '2025-12-31 03:26:36'),
(53, 2, 189, 'thanh_vien', '2025-12-31 03:26:36', '2025-12-31 03:26:36'),
(54, 3, 182, 'truong_nhom', '2025-12-31 03:27:06', '2025-12-31 03:27:06'),
(55, 3, 176, 'thanh_vien', '2025-12-31 03:27:06', '2025-12-31 03:27:06'),
(56, 4, 180, 'truong_nhom', '2025-12-31 03:27:15', '2025-12-31 03:27:15'),
(57, 4, 178, 'thanh_vien', '2025-12-31 03:27:15', '2025-12-31 03:27:15'),
(58, 5, 179, 'truong_nhom', '2025-12-31 03:36:38', '2025-12-31 03:36:38'),
(59, 5, 177, 'thanh_vien', '2025-12-31 03:41:26', '2025-12-31 03:41:26'),
(60, 6, 175, 'truong_nhom', '2025-12-31 03:41:26', '2025-12-31 03:41:26'),
(61, 6, 190, 'thanh_vien', '2025-12-31 03:41:26', '2025-12-31 03:41:26'),
(62, 7, 185, 'truong_nhom', '2025-12-31 03:41:26', '2025-12-31 03:41:26'),
(63, 8, 188, 'truong_nhom', '2025-12-31 03:41:26', '2025-12-31 03:41:26'),
(64, 9, 184, 'truong_nhom', '2025-12-31 18:47:26', '2025-12-31 18:47:26'),
(65, 9, 191, 'thanh_vien', '2025-12-31 18:47:26', '2025-12-31 18:47:26'),
(66, 10, 192, 'truong_nhom', '2025-12-31 18:47:26', '2025-12-31 18:47:26'),
(67, 10, 186, 'thanh_vien', '2025-12-31 18:47:26', '2025-12-31 18:47:26');

-- --------------------------------------------------------

--
-- Table structure for table `phancong`
--

CREATE TABLE `phancong` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `giang_vien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nguoi_phan_cong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_phan_cong` timestamp NULL DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phancong`
--

INSERT INTO `phancong` (`id`, `detai_id`, `giang_vien_id`, `nguoi_phan_cong_id`, `ngay_phan_cong`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, '2025-10-13 04:41:39', 'Phân công hướng dẫn cho nhóm 1', NULL, NULL),
(2, 2, NULL, 1, '2025-10-13 04:41:39', 'Phân công phản biện đề tài 2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phieu_danh_gia`
--

CREATE TABLE `phieu_danh_gia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED NOT NULL,
  `giangvien_id` bigint(20) UNSIGNED NOT NULL,
  `loai` enum('huongdan','phanbien') NOT NULL,
  `diem` int(11) DEFAULT NULL,
  `nhanxet` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('wjWTNYPh3rN59xOgu8QtNTNU0rj4jQfxR0WY9lBW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUhqeUlJbXM0TXVwYng3WnlWN1BGV1VDRmIyT1cwbzR4V2pZc0FrRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1767586719);

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nguoidung_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mssv` varchar(20) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `lop` varchar(50) DEFAULT NULL,
  `nienkhoa` int(11) DEFAULT NULL,
  `khoa` varchar(100) DEFAULT NULL,
  `trangthai` varchar(255) NOT NULL DEFAULT 'chuaphancong',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`id`, `nguoidung_id`, `mssv`, `hoten`, `email`, `sdt`, `lop`, `nienkhoa`, `khoa`, `trangthai`, `created_at`) VALUES
(174, NULL, 'DH51801379', 'Ngô Minh Đạt', 'DH51801379@student.stu.edu.vn', '792170819', 'D18_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(175, NULL, 'DH52002303', 'Lê Chí Cường', 'DH52002303@student.stu.edu.vn', '904446653', 'D20_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(176, NULL, 'DH52001367', 'Lâm Chí Minh', 'DH52001367@student.stu.edu.vn', '924405798', 'D20_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(177, NULL, 'DH52002302', 'Cao Hoàng Nam', 'DH52002302@student.stu.edu.vn', '909393047', 'D20_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(178, NULL, 'DH52001900', 'Nguyễn Minh Triều', 'DH52001900@student.stu.edu.vn', '899052420', 'D20_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(179, NULL, 'DH52001904', 'Nguyễn Hữu Trường', 'DH52001904@student.stu.edu.vn', '855021202', 'D20_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(180, NULL, 'DH52001688', 'Phạm Nhựt Linhs', 'DH52001688@student.stu.edu.vn', '794985963', 'D20_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(181, NULL, 'DH52001024', 'Nguyễn Duy Sơn', 'DH52001024@student.stu.edu.vn', '783887570', 'D20_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(182, NULL, 'DH52001330', 'Phạm Ngọc Đông', 'DH52001330@student.stu.edu.vn', '366468307', 'D20_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(183, NULL, 'DH52000682', 'Lê Tuấn', 'DH52000682@student.stu.edu.vn', '777789336', 'D20_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(184, NULL, 'DH52003563', 'Phan Văn Việt', 'DH52003563@student.stu.edu.vn', '934487805', 'D20_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(185, NULL, 'DH52002723', 'Phạm Ngọc Khoa', 'DH52002723@student.stu.edu.vn', '528051699', 'D20_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(186, NULL, 'DH52003935', 'Phạm Châu Phú', 'DH52003935@student.stu.edu.vn', '337847385', 'D20_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(187, NULL, 'DH52003995', 'Huỳnh Thanh Phúc', 'DH52003995@student.stu.edu.vn', '348095507', 'D20_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(188, NULL, 'DH52003543', 'Nguyễn Công Chi', 'DH52003543@student.stu.edu.vn', '523261143', 'D20_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(189, NULL, 'DH52001243', 'Lưu Văn Hiếu', 'DH52001243@student.stu.edu.vn', '977833079', 'D20_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(190, NULL, 'DH52002358', 'Vương Tiến Hùng', 'DH52002358@student.stu.edu.vn', '968189572', 'D20_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(191, NULL, 'DH52003835', 'Trần Đình Khoa', 'DH52003835@student.stu.edu.vn', '707035451', 'D20_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(192, NULL, 'DH52003862', 'Trần Hữu Quang', 'DH52003862@student.stu.edu.vn', '919402052', 'D20_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(193, NULL, 'DH52005747', 'Đào Thành Đạt', 'DH52005747@student.stu.edu.vn', '522939018', 'D20_TH06', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(194, NULL, 'DH52004272', 'Lưu Thị Thanh Thảo', 'DH52004272@student.stu.edu.vn', '329824880', 'D20_TH06', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(195, NULL, 'DH52005891', 'Phạm Nguyễn Hoàng Khang', 'DH52005891@student.stu.edu.vn', '833485997', 'D20_TH07', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(196, NULL, 'DH52005851', 'Nguyễn Tấn Huy', 'DH52005851@student.stu.edu.vn', '919202108', 'D20_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:28'),
(197, NULL, 'DH52005870', 'Vũ Trung Kiên', 'DH52005870@student.stu.edu.vn', '779182032', 'D20_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(198, NULL, 'DH52005068', 'Nguyễn Thanh Danh', 'DH52005068@student.stu.edu.vn', '798621883', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(199, NULL, 'DH52005731', 'Trần Lê Minh Duy', 'DH52005731@student.stu.edu.vn', '838567807', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(200, NULL, 'DH52005049', 'Đặng Ngọc Giàu', 'DH52005049@student.stu.edu.vn', '834376555', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(201, NULL, 'DH52005804', 'Mai Chí Hiệp', 'DH52005804@student.stu.edu.vn', '949619154', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(202, NULL, 'DH52006575', 'Lâm Tuấn Khoa', 'DH52006575@student.stu.edu.vn', '355002372', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(203, NULL, 'DH52006237', 'Nguyễn Trần Vân Uyển', 'DH52006237@student.stu.edu.vn', '963476850', 'D20_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(204, NULL, 'DH52005699', 'Nguyễn Hùng Cường', 'DH52005699@student.stu.edu.vn', '932464672', 'D20_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(205, NULL, 'DH52007186', 'Trần Như Nguyện', 'DH52007186@student.stu.edu.vn', '388065951', 'D20_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(206, NULL, 'DH52005770', 'Trịnh Anh Đức', 'DH52005770@student.stu.edu.vn', '582449063', 'D20_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(207, NULL, 'DH52007089', 'Huỳnh Minh Khoa', 'DH52007089@student.stu.edu.vn', '898175595', 'D20_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(208, NULL, 'DH52007161', 'Phạm Duy Thắng', 'DH52007161@student.stu.edu.vn', '335444058', 'D20_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(209, NULL, 'DH52103511', 'Phạm Hữu Chí', 'DH52103511@student.stu.edu.vn', '385920397', 'D21_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(210, NULL, 'DH52103137', 'Phan Tuấn Dũng', 'DH52103137@student.stu.edu.vn', '357716720', 'D21_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(211, NULL, 'DH52106130', 'Bùi Phi Hùng', 'DH52106130@student.stu.edu.vn', '394126389', 'D21_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(212, NULL, 'DH52103682', 'Bùi Minh Phúc', 'DH52103682@student.stu.edu.vn', '359128746', 'D21_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(213, NULL, 'DH52107203', 'Nguyễn Ngọc Thiện', 'DH52107203@student.stu.edu.vn', '962419209', 'D21_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(214, NULL, 'DH52101979', 'Phạm Thị ánh Hồng', 'DH52101979@student.stu.edu.vn', '976747106', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(215, NULL, 'DH52101465', 'Quách Thái Hùng', 'DH52101465@student.stu.edu.vn', '947252595', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(216, NULL, 'DH52104108', 'Nguyễn Đăng Khoa', 'DH52104108@student.stu.edu.vn', '938240431', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(217, NULL, 'DH52101402', 'Nguyễn Văn Hoàng Long', 'DH52101402@student.stu.edu.vn', '828599379', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(218, NULL, 'DH52100937', 'Nguyễn Xuân Long', 'DH52100937@student.stu.edu.vn', '396285403', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(219, NULL, 'DH52105312', 'Trần Hà Xuân Thịnh', 'DH52105312@student.stu.edu.vn', '349573458', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(220, NULL, 'DH52107408', 'Trần Minh Tú', 'DH52107408@student.stu.edu.vn', '772911890', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(221, NULL, 'DH52105346', 'Lê Nguyễn Thành Vũ', 'DH52105346@student.stu.edu.vn', '763163435', 'D21_TH02', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(222, NULL, 'DH52101856', 'Nguyễn Duy Bản', 'DH52101856@student.stu.edu.vn', '342271703', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(223, NULL, 'DH52105659', 'Bạch Đức Phước', 'DH52105659@student.stu.edu.vn', '866088087', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(224, NULL, 'DH52107697', 'Đinh Nguyễn Tuấn', 'DH52107697@student.stu.edu.vn', '976588770', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(225, NULL, 'DH52104582', 'Ngô Duy Tùng', 'DH52104582@student.stu.edu.vn', '946809362', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(226, NULL, 'DH52106608', 'Đỗ Quang Vinh', 'DH52106608@student.stu.edu.vn', '708738019', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(227, NULL, 'DH52103727', 'Đào Duy Hoàng Vương', 'DH52103727@student.stu.edu.vn', '983621649', 'D21_TH03', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(228, NULL, 'DH52104857', 'Lê Thị Đa Lin', 'DH52104857@student.stu.edu.vn', '374423479', 'D21_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(229, NULL, 'DH52100514', 'Trần Quốc Nam', 'DH52100514@student.stu.edu.vn', '2838506194', 'D21_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(230, NULL, 'DH52106292', 'Phan Duy Tuấn', 'DH52106292@student.stu.edu.vn', '327261528', 'D21_TH04', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(231, NULL, 'DH52104887', 'Nhữ Quốc Anh', 'DH52104887@student.stu.edu.vn', '856143299', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(232, NULL, 'DH52110568', 'Phạm Minh Anh', 'DH52110568@student.stu.edu.vn', '395168006', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(233, NULL, 'DH52110640', 'Hà Thị Mỹ Châu', 'DH52110640@student.stu.edu.vn', '394949891', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(234, NULL, 'DH52108517', 'Hoàng Hữu Lê Chinh', 'DH52108517@student.stu.edu.vn', '898671245', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(235, NULL, 'DH52108402', 'Nguyễn Trung Hiếu', 'DH52108402@student.stu.edu.vn', '326780829', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(236, NULL, 'DH52108453', 'Đinh Phạm Phú Khang', 'DH52108453@student.stu.edu.vn', '778715658', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(237, NULL, 'DH52105342', 'Trần Nguyễn Minh Quân', 'DH52105342@student.stu.edu.vn', '388073445', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(238, NULL, 'DH52108018', 'Nguyễn Quốc Thắng', 'DH52108018@student.stu.edu.vn', '765688708', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(239, NULL, 'DH52107801', 'Nguyễn Thanh Vân', 'DH52107801@student.stu.edu.vn', '349442507', 'D21_TH05', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(240, NULL, 'DH52108380', 'Đoàn Thị Yến Bình', 'DH52108380@student.stu.edu.vn', '824108001', 'D21_TH06', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(241, NULL, 'DH52110836', 'Nguyễn Hồng Gấm', 'DH52110836@student.stu.edu.vn', '775160497', 'D21_TH06', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(242, NULL, 'DH52108656', 'Võ Minh Thuận', 'DH52108656@student.stu.edu.vn', '936452676', 'D21_TH06', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(243, NULL, 'DH52106176', 'Nguyễn Minh Huy', 'DH52106176@student.stu.edu.vn', '933881276', 'D21_TH07', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(244, NULL, 'DH52110534', 'Nguyễn Mậu An', 'DH52110534@student.stu.edu.vn', '343513046', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(245, NULL, 'DH52110793', 'Trịnh Phát Đạt', 'DH52110793@student.stu.edu.vn', '977336644', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(246, NULL, 'DH52110857', 'Nguyễn Đăng Hải', 'DH52110857@student.stu.edu.vn', '909523075', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(247, NULL, 'DH52111085', 'Trương Minh Khải', 'DH52111085@student.stu.edu.vn', '835359010', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(248, NULL, 'DH52111086', 'Dương Trí Khang', 'DH52111086@student.stu.edu.vn', '836169654', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(249, NULL, 'DH52113292', 'Lê Minh Kiệt', 'DH52113292@student.stu.edu.vn', '937733385', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(250, NULL, 'DH52111174', 'Ngô Tuấn Kiệt', 'DH52111174@student.stu.edu.vn', '849929007', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(251, NULL, 'DH52111204', 'Trương Văn Liêu', 'DH52111204@student.stu.edu.vn', '393726628', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(252, NULL, 'DH52104298', 'Lê Thị Ly Ly', 'DH52104298@student.stu.edu.vn', '339519874', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(253, NULL, 'DH52111358', 'Đồng Văn Nghĩa', 'DH52111358@student.stu.edu.vn', '382149204', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(254, NULL, 'DH52111401', 'Lê Quang Nhân', 'DH52111401@student.stu.edu.vn', '393638193', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(255, NULL, 'DH52111411', 'Trần Trọng Nhân', 'DH52111411@student.stu.edu.vn', '2723867856', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(256, NULL, 'DH52111560', 'Võ Hoàng Phúc', 'DH52111560@student.stu.edu.vn', '767764470', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(257, NULL, 'DH52113345', 'Lữ Mai Phương', 'DH52113345@student.stu.edu.vn', '833063875', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(258, NULL, 'DH52111833', 'Lê Nguyễn Minh Thông', 'DH52111833@student.stu.edu.vn', '769630210', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(259, NULL, 'DH52111847', 'Lương Hiếu Thuận', 'DH52111847@student.stu.edu.vn', '965629532', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(260, NULL, 'DH52111881', 'Trần Thủy Tiên', 'DH52111881@student.stu.edu.vn', '327458490', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(261, NULL, 'DH52112019', 'Nguyễn Ngọc Thanh Tuệ', 'DH52112019@student.stu.edu.vn', '907355548', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(262, NULL, 'DH52112127', 'Lương Triều Vỹ', 'DH52112127@student.stu.edu.vn', '', 'D21_TH08', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(263, NULL, 'DH52110677', 'Nguyễn Ngọc Doanh', 'DH52110677@student.stu.edu.vn', '902904122', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(264, NULL, 'DH52110995', 'Đỗ Quang Huy', 'DH52110995@student.stu.edu.vn', '395553134', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(265, NULL, 'DH52111030', 'Nguyễn Quốc Huy', 'DH52111030@student.stu.edu.vn', '933705051', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(266, NULL, 'DH52111083', 'Trần Mai Huy Khải', 'DH52111083@student.stu.edu.vn', '582079957', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(267, NULL, 'DH52100776', 'Vũ Trung Nguyên', 'DH52100776@student.stu.edu.vn', '931329585', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(268, NULL, 'DH52111441', 'Nguyễn Thị Nhung', 'DH52111441@student.stu.edu.vn', '359439628', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(269, NULL, 'DH52111482', 'Võ Văn Phát', 'DH52111482@student.stu.edu.vn', '937689655', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(270, NULL, 'DH52111486', 'Nguyễn Tấn Phi', 'DH52111486@student.stu.edu.vn', '703760626', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(271, NULL, 'DH52111579', 'Nguyễn Việt Phương', 'DH52111579@student.stu.edu.vn', '978699529', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(272, NULL, 'DH52111700', 'Thái Tấn Tài', 'DH52111700@student.stu.edu.vn', '353004163', 'D21_TH09', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(273, NULL, 'DH52111055', 'Trần Đức Huynh', 'DH52111055@student.stu.edu.vn', '866714807', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(274, NULL, 'DH52111112', 'Đỗ Quốc Khánh', 'DH52111112@student.stu.edu.vn', '983062644', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(275, NULL, 'DH52111115', 'Mai Lâm Quang Khánh', 'DH52111115@student.stu.edu.vn', '707347324', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(276, NULL, 'DH52111171', 'Lâm Tuấn Kiệt', 'DH52111171@student.stu.edu.vn', '941693505', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(277, NULL, 'DH52111258', 'Trần Tấn Lộc', 'DH52111258@student.stu.edu.vn', '332345957', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(278, NULL, 'DH52111245', 'Võ Thành Long', 'DH52111245@student.stu.edu.vn', '937369772', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(279, NULL, 'DH52111491', 'Nguyễn Chí Phong', 'DH52111491@student.stu.edu.vn', '903073250', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(280, NULL, 'DH52111509', 'Nguyễn Thành Tỷ Phú', 'DH52111509@student.stu.edu.vn', '767392039', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(281, NULL, 'DH52111529', 'Lê Trần Trọng Phúc', 'DH52111529@student.stu.edu.vn', '946129499', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(282, NULL, 'DH52111612', 'Trần Nguyễn Hoàng Quân', 'DH52111612@student.stu.edu.vn', '911341117', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(283, NULL, 'DH52111637', 'Nguyễn Đăng Quyền', 'DH52111637@student.stu.edu.vn', '815804376', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(284, NULL, 'DH52111681', 'Lê Anh Tài', 'DH52111681@student.stu.edu.vn', '967788246', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(285, NULL, 'DH52111720', 'Nguyễn Công Tấn', 'DH52111720@student.stu.edu.vn', '', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(286, NULL, 'DH52112786', 'Đinh Quang Thịnh', 'DH52112786@student.stu.edu.vn', '931487603', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(287, NULL, 'DH52111823', 'Võ Thị Tho', 'DH52111823@student.stu.edu.vn', '969747148', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(288, NULL, 'DH52111863', 'Nguyễn Thị Minh Thư', 'DH52111863@student.stu.edu.vn', '97473170', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(289, NULL, 'DH52111923', 'Đỗ Minh Trí', 'DH52111923@student.stu.edu.vn', '704651788', 'D21_TH10', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(290, NULL, 'DH52110561', 'Nguyễn Lan Anh', 'DH52110561@student.stu.edu.vn', '329186138', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(291, NULL, 'DH52110812', 'Trương Thanh Đông', 'DH52110812@student.stu.edu.vn', '706766557', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(292, NULL, 'DH52110733', 'Nguyễn Sơn Dương', 'DH52110733@student.stu.edu.vn', '826464186', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(293, NULL, 'DH52113526', 'Trần Thái Duy', 'DH52113526@student.stu.edu.vn', '935183461', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(294, NULL, 'DH52111063', 'Nguyễn Mạnh Hưng', 'DH52111063@student.stu.edu.vn', '328707978', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(295, NULL, 'DH52111067', 'Trần Minh Hưng', 'DH52111067@student.stu.edu.vn', '932078352', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(296, NULL, 'DH52111212', 'Nguyễn Hoàng Linh', 'DH52111212@student.stu.edu.vn', '941412077', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(297, NULL, 'DH52112944', 'Lê Đoàn Anh Quân', 'DH52112944@student.stu.edu.vn', '866603591', 'D21_TH11', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(298, NULL, 'DH52112809', 'Mai Hoàng An', 'DH52112809@student.stu.edu.vn', '972285275', 'D21_TH12', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(299, NULL, 'DH52111293', 'Ong Văn Mến', 'DH52111293@student.stu.edu.vn', '933331843', 'D21_TH12', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(300, NULL, 'DH52112805', 'Võ Trọng Nghĩa', 'DH52112805@student.stu.edu.vn', '', 'D21_TH12', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(301, NULL, 'DH52113777', 'Huỳnh Xuân Thọ', 'DH52113777@student.stu.edu.vn', '', 'D21_TH12', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(302, NULL, 'DH52113134', 'Mai Quang Vinh', 'DH52113134@student.stu.edu.vn', '523756478', 'D21_TH12', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(303, NULL, 'DH52110581', 'Nguyễn Ngọc Ân', 'DH52110581@student.stu.edu.vn', '921266924', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(304, NULL, 'DH52110553', 'Mai Trần Duy Anh', 'DH52110553@student.stu.edu.vn', '947657637', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(305, NULL, 'DH52110593', 'Lê Tôn Bảo', 'DH52110593@student.stu.edu.vn', '949965772', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(306, NULL, 'DH52110693', 'Đỗ Ngọc Anh Duy', 'DH52110693@student.stu.edu.vn', '865006929', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(307, NULL, 'DH52110924', 'Trần Nguyễn Minh Hiếu', 'DH52110924@student.stu.edu.vn', '936049080', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(308, NULL, 'DH52110935', 'Nguyễn Đình Hòa', 'DH52110935@student.stu.edu.vn', '888254294', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(309, NULL, 'DH52111224', 'Giang Nhật Long', 'DH52111224@student.stu.edu.vn', '856639637', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(310, NULL, 'DH52111439', 'Huỳnh Tấn Nhớ', 'DH52111439@student.stu.edu.vn', '977979791', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(311, NULL, 'DH52111531', 'Lưu Hoàng Phúc', 'DH52111531@student.stu.edu.vn', '396895104', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(312, NULL, 'DH52111615', 'Võ Minh Quân', 'DH52111615@student.stu.edu.vn', '854381067', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(313, NULL, 'DH52111695', 'Nguyễn Văn Tài', 'DH52111695@student.stu.edu.vn', '985141631', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(314, NULL, 'DH52111756', 'Lê Minh Thảo', 'DH52111756@student.stu.edu.vn', '522731750', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(315, NULL, 'DH52111794', 'Nguyễn Chí Thiện', 'DH52111794@student.stu.edu.vn', '979286060', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(316, NULL, 'DH52111845', 'Lâm Gia Thuận', 'DH52111845@student.stu.edu.vn', '931548545', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(317, NULL, 'DH52111976', 'Nguyễn Minh Trường', 'DH52111976@student.stu.edu.vn', '939024432', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(318, NULL, 'DH52112118', 'Trần Hoàng Vương', 'DH52112118@student.stu.edu.vn', '987038840', 'D21_TH13', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(319, NULL, 'DH52110742', 'Nguyễn Quốc Đại', 'DH52110742@student.stu.edu.vn', '898366249', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(320, NULL, 'DH52110800', 'Nguyễn Võ Hoàng Hải Đăng', 'DH52110800@student.stu.edu.vn', '2837713095', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(321, NULL, 'DH52110802', 'Trần Ngọc Điền', 'DH52110802@student.stu.edu.vn', '924640701', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(322, NULL, 'DH52113016', 'Huỳnh Quốc Duy', 'DH52113016@student.stu.edu.vn', '362949286', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(323, NULL, 'DH52113047', 'Phan Đức Thắng', 'DH52113047@student.stu.edu.vn', '949985490', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(324, NULL, 'DH52112002', 'Lâm Đình Tuấn', 'DH52112002@student.stu.edu.vn', '906673427', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(325, NULL, 'DH52112079', 'Nguyễn Đình Vinh', 'DH52112079@student.stu.edu.vn', '383731640', 'D21_TH14', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(326, NULL, 'LT52200006', 'Trần Minh Nghĩa', 'LT52200006@student.stu.edu.vn', '908655034', 'L22_TH01', NULL, NULL, 'chuaphancong', '2025-12-31 03:24:29'),
(327, NULL, 'DH52200939', 'Nguyễn Hữu Kiên', 'kienhuunguyen@student.stu.vn', '0392965834', 'D22_TH09', 2021, 'CNTT', 'chuaphancong', '2026-01-04 19:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `theo_doi_tien_do`
--

CREATE TABLE `theo_doi_tien_do` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhom_sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `detai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tien_do` int(11) DEFAULT 0,
  `quyet_dinh` enum('duoc_lam_tiep','tam_dung','huy') DEFAULT 'duoc_lam_tiep',
  `ghi_chu` text DEFAULT NULL,
  `giangvien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_cap_nhat` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theo_doi_tien_do`
--

INSERT INTO `theo_doi_tien_do` (`id`, `nhom_sinhvien_id`, `detai_id`, `tien_do`, `quyet_dinh`, `ghi_chu`, `giangvien_id`, `ngay_cap_nhat`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 50, 'duoc_lam_tiep', 'Test', 36, '2026-01-04 21:10:26', '2025-12-05 09:11:52', '2026-01-04 21:10:26'),
(2, 3, 4, 20, 'tam_dung', NULL, 41, '2026-01-04 19:26:39', '2025-12-15 10:17:11', '2026-01-04 19:26:39'),
(3, 2, 2, 50, 'duoc_lam_tiep', NULL, NULL, '2025-12-15 10:18:31', '2025-12-15 10:18:31', '2025-12-15 10:18:31'),
(4, 1, 1, 50, 'duoc_lam_tiep', 'Test Waring', 41, '2026-01-04 19:17:32', '2025-12-15 10:18:50', '2026-01-04 19:17:32'),
(5, 5, 5, 50, 'duoc_lam_tiep', 'Cảnh cáo', 36, '2026-01-04 21:02:15', '2025-12-15 10:19:09', '2026-01-04 21:02:15'),
(6, 6, 6, 50, 'duoc_lam_tiep', 'Cần làm tốt hơn', NULL, '2025-12-25 20:25:22', '2025-12-15 10:19:14', '2025-12-25 20:25:22'),
(7, 7, 7, 50, 'duoc_lam_tiep', NULL, NULL, '2025-12-22 09:38:37', '2025-12-22 09:38:16', '2025-12-22 09:38:37'),
(8, 9, 9, 30, 'tam_dung', NULL, 41, '2026-01-04 19:26:45', '2025-12-24 00:16:06', '2026-01-04 19:26:45'),
(9, 8, 8, 50, 'duoc_lam_tiep', NULL, 41, '2026-01-01 05:21:33', '2026-01-01 05:21:33', '2026-01-01 05:21:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cham_diem_huong_dan`
--
ALTER TABLE `cham_diem_huong_dan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cdhd_unique` (`detai_id`,`sinhvien_id`,`giangvien_id`),
  ADD KEY `cham_diem_huong_dan_sinhvien_id_foreign` (`sinhvien_id`),
  ADD KEY `cham_diem_huong_dan_giangvien_id_foreign` (`giangvien_id`);

--
-- Indexes for table `cham_diem_phan_bien`
--
ALTER TABLE `cham_diem_phan_bien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cdpb_unique` (`detai_id`,`sinhvien_id`,`giangvien_id`),
  ADD KEY `cham_diem_phan_bien_sinhvien_id_foreign` (`sinhvien_id`),
  ADD KEY `cham_diem_phan_bien_giangvien_id_foreign` (`giangvien_id`);

--
-- Indexes for table `detai`
--
ALTER TABLE `detai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detai_giangvien_id_foreign` (`giangvien_id`),
  ADD KEY `detai_nhom_sinhvien_id_foreign` (`nhom_sinhvien_id`),
  ADD KEY `detai_giangvien_phanbien_id_foreign` (`giangvien_phanbien_id`),
  ADD KEY `detai_hoi_dong_id_foreign` (`hoi_dong_id`);

--
-- Indexes for table `diem_bao_ve`
--
ALTER TABLE `diem_bao_ve`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `diem_bao_ve_sinhvien_id_detai_id_unique` (`sinhvien_id`,`detai_id`),
  ADD KEY `diem_bao_ve_detai_id_foreign` (`detai_id`);

--
-- Indexes for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `giangvien_magv_unique` (`magv`),
  ADD KEY `giangvien_nguoidung_id_foreign` (`nguoidung_id`);

--
-- Indexes for table `hoi_dong`
--
ALTER TABLE `hoi_dong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hoi_dong_chu_tich_id_foreign` (`chu_tich_id`),
  ADD KEY `hoi_dong_thu_ky_id_foreign` (`thu_ky_id`),
  ADD KEY `hoi_dong_uy_vien_1_id_foreign` (`uy_vien_1_id`),
  ADD KEY `hoi_dong_uy_vien_2_id_foreign` (`uy_vien_2_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moc_thoi_gian`
--
ALTER TABLE `moc_thoi_gian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nguoidung_email_unique` (`email`);

--
-- Indexes for table `nhap_diem_bao_ve`
--
ALTER TABLE `nhap_diem_bao_ve`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ndbv_unique` (`detai_id`,`sinhvien_id`),
  ADD KEY `nhap_diem_bao_ve_sinhvien_id_foreign` (`sinhvien_id`);

--
-- Indexes for table `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhom_sinhvien_truong_nhom_id_foreign` (`truong_nhom_id`);

--
-- Indexes for table `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhom_sinhvien_chitiet_nhom_sinhvien_id_foreign` (`nhom_sinhvien_id`),
  ADD KEY `nhom_sinhvien_chitiet_sinhvien_id_foreign` (`sinhvien_id`);

--
-- Indexes for table `phancong`
--
ALTER TABLE `phancong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phancong_detai_id_foreign` (`detai_id`),
  ADD KEY `phancong_giang_vien_id_foreign` (`giang_vien_id`),
  ADD KEY `phancong_nguoi_phan_cong_id_foreign` (`nguoi_phan_cong_id`);

--
-- Indexes for table `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phieu_danh_gia_detai_id_foreign` (`detai_id`),
  ADD KEY `phieu_danh_gia_giangvien_id_foreign` (`giangvien_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_last_activity` (`last_activity`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sinhvien_mssv_unique` (`mssv`),
  ADD KEY `sinhvien_nguoidung_id_foreign` (`nguoidung_id`);

--
-- Indexes for table `theo_doi_tien_do`
--
ALTER TABLE `theo_doi_tien_do`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nhom` (`nhom_sinhvien_id`),
  ADD KEY `theo_doi_tien_do_detai_id_foreign` (`detai_id`),
  ADD KEY `theo_doi_tien_do_giangvien_id_foreign` (`giangvien_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cham_diem_huong_dan`
--
ALTER TABLE `cham_diem_huong_dan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `cham_diem_phan_bien`
--
ALTER TABLE `cham_diem_phan_bien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `detai`
--
ALTER TABLE `detai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `diem_bao_ve`
--
ALTER TABLE `diem_bao_ve`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `giangvien`
--
ALTER TABLE `giangvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `hoi_dong`
--
ALTER TABLE `hoi_dong`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `moc_thoi_gian`
--
ALTER TABLE `moc_thoi_gian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `nhap_diem_bao_ve`
--
ALTER TABLE `nhap_diem_bao_ve`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `phancong`
--
ALTER TABLE `phancong`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sinhvien`
--
ALTER TABLE `sinhvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=328;

--
-- AUTO_INCREMENT for table `theo_doi_tien_do`
--
ALTER TABLE `theo_doi_tien_do`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cham_diem_huong_dan`
--
ALTER TABLE `cham_diem_huong_dan`
  ADD CONSTRAINT `cham_diem_huong_dan_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cham_diem_huong_dan_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cham_diem_huong_dan_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cham_diem_phan_bien`
--
ALTER TABLE `cham_diem_phan_bien`
  ADD CONSTRAINT `cham_diem_phan_bien_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cham_diem_phan_bien_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cham_diem_phan_bien_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detai`
--
ALTER TABLE `detai`
  ADD CONSTRAINT `detai_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `detai_giangvien_phanbien_id_foreign` FOREIGN KEY (`giangvien_phanbien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `detai_hoi_dong_id_foreign` FOREIGN KEY (`hoi_dong_id`) REFERENCES `hoi_dong` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `detai_nhom_sinhvien_id_foreign` FOREIGN KEY (`nhom_sinhvien_id`) REFERENCES `nhom_sinhvien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `diem_bao_ve`
--
ALTER TABLE `diem_bao_ve`
  ADD CONSTRAINT `diem_bao_ve_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diem_bao_ve_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD CONSTRAINT `giangvien_nguoidung_id_foreign` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hoi_dong`
--
ALTER TABLE `hoi_dong`
  ADD CONSTRAINT `hoi_dong_chu_tich_id_foreign` FOREIGN KEY (`chu_tich_id`) REFERENCES `giangvien` (`id`),
  ADD CONSTRAINT `hoi_dong_thu_ky_id_foreign` FOREIGN KEY (`thu_ky_id`) REFERENCES `giangvien` (`id`),
  ADD CONSTRAINT `hoi_dong_uy_vien_1_id_foreign` FOREIGN KEY (`uy_vien_1_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hoi_dong_uy_vien_2_id_foreign` FOREIGN KEY (`uy_vien_2_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `nhap_diem_bao_ve`
--
ALTER TABLE `nhap_diem_bao_ve`
  ADD CONSTRAINT `nhap_diem_bao_ve_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhap_diem_bao_ve_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  ADD CONSTRAINT `nhom_sinhvien_truong_nhom_id_foreign` FOREIGN KEY (`truong_nhom_id`) REFERENCES `sinhvien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  ADD CONSTRAINT `nhom_sinhvien_chitiet_nhom_sinhvien_id_foreign` FOREIGN KEY (`nhom_sinhvien_id`) REFERENCES `nhom_sinhvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhom_sinhvien_chitiet_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phancong`
--
ALTER TABLE `phancong`
  ADD CONSTRAINT `phancong_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancong_giang_vien_id_foreign` FOREIGN KEY (`giang_vien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phancong_nguoi_phan_cong_id_foreign` FOREIGN KEY (`nguoi_phan_cong_id`) REFERENCES `nguoidung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  ADD CONSTRAINT `phieu_danh_gia_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_danh_gia_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD CONSTRAINT `sinhvien_nguoidung_id_foreign` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `theo_doi_tien_do`
--
ALTER TABLE `theo_doi_tien_do`
  ADD CONSTRAINT `theo_doi_tien_do_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `theo_doi_tien_do_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `theo_doi_tien_do_nhom_sinhvien_id_foreign` FOREIGN KEY (`nhom_sinhvien_id`) REFERENCES `nhom_sinhvien` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
