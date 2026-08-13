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
        $activeTab = $request->query('tab', 'overview');

        $pembina = Pembina::where('id_user', Auth::id())->first();
        $ekskulIds = $pembina ? $pembina->ekskuls()->pluck('id_ekskul') : collect();
        $pelatihIds = $pembina ? $pembina->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih') : collect();
        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        // Absensi peserta pada tanggal terbaru yang tercatat (pengganti "sesi terbaru")
        $tanggalTerbaru = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
            ->max('tanggal_absensi');

        $pesertaAbsensi = $tanggalTerbaru
            ? AbsensiPeserta::with('peserta.siswa.kelas')
                ->whereIn('id_anggota', $anggotaIds)
                ->whereDate('tanggal_absensi', $tanggalTerbaru)
                ->get()
            : collect();

        $hadirCount = $pesertaAbsensi->where('status_kehadiran', 'hadir')->count();

        // Antrian validasi absensi pelatih
        $validasiPelatih = AbsensiPelatih::with(['pelatih', 'validator'])
            ->whereIn('id_pelatih', $pelatihIds)
            ->orderByRaw("status_validasi = 'Menunggu' desc")
            ->latest('created_at')
            ->get();

        $pendingValidasi = $validasiPelatih->where('status_validasi', 'Menunggu')->count();

        // Daftar peserta (anggota aktif) untuk form penilaian cepat (nilai terakhir per peserta)
        $daftarNilai = Peserta::with(['siswa.kelas', 'nilai' => function ($q) {
            $q->latest('id_nilai')->limit(1);
        }])
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy('nama')
            ->values();

        // Riwayat lengkap nilai peserta untuk keperluan edit / hapus (CRUD)
        $riwayatNilai = NilaiPeserta::with('peserta.siswa')
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('id_nilai')
            ->get();

        $nilaiTerisi = NilaiPeserta::whereIn('id_anggota', $anggotaIds)
            ->whereNotNull('nilai')
            ->whereMonth('created_at', now()->month)
            ->count();

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
            'activeTab' => $activeTab,
            'tanggalTerbaru' => $tanggalTerbaru,
            'pesertaAbsensi' => $pesertaAbsensi,
            'hadirCount' => $hadirCount,
            'validasiPelatih' => $validasiPelatih,
            'pendingValidasi' => $pendingValidasi,
            'daftarNilai' => $daftarNilai,
            'riwayatNilai' => $riwayatNilai,
            'nilaiTerisi' => $nilaiTerisi,
            'trend' => $trend,
        ]);
    }
}
