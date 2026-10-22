<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AbsensiPesertaController extends Controller
{
    /**
     * Ekskul yang dipimpin ketua yang login (diset middleware 'ketua.ekskul',
     * jadi di sini pasti ada).
     */
    private function idEkskul(Request $request): int
    {
        return $request->attributes->get('ekskul_ketua')->id_ekskul;
    }

    /**
     * Halaman absensi peserta: ketua scan QR kartu peserta (isi QR = NIS).
     * Tidak ada tabel absensi lagi -- yang tampil hanya scanner dan daftar
     * peserta yang SUDAH tercatat hadir pada tanggal yang dipilih.
     */
    public function index(Request $request): View
    {
        $idEkskul = $this->idEkskul($request);

        $request->validate([
            'tanggal' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $tanggal = Carbon::parse($request->query('tanggal', now()))->toDateString();

        $absensiHariIni = AbsensiPeserta::with('peserta.siswa.kelas')
            ->whereDate('tanggal_absensi', $tanggal)
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->orderByDesc('updated_at')
            ->get();

        $log = $absensiHariIni
            ->where('status_kehadiran', 'hadir')
            ->map(fn ($a) => [
                'nama'  => $a->peserta->nama,
                'nis'   => $a->peserta->siswa->NIS ?? '-',
                'kelas' => $a->peserta->kelas ?? '-',
                'waktu' => $a->updated_at->format('H:i'),
            ])
            ->values();

        $totalPeserta = Peserta::where('id_ekskul', $idEkskul)
            ->where('status', 'aktif')
            ->count();

        return view('dashboard-ketua.absensi-peserta', [
            'tanggal'      => $tanggal,
            'deskripsi'    => $absensiHariIni->pluck('deskripsi_kegiatan')->filter()->first() ?? '',
            'log'          => $log,
            'totalPeserta' => $totalPeserta,
        ]);
    }

    /**
     * Dipanggil (AJAX/JSON) setiap kali ketua scan satu QR.
     * Isi QR = NIS siswa. Peserta yang valid dicatat "hadir".
     */
    public function scan(Request $request): JsonResponse
    {
        $idEkskul = $this->idEkskul($request);

        $data = $request->validate([
            'nis'                => ['required', 'string', 'max:20'],
            'tanggal_absensi'    => ['required', 'date', 'before_or_equal:today'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $nis = trim($data['nis']);

        // Hanya anggota AKTIF dari ekskul yang dipimpin ketua ini yang boleh dicatat.
        $peserta = Peserta::with('siswa.kelas')
            ->where('id_ekskul', $idEkskul)
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
            'peserta'  => [
                'nama'  => $peserta->nama,
                'nis'   => $peserta->siswa->NIS,
                'kelas' => $peserta->kelas ?? '-',
                'waktu' => now()->format('H:i'),
            ],
        ]);
    }

    /**
     * "Selesai absen": peserta aktif yang belum punya catatan di tanggal ini
     * (tidak scan) dicatat Alpha. Catatan yang sudah ada tidak diubah.
     */
    public function selesai(Request $request): RedirectResponse
    {
        $idEkskul = $this->idEkskul($request);

        $data = $request->validate([
            'tanggal_absensi'    => ['required', 'date', 'before_or_equal:today'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $idBelumAbsen = Peserta::where('id_ekskul', $idEkskul)
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

        return redirect()
            ->route('ketua.absensi-peserta', ['tanggal' => $data['tanggal_absensi']])
            ->with('success', count($rows) > 0
                ? 'Absensi ditutup. ' . count($rows) . ' peserta yang tidak scan dicatat Alpha.'
                : 'Absensi ditutup. Semua peserta aktif sudah tercatat.');
    }
}