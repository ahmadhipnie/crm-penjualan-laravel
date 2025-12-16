<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisEkspedisi;

class JenisEkspedisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ekspedisis = [
            'Ekspedisi Furniture Khusus',
            'Cargo Heavy Duty',
            'Truck Delivery Service',
            'JNE Trucking',
            'TIKI Cargo',
            'Lion Parcel Cargo',
            'Wahana Heavy Cargo',
            'RPX Furniture Delivery',
            'SAP Express Cargo',
            'Kurir Furniture Profesional',
            'Same Day Furniture Delivery',
            'Assembly & Delivery Service'
        ];

        foreach ($ekspedisis as $ekspedisi) {
            JenisEkspedisi::create([
                'nama_ekspedisi' => $ekspedisi
            ]);
        }
    }
}
