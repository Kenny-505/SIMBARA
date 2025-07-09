<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_user',
        'id_peminjaman',
        'id_pengembalian',
        'jenis_transaksi',
        'nominal',
        'bukti_pembayaran',
        'status_verifikasi',
        'tanggal_pembayaran',
        'tanggal_verifikasi',
        'verified_by',
        'notes_admin'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }
}
