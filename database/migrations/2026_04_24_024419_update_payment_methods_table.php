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
        // 1. Cập nhật bảng thanh_toan
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->string('dia_diem_hen')->nullable()->after('phuong_thuc');
            $table->datetime('thoi_gian_hen')->nullable()->after('dia_diem_hen');
        });

        // Cập nhật ENUM phuong_thuc và trang_thai trong bảng thanh_toan dùng DB::statement
        // Vì Laravel Schema không hỗ trợ tốt việc đổi ENUM
        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN phuong_thuc ENUM('vnpay', 'momo', 'cod', 'chuyen_khoan', 'zalopay', 'tien_mat', 'dat_coc') NOT NULL");
        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN trang_thai ENUM('cho_xu_ly', 'thanh_cong', 'that_bai', 'hoan_tien', 'da_thanh_toan', 'da_dat_coc') NOT NULL DEFAULT 'cho_xu_ly'");

        // 2. Cập nhật bảng dat_tour
        Schema::table('dat_tour', function (Blueprint $table) {
            $table->decimal('tong_tien_da_thanh_toan', 14, 0)->default(0)->after('tong_tien_thanh_toan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_tour', function (Blueprint $table) {
            $table->dropColumn('tong_tien_da_thanh_toan');
        });

        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->dropColumn(['dia_diem_hen', 'thoi_gian_hen']);
        });

        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN phuong_thuc ENUM('vnpay', 'momo', 'cod', 'chuyen_khoan', 'zalopay', 'tien_mat') NOT NULL");
        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN trang_thai ENUM('cho_xu_ly', 'thanh_cong', 'that_bai', 'hoan_tien', 'da_thanh_toan') NOT NULL DEFAULT 'cho_xu_ly'");
    }
};
