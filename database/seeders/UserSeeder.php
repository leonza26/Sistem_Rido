<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // firstOrCreate: akun yang sudah ada tidak diubah,
        // sehingga menjalankan seeder ulang tidak me-reset password yang sudah diganti.

        // Akun Pemilik (role = 2) - akses semua menu
        User::firstOrCreate(
            ['email' => 'pemilik@toserbahasan.test'],
            [
                'name' => 'Pemilik Toserba Hasan',
                'password' => Hash::make('Pemilik@12345'),
                'role' => User::ROLE_PEMILIK,
            ]
        );

        // Akun Admin (role = 0) - menu Produk dan Pengguna
        User::firstOrCreate(
            ['email' => 'admin@toserbahasan.test'],
            [
                'name' => 'Admin Toserba Hasan',
                'password' => Hash::make('Admin@12345'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Akun Kasir (role = 1)
        User::firstOrCreate(
            ['email' => 'kasir@toserbahasan.test'],
            [
                'name' => 'Kasir Toserba Hasan',
                'password' => Hash::make('Kasir@12345'),
                'role' => User::ROLE_KASIR,
            ]
        );
    }
}
