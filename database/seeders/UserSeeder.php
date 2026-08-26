<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'ketua@test.com'],
            [
                'name' => 'Ketua Futsal',
                'password' => bcrypt('password123'),
                'role' => 'Ketua',     
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );
    }
}