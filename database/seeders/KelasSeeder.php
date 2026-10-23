<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    private const DATA = [
        '10' => [
            'PPLG' => 2,
            'MPLB' => 5,
            'AKL'  => 4,
            'TJKT' => 1,
            'PM'   => 3,
            'DKV'  => 2,
        ],
        '11' => [
            'RPL'  => 2,
            'MP'   => 3,
            'MLOG' => 1,
            'AK'   => 4,
            'TKJ'  => 1,
            'BR'   => 3,
            'DKV'  => 2,
        ],
    ];

    public function run(): void
    {
        // Tingkat 12 sama persis dengan tingkat 11.
        $data = self::DATA + ['12' => self::DATA['11']];

        foreach ($data as $tingkat => $programs) {
            foreach ($programs as $program => $jumlahRombel) {
                for ($rombel = 1; $rombel <= $jumlahRombel; $rombel++) {
                    Kelas::firstOrCreate([
                        'tingkat'          => (string) $tingkat,
                        'program_keahlian' => $program,
                        'rombel'           => (string) $rombel,
                    ]);
                }
            }
        }
    }
}