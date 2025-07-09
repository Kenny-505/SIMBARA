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
        // Update enum status_pengajuan untuk include status 'partial'
        DB::statement("ALTER TABLE `peminjaman` MODIFY COLUMN `status_pengajuan` ENUM('draft', 'pending_approval', 'approved', 'rejected', 'confirmed', 'partial') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to original enum (remove 'partial')
        DB::statement("ALTER TABLE `peminjaman` MODIFY COLUMN `status_pengajuan` ENUM('draft', 'pending_approval', 'approved', 'rejected', 'confirmed') DEFAULT 'draft'");
    }
};
