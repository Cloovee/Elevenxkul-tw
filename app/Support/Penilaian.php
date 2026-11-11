<?php

namespace App\Support;

use App\Models\AbsensiPeserta;
use App\Models\NilaiPeserta;
use App\Models\Peserta;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Logika penilaian peserta ekskul: angka -> grade A/B/C, hitung kehadiran dari
 * absensi, dan gabungkan grade kehadiran + keaktifan jadi grade akhir.
 * Aturan angkanya ada di config/penilaian.php.
 */
class Penilaian
{
    // ------------------------------------------------------------------
    // Konversi angka -> grade
    // ------------------------------------------------------------------

    /** Angka (0-100) -> A/B/C. Di bawah batas terendah tetap grade terendah. */
    public static function gradeDariAngka(float|int|string $angka): string
    {
        $batas = config('penilaian.batas');

        foreach ($batas as $grade => $minimal) {
            if ((float) $angka >= $minimal) {
                return $grade;
            }
        }

        return array_key_last($batas);
    }

    /** Nilai keaktifan terendah yang boleh diinput (= batas grade terendah, saat ini 0). */
    public static function nilaiMinimum(): int
    {
        return (int) min(config('penilaian.batas'));
    }

    /**
     * Apakah peserta pernah hadir pada semester tsb? (hasil dari kehadiran()).
     *
     * Peserta yang tidak pernah hadir sama sekali (kehadiran 0%) TIDAK diberi nilai
     * maupun grade. Pakai persentase (bukan sekadar jumlah "hadir") supaya ikut aturan
     * bobot_status di config: kalau sekolah menghitung izin/sakit sebagai hadir, peserta
     * yang hanya izin tetap dianggap pernah hadir. Dengan setelan default, ini sama
     * dengan "jumlah hadir > 0".
     */
    public static function pernahHadir(array $kehadiran): bool
    {
        return ($kehadiran['persen'] ?? 0) > 0;
    }

    /** Gabungkan grade kehadiran + keaktifan menjadi grade akhir. */
    public static function gabungkan(string $gradeKehadiran, string $gradeKeaktifan): string
    {
        $poin = config('penilaian.poin');
        $bobot = config('penilaian.bobot');

        $skor = ($poin[$gradeKehadiran] * $bobot['kehadiran'] + $poin[$gradeKeaktifan] * $bobot['keaktifan'])
            / ($bobot['kehadiran'] + $bobot['keaktifan']);

        // Bulatkan ke poin terdekat; tepat di tengah -> ke atas.
        $bulat = (int) floor($skor + 0.5 + 1e-9);
        $bulat = max(min($poin), min(max($poin), $bulat));

        return (string) array_search($bulat, $poin, true);
    }

    // ------------------------------------------------------------------
    // Tahun ajaran & semester
    // ------------------------------------------------------------------

    /** Semester 1 = Juli-Desember, semester 2 = Januari-Juni. */
    public static function semesterSaatIni(?Carbon $tanggal = null): int
    {
        return ($tanggal ?? now())->month >= 7 ? 1 : 2;
    }

    public static function tahunAjaranSaatIni(?Carbon $tanggal = null): string
    {
        $t = $tanggal ?? now();
        $mulai = $t->month >= 7 ? $t->year : $t->year - 1;

        return $mulai . '/' . ($mulai + 1);
    }

    /**
     * Tahun ajaran yang datanya (anggota/peserta) saat ini tersedia = Tahun Ajaran
     * AKTIF di modul Tahun Ajaran (diganti Admin lewat "Ganti Tahun Ajaran").
     * Bila belum ada yang aktif, jatuh ke tahun ajaran berjalan menurut tanggal.
     */
    public static function tahunAjaranAnggota(): string
    {
        $aktif = \App\Models\TahunAjaran::aktif()->orderByDesc('tahun_ajaran')->value('tahun_ajaran');

        return $aktif && self::tahunAjaranValid($aktif) ? $aktif : self::tahunAjaranSaatIni();
    }

    /** True bila $tahunAjaran lebih baru dari tahun ajaran yang anggotanya sudah tersedia. */
    public static function anggotaBelumTersedia(string $tahunAjaran): bool
    {
        return (int) substr($tahunAjaran, 0, 4) > (int) substr(self::tahunAjaranAnggota(), 0, 4);
    }

    /** Format "2026/2027" (tahun kedua = tahun pertama + 1). */
    public static function tahunAjaranValid(string $tahunAjaran): bool
    {
        if (! preg_match('#^(\d{4})/(\d{4})$#', $tahunAjaran, $m)) {
            return false;
        }

        return (int) $m[2] === (int) $m[1] + 1;
    }

    /** Pilihan tahun ajaran: tahun ajaran depan sampai 3 tahun ke belakang. */
    public static function opsiTahunAjaran(): array
    {
        $sekarang = (int) explode('/', self::tahunAjaranSaatIni())[0];
        $opsi = [];

        for ($y = $sekarang + 1; $y >= $sekarang - 3; $y--) {
            $opsi[] = $y . '/' . ($y + 1);
        }

        return $opsi;
    }

    /** Rentang tanggal sebuah semester: [mulai, selesai]. */
    public static function periode(string $tahunAjaran, int|string $semester): array
    {
        $awal = (int) substr($tahunAjaran, 0, 4);

        if ((int) $semester === 1) {
            return [Carbon::create($awal, 7, 1)->startOfDay(), Carbon::create($awal, 12, 31)->endOfDay()];
        }

        return [Carbon::create($awal + 1, 1, 1)->startOfDay(), Carbon::create($awal + 1, 6, 30)->endOfDay()];
    }

