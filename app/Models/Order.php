<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'shipping_address',
        'notes',
        'status',
        'status_updated_at'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status_updated_at' => 'datetime',
    ];

    // Relasi ke User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Produk::class);
    }
}