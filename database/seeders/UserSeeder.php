<?php

namespace Database\Seeders;

use App\Models\Pembina;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ketua OSIS',
            'email' => 'ketua@test.com',
            'password' => bcrypt('password123'),
            'role' => 'Ketua',
            'email_verified_at' => now(),
        ]);

        $pembinaUser = User::create([
            'name' => 'Pembina OSIS',
            'email' => 'pembina@test.com',
            'password' => bcrypt('password123'),
            'role' => 'Pembina',
            'email_verified_at' => now(),
        ]);

        // Wajib: buat baris data pembina yang terhubung ke user di atas,
        // supaya akun ini bisa mengakses halaman Absensi/Validasi/Nilai
        // tanpa kena pesan "Akun Anda belum terhubung dengan data pembina".
        Pembina::create([
            'id_user' => $pembinaUser->id,
            'nama_pembina' => 'Pembina OSIS',
            'email' => 'pembina@test.com',
        ]);
    }
}