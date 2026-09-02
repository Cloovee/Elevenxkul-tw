<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $dataSiswa = [

            [
                'NISN' => '0012345678',
                'NIS' => '2026001',
                'nama_siswa' => 'Raka Pratama',
                'jk' => 'L',
                'agama' => 'Islam',
                'nomor_hp' => '081234567890',
                'email' => 'raka@example.com',
                'alamat' => 'Bandung',
            ],

            [
                'NISN' => '0012345679',
                'NIS' => '2026002',
                'nama_siswa' => 'Dinda Ayu',
                'jk' => 'P',
                'agama' => 'Islam',
                'nomor_hp' => '081234567891',
                'email' => 'dinda@example.com',
                'alamat' => 'Bandung',
            ],

            [
                'NISN' => '0012345680',
                'NIS' => '2026003',
                'nama_siswa' => 'Bagas Wirawan',
                'jk' => 'L',
                'agama' => 'Islam',
                'nomor_hp' => '081234567892',
                'email' => 'bagas@example.com',
                'alamat' => 'Bandung',
            ],

            [
                'NISN' => '0012345681',
                'NIS' => '2026004',
                'nama_siswa' => 'Sarah Putri',
                'jk' => 'P',
                'agama' => 'Islam',
                'nomor_hp' => '081234567893',
                'email' => 'sarah@example.com',
                'alamat' => 'Bandung',
            ],

            [
                'NISN' => '0012345682',
                'NIS' => '2026005',
                'nama_siswa' => 'Andi Saputra',
                'jk' => 'L',
                'agama' => 'Islam',
                'nomor_hp' => '081234567894',
                'email' => 'andi@example.com',
                'alamat' => 'Bandung',
            ],

        ];

        foreach ($dataSiswa as $siswa) {

            Siswa::firstOrCreate(
                ['NIS' => $siswa['NIS']],
                $siswa
            );

        }
    }
}