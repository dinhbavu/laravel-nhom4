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
        Schema::table('lich_su_dang_nhap', function (Blueprint $table) {
            if (!Schema::hasColumn('lich_su_dang_nhap', 'device_id')) {
                $table->string('device_id')->nullable()->after('khach_hang_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lich_su_dang_nhap', function (Blueprint $table) {
            $table->dropColumn('device_id');
        });
    }
};
