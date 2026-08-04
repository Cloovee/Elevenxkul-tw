<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\NilaiPeserta;
use App\Models\Peserta;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

        // Daftar peserta untuk form penilaian (nilai kategori "Teknik" bulan berjalan)
        $daftarNilai = Peserta::with(['nilai' => function ($q) {
            $q->latest()->limit(1);
        }])->get();

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
            'nilaiTerisi' => $nilaiTerisi,
            'trend' => $trend,
        ]);
    }

    /**
     * Use case: memvalidasi absensi pelatih -> setujui.
     */
    public function setujuiValidasi(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->update([
            'status' => 'Divalidasi',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Absensi pelatih berhasil divalidasi.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> tolak.
     */
    public function tolakValidasi(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->update([
            'status' => 'Ditolak',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Absensi pelatih ditolak.');
    }

    /**
     * Use case: memberi nilai peserta.
     */
    public function simpanNilai(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'sesi_id' => ['nullable', 'exists:sesis,id'],
        ]);

        NilaiPeserta::create([
            'peserta_id' => $peserta->id,
            'sesi_id' => $validated['sesi_id'] ?? null,
            'kategori' => $validated['kategori'],
            'nilai' => $validated['nilai'],
            'diberikan_oleh' => Auth::id(),
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'nilai'])
            ->with('success', 'Nilai peserta berhasil disimpan.');
    }
}