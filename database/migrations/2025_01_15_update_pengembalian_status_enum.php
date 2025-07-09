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
        // Update enum status_pengembalian untuk include status baru
        DB::statement("ALTER TABLE `pengembalian` MODIFY COLUMN `status_pengembalian` ENUM('pending', 'verified', 'completed', 'payment_required', 'payment_uploaded', 'fully_completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to original enum
        DB::statement("ALTER TABLE `pengembalian` MODIFY COLUMN `status_pengembalian` ENUM('pending', 'verified', 'completed') DEFAULT 'pending'");
    }
}; 