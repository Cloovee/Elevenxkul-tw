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
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AbsensiPesertaController extends Controller
{
    /**
     * Cara absen per tingkat kelas:
     *  - Kelas 10 : dua cara, scan QR atau tabel manual (H / S / I).
     *  - Kelas 11 : tabel manual saja.
     *  - Kelas 12 : tabel manual saja.
     * Siswa yang tingkat kelasnya belum diisi tidak tampil di halaman ini.
     * Scan QR hanya berlaku untuk kelas 10.
     */
    private const TINGKAT_QR = '10';

    /**
     * Ekskul yang dipimpin ketua yang login (diset middleware 'ketua.ekskul',
     * jadi di sini pasti ada).
     */
    private function idEkskul(Request $request): int
    {
        return $request->attributes->get('ekskul_ketua')->id_ekskul;
    }

    /**
     * Tingkat peserta: '10', '11', '12', atau 'lain' kalau tingkat kelasnya belum diisi
     * (peserta 'lain' tidak ditampilkan di halaman ini).
     */
    private function tingkatPeserta(Peserta $peserta): string
    {
        $tingkat = (string) optional(optional($peserta->siswa)->kelas)->tingkat;

        return in_array($tingkat, ['10', '11', '12'], true) ? $tingkat : 'lain';
    }

    /**
     * Peserta ini boleh diabsen lewat scan QR? (hanya siswa kelas 10)
     */
    private function pakaiQr(Peserta $peserta): bool
    {
        return $this->tingkatPeserta($peserta) === self::TINGKAT_QR;
    }

    /**
     * Halaman absensi peserta. Ketua memilih kelas dulu (10 / 11 / 12):
     *  - Kelas 10 : pilih scan QR kartu peserta (isi QR = NIS) ATAU tabel manual.
     *  - Kelas 11 : tabel manual.
     *  - Kelas 12 : tabel manual.
     * Di tabel manual (H / S / I), kolom Catatan hanya tampil kalau ada
     * peserta yang berstatus Izin.
     */
    public function index(Request $request): View
    {
        $idEkskul = $this->idEkskul($request);

        $request->validate([
            'tanggal' => ['nullable', 'date', 'before_or_equal:today'],
            'kelas'   => ['nullable', 'in:10,11,12'],
            'cara'    => ['nullable', 'in:qr,manual'],
        ]);

        $tanggal = Carbon::parse($request->query('tanggal', now()))->toDateString();

        $pesertaAktif = Peserta::with('siswa.kelas')
            ->where('id_ekskul', $idEkskul)
            ->where('status', 'aktif')
            ->get();

        $pesertaPerTingkat = $pesertaAktif->groupBy(fn ($p) => $this->tingkatPeserta($p));
        $pesertaKelas10 = $pesertaPerTingkat->get('10', collect());

        $absensiHariIni = AbsensiPeserta::with('peserta.siswa.kelas')
            ->whereDate('tanggal_absensi', $tanggal)
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $idEkskul))
            ->orderByDesc('updated_at')
            ->get();

        // Daftar "Sudah Hadir" (bagian scan QR) hanya untuk kelas 10.
        $log = $absensiHariIni
            ->where('status_kehadiran', 'hadir')
            ->filter(fn ($a) => $this->pakaiQr($a->peserta))
            ->map(fn ($a) => [
                'nama'  => $a->peserta->nama,
                'nis'   => $a->peserta->siswa->NIS ?? '-',
                'kelas' => $a->peserta->kelas ?? '-',
                'waktu' => $a->updated_at->format('H:i'),
            ])
            ->values();

        // Baris tabel manual untuk SEMUA kelas (halaman memfilter sesuai kelas yang dipilih),
        // terisi dari absensi yang sudah tersimpan di tanggal ini.
        $absensiPerAnggota = $absensiHariIni->keyBy('id_anggota');

        $baris = $pesertaAktif
            ->reject(fn ($p) => $this->tingkatPeserta($p) === 'lain')
            ->sortBy(fn ($p) => [$p->kelas ?? '', mb_strtolower($p->nama)])
            ->map(function ($p) use ($absensiPerAnggota) {
                $absen = $absensiPerAnggota->get($p->id_anggota);

                return [
                    'id'      => $p->id_anggota,
                    'tingkat' => $this->tingkatPeserta($p),
                    'nis'     => $p->siswa->NIS ?? '-',
                    'nama'    => $p->nama,
                    'kelas'   => $p->kelas ?? '-',
                    'waktu'   => $absen && $absen->status_kehadiran !== 'alpha' ? $absen->updated_at->format('H:i') : null,
                    'status'  => $absen->status_kehadiran ?? null,
                    'catatan' => $absen->catatan ?? '',
                ];
            })
            ->values();

        // Kelas awal: ikut query (?kelas=), atau kelas pertama yang punya anggota aktif (10 -> 11 -> 12).
        $kelas = $request->query('kelas')
            ?? collect(['10', '11', '12'])->first(fn ($k) => $pesertaPerTingkat->has($k))
            ?? '10';

        // Cara absen hanya relevan untuk kelas 10 (QR atau manual); kelas lain selalu manual.
        $cara = $request->query('cara', 'qr');

        return view('dashboard-ketua.absensi-peserta', [
            'tanggal'      => $tanggal,
            'deskripsi'    => $absensiHariIni->pluck('deskripsi_kegiatan')->filter()->first() ?? '',
            'log'          => $log,
            'totalPeserta' => $pesertaKelas10->count(),
            'baris'        => $baris,
            'kelas'        => $kelas,
            'cara'         => $cara,
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

        // Scan QR hanya untuk kelas 10. Kelas 11 & 12 hanya lewat tabel manual.
        if (! $this->pakaiQr($peserta)) {
            return response()->json([
                'message' => "{$peserta->nama} bukan kelas 10. Scan QR hanya untuk kelas 10, kelas lain diabsen lewat Tabel Manual.",
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
            'peserta'  => [
                'nama'  => $peserta->nama,
                'nis'   => $peserta->siswa->NIS,
                'kelas' => $peserta->kelas ?? '-',
                'waktu' => now()->format('H:i'),
            ],
        ]);
    }

    /**
     * Simpan absensi manual (kelas 10, 11, 12) dari tabel (status[id_anggota] = hadir|sakit|izin).
     * Catatan hanya disimpan untuk status izin. Peserta yang statusnya tidak
     * dipilih tidak disentuh (nanti jadi Alpha lewat "Selesai Absen").
     */
    public function simpanManual(Request $request): RedirectResponse
    {
        $idEkskul = $this->idEkskul($request);

        $data = $request->validate([
            'tanggal_absensi'    => ['required', 'date', 'before_or_equal:today'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
            'kelas'              => ['nullable', 'in:10,11,12'],
            'status'             => ['required', 'array', 'min:1'],
            'status.*'           => ['in:hadir,sakit,izin'],
            'catatan'            => ['nullable', 'array'],
            'catatan.*'          => ['nullable', 'string', 'max:255'],
        ], [
            'status.required' => 'Pilih status (H / S / I) untuk minimal satu peserta dulu.',
            'status.min'      => 'Pilih status (H / S / I) untuk minimal satu peserta dulu.',
            'status.*.in'     => 'Status kehadiran harus Hadir, Sakit, atau Izin.',
            'catatan.*.max'   => 'Catatan maksimal 255 karakter.',
        ]);

        // Hanya anggota AKTIF ekskul ini yang bisa diabsen.
        $peserta = Peserta::where('id_ekskul', $idEkskul)
            ->where('status', 'aktif')
            ->whereIn('id_anggota', array_keys($data['status']))
            ->get()
            ->keyBy('id_anggota');

        if ($peserta->isEmpty()) {
            return back()
                ->withErrors(['status' => 'Tidak ada peserta yang valid untuk disimpan.'])
                ->withInput();
        }

        $tersimpan = 0;

        DB::transaction(function () use ($data, $peserta, &$tersimpan) {
            foreach ($data['status'] as $idAnggota => $status) {
                $p = $peserta->get($idAnggota);

                if (! $p) {
                    continue;
                }

                $catatan = $status === 'izin'
                    ? trim((string) ($data['catatan'][$idAnggota] ?? ''))
                    : '';

                $absensi = AbsensiPeserta::firstOrNew([
                    'id_anggota'      => $p->id_anggota,
                    'tanggal_absensi' => $data['tanggal_absensi'],
                ]);

                $absensi->fill([
                    'status_kehadiran'   => $status,
                    'catatan'            => $catatan !== '' ? $catatan : null,
                    'deskripsi_kegiatan' => $data['deskripsi_kegiatan'] ?? $absensi->deskripsi_kegiatan,
                ])->save();

                $tersimpan++;
            }
        });

        return redirect()
            ->route('ketua.absensi-peserta', [
                'tanggal' => $data['tanggal_absensi'],
                'kelas'   => $data['kelas'] ?? null,
                'cara'    => 'manual',
            ])
            ->with('success', "Absensi manual tersimpan untuk {$tersimpan} peserta.");
    }

    /**
     * "Selesai absen": peserta aktif yang belum punya catatan di tanggal ini
     * (tidak scan / belum diisi di tabel manual, SEMUA kelas) dicatat Alpha. Catatan yang sudah ada tidak diubah.
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