<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    protected $table = 'peminjaman_barang';
    protected $primaryKey = 'id_peminjaman_barang';
    
    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah_pinjam',
        'harga_satuan',
        'subtotal',
        'status_persetujuan',
        'approved_by',
        'notes_admin',
        'tanggal_persetujuan',
        'user_action',
        'action_timestamp'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tanggal_persetujuan' => 'datetime',
        'action_timestamp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
