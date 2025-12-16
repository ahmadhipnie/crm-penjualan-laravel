<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualans';

    protected $fillable = [
        'id_penjualan',
        'id_produk',
        'qty',
        'harga',
        'subtotal'
    ];

    protected $casts = [
        'id_penjualan' => 'integer',
        'id_produk' => 'integer',
        'qty' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'integer'
    ];

    // Relationships
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_produk');
    }
}
