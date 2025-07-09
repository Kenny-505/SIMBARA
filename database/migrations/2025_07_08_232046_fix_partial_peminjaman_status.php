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
        // Fix peminjaman with partial status that incorrectly have ongoing status
        // When status_pengajuan is 'partial', status_peminjaman should be NULL
        // because the loan hasn't been confirmed yet
        DB::statement("UPDATE `peminjaman` SET `status_peminjaman` = NULL WHERE `status_pengajuan` = 'partial' AND `status_peminjaman` = 'ongoing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data inconsistency, no need to rollback
        // as the original state was incorrect
    }
};
