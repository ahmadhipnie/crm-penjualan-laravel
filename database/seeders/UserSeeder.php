<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Furniture Store',
            'email' => 'admin@furniturestore.com',
            'nomor_telepon' => '081234567890',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => 'L',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti@customer.com',
            'nomor_telepon' => '081234567891',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'P',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@customer.com',
            'nomor_telepon' => '081234567892',
            'tanggal_lahir' => '1988-12-20',
            'jenis_kelamin' => 'L',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'nama' => 'Rina Wijayanti',
            'email' => 'rina@customer.com',
            'nomor_telepon' => '081234567893',
            'tanggal_lahir' => '1992-08-10',
            'jenis_kelamin' => 'P',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
