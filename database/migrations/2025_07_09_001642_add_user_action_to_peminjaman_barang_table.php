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
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->enum('user_action', ['deleted'])->nullable()->after('tanggal_persetujuan');
            $table->timestamp('action_timestamp')->nullable()->after('user_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->dropColumn(['user_action', 'action_timestamp']);
        });
    }
};
