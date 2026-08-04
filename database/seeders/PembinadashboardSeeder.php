<?php

namespace Database\Seeders;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\NilaiPeserta;
use App\Models\Pelatih;
use App\Models\Peserta;
use App\Models\Sesi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PembinaDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $pembina = User::firstOrCreate(
            ['email' => 'pembina@elevenxkul.test'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => 'pembina',
            ]
        );

        $pelatihData = [
            ['nama' => 'Andi Saputra', 'spesialisasi' => 'Teknik Dasar'],
            ['nama' => 'Rina Kartika', 'spesialisasi' => 'Fisik'],
            ['nama' => 'Yoga Firmansyah', 'spesialisasi' => 'Evaluasi Mingguan'],
        ];

        $pelatihs = collect($pelatihData)->map(fn ($p) => Pelatih::create($p));

        $pesertaData = [
            ['nama' => 'Raka Pratama', 'nis' => '2026001', 'kelas' => 'X-A'],
            ['nama' => 'Dinda Ayu', 'nis' => '2026002', 'kelas' => 'X-A'],
            ['nama' => 'Bagas Wirawan', 'nis' => '2026003', 'kelas' => 'X-B'],
            ['nama' => 'Salsa Amelia', 'nis' => '2026004', 'kelas' => 'X-B'],
            ['nama' => 'Fauzi Akmal', 'nis' => '2026005', 'kelas' => 'X-A'],
            ['nama' => 'Nadia Putri', 'nis' => '2026006', 'kelas' => 'X-B'],
        ];

        $pesertas = collect($pesertaData)->map(fn ($p) => Peserta::create($p));

        $sesiIds = [];
        foreach (range(5, 0) as $i) {
            $tanggal = Carbon::now()->subMonths($i)->startOfMonth()->addDays(4);
            $sesi = Sesi::create([
                'pelatih_id' => $pelatihs->random()->id,
                'nama_sesi' => 'Latihan Fisik - ' . $tanggal->translatedFormat('F Y'),
                'tanggal' => $tanggal,
                'jam_mulai' => '07:00',
                'jam_selesai' => '09:00',
            ]);
            $sesiIds[] = $sesi;

            $statusPool = ['Hadir', 'Hadir', 'Hadir', 'Terlambat', 'Izin', 'Tidak Hadir'];
            foreach ($pesertas as $index => $peserta) {
                $status = $statusPool[$index % count($statusPool)];
                AbsensiPeserta::create([
                    'peserta_id' => $peserta->id,
                    'sesi_id' => $sesi->id,
                    'jam_hadir' => $status === 'Hadir' || $status === 'Terlambat' ? '07:0' . rand(0, 9) : null,
                    'status' => $status,
                ]);
            }
        }

        $sesiTerakhir = end($sesiIds);
        $sesiSekarang = Sesi::create([
            'pelatih_id' => $pelatihs->first()->id,
            'nama_sesi' => 'Latihan Teknik Dasar',
            'tanggal' => Carbon::today(),
            'jam_mulai' => '06:55',
            'jam_selesai' => '08:30',
        ]);

        foreach ($pelatihs as $index => $pelatih) {
            AbsensiPelatih::create([
                'pelatih_id' => $pelatih->id,
                'sesi_id' => $index === 0 ? $sesiSekarang->id : $sesiTerakhir->id,
                'jam_lapor' => '06:5' . rand(0, 9),
                'status' => 'Menunggu',
            ]);
        }

        $kategori = ['Teknik', 'Disiplin', 'Kerja Sama'];
        foreach ($pesertas->take(3) as $index => $peserta) {
            NilaiPeserta::create([
                'peserta_id' => $peserta->id,
                'sesi_id' => $sesiTerakhir->id,
                'kategori' => $kategori[$index % count($kategori)],
                'nilai' => rand(70, 95),
                'diberikan_oleh' => $pembina->id,
            ]);
        }
    }
}