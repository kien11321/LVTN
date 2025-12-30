<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cham_diem_phan_bien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detai_id');
            $table->unsignedBigInteger('sinhvien_id');
            $table->unsignedBigInteger('giangvien_id'); // GVPB chấm
            $table->decimal('phan_tich', 5, 2)->default(0);
            $table->decimal('thiet_ke', 5, 2)->default(0);
            $table->decimal('hien_thuc', 5, 2)->default(0);
            $table->decimal('bao_cao', 5, 2)->default(0);
            $table->decimal('tong_phan_tram', 5, 2)->default(0); // 0-100
            $table->decimal('diem_10', 4, 2)->default(0);       // thang 10
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->unique(['detai_id', 'sinhvien_id', 'giangvien_id'], 'cdpb_unique');
            $table->foreign('detai_id')->references('id')->on('detai')->onDelete('cascade');
            $table->foreign('sinhvien_id')->references('id')->on('sinhvien')->onDelete('cascade');
            $table->foreign('giangvien_id')->references('id')->on('giangvien')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cham_diem_phan_bien');
    }
};

