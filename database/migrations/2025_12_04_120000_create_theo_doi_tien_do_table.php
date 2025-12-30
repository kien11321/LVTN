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
        Schema::create('theo_doi_tien_do', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhom_sinhvien_id');
            $table->unsignedBigInteger('detai_id')->nullable();
            $table->integer('tien_do')->default(0)->comment('Tiến độ hoàn thành 0-100%');
            $table->enum('quyet_dinh', ['duoc_lam_tiep', 'tam_dung', 'huy'])->default('duoc_lam_tiep')->comment('Quyết định: được làm tiếp, tạm dừng, hủy');
            $table->text('ghi_chu')->nullable()->comment('Ghi chú giữa kỳ');
            $table->unsignedBigInteger('giangvien_id')->nullable()->comment('Giảng viên cập nhật');
            $table->timestamp('ngay_cap_nhat')->nullable();
            $table->timestamps();

            $table->foreign('nhom_sinhvien_id')->references('id')->on('nhom_sinhvien')->onDelete('cascade');
            $table->foreign('detai_id')->references('id')->on('detai')->onDelete('set null');
            $table->foreign('giangvien_id')->references('id')->on('giangvien')->onDelete('set null');
            
            $table->unique('nhom_sinhvien_id'); // Mỗi nhóm chỉ có 1 bản ghi tiến độ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theo_doi_tien_do');
    }
};







