<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Padanan API dari Ketua\AbsensiPesertaController (web). Aturan bisnisnya sama:
 *  - hanya anggota AKTIF ekskul yang dipimpin yang bisa diabsen;
 *  - scan NIS (QR) hanya untuk siswa kelas 10;
 *  - "selesai absen" mencatat Alpha untuk yang belum tercatat.
 */
class AbsensiPesertaController extends ApiController
{
    private const TINGKAT_QR = '10';

    /** GET /api/v1/absensi-peserta?tanggal=YYYY-MM-DD */
    public function index(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = $request->validate([
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $tanggal = $data['tanggal'] ?? now()->toDateString();

        $rows = AbsensiPeserta::with('peserta.siswa')
            ->whereDate('tanggal_absensi', $tanggal)
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $ekskul->id_ekskul))
            ->orderBy('id_absensi')
            ->get();

        return response()->json([
            'data' => $rows->map(fn ($a) => $this->absensiPesertaJson($a))->values(),
        ]);
    }

    /** POST /api/v1/absensi-peserta/scan   body: nis, tanggal_absensi, deskripsi_kegiatan? */
    public function scan(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = $request->validate([
            'nis'                => ['required', 'string', 'max:20'],
            'tanggal_absensi'    => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $nis = trim($data['nis']);

        $peserta = Peserta::with('siswa.kelas')
            ->where('id_ekskul', $ekskul->id_ekskul)
            ->where('status', 'aktif')
            ->whereHas('siswa', fn ($q) => $q->where('NIS', $nis))
            ->first();

        if (! $peserta) {
            $siswaAda = Siswa::where('NIS', $nis)->exists();

            return response()->json([
                'message' => $siswaAda
                    ? "NIS {$nis} bukan anggota aktif ekskul ini."
                    : "NIS {$nis} tidak ditemukan.",
            ], 404);
        }

        $tingkat = (string) optional(optional($peserta->siswa)->kelas)->tingkat;

        if ($tingkat !== self::TINGKAT_QR) {
            return response()->json([
                'message' => "{$peserta->nama} bukan kelas 10. Scan QR hanya untuk kelas 10, kelas lain diabsen manual.",
            ], 422);
        }

        $absensi = AbsensiPeserta::firstOrNew([
            'id_anggota'      => $peserta->id_anggota,
            'tanggal_absensi' => $data['tanggal_absensi'],
        ]);

        $sudahHadir = $absensi->exists && $absensi->status_kehadiran === 'hadir';

        if (! $sudahHadir) {
            $absensi->fill([
                'status_kehadiran'   => 'hadir',
                'catatan'            => null,
                'deskripsi_kegiatan' => $data['deskripsi_kegiatan'] ?? $absensi->deskripsi_kegiatan,
            ])->save();
        }

        return response()->json([
            'message'  => $sudahHadir
                ? "{$peserta->nama} sudah tercatat hadir."
                : "{$peserta->nama} tercatat hadir.",
            'duplikat' => $sudahHadir,
            'peserta'  => $this->pesertaJson($peserta),
        ]);
    }

    /**
     * POST /api/v1/absensi-peserta/status
     * body: id_anggota, tanggal_absensi, status (hadir|izin|sakit|alpha), deskripsi_kegiatan?, catatan?
     * Catatan hanya disimpan untuk status izin (sama seperti web).
     */
    public function status(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = $request->validate([
            'id_anggota'         => ['required', 'integer'],
            'tanggal_absensi'    => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'status'             => ['required', 'in:hadir,izin,sakit,alpha'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
            'catatan'            => ['nullable', 'string', 'max:255'],
        ], [
            'status.in' => 'Status kehadiran harus Hadir, Izin, Sakit, atau Alpha.',
        ]);

        $peserta = Peserta::with('siswa')
            ->where('id_ekskul', $ekskul->id_ekskul)
            ->where('status', 'aktif')
            ->where('id_anggota', $data['id_anggota'])
            ->first();

        if (! $peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan atau bukan anggota aktif.'], 404);
        }

        $catatan = $data['status'] === 'izin' ? trim((string) ($data['catatan'] ?? '')) : '';

        $absensi = AbsensiPeserta::firstOrNew([
            'id_anggota'      => $peserta->id_anggota,
            'tanggal_absensi' => $data['tanggal_absensi'],
        ]);

        $absensi->fill([
            'status_kehadiran'   => $data['status'],
            'catatan'            => $catatan !== '' ? $catatan : null,
            'deskripsi_kegiatan' => $data['deskripsi_kegiatan'] ?? $absensi->deskripsi_kegiatan,
        ])->save();

        $absensi->setRelation('peserta', $peserta);

        return response()->json([
            'message' => 'Absensi tersimpan.',
            'data'    => $this->absensiPesertaJson($absensi),
        ]);
    }

    /** POST /api/v1/absensi-peserta/selesai   body: tanggal_absensi, deskripsi_kegiatan? */
    public function selesai(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $data = $request->validate([
            'tanggal_absensi'    => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $idBelumAbsen = Peserta::where('id_ekskul', $ekskul->id_ekskul)
            ->where('status', 'aktif')
            ->whereDoesntHave('absensi', fn ($q) => $q->whereDate('tanggal_absensi', $data['tanggal_absensi']))
            ->pluck('id_anggota');

        $now = now();
        $rows = $idBelumAbsen->map(fn ($id) => [
            'id_anggota'         => $id,
            'tanggal_absensi'    => $data['tanggal_absensi'],
            'status_kehadiran'   => 'alpha',
            'catatan'            => null,
            'deskripsi_kegiatan' => $data['deskripsi_kegiatan'] ?? null,
            'created_at'         => $now,
            'updated_at'         => $now,
        ])->all();

        if ($rows) {
            AbsensiPeserta::insert($rows);
        }

        $jumlah = count($rows);

        return response()->json([
            'message' => $jumlah > 0
                ? "Absensi ditutup. {$jumlah} peserta yang belum tercatat dicatat Alpha."
                : 'Absensi ditutup. Semua peserta aktif sudah tercatat.',
            'jumlah'  => $jumlah,
        ]);
    }

    /** GET /api/v1/absensi-peserta/riwayat - seluruh riwayat (maks. 1000 baris terbaru). */
    public function riwayat(Request $request): JsonResponse
    {
        $ekskul = $this->ekskulKetua($request);

        $rows = AbsensiPeserta::with('peserta.siswa')
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $ekskul->id_ekskul))
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('id_absensi')
            ->limit(1000)
            ->get();

        return response()->json([
            'data' => $rows->map(fn ($a) => $this->absensiPesertaJson($a))->values(),
        ]);
    }
}
