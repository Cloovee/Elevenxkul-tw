<?php

namespace Database\Seeders;

use App\Models\Pembina;
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
                'name' => 'Ketua OSIS',
                'password' => bcrypt('password123'),
                'role' => 'Ketua',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        $pembinaUser = User::firstOrCreate(
            ['email' => 'pembina@test.com'],
            [
                 'name' => 'Pembina OSIS',
                'password' => bcrypt('password123'),
                'role' => 'Pembina',
                'email_verified_at' => now(),
            ]
        );

        // Wajib: buat baris data pembina yang terhubung ke user di atas,
        // supaya akun ini bisa mengakses halaman Absensi/Validasi/Nilai
        // tanpa kena pesan "Akun Anda belum terhubung dengan data pembina".
        Pembina::firstOrCreate(
            ['id_user' => $pembinaUser->id],
            [
                'nama_pembina' => 'Pembina OSIS',
                'email' => 'pembina@test.com',
            ]
        );
    }
}