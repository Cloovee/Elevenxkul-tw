<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RiwayatAbsensiController extends Controller
{
    /**
     * GET /riwayat-absensi?bulan=YYYY-MM
     *
     * Beda dengan "Aktivitas Terbaru" di dashboard (yang cuma nunjukin
     * beberapa hal TERAKHIR yang ketua lakukan), halaman ini nunjukin
     * riwayat absensi peserta & pelatih -- dua tab terpisah.
     *
     * Tab peserta berformat: NIS | Nama | Tanggal | Waktu | Keterangan,
     * dikelompokkan per pertemuan (setiap kali ekskul dilaksanakan) dan bisa
     * difilter per bulan. Bulan yang dipilih juga dipakai tombol "Export PDF".
     */
    public function index(Request $request): View
    {
        // Ekskul yang dipimpin ketua yang login (diset middleware 'ketua.ekskul').
        $idEkskul = $request->attributes->get('ekskul_ketua')->id_ekskul;

        [$bulan, $awal, $akhir] = $this->periode($request);

        $riwayatPeserta = $this->absensiPeserta($idEkskul, $awal, $akhir);

        // Dikelompokkan per tanggal (pertemuan), pertemuan terbaru di atas.
        $pertemuan = $riwayatPeserta
            ->groupBy(fn ($a) => $a->tanggal_absensi->toDateString())
            ->sortKeysDesc()
            ->map(fn (Collection $baris, string $tanggal) => [
                'tanggal'  => Carbon::parse($tanggal),
                'kegiatan' => $baris->pluck('deskripsi_kegiatan')->filter()->first(),
                'baris'    => $baris->sortBy(fn ($a) => mb_strtolower($a->peserta->nama ?? ''))->values(),
                'hadir'    => $baris->where('status_kehadiran', 'hadir')->count(),
                'izin'     => $baris->where('status_kehadiran', 'izin')->count(),
                'sakit'    => $baris->where('status_kehadiran', 'sakit')->count(),
                'alpha'    => $baris->where('status_kehadiran', 'alpha')->count(),
            ]);

        // Pilihan bulan: semua bulan yang punya absensi + bulan yang sedang dipilih.
        $daftarBulan = AbsensiPeserta::query()
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->select('tanggal_absensi')
            ->distinct()
            ->get()
            ->toBase() // Collection biasa: map() pada Eloquent Collection kosong tetap Eloquent -> unique() error
            ->map(fn ($a) => $a->tanggal_absensi->format('Y-m'))
            ->push($bulan)
            ->unique()
            ->sortDesc()
            ->mapWithKeys(fn ($b) => [$b => $this->labelBulan($b)]);

        // Satu ekskul dilatih oleh satu pelatih (lihat relasi Ekskul::pelatih()).
        $idPelatih = Ekskul::where('id_ekskul', $idEkskul)->value('id_pelatih');

        $riwayatPelatih = AbsensiPelatih::with('pelatih')
            ->where('id_pelatih', $idPelatih)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        return view('dashboard-ketua.riwayat-absensi', [
            'riwayatPeserta' => $riwayatPeserta,
            'pertemuan'      => $pertemuan,
            'riwayatPelatih' => $riwayatPelatih,
            'bulan'          => $bulan,
            'labelBulan'     => $this->labelBulan($bulan),
            'daftarBulan'    => $daftarBulan,
        ]);
    }

    /**
     * GET /riwayat-absensi/export-pdf?bulan=YYYY-MM
     *
     * PDF rekap absensi ekskul satu bulan: kop surat -> identitas ekskul ->
     * tabel kehadiran peserta (NIS, Nama, Tanggal, Waktu, Keterangan) ->
     * tabel kehadiran pelatih -> kolom tanda tangan pelatih.
     * Tambahkan ?preview=1 untuk menampilkan PDF di browser tanpa mengunduh.
     */
    public function exportPdf(Request $request)
    {
        $ekskul = $request->attributes->get('ekskul_ketua');
        $ekskul->loadMissing('pelatih');

        [$bulan, $awal, $akhir] = $this->periode($request);

        $absensi = $this->absensiPeserta($ekskul->id_ekskul, $awal, $akhir)
            ->sortBy([
                fn ($a, $b) => $a->tanggal_absensi <=> $b->tanggal_absensi,
                fn ($a, $b) => mb_strtolower($a->peserta->nama ?? '') <=> mb_strtolower($b->peserta->nama ?? ''),
            ])
            ->values();

        $barisPeserta = $absensi->map(fn ($a) => [
            'nis'        => $a->peserta->siswa->NIS ?? '-',
            'nama'       => $a->peserta->nama,
            'tanggal'    => $a->tanggal_absensi->format('d/m/Y'),
            'waktu'      => $a->updated_at ? $a->updated_at->format('H:i') : '-',
            'keterangan' => $this->keterangan($a),
        ]);

        // Absensi pelatih pada bulan yang sama (laporan yang ditolak pembina tidak dihitung hadir).
        $absensiPelatih = $ekskul->id_pelatih
            ? AbsensiPelatih::where('id_pelatih', $ekskul->id_pelatih)
                ->whereBetween('tanggal_absensi', [$awal->toDateString(), $akhir->toDateString()])
                ->get()
                ->keyBy(fn ($a) => $a->tanggal_absensi->toDateString())
            : collect();

        // Kolom tanggal tabel pelatih = semua tanggal pertemuan (peserta) + tanggal absensi pelatih.
        $tanggalKegiatan = $absensi->toBase()->map(fn ($a) => $a->tanggal_absensi->toDateString())->unique()->sort()->values();
        $kolomTanggal = $tanggalKegiatan
            ->merge($absensiPelatih->keys())
            ->unique()
            ->sort()
            ->values()
            ->map(fn ($t) => Carbon::parse($t));

        $kodeKehadiran = ['hadir' => 'H', 'izin' => 'I', 'sakit' => 'S', 'alpha' => 'A'];
        $kehadiranPelatih = $kolomTanggal->map(function (Carbon $t) use ($absensiPelatih, $kodeKehadiran) {
            $a = $absensiPelatih->get($t->toDateString());

            if (! $a || $a->status_validasi === 'Ditolak') {
                return '–';
            }

            return $kodeKehadiran[strtolower($a->status_kehadiran)] ?? '–';
        });

        $jumlahAnggotaAktif = Peserta::where('id_ekskul', $ekskul->id_ekskul)
            ->where('status', 'aktif')
            ->count();

        $pdf = Pdf::loadView('pdf.rekap-absensi', [
            'ekskul'             => $ekskul,
            'namaPelatih'        => $ekskul->pelatih->nama_pelatih ?? null,
            'labelBulan'         => Str::upper($this->labelBulan($bulan)),
            'hariTanggal'        => $this->hariTanggal($tanggalKegiatan, $awal),
            'jumlahAnggotaAktif' => $jumlahAnggotaAktif,
            'barisPeserta'       => $barisPeserta,
            'kolomTanggal'       => $kolomTanggal,
            'kehadiranPelatih'   => $kehadiranPelatih,
            'tanggalCetak'       => now()->locale('id')->translatedFormat('d F Y'),
            'logoJabar'          => $this->gambarBase64(public_path('images/logo-jabar.png')),
        ])->setPaper('a4', 'portrait');

        $namaFile = 'rekap-absensi-' . Str::slug($ekskul->nama_ekskul) . '-' . $bulan . '.pdf';

        // ?preview=1 -> tampil di browser/iframe (inline), selain itu -> unduh file.
        return $request->boolean('preview')
            ? $pdf->stream($namaFile)
            : $pdf->download($namaFile);
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /**
     * Baca ?bulan=YYYY-MM (default: bulan ini).
     * Mengembalikan [string Y-m, awal bulan, akhir bulan].
     */
    private function periode(Request $request): array
    {
        $request->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);

        $bulan = $request->query('bulan') ?: now()->format('Y-m');
        $awal = Carbon::createFromFormat('Y-m-d', $bulan . '-01')->startOfMonth();

        return [$bulan, $awal, $awal->copy()->endOfMonth()];
    }

    /**
     * Seluruh absensi peserta ekskul ini pada rentang tanggal tertentu.
     */
    private function absensiPeserta(int $idEkskul, Carbon $awal, Carbon $akhir): Collection
    {
        return AbsensiPeserta::with('peserta.siswa')
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->whereBetween('tanggal_absensi', [$awal->toDateString(), $akhir->toDateString()])
            ->orderBy('tanggal_absensi')
            ->orderBy('id_absensi')
            ->get();
    }

    /**
     * Kolom "Keterangan": status kehadiran (+ catatan kalau ada),
     * mis. "Izin - ada acara keluarga".
     */
    private function keterangan(AbsensiPeserta $a): string
    {
        $status = ucfirst((string) $a->status_kehadiran);

        return $a->catatan ? "{$status} - {$a->catatan}" : $status;
    }

    /**
     * Teks "Hari/Tanggal Kegiatan", mis. "Rabu, Jumat (9, 14, 22 Oktober 2026)".
     */
    private function hariTanggal(Collection $tanggalKegiatan, Carbon $awal): string
    {
        if ($tanggalKegiatan->isEmpty()) {
            return '-';
        }

        $tanggal = $tanggalKegiatan->map(fn ($t) => Carbon::parse($t)->locale('id'));

        $hari = $tanggal->map(fn (Carbon $t) => $t->translatedFormat('l'))->unique()->implode(', ');
        $hariBulan = $tanggal->map(fn (Carbon $t) => (int) $t->format('j'))->implode(', ');

        return "{$hari} ({$hariBulan} " . $awal->copy()->locale('id')->translatedFormat('F Y') . ')';
    }

    private function labelBulan(string $bulan): string
    {
        return Carbon::createFromFormat('Y-m-d', $bulan . '-01')->locale('id')->translatedFormat('F Y');
    }

    /**
     * Gambar -> data URI supaya DomPDF tidak perlu akses file/URL eksternal.
     */
    private function gambarBase64(string $path): ?string
    {
        if (! is_file($path)) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}