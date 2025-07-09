<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM kondisi_barang to match controller values
        DB::statement("ALTER TABLE `pengembalian_barang` MODIFY COLUMN `kondisi_barang` ENUM('baik', 'ringan', 'sedang', 'parah') DEFAULT 'baik'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to original ENUM values
        DB::statement("ALTER TABLE `pengembalian_barang` MODIFY COLUMN `kondisi_barang` ENUM('baik', 'rusak_ringan', 'rusak_sedang', 'rusak_parah') DEFAULT 'baik'");
    }
};