    // ------------------------------------------------------------------
    // Kehadiran dari absensi
    // ------------------------------------------------------------------

    /**
     * Rekap kehadiran per peserta (id_anggota) pada rentang tanggal.
     *
     * @param  iterable<int>  $idAnggota
     * @return array<int, array{hadir:int,izin:int,sakit:int,alpha:int,total:int,persen:?float,grade:?string}>
     */
    public static function kehadiran(iterable $idAnggota, Carbon $mulai, Carbon $selesai): array
    {
        $ids = collect($idAnggota)->map(fn ($i) => (int) $i)->unique()->values();

        $hasil = [];
        foreach ($ids as $id) {
            $hasil[$id] = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0, 'total' => 0, 'persen' => null, 'grade' => null];
        }

        if ($ids->isEmpty()) {
            return $hasil;
        }

        $baris = AbsensiPeserta::query()
            ->whereIn('id_anggota', $ids)
            ->whereBetween('tanggal_absensi', [$mulai->toDateString(), $selesai->toDateString()])
            ->selectRaw('id_anggota, status_kehadiran, COUNT(*) as jumlah')
            ->groupBy('id_anggota', 'status_kehadiran')
            ->get();

        foreach ($baris as $b) {
            $status = strtolower((string) $b->status_kehadiran);
            $id = (int) $b->id_anggota;

            if (! isset($hasil[$id][$status])) {
                continue;
            }

            $hasil[$id][$status] += (int) $b->jumlah;
            $hasil[$id]['total'] += (int) $b->jumlah;
        }

        $bobotStatus = config('penilaian.bobot_status');

        foreach ($hasil as $id => $h) {
            if ($h['total'] === 0) {
                continue;
            }

            $poin = 0;
            foreach ($bobotStatus as $status => $bobot) {
                $poin += $h[$status] * $bobot;
            }

            $hasil[$id]['persen'] = round($poin / $h['total'] * 100, 2);
            $hasil[$id]['grade'] = self::gradeDariAngka($hasil[$id]['persen']);
        }

        return $hasil;
    }

    // ------------------------------------------------------------------
    // Rekap untuk export
    // ------------------------------------------------------------------

    /**
     * Satu baris per peserta aktif, berisi nilai yang SUDAH disimpan pembina
     * pada semester tsb (kosong bila belum dinilai).
     */
    public static function rekap(Collection $ekskulIds, string $tahunAjaran, int $semester): Collection
    {
        $peserta = Peserta::with(['siswa.kelas', 'ekskul'])
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy([
                fn ($a, $b) => mb_strtolower($a->ekskul->nama_ekskul ?? '') <=> mb_strtolower($b->ekskul->nama_ekskul ?? ''),
                fn ($a, $b) => mb_strtolower($a->nama) <=> mb_strtolower($b->nama),
            ])
            ->values();

        $nilai = NilaiPeserta::whereIn('id_anggota', $peserta->pluck('id_anggota'))
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', (string) $semester)
            ->latest('id_nilai')
            ->get()
            ->unique('id_anggota')
            ->keyBy('id_anggota');

        // Kehadiran semester tsb, untuk menandai peserta yang tidak pernah hadir (tanpa grade).
        [$mulai, $selesai] = self::periode($tahunAjaran, $semester);
        $kehadiran = self::kehadiran($peserta->pluck('id_anggota'), $mulai, $selesai);

        return $peserta->map(function (Peserta $p) use ($nilai, $kehadiran) {
            $n = $nilai->get($p->id_anggota);
            $k = $kehadiran[$p->id_anggota] ?? [];
            $pernahHadir = self::pernahHadir($k);
            $tanpaHadir = ! $pernahHadir && ($k['total'] ?? 0) > 0; // ada absensi, tapi tidak pernah hadir
            $dinilai = $pernahHadir && $n !== null && $n->grade_akhir !== null;

            // Hanya baris yang benar-benar dinilai yang menampilkan grade/nilai tersimpan.
            // Peserta yang tidak pernah hadir selalu tanpa grade (walau ada data lama).
            $nilaiTampil = $dinilai ? $n : null;

            return [
                'ekskul'          => $p->ekskul->nama_ekskul ?? '-',
                'nis'             => (string) ($p->siswa->NIS ?? ''),
                'nama'            => $p->nama,
                'kelas'           => $p->kelas ?? '',
                // Tanpa grade tetap menampilkan data kehadirannya sebagai informasi.
                'hadir'           => $nilaiTampil?->jumlah_hadir ?? ($tanpaHadir ? $k['hadir'] : null),
                'pertemuan'       => $nilaiTampil?->jumlah_pertemuan ?? ($tanpaHadir ? $k['total'] : null),
                'persen'          => $nilaiTampil?->persen_kehadiran !== null
                    ? (float) $nilaiTampil->persen_kehadiran
                    : ($tanpaHadir ? (float) $k['persen'] : null),
                'grade_kehadiran' => $nilaiTampil?->grade_kehadiran,
                'nilai_keaktifan' => $nilaiTampil?->nilai_keaktifan !== null ? (float) $nilaiTampil->nilai_keaktifan : null,
                'grade_keaktifan' => $nilaiTampil?->grade_keaktifan,
                'grade_akhir'     => $nilaiTampil?->grade_akhir,
                'catatan'         => $nilaiTampil?->catatan_pembina ?? '',
                'dinilai'         => $dinilai,
                // 'dinilai' | 'belum' (belum dinilai / belum ada absensi) | 'tanpa_hadir' (tidak pernah hadir)
                'status'          => $tanpaHadir ? 'tanpa_hadir' : ($dinilai ? 'dinilai' : 'belum'),
            ];
        });
    }
}