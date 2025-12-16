<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa user dan barang yang sudah ada
        $users = User::where('role', 'customer')->take(5)->get();
        $barangs = Barang::take(10)->get();

        if ($users->count() > 0 && $barangs->count() > 0) {
            foreach ($users as $user) {
                // Setiap user mendapat 2-4 item random di keranjang
                $randomBarangs = $barangs->random(rand(2, 4));

                foreach ($randomBarangs as $barang) {
                    Keranjang::create([
                        'id_user' => $user->id,
                        'id_barang' => $barang->id,
                        'jumlah' => rand(1, 3),
                    ]);
                }
            }
        }

    }
}
