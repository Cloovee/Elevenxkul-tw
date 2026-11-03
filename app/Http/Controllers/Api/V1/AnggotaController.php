<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Peserta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnggotaController extends ApiController
{
    /** GET /api/v1/anggota - semua anggota ekskul yang dipimpin (aktif & nonaktif). */
    public function index(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = Peserta::with('siswa.kelas')
            ->where('id_ekskul', $ekskul->id_ekskul)
            ->get()
            ->sortBy(fn ($p) => mb_strtolower($p->nama))
            ->map(fn ($p) => $this->pesertaJson($p))
            ->values();

        return response()->json(['data' => $data]);
    }
}
