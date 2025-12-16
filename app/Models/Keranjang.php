<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_barang',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    /**
     * Relasi ke tabel users
     * Satu keranjang dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke tabel barangs
     * Satu keranjang berisi satu barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    /**
     * Accessor untuk mendapatkan total harga item di keranjang
     */
    public function getSubtotalAttribute()
    {
        return $this->jumlah * $this->barang->harga;
    }

    /**
     * Scope untuk mendapatkan keranjang berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    /**
     * Scope untuk mendapatkan keranjang dengan relasi barang
     */
    public function scopeWithBarang($query)
    {
        return $query->with(['barang', 'barang.gambarBarangs']);
    }

    /**
     * Static method untuk menambah item ke keranjang
     * Jika item sudah ada, maka jumlahnya akan ditambah
     */
    public static function addToCart($userId, $barangId, $jumlah = 1)
    {
        $existingItem = self::where('id_user', $userId)
                           ->where('id_barang', $barangId)
                           ->first();

        if ($existingItem) {
            $existingItem->increment('jumlah', $jumlah);
            return $existingItem;
        }

        return self::create([
            'id_user' => $userId,
            'id_barang' => $barangId,
            'jumlah' => $jumlah,
        ]);
    }

    /**
     * Static method untuk update jumlah item di keranjang
     */
    public static function updateQuantity($userId, $barangId, $jumlah)
    {
        $item = self::where('id_user', $userId)
                   ->where('id_barang', $barangId)
                   ->first();

        if ($item) {
            if ($jumlah <= 0) {
                $item->delete();
                return null;
            }

            $item->update(['jumlah' => $jumlah]);
            return $item;
        }

        return null;
    }

    /**
     * Static method untuk menghitung total keranjang user
     */
    public static function getTotalCart($userId)
    {
        return self::byUser($userId)
                  ->withBarang()
                  ->get()
                  ->sum('subtotal');
    }

    /**
     * Static method untuk menghitung jumlah item dalam keranjang user
     */
    public static function getCartItemCount($userId)
    {
        return self::byUser($userId)->sum('jumlah');
    }

    /**
     * Static method untuk mengosongkan keranjang user
     */
    public static function clearCart($userId)
    {
        return self::byUser($userId)->delete();
    }
}
