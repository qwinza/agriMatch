<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',      // User yang menerima notifikasi (petani)
        'type',         // Jenis notifikasi, misal 'order'
        'message',      // Pesan notifikasi
        'is_read',      // Status sudah dibaca
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
