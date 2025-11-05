<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'recipient_name',
        'phone',
        'shipping_address',
        'notes',
        'total_price',
        'status',
        'order_code',

        // Kolom Midtrans
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_gross_amount',
        'midtrans_response',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaksi::class, 'order_id');
    }
}
