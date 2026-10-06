<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatAbsensiController extends Controller
{
    /**
     * GET /riwayat-absensi
     *
     * Beda dengan "Aktivitas Terbaru" di dashboard (yang cuma nunjukin
     * beberapa hal TERAKHIR yang ketua lakukan), halaman ini nunjukin
     * SELURUH riwayat absensi peserta & pelatih sepanjang ekskul ini
     * berjalan — dua tab terpisah: Histori Absensi Peserta & Histori
     * Absensi Pelatih.
     */
    public function index(Request $request): View
    {
        // Ekskul yang dipimpin ketua yang login (diset middleware 'ketua.ekskul').
        $idEkskul = $request->attributes->get('ekskul_ketua')->id_ekskul;

        $riwayatPeserta = AbsensiPeserta::with('peserta.siswa')
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        // Satu ekskul dilatih oleh satu pelatih (lihat relasi Ekskul::pelatih()).
        $idPelatih = Ekskul::where('id_ekskul', $idEkskul)->value('id_pelatih');

        $riwayatPelatih = AbsensiPelatih::with('pelatih')
            ->where('id_pelatih', $idPelatih)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        return view('dashboard-ketua.riwayat-absensi', compact('riwayatPeserta', 'riwayatPelatih'));
    }
}