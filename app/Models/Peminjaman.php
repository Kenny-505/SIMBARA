<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    
    protected $fillable = [
        'id_user',
        'kode_peminjaman',
        'nama_pengambil',
        'no_identitas_pengambil',
        'no_hp_pengambil',
        'tujuan_peminjaman',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pengajuan',
        'status_pembayaran',
        'status_peminjaman',
        'total_biaya',
        'notes_admin'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'total_biaya' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function peminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_peminjaman');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_peminjaman');
    }

    // Helper method to get all barang through peminjamanBarangs
    public function barang()
    {
        return $this->hasManyThrough(
            Barang::class,
            PeminjamanBarang::class,
            'id_peminjaman',
            'id_barang',
            'id_peminjaman',
            'id_barang'
        );
    }
}
