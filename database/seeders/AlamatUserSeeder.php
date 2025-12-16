<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlamatUser;
use App\Models\User;

class AlamatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        AlamatUser::create([
            'alamat' => 'Jl. Furniture Center No. 123, Ruko Blok A-5',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'kecamatan' => 'Kebayoran Baru',
            'kode_pos' => 12180,
            'id_user' => $users->first()->id,
        ]);

        AlamatUser::create([
            'alamat' => 'Jl. Cihampelas No. 456, Perumahan Green Valley Blok B-12',
            'provinsi' => 'Jawa Barat',
            'kabupaten' => 'Bandung',
            'kecamatan' => 'Cidadap',
            'kode_pos' => 40142,
            'id_user' => $users->skip(1)->first()->id,
        ]);

        AlamatUser::create([
            'alamat' => 'Jl. Kaliurang Km 5, Komplek Villa Sejahtera No. 25',
            'provinsi' => 'DI Yogyakarta',
            'kabupaten' => 'Sleman',
            'kecamatan' => 'Ngaglik',
            'kode_pos' => 55581,
            'id_user' => $users->skip(2)->first()->id,
        ]);

        AlamatUser::create([
            'alamat' => 'Jl. Ahmad Yani No. 88, Apartemen Skyview Tower 2 Unit 1205',
            'provinsi' => 'Jawa Timur',
            'kabupaten' => 'Surabaya',
            'kecamatan' => 'Wonokromo',
            'kode_pos' => 60243,
            'id_user' => $users->skip(3)->first()->id,
        ]);
    }
}
