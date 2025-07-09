<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';
    
    protected $fillable = [
        'user_type',
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'action_url'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Polymorphic relationships
    public function notifiable()
    {
        return $this->morphTo('user');
    }
}
