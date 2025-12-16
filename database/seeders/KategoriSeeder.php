<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Sofa & Kursi',
            'Meja & Kursi Makan',
            'Lemari & Rak',
            'Tempat Tidur',
            'Meja Kerja',
            'Dekorasi Rumah',
            'Furniture Outdoor',
            'Furniture Anak',
            'Kitchen Set',
            'Furniture Kamar Mandi'
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create([
                'nama_kategori' => $kategori
            ]);
        }
    }
}
