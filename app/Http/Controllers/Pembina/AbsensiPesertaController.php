<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;

use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use App\Models\Pembina;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AbsensiPesertaController extends Controller
{
    private function pembinaAktif(): ?Pembina
    {
        return Pembina::where('id_user', Auth::id())->first();
    }

    /**
     * Redirect ramah (bukan 404) ketika akun yang login belum
     * terhubung ke data pembina manapun.
     */
    private function belumTerhubung(): RedirectResponse
    {
        return redirect()->route('pembina.dashboard')
            ->with('error', 'Akun Anda belum terhubung dengan data pembina. Hubungi admin untuk menghubungkan akun Anda.');
    }

    /**
     * Use case: melihat absensi peserta -> halaman daftar absensi (terpisah dari dashboard).
     *
     * Ditampilkan sebagai kartu per ekskul (nama ekskul, jumlah hadir/total peserta,
     * tanggal submit absensi terbaru). Setiap kartu bisa dibuka (dropdown) untuk
     * melihat riwayat semua tanggal absensi yang pernah disubmit Ketua untuk
     * ekskul tersebut, dan tiap tanggal bisa dibuka lagi untuk melihat detail
     * kehadiran per peserta.
     */
    public function index()
    {
        $pembina = $this->pembinaAktif();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $ekskuls = Ekskul::where('id_pembina', $pembina->id_pembina)->get();
        $ekskulIds = $ekskuls->pluck('id_ekskul');

        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        $totalAnggotaPerEkskul = Peserta::whereIn('id_ekskul', $ekskulIds)
            ->selectRaw('id_ekskul, count(*) as total')
            ->groupBy('id_ekskul')
            ->pluck('total', 'id_ekskul');

        $semuaAbsensi = AbsensiPeserta::with('peserta.siswa.kelas', 'peserta.ekskul')
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('tanggal_absensi')
            ->get();

        // Kelompokkan riwayat absensi per ekskul, lalu per tanggal submit,
        // supaya siap ditampilkan sebagai kartu + dropdown riwayat.
        $riwayatPerEkskul = $ekskuls->map(function ($ekskul) use ($semuaAbsensi, $totalAnggotaPerEkskul) {
            $milikEkskulIni = $semuaAbsensi->filter(
                fn ($a) => optional($a->peserta)->id_ekskul === $ekskul->id_ekskul
            );

            $sesi = $milikEkskulIni
                ->groupBy(fn ($a) => optional($a->tanggal_absensi)->format('Y-m-d'))
                ->map(function ($records, $tanggal) {
                    return [
                        'tanggal' => $records->first()->tanggal_absensi,
                        'hadir' => $records->where('status_kehadiran', 'hadir')->count(),
                        'total' => $records->count(),
                        'records' => $records->sortBy(fn ($r) => $r->peserta->nama ?? '')->values(),
                    ];
                })
                ->sortByDesc('tanggal')
                ->values();

            return [
                'ekskul' => $ekskul,
                'total_anggota' => $totalAnggotaPerEkskul[$ekskul->id_ekskul] ?? 0,
                'total_sesi' => $sesi->count(),
                'sesi_terbaru' => $sesi->first(),
                'sesi' => $sesi,
            ];
        })->values();

        $hadirBulanIni = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
            ->where('status_kehadiran', 'hadir')
            ->whereMonth('tanggal_absensi', now()->month)
            ->count();

        $totalBulanIni = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
            ->whereMonth('tanggal_absensi', now()->month)
            ->count();

        return view('pembina.absensi-peserta.index', [
            'riwayatPerEkskul' => $riwayatPerEkskul,
            'hadirBulanIni' => $hadirBulanIni,
            'totalBulanIni' => $totalBulanIni,
        ]);
    }

    /**
     * Catatan: Absensi Peserta TIDAK bisa ditambahkan/diubah dari sisi Pembina.
     * Data ini murni hasil input dari halaman "Absensi Peserta" milik Ketua.
     * Pembina hanya menampilkan (read-only) riwayat kehadiran peserta di sini.
     * Aksi yang tersisa untuk pembina hanyalah menghapus data yang keliru/ganda.
     */

    /**
     * Hapus data absensi peserta (mis. data ganda/keliru dari input Ketua).
     */
    public function destroy(AbsensiPeserta $absensiPeserta)
    {
        $absensiPeserta->delete();

        return redirect()
            ->route('pembina.absensi.index')
            ->with('success', 'Data absensi peserta berhasil dihapus.');
    }
}