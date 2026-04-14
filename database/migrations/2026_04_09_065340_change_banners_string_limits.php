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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('tieu_de', 255)->change();
            $table->string('hinh_anh_url', 2000)->change();
            $table->string('duong_dan', 2000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('tieu_de', 100)->change();
            $table->string('hinh_anh_url', 255)->change();
            $table->string('duong_dan', 255)->nullable()->change();
        });
    }
};
