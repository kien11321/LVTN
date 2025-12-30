<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tự động tạo bảng sessions nếu chưa tồn tại
        try {
            $tables = DB::select('SHOW TABLES');
            $sessionsExists = false;
            
            foreach ($tables as $table) {
                $tableArray = (array)$table;
                foreach ($tableArray as $value) {
                    if (strpos($value, 'sessions') !== false) {
                        $sessionsExists = true;
                        break 2;
                    }
                }
            }
            
            if (!$sessionsExists) {
                DB::statement("
                    CREATE TABLE IF NOT EXISTS sessions (
                        id VARCHAR(255) PRIMARY KEY,
                        user_id BIGINT UNSIGNED NULL,
                        ip_address VARCHAR(45) NULL,
                        user_agent TEXT NULL,
                        payload LONGTEXT NOT NULL,
                        last_activity INT NOT NULL,
                        INDEX idx_user_id (user_id),
                        INDEX idx_last_activity (last_activity)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
            }
            
            // Kiểm tra và thêm cột remember_token vào bảng nguoidung nếu chưa có
            try {
                $columns = DB::select("SHOW COLUMNS FROM nguoidung LIKE 'remember_token'");
                if (count($columns) == 0) {
                    DB::statement("ALTER TABLE nguoidung ADD COLUMN remember_token VARCHAR(100) NULL AFTER matkhau");
                }
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không thể thêm cột
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu không thể tạo bảng
        }
    }
}
