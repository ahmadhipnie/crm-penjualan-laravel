<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarBarang extends Model
{
    use HasFactory;

    protected $table = 'gambar_barangs';

    protected $fillable = [
        'id_barang',
        'gambar_url',
        'is_primary'
    ];

    protected $casts = [
        'id_barang' => 'integer',
        'is_primary' => 'boolean'
    ];

    // Relationship
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Accessor untuk mendapatkan URL gambar dengan fallback ke placeholder
    public function getGambarUrlAttribute($value)
    {
        if ($value && file_exists(public_path($value))) {
            return $value;
        }
        return 'img/placeholder-furniture.jpg';
    }

    // Method untuk mendapatkan full URL
    public function getFullUrlAttribute()
    {
        return asset($this->gambar_url);
    }
}
