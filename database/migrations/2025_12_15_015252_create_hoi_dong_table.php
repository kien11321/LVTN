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
        Schema::create('hoi_dong', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hoi_dong', 255);
            $table->date('ngay_bao_ve'); // Chỉ lưu ngày, không lưu giờ
            $table->string('phong_bao_ve', 100)->nullable();
            $table->unsignedBigInteger('chu_tich_id'); // Chủ tịch
            $table->unsignedBigInteger('thu_ky_id'); // Thư ký
            $table->unsignedBigInteger('uy_vien_1_id')->nullable(); // Ủy viên 1
            $table->unsignedBigInteger('uy_vien_2_id')->nullable(); // Ủy viên 2
            $table->timestamps();

            $table->foreign('chu_tich_id')->references('id')->on('giangvien')->onDelete('restrict');
            $table->foreign('thu_ky_id')->references('id')->on('giangvien')->onDelete('restrict');
            $table->foreign('uy_vien_1_id')->references('id')->on('giangvien')->onDelete('set null');
            $table->foreign('uy_vien_2_id')->references('id')->on('giangvien')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoi_dong');
    }
};
