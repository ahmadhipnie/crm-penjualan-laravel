<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'id_user',
        'id_jenis_ekspedisi',
        'kode_transaksi',
        'nomor_resi',
        'snap_token',
        'alamat_pengiriman',
        'status',
        'prakiraan_tanggal_sampai',
        'gambar_bukti_sampai',
        'total_harga'
    ];

    protected $casts = [
        'id_user' => 'integer',
        'id_jenis_ekspedisi' => 'integer',
        'prakiraan_tanggal_sampai' => 'date',
        'ongkos_kirim' => 'integer',
        'diskon' => 'integer',
        'total_harga' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jenisEkspedisi()
    {
        return $this->belongsTo(JenisEkspedisi::class, 'id_jenis_ekspedisi');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    // Accessor untuk gambar bukti sampai
    public function getGambarBuktiSampaiAttribute($value)
    {
        if ($value && file_exists(public_path($value))) {
            return asset($value);
        }
        return null;
    }

    // Method untuk generate kode transaksi
    public static function generateKodeTransaksi()
    {
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', today())->count() + 1;
        return 'TRX' . $date . str_pad($lastTransaction, 4, '0', STR_PAD_LEFT);
    }
}
