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
        // Add 'cancelled' to status_persetujuan enum
        DB::statement("ALTER TABLE `peminjaman_barang` MODIFY COLUMN `status_persetujuan` ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'cancelled' from status_persetujuan enum
        DB::statement("ALTER TABLE `peminjaman_barang` MODIFY COLUMN `status_persetujuan` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
