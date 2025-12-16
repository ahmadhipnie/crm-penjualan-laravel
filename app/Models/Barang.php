<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'id_kategori',
        'sku_barang',
        'nama_barang',
        'deskripsi',
        'material',
        'harga',
        'berat',
        'stok'
    ];

    // Accessor untuk nama (agar bisa akses dengan $barang->nama)
    public function getNamaAttribute()
    {
        return $this->nama_barang;
    }

    // Accessor untuk stock (agar bisa akses dengan $barang->stock)
    public function getStockAttribute()
    {
        return $this->stok;
    }

    protected $casts = [
        'id_kategori' => 'integer',
        'harga' => 'integer',
        'berat' => 'integer',
        'stok' => 'integer'
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function gambarBarangs()
    {
        return $this->hasMany(GambarBarang::class, 'id_barang');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_produk');
    }

    /**
     * Relasi ke tabel keranjangs
     * Satu barang bisa ada di banyak keranjang
     */
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'id_barang');
    }

    /**
     * Helper method untuk mendapatkan gambar utama barang
     */
    public function getPrimaryImageAttribute()
    {
        $primaryImage = $this->gambarBarangs()->where('is_primary', true)->first();
        return $primaryImage ? $primaryImage->gambar_url : null;
    }

    /**
     * Helper method untuk cek apakah barang masih tersedia
     */
    public function getIsAvailableAttribute()
    {
        return $this->stok > 0;
    }

    /**
     * Helper method untuk mendapatkan format harga
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
