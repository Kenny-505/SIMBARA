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
        'verified_by'
    ];

    protected $casts = [
        'tanggal_pengembalian_aktual' => 'datetime',
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

    public function pengembalianBarangs()
    {
        return $this->hasMany(PengembalianBarang::class, 'id_pengembalian');
    }
}
