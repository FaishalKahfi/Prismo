<?php

/**
 * Script untuk memperbaiki data voucher yang sudah ada
 * Script ini akan:
 * 1. Set claimed_count berdasarkan jumlah user yang sudah claim
 * 2. Set max_claims jika masih NULL (opsional, bisa di-comment jika tidak diperlukan)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Voucher;
use App\Models\UserVoucher;

echo "Starting voucher data fix...\n\n";

$vouchers = Voucher::all();

foreach ($vouchers as $voucher) {
    // Hitung jumlah user yang sudah claim voucher ini
    $claimedCount = UserVoucher::where('voucher_id', $voucher->id)->count();

    echo "Voucher: {$voucher->code} (ID: {$voucher->id})\n";
    echo "  Current claimed_count in DB: {$voucher->claimed_count}\n";
    echo "  Actual claimed count: {$claimedCount}\n";

    // Update claimed_count
    $voucher->claimed_count = $claimedCount;

    // Jika max_claims masih NULL, set ke 0 (unlimited)
    // Anda bisa mengubah ini sesuai kebutuhan
    if (is_null($voucher->max_claims)) {
        echo "  max_claims is NULL, keeping as NULL (unlimited)\n";
    } else {
        echo "  max_claims: {$voucher->max_claims}\n";
    }

    $voucher->save();

    echo "  ✓ Updated claimed_count to {$claimedCount}\n";
    echo "\n";
}

echo "Done! Total vouchers processed: " . $vouchers->count() . "\n";
