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
        Schema::create('nhap_diem_bao_ve', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detai_id');
            $table->unsignedBigInteger('sinhvien_id');
            $table->decimal('diem_bao_ve', 4, 2)->default(0); // Điểm nhập vào (thang 10)
            $table->decimal('diem_gv', 4, 2)->default(0); // Điểm GV = (GVHD*0.2 + GVPB*0.2)
            $table->decimal('diem_tong', 4, 2)->default(0); // Điểm tổng = diem_gv + (diem_bao_ve*0.6)
            $table->string('trang_thai', 50)->nullable(); // Trạng thái
            $table->timestamps();

            $table->unique(['detai_id', 'sinhvien_id'], 'ndbv_unique');
            $table->foreign('detai_id')->references('id')->on('detai')->onDelete('cascade');
            $table->foreign('sinhvien_id')->references('id')->on('sinhvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhap_diem_bao_ve');
    }
};
