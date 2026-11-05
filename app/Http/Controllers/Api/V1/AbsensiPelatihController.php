<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AbsensiPelatih;
use App\Models\Ekskul;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Upload foto kegiatan (sebelumnya "absensi pelatih") dari aplikasi Ketua.
 *
 * Petugas tidak dipilih Ketua:
 *  - ekskul punya pelatih  -> otomatis pelatih ekskul (ekskuls.id_pelatih), menunggu validasi pembina.
 *  - ekskul tanpa pelatih  -> otomatis pembina ekskul sebagai pengganti pelatih. Karena pembina
 *    sendiri yang bertanggung jawab, laporannya langsung berstatus "Divalidasi".
 * Kedua jenis baris ikut ke laporan kegiatan.
 */
class AbsensiPelatihController extends ApiController
{
    private function json(AbsensiPelatih $a): array
    {
        return [
            'id_absensi'       => $a->id_absensi,
            'nama_pelatih'     => $a->nama_petugas,
            'peran'            => $a->oleh_pembina ? 'pembina' : 'pelatih',
            'tanggal'          => $a->tanggal_absensi->toDateString(),
            'kegiatan'         => $a->kegiatan,
            'status'           => $a->status_kehadiran,
            'status_validasi'  => $a->status_validasi,
            'catatan_validasi' => $a->catatan_validasi,
            'foto_url'         => $this->urlAbsolut($a->foto_url),
            'created_at'       => $a->created_at?->toIso8601String(),
        ];
    }

    private function adaPetugas(Ekskul $ekskul): bool
    {
        return $ekskul->id_pelatih || $ekskul->id_pembina;
    }

    /** GET /api/v1/absensi-pelatih - riwayat foto kegiatan ekskul ini. */
    public function index(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $rows = AbsensiPelatih::with(['pelatih', 'pembina'])
            ->untukEkskul($ekskul)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->get();

        return response()->json(['data' => $rows->map(fn ($a) => $this->json($a))->values()]);
    }

    /**
     * POST /api/v1/absensi-pelatih   (multipart/form-data)
     * field: tanggal_absensi, status_kehadiran, kegiatan, foto_kehadiran (gambar maks. 2 MB; keduanya wajib)
     */
    public function store(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        if (! $this->adaPetugas($ekskul)) {
            return response()->json([
                'message' => 'Ekskul kamu belum punya pelatih maupun pembina. Hubungi admin untuk menentukannya dulu.',
            ], 422);
        }

        // Foto dan kegiatan wajib: laporan kosong tidak boleh tersimpan.
        $validated = $request->validate([
            'tanggal_absensi'  => ['required', 'date_format:Y-m-d'],
            'kegiatan'         => ['required', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'foto_kehadiran'   => ['required', 'image', 'max:2048'],
        ], [
            'kegiatan.required'         => 'Kegiatan wajib diisi.',
            'kegiatan.max'              => 'Kegiatan maksimal 100 karakter.',
            'foto_kehadiran.required'   => 'Foto kegiatan wajib diunggah.',
            'foto_kehadiran.image'      => 'File harus berupa gambar (JPG atau PNG).',
            'foto_kehadiran.max'        => 'Ukuran foto maksimal 2MB.',
            'foto_kehadiran.uploaded'   => 'Foto gagal diunggah. Coba lagi.',
        ]);

        if ($request->hasFile('foto_kehadiran')) {
            $validated['foto_kehadiran'] = $request->file('foto_kehadiran')
                ->store('absensi-pelatih', 'public');
        }

        $validated['id_ekskul'] = $ekskul->id_ekskul;

        if ($ekskul->id_pelatih) {
            $validated['id_pelatih'] = $ekskul->id_pelatih;
            $validated['status_validasi'] = 'Menunggu';
            $pesan = 'Foto kegiatan berhasil dikirim, menunggu validasi pembina.';
        } else {
            // Tanpa pelatih: pembina menggantikan, jadi tidak perlu menunggu validasi dirinya sendiri.
            $validated['id_pelatih'] = null;
            $validated['id_pembina'] = $ekskul->id_pembina;
            $validated['status_validasi'] = 'Divalidasi';
            $validated['id_pembina_validasi'] = $ekskul->id_pembina;
            $validated['tgl_validasi'] = now();
            $pesan = 'Foto kegiatan berhasil dikirim atas nama pembina (ekskul ini belum punya pelatih).';
        }

        $absensi = AbsensiPelatih::create($validated);
        $absensi->load(['pelatih', 'pembina']);

        return response()->json([
            'message' => $pesan,
            'data'    => $this->json($absensi),
        ], 201);
    }
}
