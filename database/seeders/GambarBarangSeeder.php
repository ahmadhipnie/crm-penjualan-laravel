<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GambarBarang;
use App\Models\Barang;

class GambarBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            // Primary image
            GambarBarang::create([
                'id_barang' => $barang->id,
                'gambar_url' => 'gambar_barang/' . $barang->sku_barang . '_main.jpg',
                'is_primary' => true
            ]);

            // Secondary images - Detail view
            GambarBarang::create([
                'id_barang' => $barang->id,
                'gambar_url' => 'gambar_barang/' . $barang->sku_barang . '_detail.jpg',
                'is_primary' => false
            ]);

            // Secondary images - Side angle
            GambarBarang::create([
                'id_barang' => $barang->id,
                'gambar_url' => 'gambar_barang/' . $barang->sku_barang . '_angle.jpg',
                'is_primary' => false
            ]);

            // Secondary images - In room setting
            GambarBarang::create([
                'id_barang' => $barang->id,
                'gambar_url' => 'gambar_barang/' . $barang->sku_barang . '_room.jpg',
                'is_primary' => false
            ]);
        }
    }
}
