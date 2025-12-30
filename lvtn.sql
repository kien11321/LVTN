-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2025 lúc 01:44 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lvtn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `detai`
--

CREATE TABLE `detai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_detai` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `giangvien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` enum('ca_nhan','nhom') NOT NULL DEFAULT 'ca_nhan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `detai`
--

INSERT INTO `detai` (`id`, `ten_detai`, `mo_ta`, `giangvien_id`, `loai`, `created_at`, `updated_at`) VALUES
(1, 'Xây dựng hệ thống quản lý sinh viên LVTN khoa CNTT', 'Ứng dụng web hỗ trợ quản lý sinh viên làm luận văn tốt nghiệp, gồm các chức năng phân công, chấm điểm, phản biện.', 1, 'nhom', '2025-10-13 04:41:39', '2025-10-13 04:41:39'),
(2, 'Hệ thống quản lý điểm rèn luyện sinh viên', 'Website hỗ trợ nhập và tính điểm rèn luyện của sinh viên, có phân quyền GV và SV.', 1, 'ca_nhan', '2025-10-13 04:41:39', '2025-10-13 04:41:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giangvien`
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
-- Đang đổ dữ liệu cho bảng `giangvien`
--

INSERT INTO `giangvien` (`id`, `nguoidung_id`, `magv`, `hoten`, `email`, `sdt`, `bo_mon`, `created_at`, `updated_at`) VALUES
(1, NULL, 'GV001', 'Nguyễn Văn A', 'a@stu.edu.vn', '0901234567', 'CNTT', NULL, NULL),
(2, NULL, 'GV002', 'Trần Thanh Thanh', 'b@stu.edu.vn', '0907654321', 'CNTT', NULL, NULL),
(3, NULL, 'GV003', 'Lê Văn C', 'c@stu.edu.vn', '0909876543', 'HTTT', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
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
(12, '2025_10_13_064241_create_nhom_sinhvien_chitiet_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `moc_thoi_gian`
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
-- Đang đổ dữ liệu cho bảng `moc_thoi_gian`
--

INSERT INTO `moc_thoi_gian` (`id`, `ten_moc`, `ngay_batdau`, `ngay_ketthuc`, `mota`, `created_at`, `updated_at`) VALUES
(1, 'Nhập đề tài', '2025-03-01', '2025-03-15', 'Sinh viên nhập đề tài và đăng ký nhóm', NULL, NULL),
(2, 'Phân công giảng viên hướng dẫn', '2025-03-16', '2025-03-25', 'Admin và khoa phân công GVHD cho sinh viên', NULL, NULL),
(3, 'Báo cáo tiến độ lần 1', '2025-04-01', '2025-04-10', 'Sinh viên nộp báo cáo tiến độ đầu tiên', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `sdt` varchar(255) DEFAULT NULL,
  `vaitro` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `hoten`, `email`, `matkhau`, `sdt`, `vaitro`, `created_at`, `updated_at`) VALUES
(1, 'Admin Khoa CNTT', 'admin.cntt@stu.edu.vn', '$2y$12$Owi8r6KXr2pJoJAKK0btNOQtBLDMuSLlBnjbHGWzQlTcd/gMIUhWa', '0900000000', 'admin', NULL, NULL),
(2, 'GVHD Tran Thanh Binh', 'nguyenvana@stu.edu.vn', '$2y$12$w7Mar9H6UijaFF5YvEQ2Ee5yjR2fAMAWzl9FwBdPX9jhudPlfOkBO', '0901111111', 'gvhd', NULL, NULL),
(3, 'GVPB Tran Thi Bay', 'tranthib@stu.edu.vn', '$2y$12$Dk.mMoU3K1juqYTCba0PNuXhixE18.AWi8WlcDhruza8strBm1R/e', '0902222222', 'gvpb', NULL, NULL),
(4, 'tong thanh binh', 'dh52102314@student.stu.edu.vn', '$2y$12$TslHm2mZvXpXTy.GCH.WVO9zLXv1YGq1z.MuL9c5MLxONaJv3.wQS', '0903333333', 'sv', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhom_sinhvien`
--

CREATE TABLE `nhom_sinhvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_nhom` varchar(255) NOT NULL,
  `truong_nhom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhom_sinhvien`
--

INSERT INTO `nhom_sinhvien` (`id`, `ten_nhom`, `truong_nhom_id`, `created_at`, `updated_at`) VALUES
(1, 'Nhóm 1', 1, NULL, NULL),
(2, 'Nhóm 2', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhom_sinhvien_chitiet`
--

CREATE TABLE `nhom_sinhvien_chitiet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhom_sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `sinhvien_id` bigint(20) UNSIGNED NOT NULL,
  `vai_tro` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
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
-- Đang đổ dữ liệu cho bảng `phancong`
--

INSERT INTO `phancong` (`id`, `detai_id`, `giang_vien_id`, `nguoi_phan_cong_id`, `ngay_phan_cong`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2025-10-13 04:41:39', 'Phân công hướng dẫn cho nhóm 1', NULL, NULL),
(2, 2, 3, 1, '2025-10-13 04:41:39', 'Phân công phản biện đề tài 2', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_danh_gia`
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

--
-- Đang đổ dữ liệu cho bảng `phieu_danh_gia`
--

INSERT INTO `phieu_danh_gia` (`id`, `detai_id`, `giangvien_id`, `loai`, `diem`, `nhanxet`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'huongdan', 9, 'Sinh viên làm tốt', NULL, NULL),
(2, 1, 2, 'phanbien', 8, 'Cần cải thiện giao diện', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sinhvien`
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
-- Đang đổ dữ liệu cho bảng `sinhvien`
--

INSERT INTO `sinhvien` (`id`, `nguoidung_id`, `mssv`, `hoten`, `email`, `sdt`, `lop`, `nienkhoa`, `khoa`, `trangthai`, `created_at`) VALUES
(1, NULL, 'DH52102314', 'Nguyen Van Anh', 'dh52102314@student.stu.edu.vn', '0901111111', 'D21_TH01', 2021, 'CNTT', 'chuaphancong', '2025-10-13 11:41:39'),
(2, NULL, 'DH52102315', 'Tran Thi Bong', 'dh52102315@student.stu.edu.vn', '0902222222', 'D21_TH01', 2021, 'CNTT', 'chuaphancong', '2025-10-13 11:41:39'),
(3, NULL, 'DH52102316', 'Le Van Cuong', 'dh52102316@student.stu.edu.vn', '0903333333', 'D21_TH02', 2021, 'CNTT', 'chuaphancong', '2025-10-13 11:41:39'),
(4, NULL, 'DH52102317', 'Pham Thi Dung', 'dh52102317@student.stu.edu.vn', '0904444444', 'D21_TH02', 2021, 'CNTT', 'chuaphancong', '2025-10-13 11:41:39'),
(5, NULL, 'DH52102318', 'Do Van Em', 'dh52102318@student.stu.edu.vn', '0905555555', 'D21_TH03', 2021, 'CNTT', 'chuaphancong', '2025-10-13 11:41:39');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `detai`
--
ALTER TABLE `detai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detai_giangvien_id_foreign` (`giangvien_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `giangvien_magv_unique` (`magv`),
  ADD KEY `giangvien_nguoidung_id_foreign` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `moc_thoi_gian`
--
ALTER TABLE `moc_thoi_gian`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nguoidung_email_unique` (`email`);

--
-- Chỉ mục cho bảng `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhom_sinhvien_truong_nhom_id_foreign` (`truong_nhom_id`);

--
-- Chỉ mục cho bảng `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhom_sinhvien_chitiet_nhom_sinhvien_id_foreign` (`nhom_sinhvien_id`),
  ADD KEY `nhom_sinhvien_chitiet_sinhvien_id_foreign` (`sinhvien_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Chỉ mục cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phancong_detai_id_foreign` (`detai_id`),
  ADD KEY `phancong_giang_vien_id_foreign` (`giang_vien_id`),
  ADD KEY `phancong_nguoi_phan_cong_id_foreign` (`nguoi_phan_cong_id`);

--
-- Chỉ mục cho bảng `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phieu_danh_gia_detai_id_foreign` (`detai_id`),
  ADD KEY `phieu_danh_gia_giangvien_id_foreign` (`giangvien_id`);

--
-- Chỉ mục cho bảng `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sinhvien_mssv_unique` (`mssv`),
  ADD KEY `sinhvien_nguoidung_id_foreign` (`nguoidung_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `detai`
--
ALTER TABLE `detai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giangvien`
--
ALTER TABLE `giangvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `moc_thoi_gian`
--
ALTER TABLE `moc_thoi_gian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phancong`
--
ALTER TABLE `phancong`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `sinhvien`
--
ALTER TABLE `sinhvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `detai`
--
ALTER TABLE `detai`
  ADD CONSTRAINT `detai_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `giangvien`
--
ALTER TABLE `giangvien`
  ADD CONSTRAINT `giangvien_nguoidung_id_foreign` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhom_sinhvien`
--
ALTER TABLE `nhom_sinhvien`
  ADD CONSTRAINT `nhom_sinhvien_truong_nhom_id_foreign` FOREIGN KEY (`truong_nhom_id`) REFERENCES `sinhvien` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `nhom_sinhvien_chitiet`
--
ALTER TABLE `nhom_sinhvien_chitiet`
  ADD CONSTRAINT `nhom_sinhvien_chitiet_nhom_sinhvien_id_foreign` FOREIGN KEY (`nhom_sinhvien_id`) REFERENCES `nhom_sinhvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhom_sinhvien_chitiet_sinhvien_id_foreign` FOREIGN KEY (`sinhvien_id`) REFERENCES `sinhvien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD CONSTRAINT `phancong_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancong_giang_vien_id_foreign` FOREIGN KEY (`giang_vien_id`) REFERENCES `giangvien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phancong_nguoi_phan_cong_id_foreign` FOREIGN KEY (`nguoi_phan_cong_id`) REFERENCES `nguoidung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `phieu_danh_gia`
--
ALTER TABLE `phieu_danh_gia`
  ADD CONSTRAINT `phieu_danh_gia_detai_id_foreign` FOREIGN KEY (`detai_id`) REFERENCES `detai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_danh_gia_giangvien_id_foreign` FOREIGN KEY (`giangvien_id`) REFERENCES `giangvien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD CONSTRAINT `sinhvien_nguoidung_id_foreign` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
