<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\NilaiPeserta;
use App\Models\Pembina;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PembinaDashboardController extends Controller
{
    /**
     * Tampilkan dashboard pembina: overview, absensi peserta,
     * validasi absensi pelatih, dan form nilai peserta.
     *
     * Data dibatasi hanya untuk ekskul yang dibina oleh pembina yang login.
     */
    public function index(Request $request)
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();
        $ekskulIds = $pembina ? $pembina->ekskuls()->pluck('id_ekskul') : collect();
        $pelatihIds = $pembina ? $pembina->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih') : collect();
        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        $pelatihCount = $pelatihIds->unique()->count();

        $pendingValidasi = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Menunggu')
            ->count();

        // ===== Riwayat aktivitas terbaru (gabungan nilai + laporan absensi pelatih) =====
        $riwayatNilai = NilaiPeserta::with('peserta.siswa', 'peserta.ekskul')
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('id_nilai')
            ->limit(5)
            ->get()
            ->map(function ($n) {
                return [
                    'nama' => $n->peserta->nama ?? '-',
                    'pesan' => 'Anda baru saja memberikan nilai '.($n->nilai ?? '-').' untuk ekstrakulikuler '.($n->peserta->ekskul->nama_ekskul ?? '-').'.',
                    'waktu' => $n->created_at,
                ];
            });

        $riwayatAbsensiPelatih = AbsensiPelatih::with('pelatih')
            ->whereIn('id_pelatih', $pelatihIds)
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($a) {
                $pesan = 'Baru saja mengisi absensi';
                $pesan .= $a->foto_kehadiran ? ' dan melampirkan foto absensi.' : ' untuk kegiatan '.($a->kegiatan ?? 'latihan').'.';

                return [
                    'nama' => $a->pelatih->nama_pelatih ?? '-',
                    'pesan' => $pesan,
                    'waktu' => $a->created_at,
                ];
            });

        $riwayatAktivitas = $riwayatNilai->concat($riwayatAbsensiPelatih)
            ->sortByDesc('waktu')
            ->take(5)
            ->values();

        // Tren kehadiran peserta 6 bulan terakhir (persentase hadir per bulan)
        $trend = collect(range(5, 0))->map(function ($i) use ($anggotaIds) {
            $bulan = Carbon::now()->subMonths($i);

            $total = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
                ->whereMonth('tanggal_absensi', $bulan->month)
                ->whereYear('tanggal_absensi', $bulan->year)
                ->count();

            $hadir = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
                ->whereMonth('tanggal_absensi', $bulan->month)
                ->whereYear('tanggal_absensi', $bulan->year)
                ->where('status_kehadiran', 'hadir')
                ->count();

            return [
                'bulan' => $bulan->translatedFormat('M'),
                'persentase' => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        });

        return view('pembina.dashboard', [
            'pembina' => $pembina,
            'pelatihCount' => $pelatihCount,
            'pendingValidasi' => $pendingValidasi,
            'riwayatAktivitas' => $riwayatAktivitas,
            'trend' => $trend,
        ]);
    }
}
