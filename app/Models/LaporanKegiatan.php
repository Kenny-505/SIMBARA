<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    protected $table = 'laporan_kegiatan';
    protected $primaryKey = 'id_laporan';
    
    protected $fillable = [
        'id_peminjaman',
        'dokumentasi_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_upload',
        'status_verifikasi',
        'notes_admin'
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}
