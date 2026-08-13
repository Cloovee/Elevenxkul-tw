<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ketua Futsal',
            'email' => 'ketua@test.com',
            'password' => bcrypt('password123'),
            'role' => 'ketua',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pembina Futsal',
            'email' => 'pembina@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pembina',
            'email_verified_at' => now(),
        ]);
    }
}