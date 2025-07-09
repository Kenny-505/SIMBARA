<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    
    protected $fillable = [
        'id_role',
        'username',
        'password',
        'nama_lengkap',
        'email',
        'asal',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_admin');
    }

    public function verifiedPengajuans()
    {
        return $this->hasMany(PengajuanPendaftaran::class, 'verified_by');
    }

    public function approvedPeminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::class, 'approved_by');
    }
}
