<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'gross_amount',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }
}
