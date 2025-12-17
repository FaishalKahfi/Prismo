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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('max_claims')->nullable()->after('color'); // Maksimal jumlah klaim
            $table->integer('claimed_count')->default(0)->after('max_claims'); // Jumlah yang sudah diklaim
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['max_claims', 'claimed_count']);
        });
    }
};
