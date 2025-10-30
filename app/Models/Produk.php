<?php

namespace App\Models;

use App\Models\Traits\HasEncryptionId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    use HasFactory, HasEncryptionId;

    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'kategori',
        'lokasi',
        'status'
    ];

    protected $casts = [
        'harga' => 'float', // Ganti dari 'decimal:2' ke 'float'
        'stok' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    // Accessor untuk foto URL
    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return asset('images/default-product.jpg');
        }

        return Storage::url($this->foto);
    }

    // Accessor untuk format harga - PERBAIKI INI
    public function getHargaFormattedAttribute()
    {
        // Pastikan harga tidak null dan convert ke float
        $harga = $this->harga ? (float) $this->harga : 0;
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_produk', 'like', '%' . $search . '%')
            ->orWhere('deskripsi', 'like', '%' . $search . '%');
    }

    // Scope untuk filter kategori
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }
}