<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create akun user admin
        \App\Models\User::create([
            'name' => 'Admin Toserba Hasan',
            'email' => 'admin@toserbahasan.test',
            'password' => 'Admin@12345',
            'role' => 0,
        ]);

        //create akun user kasir
        \App\Models\User::create([
            'name' => 'Kasir Toserba Hasan',
            'email' => 'kasir@toserbahasan.test',
            'password' => 'Kasir@12345',
            'role' => 1,
        ]);
    }
}
