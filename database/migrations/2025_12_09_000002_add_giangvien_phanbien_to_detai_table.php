<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detai', function (Blueprint $table) {
            if (!Schema::hasColumn('detai', 'giangvien_phanbien_id')) {
                $table->unsignedBigInteger('giangvien_phanbien_id')->nullable()->after('giangvien_id');
                $table->foreign('giangvien_phanbien_id')
                    ->references('id')
                    ->on('giangvien')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detai', function (Blueprint $table) {
            if (Schema::hasColumn('detai', 'giangvien_phanbien_id')) {
                $table->dropForeign(['giangvien_phanbien_id']);
                $table->dropColumn('giangvien_phanbien_id');
            }
        });
    }
};




