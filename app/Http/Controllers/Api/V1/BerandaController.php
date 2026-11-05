<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BerandaController extends ApiController
{
    /**
     * GET /api/v1/aktivitas
     * Padanan "Riwayat aktivitas" di dashboard web: gabungan 3 sumber, 5 terbaru.
     */
    public function aktivitas(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);
        $idEkskul = $ekskul->id_ekskul;

        $pelatih = AbsensiPelatih::with(['pelatih', 'pembina'])
            ->untukEkskul($ekskul)
            ->latest('created_at')->take(5)->get()
            ->map(fn ($r) => [
                'tipe'  => 'absensi_pelatih',
                'teks'  => 'Mengunggah foto kegiatan (' . $r->nama_petugas . ')',
                'waktu' => $r->created_at,
            ]);

        $peserta = AbsensiPeserta::whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->selectRaw('tanggal_absensi, COUNT(*) as jumlah, MAX(created_at) as waktu')
            ->groupBy('tanggal_absensi')
            ->orderByDesc('waktu')
            ->take(5)->get()
            ->map(fn ($r) => [
                'tipe'  => 'absensi_peserta',
                'teks'  => 'Mengisi absensi peserta (' . $r->jumlah . ' orang)',
                'waktu' => Carbon::parse($r->waktu),
            ]);

        $anggota = Peserta::with('siswa')
            ->where('id_ekskul', $idEkskul)
            ->latest('created_at')->take(5)->get()
            ->map(fn ($p) => [
                'tipe'  => 'anggota_baru',
                'teks'  => 'Menambahkan anggota baru: ' . ($p->siswa->nama_siswa ?? '-'),
                'waktu' => $p->created_at,
            ]);

        $data = $pelatih->concat($peserta)->concat($anggota)
            ->filter(fn ($r) => $r['waktu'] !== null)
            ->sortByDesc('waktu')
            ->take(5)
            ->map(fn ($r) => [
                'tipe'  => $r['tipe'],
                'teks'  => $r['teks'],
                'waktu' => $r['waktu']->toIso8601String(),
            ])
            ->values();

        return response()->json(['data' => $data]);
    }
}
