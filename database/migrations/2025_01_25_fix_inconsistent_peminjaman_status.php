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
        // Fix inconsistent peminjaman status where status_peminjaman is 'ongoing' 
        // but status_pengajuan is not 'confirmed'
        // This happens when stock reduction logic was running prematurely
        
        $affectedRows = DB::update("
            UPDATE `peminjaman` 
            SET `status_peminjaman` = NULL 
            WHERE `status_peminjaman` = 'ongoing' 
            AND `status_pengajuan` IN ('draft', 'pending_approval', 'approved', 'partial', 'rejected')
        ");
        
        // Log the number of affected rows for debugging
        \Log::info('Fixed inconsistent peminjaman status', [
            'affected_rows' => $affectedRows,
            'description' => 'Reset status_peminjaman to NULL for loans that were incorrectly marked as ongoing'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data inconsistency, no need to rollback
        // as the original state was incorrect and could cause confusion
    }
}; 