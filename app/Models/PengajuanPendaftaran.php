<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pendaftaran';
    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'nama_penanggung_jawab',
        'email',
        'no_hp',
        'no_identitas',
        'tujuan_peminjaman',
        'surat_keterangan',
        'jenis_peminjam',
        'tanggal_mulai_kegiatan',
        'tanggal_berakhir_kegiatan',
        'status_verifikasi',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
        'alasan_penolakan',
        'id_user_created',
    ];

    protected $dates = [
        'tanggal_mulai_kegiatan',
        'tanggal_berakhir_kegiatan',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_mulai_kegiatan' => 'date',
        'tanggal_berakhir_kegiatan' => 'date',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    // Disable timestamps since we use custom datetime fields
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->tanggal_pengajuan) {
                $model->tanggal_pengajuan = now();
            }
        });
    }

    /**
     * Calculate account expiry date (H+7 from tanggal_berakhir_kegiatan)
     * 
     * @return \Carbon\Carbon
     */
    public function getAccountExpiryDate()
    {
        return $this->tanggal_berakhir_kegiatan->addDays(7);
    }

    /**
     * Get duration of activity in days
     * 
     * @return int
     */
    public function getActivityDuration()
    {
        return $this->tanggal_mulai_kegiatan->diffInDays($this->tanggal_berakhir_kegiatan) + 1;
    }

    /**
     * Check if activity is ongoing
     * 
     * @return bool
     */
    public function isActivityOngoing()
    {
        $today = now()->toDateString();
        return $today >= $this->tanggal_mulai_kegiatan && $today <= $this->tanggal_berakhir_kegiatan;
    }

    /**
     * Get the user that was created from this pengajuan
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id_user');
    }
}
