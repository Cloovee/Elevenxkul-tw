<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Berita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Berita dari Pembina untuk Ketua. Ekskul selalu diambil dari akun yang login
 * (bukan dari parameter), sama seperti controller API lainnya.
 *
 * "Baru"  = berita yang dibuat setelah users.berita_dilihat_at (atau semuanya
 *           bila Ketua belum pernah membuka halaman Berita).
 */
class BeritaController extends ApiController
{
    /**
     * GET /api/v1/berita
     * Daftar berita terbaru (maks. 50) + jumlah yang belum dibaca.
     * Aplikasi memanggil ini berkala untuk titik notifikasi di navbar.
     */
    public function index(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);
        $dilihat = $request->user()->berita_dilihat_at;

        $data = Berita::with('pembina')
            ->where('id_ekskul', $ekskul->id_ekskul)
            ->latest('created_at')
            ->latest('id_berita')
            ->limit(50)
            ->get()
            ->map(fn (Berita $b) => [
                'id'         => $b->id_berita,
                'judul'      => $b->judul,
                'isi'        => $b->isi,
                'penting'    => $b->penting,
                'pembina'    => $b->pembina?->nama_pembina,
                'baru'       => $dilihat === null || $b->created_at->gt($dilihat),
                'created_at' => $b->created_at?->toIso8601String(),
            ])
            ->values();

        return response()->json([
            'data'         => $data,
            'belum_dibaca' => $data->where('baru', true)->count(),
        ]);
    }

    /**
     * POST /api/v1/berita/dilihat
     * Dipanggil saat Ketua membuka halaman Berita: titik notifikasi dihapus.
     */
    public function dilihat(Request $request): JsonResponse
    {
        $this->ekskulKetua($request);

        $user = $request->user();
        $user->berita_dilihat_at = now();
        $user->save();

        return response()->json(['ok' => true, 'belum_dibaca' => 0]);
    }
}
