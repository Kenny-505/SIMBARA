<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    
    protected $fillable = [
        'id_peminjaman',
        'tanggal_pengembalian_aktual',
        'status_pengembalian',
        'total_denda',
        'denda_telat',
        'hari_telat',
        'notes_admin',
        'verified_by',
        'bukti_pembayaran_denda',
        'tanggal_upload_pembayaran',
        'status_pembayaran_denda',
        'catatan_pembayaran',
        'verified_payment_by',
        'verified_payment_at'
    ];

    protected $casts = [
        'tanggal_pengembalian_aktual' => 'datetime',
        'tanggal_upload_pembayaran' => 'datetime',
        'verified_payment_at' => 'datetime',
        'total_denda' => 'decimal:2',
        'denda_telat' => 'decimal:2',
        'hari_telat' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }
    
    // Alias untuk processedBy (sama dengan verifiedBy)
    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function pengembalianBarangs()
    {
        return $this->hasMany(PengembalianBarang::class, 'id_pengembalian');
    }
}
