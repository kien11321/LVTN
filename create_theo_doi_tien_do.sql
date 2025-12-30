-- Tạo bảng theo_doi_tien_do
CREATE TABLE IF NOT EXISTS theo_doi_tien_do (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nhom_sinhvien_id BIGINT UNSIGNED NOT NULL,
    detai_id BIGINT UNSIGNED NULL,
    tien_do INT DEFAULT 0 COMMENT 'Tiến độ hoàn thành 0-100%',
    quyet_dinh ENUM('duoc_lam_tiep', 'tam_dung', 'huy') DEFAULT 'duoc_lam_tiep' COMMENT 'Quyết định',
    ghi_chu TEXT NULL COMMENT 'Ghi chú giữa kỳ',
    giangvien_id BIGINT UNSIGNED NULL COMMENT 'Giảng viên cập nhật',
    ngay_cap_nhat TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_nhom (nhom_sinhvien_id),
    FOREIGN KEY (nhom_sinhvien_id) REFERENCES nhom_sinhvien(id) ON DELETE CASCADE,
    FOREIGN KEY (detai_id) REFERENCES detai(id) ON DELETE SET NULL,
    FOREIGN KEY (giangvien_id) REFERENCES giangvien(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;







