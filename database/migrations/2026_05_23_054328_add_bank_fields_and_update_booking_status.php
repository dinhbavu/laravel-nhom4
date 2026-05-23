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
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->string('ten_ngan_hang', 100)->nullable()->after('dia_chi');
            $table->string('so_tai_khoan', 50)->nullable()->after('ten_ngan_hang');
            $table->string('ten_tai_khoan', 150)->nullable()->after('so_tai_khoan');
        });

        Schema::table('dat_tour', function (Blueprint $table) {
            $table->string('trang_thai', 50)->default('cho_duyet')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn(['ten_ngan_hang', 'so_tai_khoan', 'ten_tai_khoan']);
        });

        Schema::table('dat_tour', function (Blueprint $table) {
            // Leave it as string or fallback
            $table->string('trang_thai', 50)->default('cho_duyet')->change();
        });
    }
};
