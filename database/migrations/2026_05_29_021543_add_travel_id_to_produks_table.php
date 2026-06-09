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
        Schema::table('produks', function (Blueprint $table) {
            if (!Schema::hasColumn('produks', 'travel_id')) {
                $table->unsignedBigInteger('travel_id')->nullable()->comment('ID of the travel agent (role_users) who owns this product. NULL = available to all.');
            }

            // Add index for faster filtering
            $table->index('travel_id', 'produks_travel_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropIndex('produks_travel_id_index');
            $table->dropColumn('travel_id');
        });
    }
};
