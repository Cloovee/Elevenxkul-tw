<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AbsensiPelatih;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Padanan API dari Ketua\AbsensiPelatihController (web).
 * Pelatih tidak dipilih Ketua: otomatis pelatih milik ekskul (ekskuls.id_pelatih).
 * Status validasi awal selalu "Menunggu" dan divalidasi Pembina di web.
 */
class AbsensiPelatihController extends ApiController
{
    private function json(AbsensiPelatih $a): array
    {
        return [
            'id_absensi'       => $a->id_absensi,
            'nama_pelatih'     => $a->pelatih->nama_pelatih ?? '-',
            'tanggal'          => $a->tanggal_absensi->toDateString(),
            'kegiatan'         => $a->kegiatan,
            'status'           => $a->status_kehadiran,
            'status_validasi'  => $a->status_validasi,
            'catatan_validasi' => $a->catatan_validasi,
            'foto_url'         => $this->urlAbsolut($a->foto_url),
            'created_at'       => $a->created_at?->toIso8601String(),
        ];
    }

    /** GET /api/v1/absensi-pelatih - riwayat laporan absensi pelatih ekskul ini. */
    public function index(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        if (! $ekskul->id_pelatih) {
            return response()->json(['data' => []]);
        }

        $rows = AbsensiPelatih::with('pelatih')
            ->where('id_pelatih', $ekskul->id_pelatih)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        return response()->json(['data' => $rows->map(fn ($a) => $this->json($a))->values()]);
    }

    /**
     * POST /api/v1/absensi-pelatih   (multipart/form-data)
     * field: tanggal_absensi, status_kehadiran, kegiatan?, foto_kehadiran? (gambar maks. 2 MB)
     */
    public function store(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        if (! $ekskul->id_pelatih) {
            return response()->json([
                'message' => 'Ekskul kamu belum punya pelatih. Minta pembina menentukan pelatihnya dulu.',
            ], 422);
        }

        $validated = $request->validate([
            'tanggal_absensi'  => ['required', 'date_format:Y-m-d'],
            'kegiatan'         => ['nullable', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'foto_kehadiran'   => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_kehadiran')) {
            $validated['foto_kehadiran'] = $request->file('foto_kehadiran')
                ->store('absensi-pelatih', 'public');
        }

        $validated['id_pelatih'] = $ekskul->id_pelatih;
        $validated['status_validasi'] = 'Menunggu';

        $absensi = AbsensiPelatih::create($validated);
        $absensi->load('pelatih');

        return response()->json([
            'message' => 'Absensi pelatih berhasil dikirim, menunggu validasi pembina.',
            'data'    => $this->json($absensi),
        ], 201);
    }
}
