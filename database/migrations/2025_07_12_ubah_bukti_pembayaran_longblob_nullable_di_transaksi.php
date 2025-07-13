<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `transaksi` MODIFY `bukti_pembayaran` LONGBLOB NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah kembali ke TEXT NOT NULL jika perlu (atau sesuaikan dengan tipe sebelumnya)
        DB::statement('ALTER TABLE `transaksi` MODIFY `bukti_pembayaran` TEXT NOT NULL');
    }
}; 