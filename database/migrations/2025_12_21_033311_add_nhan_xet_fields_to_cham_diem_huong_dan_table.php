<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cham_diem_huong_dan', function (Blueprint $table) {
            $table->text('noi_dung_dieu_chinh')->nullable()->after('ghi_chu');
            $table->text('nhan_xet_tong_quat')->nullable()->after('noi_dung_dieu_chinh');
            $table->enum('thuyet_minh', ['dat', 'khong_dat'])->nullable()->after('nhan_xet_tong_quat');
            $table->text('uu_diem')->nullable()->after('thuyet_minh');
            $table->text('thieu_sot')->nullable()->after('uu_diem');
            $table->text('cau_hoi')->nullable()->after('thieu_sot');
            $table->enum('de_nghi', ['duoc_bao_ve', 'khong_bao_ve', 'bo_sung'])->nullable()->after('cau_hoi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cham_diem_huong_dan', function (Blueprint $table) {
            $table->dropColumn([
                'noi_dung_dieu_chinh',
                'nhan_xet_tong_quat',
                'thuyet_minh',
                'uu_diem',
                'thieu_sot',
                'cau_hoi',
                'de_nghi',
            ]);
        });
    }
};
