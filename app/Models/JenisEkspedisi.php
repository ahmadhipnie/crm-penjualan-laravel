<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisEkspedisi extends Model
{
    use HasFactory;

    protected $table = 'jenis_ekspedisis';

    protected $fillable = [
        'nama_ekspedisi'
    ];

    // Relationship
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_jenis_ekspedisi');
    }
}
