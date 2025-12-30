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
        Schema::table('detai', function (Blueprint $table) {
            if (!Schema::hasColumn('detai', 'hoi_dong_id')) {
                $table->unsignedBigInteger('hoi_dong_id')->nullable()->after('nhom_sinhvien_id');
                $table->foreign('hoi_dong_id')
                    ->references('id')
                    ->on('hoi_dong')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detai', function (Blueprint $table) {
            if (Schema::hasColumn('detai', 'hoi_dong_id')) {
                $table->dropForeign(['hoi_dong_id']);
                $table->dropColumn('hoi_dong_id');
            }
        });
    }
};
