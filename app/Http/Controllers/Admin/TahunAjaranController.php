<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\RiwayatKelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Modul Tahun Ajaran -- Admin only.
 *
 * Alur: Tahun Ajaran Aktif ditampilkan di halaman utama -> Admin klik "Ganti
 * Tahun Ajaran" -> sistem menyiapkan periode tujuan (belum aktif, belum
 * mengubah siswa apa pun) -> halaman "Perubahan" menampilkan siswa aktif
 * dengan keputusan (Naik Kelas/Tidak Naik/Lulus/Keluar) + kelas tujuan bebas
 * per siswa (dari data Kelas existing, bukan hasil hitungan tingkat+1) ->
 * Preview -> Finalisasi (DB transaction, all-or-nothing): periode lama jadi
 * "selesai", periode tujuan jadi "aktif", siswa diproses, histori dicatat.
 *
 * Finalisasi mengubah siswa.id_kelas & siswa.status (kondisi sekarang) dan
 * menulis satu baris riwayat_kelas_siswa per siswa (histori, tidak diubah lagi
 * kecuali finalisasi periode yang sama diulang). Tidak ada siswa yang dihapus
 * atau dibuat baru, dan tidak ada Tahun Ajaran lama yang dihapus.
 */
class TahunAjaranController extends Controller
{
    /**
     * Halaman utama: Tahun Ajaran Aktif + tombol Ganti Tahun Ajaran,
     * dan histori riwayat siswa yang bisa difilter per Tahun Ajaran.
     */
    public function index(Request $request)
    {
        $aktif = TahunAjaran::aktif()->first();
        $daftarTahunAjaran = TahunAjaran::orderByDesc('tahun_ajaran')->get();

        $tahunAjaranId = $request->integer('tahun_ajaran_id') ?: optional($aktif)->id_tahun_ajaran;
        $tahunAjaranTerpilih = $tahunAjaranId
            ? $daftarTahunAjaran->firstWhere('id_tahun_ajaran', $tahunAjaranId)
            : null;

        $histori = null;
        if ($tahunAjaranTerpilih) {
            $histori = RiwayatKelasSiswa::with(['siswa', 'kelas'])
                ->join('siswa', 'siswa.id_siswa', '=', 'riwayat_kelas_siswa.id_siswa')
                ->where('riwayat_kelas_siswa.id_tahun_ajaran', $tahunAjaranTerpilih->id_tahun_ajaran)
                ->orderBy('siswa.nama_siswa')
                ->select('riwayat_kelas_siswa.*')
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.tahun-ajaran.index', compact(
            'aktif', 'daftarTahunAjaran', 'tahunAjaranTerpilih', 'histori'
        ));
    }

    /**
     * Dipakai sekali di awal saja, ketika belum ada Tahun Ajaran aktif sama sekali
     * (mis. instalasi baru). Bukan langkah rutin setiap pergantian tahun -- untuk
     * itu gunakan "Ganti Tahun Ajaran".
     */
    public function bootstrap(Request $request)
    {
        if (TahunAjaran::aktif()->exists()) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Sudah ada Tahun Ajaran aktif. Gunakan "Ganti Tahun Ajaran" untuk berpindah periode.');
        }

        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/', 'unique:tahun_ajaran,tahun_ajaran'],
        ], [
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2026/2027.',
        ]);

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'status' => TahunAjaran::STATUS_AKTIF,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun Ajaran {$request->tahun_ajaran} ditetapkan sebagai Tahun Ajaran aktif.");
    }

    /**
     * Langkah 1 wizard "Ganti Tahun Ajaran": tampilkan Dari (periode aktif
     * sekarang) -> Ke (saran periode berikutnya, dapat diubah Admin).
     */
    public function gantiForm()
    {
        $aktif = TahunAjaran::aktif()->first();

        if (! $aktif) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Belum ada Tahun Ajaran aktif. Tetapkan Tahun Ajaran aktif terlebih dahulu.');
        }

        return view('admin.tahun-ajaran.ganti', [
            'aktif' => $aktif,
            'saran' => $this->saranTahunBerikutnya($aktif->tahun_ajaran),
        ]);
    }

    /**
     * Langkah 2: siapkan record periode tujuan (status masih "selesai" --
     * BELUM aktif, siswa BELUM diubah). Aktivasi baru terjadi saat Finalisasi.
     */
    public function gantiStore(Request $request)
    {
        $aktif = TahunAjaran::aktif()->first();

        if (! $aktif) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Belum ada Tahun Ajaran aktif. Tetapkan Tahun Ajaran aktif terlebih dahulu.');
        }

        $request->validate([
            'tahun_ajaran_baru' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
        ], [
            'tahun_ajaran_baru.regex' => 'Format Tahun Ajaran harus seperti 2027/2028.',
        ]);

        if ($request->tahun_ajaran_baru === $aktif->tahun_ajaran) {
            return back()->withErrors(['tahun_ajaran_baru' => 'Tahun Ajaran tujuan tidak boleh sama dengan yang aktif sekarang.'])->withInput();
        }

        // Kalau periode tujuan ini sudah pernah dibuat sebelumnya (mis. wizard
        // ditinggal sebelum Finalisasi), lanjutkan draft yang sama -- jangan buat duplikat.
        $tujuan = TahunAjaran::firstOrCreate(
            ['tahun_ajaran' => $request->tahun_ajaran_baru],
            ['status' => TahunAjaran::STATUS_SELESAI]
        );

        return redirect()->route('admin.tahun-ajaran.perubahan', $tujuan->id_tahun_ajaran);
    }

    private function saranTahunBerikutnya(string $label): string
    {
        if (preg_match('/^(\d{4})\/(\d{4})$/', $label, $m)) {
            return ($m[1] + 1) . '/' . ($m[2] + 1);
        }

        return '';
    }

    /**
     * Halaman "Perubahan Tahun Ajaran": daftar siswa aktif + form keputusan per siswa.
     */
    public function perubahan(TahunAjaran $tahunAjaran, Request $request)
    {
        $sudahDiproses = RiwayatKelasSiswa::where('id_tahun_ajaran', $tahunAjaran->id_tahun_ajaran)->exists();

        $query = Siswa::with('kelas')
            ->where('status', Siswa::STATUS_AKTIF)
            ->when($request->search, fn ($q, $s) => $q->where('nama_siswa', 'like', "%{$s}%"))
            ->orderBy('nama_siswa');

        $siswa = $query->paginate(20)->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('program_keahlian')->orderBy('rombel')->get();

        // Kalau tahun ajaran ini sudah pernah difinalisasi, tampilkan keputusan sebelumnya sebagai default.
        $riwayatSebelumnya = RiwayatKelasSiswa::where('id_tahun_ajaran', $tahunAjaran->id_tahun_ajaran)
            ->get()
            ->keyBy('id_siswa');

        return view('admin.tahun-ajaran.perubahan', compact('tahunAjaran', 'siswa', 'kelasList', 'sudahDiproses', 'riwayatSebelumnya'));
    }

    /**
     * Terima form keputusan per siswa dari halaman Perubahan, lalu tampilkan Preview
     * (belum menyimpan apa pun ke database).
     */
    public function preview(TahunAjaran $tahunAjaran, Request $request)
    {
        $keputusan = $this->validasiKeputusan($request);

        $siswaIds = array_keys($keputusan);
        $siswaMap = Siswa::with('kelas')->whereIn('id_siswa', $siswaIds)->get()->keyBy('id_siswa');
        $kelasMap = Kelas::all()->keyBy('id_kelas');

        $baris = [];
        foreach ($keputusan as $idSiswa => $item) {
            $siswa = $siswaMap->get($idSiswa);
            if (! $siswa) {
                continue;
            }

            $kelasTujuan = null;
            if ($item['keputusan'] === RiwayatKelasSiswa::KEPUTUSAN_NAIK_KELAS) {
                $kelasTujuan = $kelasMap->get($item['id_kelas_tujuan']);
            } elseif ($item['keputusan'] === RiwayatKelasSiswa::KEPUTUSAN_TIDAK_NAIK) {
                $kelasTujuan = $siswa->kelas;
            }

            $baris[] = [
                'id_siswa' => $siswa->id_siswa,
                'nama_siswa' => $siswa->nama_siswa,
                'nis' => $siswa->NIS,
                'kelas_lama' => $siswa->kelas->nama_kelas ?? '-',
                'keputusan' => $item['keputusan'],
                'kelas_baru' => $kelasTujuan->nama_kelas ?? match ($item['keputusan']) {
                    RiwayatKelasSiswa::KEPUTUSAN_LULUS => 'Alumni',
                    RiwayatKelasSiswa::KEPUTUSAN_KELUAR => 'Tidak Aktif',
                    default => '-',
                },
                'id_kelas_tujuan' => $kelasTujuan->id_kelas ?? null,
            ];
        }

        // Simpan sementara di session supaya Finalisasi tidak perlu form raksasa lagi
        // dan tidak bisa dimanipulasi jadi kelas yang tidak pernah dipilih Admin di form.
        session(["perubahan-ta-{$tahunAjaran->id_tahun_ajaran}" => $keputusan]);

        return view('admin.tahun-ajaran.preview', compact('tahunAjaran', 'baris'));
    }

    /**
     * Finalisasi: benar-benar menulis perubahan ke database, dalam satu transaction.
     */
    public function finalisasi(TahunAjaran $tahunAjaran, Request $request)
    {
        $keputusan = session("perubahan-ta-{$tahunAjaran->id_tahun_ajaran}");

        if (! $keputusan) {
            return redirect()->route('admin.tahun-ajaran.perubahan', $tahunAjaran)
                ->with('error', 'Sesi preview sudah kedaluwarsa. Silakan ulangi dari halaman Perubahan.');
        }

        DB::transaction(function () use ($tahunAjaran, $keputusan) {
            // Aktivasi periode: periode aktif LAMA (selain periode tujuan ini) jadi
            // "selesai", periode tujuan jadi "aktif". Baru terjadi di sini, bukan
            // sebelumnya, supaya "Ganti Tahun Ajaran" tidak langsung mengubah status
            // sebelum Admin benar-benar menekan Finalisasi.
            TahunAjaran::aktif()
                ->where('id_tahun_ajaran', '!=', $tahunAjaran->id_tahun_ajaran)
                ->update(['status' => TahunAjaran::STATUS_SELESAI]);

            if ($tahunAjaran->status !== TahunAjaran::STATUS_AKTIF) {
                $tahunAjaran->update(['status' => TahunAjaran::STATUS_AKTIF]);
            }

            foreach ($keputusan as $idSiswa => $item) {
                $siswa = Siswa::lockForUpdate()->findOrFail($idSiswa);

                switch ($item['keputusan']) {
                    case RiwayatKelasSiswa::KEPUTUSAN_NAIK_KELAS:
                        $kelasTujuan = Kelas::findOrFail($item['id_kelas_tujuan']);
                        $siswa->id_kelas = $kelasTujuan->id_kelas;
                        $siswa->status = Siswa::STATUS_AKTIF;
                        break;

                    case RiwayatKelasSiswa::KEPUTUSAN_TIDAK_NAIK:
                        // Kelas tidak berubah sama sekali.
                        $siswa->status = Siswa::STATUS_AKTIF;
                        break;

                    case RiwayatKelasSiswa::KEPUTUSAN_LULUS:
                        $siswa->status = Siswa::STATUS_ALUMNI;
                        break;

                    case RiwayatKelasSiswa::KEPUTUSAN_KELUAR:
                        $siswa->status = Siswa::STATUS_TIDAK_AKTIF;
                        break;
                }

                $siswa->save();

                // Histori: satu baris per siswa per tahun ajaran (upsert biar aman kalau
                // finalisasi tahun ajaran yang sama pernah dijalankan sebelumnya).
                RiwayatKelasSiswa::updateOrCreate(
                    [
                        'id_siswa' => $siswa->id_siswa,
                        'id_tahun_ajaran' => $tahunAjaran->id_tahun_ajaran,
                    ],
                    [
                        'id_kelas' => in_array($item['keputusan'], [RiwayatKelasSiswa::KEPUTUSAN_NAIK_KELAS, RiwayatKelasSiswa::KEPUTUSAN_TIDAK_NAIK])
                            ? $siswa->id_kelas
                            : null,
                        'status' => $siswa->status,
                        'keputusan' => $item['keputusan'],
                    ]
                );
            }
        });

        session()->forget("perubahan-ta-{$tahunAjaran->id_tahun_ajaran}");

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Perubahan Tahun Ajaran {$tahunAjaran->tahun_ajaran} berhasil difinalisasi.");
    }

    /**
     * Validasi payload keputusan per siswa yang dikirim dari halaman Perubahan.
     *
     * Bentuk input yang diharapkan:
     *   keputusan[id_siswa] = naik_kelas|tidak_naik|lulus|keluar
     *   kelas_tujuan[id_siswa] = id_kelas   (wajib hanya kalau keputusan = naik_kelas)
     *
     * @return array<int, array{keputusan: string, id_kelas_tujuan: int|null}>
     */
    private function validasiKeputusan(Request $request): array
    {
        $data = $request->validate([
            'keputusan' => ['required', 'array', 'min:1'],
            'keputusan.*' => ['required', Rule::in(RiwayatKelasSiswa::KEPUTUSANS)],
            'kelas_tujuan' => ['nullable', 'array'],
            'kelas_tujuan.*' => ['nullable', 'integer', 'exists:kelas,id_kelas'],
        ]);

        // Siswa yang diproses harus benar-benar siswa aktif (alumni/tidak_aktif tidak boleh ikut).
        $idSiswaValid = Siswa::where('status', Siswa::STATUS_AKTIF)
            ->whereIn('id_siswa', array_keys($data['keputusan']))
            ->pluck('id_siswa')
            ->all();

        $hasil = [];
        foreach ($data['keputusan'] as $idSiswa => $keputusan) {
            if (! in_array((int) $idSiswa, $idSiswaValid, true)) {
                continue;
            }

            $idKelasTujuan = $data['kelas_tujuan'][$idSiswa] ?? null;

            if ($keputusan === RiwayatKelasSiswa::KEPUTUSAN_NAIK_KELAS) {
                // Kelas tujuan wajib ada & valid untuk siswa yang naik kelas.
                if (! $idKelasTujuan) {
                    continue;
                }
            }

            $hasil[(int) $idSiswa] = [
                'keputusan' => $keputusan,
                'id_kelas_tujuan' => $idKelasTujuan,
            ];
        }

        if (empty($hasil)) {
            abort(422, 'Tidak ada data siswa valid untuk diproses.');
        }

        return $hasil;
    }
}