<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\NilaiPeserta;
use App\Models\Peserta;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PembinaDashboardController extends Controller
{
    /**
     * Tampilkan dashboard pembina: overview, absensi peserta,
     * validasi absensi pelatih, dan form nilai peserta.
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'overview');

        // Sesi terbaru (dipakai sebagai konteks tab "Absensi Peserta")
        $sesiTerbaru = Sesi::latest('tanggal')->first();

        $pesertaAbsensi = $sesiTerbaru
            ? AbsensiPeserta::with('peserta')
                ->where('sesi_id', $sesiTerbaru->id)
                ->get()
            : collect();

        $hadirCount = $pesertaAbsensi->where('status', 'Hadir')->count();

        // Antrian validasi absensi pelatih
        $validasiPelatih = AbsensiPelatih::with(['pelatih', 'sesi'])
            ->orderByRaw("status = 'Menunggu' desc")
            ->latest('created_at')
            ->get();

        $pendingValidasi = $validasiPelatih->where('status', 'Menunggu')->count();

        // Daftar peserta untuk form penilaian cepat (nilai terakhir per peserta)
        $daftarNilai = Peserta::with(['nilai' => function ($q) {
            $q->latest()->limit(1);
        }])->get();

        // Riwayat lengkap nilai peserta untuk keperluan edit / hapus (CRUD)
        $riwayatNilai = NilaiPeserta::with(['peserta', 'sesi'])
            ->latest()
            ->get();

        $nilaiTerisi = NilaiPeserta::whereNotNull('nilai')
            ->whereMonth('created_at', now()->month)
            ->count();

        // Tren kehadiran peserta 6 bulan terakhir (persentase hadir per bulan)
        $trend = collect(range(5, 0))->map(function ($i) {
            $bulan = Carbon::now()->subMonths($i);

            $total = AbsensiPeserta::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();

            $hadir = AbsensiPeserta::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->where('status', 'Hadir')
                ->count();

            return [
                'bulan' => $bulan->translatedFormat('M'),
                'persentase' => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        });

        return view('pembina.dashboard', [
            'activeTab' => $activeTab,
            'sesiTerbaru' => $sesiTerbaru,
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