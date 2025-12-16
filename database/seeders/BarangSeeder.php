<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = Kategori::all();

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Sofa & Kursi')->first()->id,
            'sku_barang' => 'SFA001',
            'nama_barang' => 'Sofa Minimalis 3 Dudukan',
            'deskripsi' => 'Sofa minimalis dengan desain modern, nyaman untuk ruang tamu dengan busa empuk berkualitas tinggi',
            'material' => 'Kayu Jati, Busa, Kain Oscar',
            'harga' => 3500000,
            'berat' => 45000,
            'stok' => 15
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Meja & Kursi Makan')->first()->id,
            'sku_barang' => 'MKM001',
            'nama_barang' => 'Set Meja Makan 6 Kursi Jati',
            'deskripsi' => 'Set meja makan kayu jati solid dengan 6 kursi, finishing natural yang elegan dan tahan lama',
            'material' => 'Kayu Jati Solid, Finishing Natural',
            'harga' => 8500000,
            'berat' => 85000,
            'stok' => 8
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Tempat Tidur')->first()->id,
            'sku_barang' => 'TTR001',
            'nama_barang' => 'Tempat Tidur Minimalis Queen Size',
            'deskripsi' => 'Tempat tidur kayu mahoni dengan desain minimalis modern, dilengkapi headboard yang stylish',
            'material' => 'Kayu Mahoni, Finishing Duco',
            'harga' => 4200000,
            'berat' => 65000,
            'stok' => 12
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Lemari & Rak')->first()->id,
            'sku_barang' => 'LMR001',
            'nama_barang' => 'Lemari Pakaian 4 Pintu',
            'deskripsi' => 'Lemari pakaian 4 pintu dengan cermin, banyak ruang penyimpanan dan gantungan baju',
            'material' => 'Kayu MDF, Laminasi HPL',
            'harga' => 3800000,
            'berat' => 55000,
            'stok' => 10
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Meja Kerja')->first()->id,
            'sku_barang' => 'MKJ001',
            'nama_barang' => 'Meja Kerja L-Shape dengan Laci',
            'deskripsi' => 'Meja kerja bentuk L dengan 3 laci, cocok untuk home office atau kantor dengan space maksimal',
            'material' => 'Kayu MDF, Laminasi Woodgrain',
            'harga' => 2800000,
            'berat' => 35000,
            'stok' => 20
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Kitchen Set')->first()->id,
            'sku_barang' => 'KTS001',
            'nama_barang' => 'Kitchen Set Minimalis 3 Meter',
            'deskripsi' => 'Kitchen set minimalis dengan top table granit, banyak storage dan desain modern',
            'material' => 'Kayu MDF, HPL, Granit',
            'harga' => 12500000,
            'berat' => 120000,
            'stok' => 5
        ]);

        Barang::create([
            'id_kategori' => $kategoris->where('nama_kategori', 'Dekorasi Rumah')->first()->id,
            'sku_barang' => 'DKR001',
            'nama_barang' => 'Rak Dinding Floating Shelf Set 3',
            'deskripsi' => 'Set 3 rak dinding minimalis untuk dekorasi dan penyimpanan, mudah dipasang tanpa bracket terlihat',
            'material' => 'Kayu MDF, Laminasi White',
            'harga' => 450000,
            'berat' => 3000,
            'stok' => 50
        ]);
    }
}
