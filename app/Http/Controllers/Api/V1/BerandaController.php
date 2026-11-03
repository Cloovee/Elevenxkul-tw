<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AbsensiPelatih;
use App\Models\AbsensiPeserta;
use App\Models\Galeri;
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

        $pelatih = $ekskul->id_pelatih
            ? AbsensiPelatih::with('pelatih')
                ->where('id_pelatih', $ekskul->id_pelatih)
                ->latest('created_at')->take(5)->get()
                ->map(fn ($r) => [
                    'tipe'  => 'absensi_pelatih',
                    'teks'  => 'Menginput absensi pelatih ' . ($r->pelatih->nama_pelatih ?? '-'),
                    'waktu' => $r->created_at,
                ])
            : collect();

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

    /**
     * GET /api/v1/berita
     * Web belum punya tabel berita, jadi dipetakan dari galeri_ekskuls milik ekskul ini.
     */
    public function berita(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = Galeri::where('id_ekskul', $ekskul->id_ekskul)
            ->orderByDesc('created_at')
            ->orderByDesc('id_galeri')
            ->limit(50)
            ->get()
            ->map(fn (Galeri $g) => [
                'id'          => $g->id_galeri,
                'judul'       => $g->judul,
                'keterangan'  => $g->keterangan,
                'foto_url'    => $this->urlAbsolut($g->foto_url),
                'tanggal'     => $g->created_at?->toIso8601String(),
                'nama_ekskul' => $ekskul->nama_ekskul,
            ])
            ->values();

        return response()->json(['data' => $data]);
    }
}
