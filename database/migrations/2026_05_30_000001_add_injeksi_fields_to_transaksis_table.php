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
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'injeksi_oleh')) {
                $table->string('injeksi_oleh')->nullable();
            }
            if (!Schema::hasColumn('transaksis', 'injeksi_at')) {
                $table->timestamp('injeksi_at')->nullable();
            }
            if (!Schema::hasColumn('transaksis', 'catatan_injeksi')) {
                $table->text('catatan_injeksi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['injeksi_oleh', 'injeksi_at', 'catatan_injeksi']);
        });
    }
};
