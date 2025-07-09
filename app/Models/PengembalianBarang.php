<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianBarang extends Model
{
    protected $table = 'pengembalian_barang';
    protected $primaryKey = 'id_pengembalian_barang';
    
    protected $fillable = [
        'id_pengembalian',
        'id_barang',
        'jumlah_kembali',
        'kondisi_barang',
        'denda_kerusakan',
        'keterangan_kerusakan'
    ];

    protected $casts = [
        'denda_kerusakan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
