<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use Illuminate\View\View;

class RiwayatAbsensiController extends Controller
{
    /**
     * Sementara: ketua mengelola ekskul dengan id_ekskul = 1.
     * (Sama seperti pola hardcode yang dipakai di controller ketua lainnya.)
     */
    private const ID_EKSKUL = 1;

    /**
     * GET /riwayat-absensi
     *
     * Beda dengan "Aktivitas Terbaru" di dashboard (yang cuma nunjukin
     * beberapa hal TERAKHIR yang ketua lakukan), halaman ini nunjukin
     * SELURUH riwayat absensi peserta & pelatih sepanjang ekskul ini
     * berjalan — dua tab terpisah: Histori Absensi Peserta & Histori
     * Absensi Pelatih.
     */
    public function index(): View
    {
        $riwayatPeserta = AbsensiPeserta::with('peserta.siswa')
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', self::ID_EKSKUL))
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        // Satu ekskul dilatih oleh satu pelatih (lihat relasi Ekskul::pelatih()).
        $idPelatih = Ekskul::where('id_ekskul', self::ID_EKSKUL)->value('id_pelatih');

        $riwayatPelatih = AbsensiPelatih::with('pelatih')
            ->where('id_pelatih', $idPelatih)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        return view('dashboard-ketua.riwayat-absensi', compact('riwayatPeserta', 'riwayatPelatih'));
    }
}