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
            $table->string('injeksi_oleh')->nullable()->after('bukti_injeksi');
            $table->timestamp('injeksi_at')->nullable()->after('injeksi_oleh');
            $table->text('catatan_injeksi')->nullable()->after('injeksi_at');
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
