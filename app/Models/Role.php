<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id_role';
    
    protected $fillable = [
        'nama_role',
        'deskripsi'
    ];

    // Relationships
    public function admins()
    {
        return $this->hasMany(Admin::class, 'id_role');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
