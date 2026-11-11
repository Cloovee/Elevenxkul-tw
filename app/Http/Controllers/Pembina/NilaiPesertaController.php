<?php

namespace App\Http\Controllers\Pembina;

use App\Exports\NilaiPesertaExport;
use App\Http\Controllers\Controller;
use App\Models\NilaiPeserta;
use App\Models\Pembina;
use App\Models\Peserta;
use App\Support\Penilaian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Penilaian peserta oleh pembina, berbentuk grade A/B/C.
 *
 *   Grade kehadiran : otomatis dari absensi peserta pada semester tsb.
 *   Grade keaktifan : pembina mengisi angka 0-100, sistem mengubahnya jadi grade.
 *   Grade akhir     : gabungan keduanya (aturan di config/penilaian.php).
 *
 * Peserta yang tidak pernah hadir sama sekali pada semester itu TIDAK diberi nilai/grade.
 * Pembina hanya bisa menilai dan meng-export peserta ekskul yang ia bina.
 */
class NilaiPesertaController extends Controller
{
    /** Jumlah peserta per halaman di tabel "Beri Nilai". */
    private const PESERTA_PER_HALAMAN = 20;

    /**
     * Redirect ramah (bukan 404) ketika akun yang login belum
     * terhubung ke data pembina manapun.
     */
    private function belumTerhubung(): RedirectResponse
    {
        return redirect()->route('pembina.dashboard')
            ->with('error', 'Akun Anda belum terhubung dengan data pembina. Hubungi admin untuk menghubungkan akun Anda.');
    }

    private function pembina(): ?Pembina
    {
        return Pembina::where('id_user', Auth::id())->first();
    }

    /** id ekskul yang dibina pembina yang login (kosong bila bukan pembina). */
    private function ekskulIdsDibina(): Collection
    {
        $pembina = $this->pembina();

        return $pembina ? $pembina->ekskuls()->pluck('id_ekskul') : collect();
    }

    private function pastikanMilik(int $idEkskul): void
    {
        abort_unless(
            $this->ekskulIdsDibina()->contains($idEkskul),
            403,
            'Peserta ini bukan peserta ekskul yang kamu bina.'
        );
    }

    /** Baca ?tahun_ajaran & ?semester; nilai tak valid -> semester berjalan. */
    private function periodeDariRequest(Request $request): array
    {
        $tahunAjaran = (string) $request->query('tahun_ajaran');
        if (! Penilaian::tahunAjaranValid($tahunAjaran)) {
            $tahunAjaran = Penilaian::tahunAjaranSaatIni();
        }

        $semester = (string) $request->query('semester');
        $semester = in_array($semester, ['1', '2'], true) ? (int) $semester : Penilaian::semesterSaatIni();

        return [$tahunAjaran, $semester];
    }

    /** Filter ekskul dari query; hanya diterima kalau memang ekskul milik pembina ini. */
    private function filterEkskul(Request $request, Collection $ekskulList): ?int
    {
        $filter = (int) $request->query('ekskul');

        return $ekskulList->contains('id_ekskul', $filter) ? $filter : null;
    }

    /**
     * Halaman beri nilai: pilih ekskul + semester, cari peserta, saring per jurusan,
     * lihat kehadiran tiap peserta (20 peserta per halaman), isi keaktifan, grade
     * terhitung otomatis.
     */
    public function index(Request $request)
    {
        $pembina = $this->pembina();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $ekskulList = $pembina->ekskuls()->orderBy('nama_ekskul')->get();
        $filterEkskul = $this->filterEkskul($request, $ekskulList);
        $ekskulIds = $filterEkskul ? collect([$filterEkskul]) : $ekskulList->pluck('id_ekskul');

        [$tahunAjaran, $semester] = $this->periodeDariRequest($request);
        [$mulai, $selesai] = Penilaian::periode($tahunAjaran, $semester);

        // Pilihan jurusan = program keahlian siswa yang benar-benar ada di ekskul yang dilihat.
        $opsiJurusan = $this->opsiJurusan($ekskulIds);
        $filterJurusan = (string) $request->query('jurusan');
        $filterJurusan = $opsiJurusan->contains($filterJurusan) ? $filterJurusan : '';

        $cari = trim((string) $request->query('search'));

        $daftarPeserta = $this->queryPeserta($ekskulIds, $cari, $filterJurusan)
            ->paginate(self::PESERTA_PER_HALAMAN, ['data_anggota.*'], 'page')
            ->onEachSide(1)
            ->withQueryString()
            ->fragment('beri-nilai');

        // Alamat halaman yang sudah tidak ada (mis. link lama setelah data berkurang) -> halaman terakhir.
        if ($daftarPeserta->isEmpty() && $daftarPeserta->currentPage() > $daftarPeserta->lastPage()) {
            return redirect()->to($daftarPeserta->url($daftarPeserta->lastPage()));
        }

        // Kehadiran & nilai hanya dihitung untuk peserta di halaman ini.
        $idHalamanIni = $daftarPeserta->getCollection()->pluck('id_anggota');

        $kehadiran = Penilaian::kehadiran($idHalamanIni, $mulai, $selesai);

        // Nilai yang sudah tersimpan pada semester ini (satu per peserta).
        $nilaiSemester = NilaiPeserta::whereIn('id_anggota', $idHalamanIni)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', (string) $semester)
            ->latest('id_nilai')
            ->get()
            ->unique('id_anggota')
            ->keyBy('id_anggota');

        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        // Riwayat punya nomor halaman sendiri (riwayat_page) supaya tidak bentrok dengan tabel peserta.
        $riwayatNilai = NilaiPeserta::with(['peserta.siswa', 'peserta.ekskul'])
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('id_nilai')
            ->paginate(10, ['*'], 'riwayat_page')
            ->onEachSide(1)
            ->withQueryString()
            ->fragment('riwayat');

        return view('pembina.nilai-peserta.index', [
            'daftarPeserta' => $daftarPeserta,
            'kehadiran' => $kehadiran,
            'nilaiSemester' => $nilaiSemester,
            'riwayatNilai' => $riwayatNilai,
            'ekskulList' => $ekskulList,
            'filterEkskul' => $filterEkskul,
            'opsiJurusan' => $opsiJurusan,
            'filterJurusan' => $filterJurusan,
            'cari' => $cari,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
            'opsiTahunAjaran' => Penilaian::opsiTahunAjaran(),
            // Tahun ajaran yang dipilih lebih baru dari yang aktif -> beri notifikasi,
            // daftar peserta tetap menampilkan anggota tahun ajaran aktif.
            'anggotaBelumTersedia' => Penilaian::anggotaBelumTersedia($tahunAjaran),
            'tahunAjaranAnggota' => Penilaian::tahunAjaranAnggota(),
        ]);
    }

    /**
     * Simpan nilai satu peserta pada satu semester. Kalau semester itu sudah
     * punya nilai, nilainya diperbarui (bukan dobel).
     */
    public function store(Request $request, Peserta $peserta)
    {
        $this->pastikanMilik($peserta->id_ekskul);

        $data = $this->validasi($request);

        $nilai = NilaiPeserta::where('id_anggota', $peserta->id_anggota)
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])
            ->latest('id_nilai')
            ->first() ?? new NilaiPeserta(['id_anggota' => $peserta->id_anggota]);

        $baru = ! $nilai->exists;
        $this->isiNilai($nilai, $peserta, $data);

        // Tetap di filter yang sedang dipakai setelah menyimpan.
        return redirect()
            ->route('pembina.nilai.index', array_filter([
                'ekskul' => $request->input('ekskul'),
                'tahun_ajaran' => $data['tahun_ajaran'],
                'semester' => $data['semester'],
                // Tetap di pencarian, jurusan, dan halaman yang sedang dibuka.
                'search' => $request->input('search'),
                'jurusan' => $request->input('jurusan'),
                'page' => $request->input('page'),
            ], fn ($v) => $v !== null && $v !== ''))
            ->with('success', $this->pesanHasil($peserta, $nilai, $baru));
    }

    /** Form ubah nilai: tampilkan kehadiran semester tsb sebagai acuan. */
    public function edit(NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->load('peserta.siswa');
        $this->pastikanMilik($nilaiPeserta->peserta->id_ekskul);

        [$mulai, $selesai] = Penilaian::periode($nilaiPeserta->tahun_ajaran, $nilaiPeserta->semester);
        $kehadiran = Penilaian::kehadiran([$nilaiPeserta->id_anggota], $mulai, $selesai)[$nilaiPeserta->id_anggota];

        return view('pembina.nilai-peserta.edit', [
            'nilaiPeserta' => $nilaiPeserta,
            'kehadiran' => $kehadiran,
        ]);
    }

    public function update(Request $request, NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->load('peserta.siswa');
        $this->pastikanMilik($nilaiPeserta->peserta->id_ekskul);

        $data = $this->validasi($request);

        $bentrok = NilaiPeserta::where('id_anggota', $nilaiPeserta->id_anggota)
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])
            ->where('id_nilai', '!=', $nilaiPeserta->id_nilai)
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'semester' => "Nilai {$nilaiPeserta->peserta->nama} untuk Semester {$data['semester']} {$data['tahun_ajaran']} sudah ada. Ubah nilai yang itu, atau hapus salah satunya.",
            ]);
        }

        $this->isiNilai($nilaiPeserta, $nilaiPeserta->peserta, $data);

        return redirect()
            ->route('pembina.nilai.index', ['tahun_ajaran' => $data['tahun_ajaran'], 'semester' => $data['semester']])
            ->with('success', $this->pesanHasil($nilaiPeserta->peserta, $nilaiPeserta, false));
    }

    public function destroy(NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->load('peserta');
        $this->pastikanMilik($nilaiPeserta->peserta->id_ekskul);

        $nilaiPeserta->delete();

        return redirect()
            ->route('pembina.nilai.index')
            ->with('success', 'Nilai peserta berhasil dihapus.');
    }

    /**
     * Export grade peserta satu semester ke Excel (default) atau PDF.
     * Isinya nilai yang sudah disimpan; peserta yang belum dinilai ikut tampil
     * dengan keterangan "Belum dinilai".
     */
    public function export(Request $request)
    {
        $pembina = $this->pembina();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $ekskulList = $pembina->ekskuls()->orderBy('nama_ekskul')->get();
        $filterEkskul = $this->filterEkskul($request, $ekskulList);
        $ekskulIds = $filterEkskul ? collect([$filterEkskul]) : $ekskulList->pluck('id_ekskul');

        [$tahunAjaran, $semester] = $this->periodeDariRequest($request);

        $baris = Penilaian::rekap($ekskulIds, $tahunAjaran, $semester);

        if ($baris->isEmpty()) {
            return back()->with('error', 'Tidak ada peserta aktif untuk diexport.');
        }

        $labelEkskul = $filterEkskul
            ? $ekskulList->firstWhere('id_ekskul', $filterEkskul)->nama_ekskul
            : 'Semua Ekskul';

        $namaFile = 'grade-peserta-' . Str::slug($labelEkskul) . '-' . str_replace('/', '-', $tahunAjaran) . '-sem' . $semester;

        if ($request->query('format') === 'pdf') {
            $pdf = Pdf::loadView('pdf.rekap-nilai', [
                'baris' => $baris,
                'labelEkskul' => $labelEkskul,
                'tahunAjaran' => $tahunAjaran,
                'semester' => $semester,
                'namaPembina' => $pembina->nama_pembina,
                'tanggalCetak' => now()->locale('id')->translatedFormat('d F Y'),
                'logoJabar' => $this->gambarBase64(public_path('images/logo-jabar.png')),
                'batas' => config('penilaian.batas'),
                'bobot' => config('penilaian.bobot'),
            ])->setPaper('a4', 'landscape');

            // ?preview=1 -> tampil di browser, selain itu unduh file.
            return $request->boolean('preview')
                ? $pdf->stream($namaFile . '.pdf')
                : $pdf->download($namaFile . '.pdf');
        }

        return Excel::download(
            new NilaiPesertaExport($baris, $labelEkskul, $tahunAjaran, $semester),
            $namaFile . '.xlsx'
        );
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /**
     * Query peserta aktif untuk tabel penilaian: bisa dicari (nama / NIS / NISN) dan
     * disaring per jurusan (program keahlian kelas siswa). Diurutkan menurut nama siswa.
     */
    private function queryPeserta(Collection $ekskulIds, string $cari, string $jurusan)
    {
        $query = Peserta::query()
            ->select('data_anggota.*')
            ->leftJoin('siswa', 'siswa.id_siswa', '=', 'data_anggota.id_siswa')
            ->leftJoin('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->with(['siswa.kelas', 'ekskul'])
            ->whereIn('data_anggota.id_ekskul', $ekskulIds)
            ->where('data_anggota.status', 'aktif');

        if ($cari !== '') {
            // Karakter khusus LIKE (% _ \) dianggap huruf biasa.
            $like = '%' . addcslashes($cari, '%_\\') . '%';

            $query->where(function ($w) use ($like) {
                $w->where('siswa.nama_siswa', 'like', $like)
                    ->orWhere('siswa.NIS', 'like', $like)
                    ->orWhere('siswa.NISN', 'like', $like);
            });
        }

        if ($jurusan !== '') {
            $query->where('kelas.program_keahlian', $jurusan);
        }

        return $query
            ->orderBy('siswa.nama_siswa')
            ->orderBy('data_anggota.id_anggota');
    }

    /** Daftar jurusan (program keahlian) peserta aktif di ekskul yang sedang dilihat. */
    private function opsiJurusan(Collection $ekskulIds): Collection
    {
        return Peserta::query()
            ->join('siswa', 'siswa.id_siswa', '=', 'data_anggota.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->whereIn('data_anggota.id_ekskul', $ekskulIds)
            ->where('data_anggota.status', 'aktif')
            ->whereNotNull('kelas.program_keahlian')
            ->where('kelas.program_keahlian', '!=', '')
            ->distinct()
            ->orderBy('kelas.program_keahlian')
            ->pluck('kelas.program_keahlian');
    }

    private function validasi(Request $request): array
    {
        $minimum = Penilaian::nilaiMinimum();

        return $request->validate([
            'semester' => ['required', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20', function ($attribute, $value, $fail) {
                if (! Penilaian::tahunAjaranValid((string) $value)) {
                    $fail('Format tahun ajaran harus seperti 2026/2027.');
                }
            }],
            'nilai_keaktifan' => ['required', 'numeric', "min:{$minimum}", 'max:100'],
            'catatan_pembina' => ['nullable', 'string', 'max:1000'],
        ], [
            'nilai_keaktifan.required' => 'Nilai keaktifan wajib diisi.',
            'nilai_keaktifan.numeric' => 'Nilai keaktifan harus berupa angka.',
            'nilai_keaktifan.min' => "Nilai keaktifan minimal {$minimum} (rentang nilai {$minimum}-100).",
            'nilai_keaktifan.max' => "Nilai keaktifan maksimal 100 (rentang nilai {$minimum}-100).",
        ]);
    }

    /**
     * Hitung grade (kehadiran dari absensi, keaktifan dari angka, lalu gabungan)
     * dan simpan. Ditolak bila:
     *   - peserta belum punya absensi pada semester tsb (grade kehadiran tidak bisa dihitung), atau
     *   - peserta tidak pernah hadir sama sekali pada semester tsb (tanpa nilai/grade).
     */
    private function isiNilai(NilaiPeserta $nilai, Peserta $peserta, array $data): void
    {
        [$mulai, $selesai] = Penilaian::periode($data['tahun_ajaran'], $data['semester']);
        $hadir = Penilaian::kehadiran([$peserta->id_anggota], $mulai, $selesai)[$peserta->id_anggota];

        if ($hadir['total'] === 0) {
            throw ValidationException::withMessages([
                'nilai_keaktifan' => "Belum ada data absensi {$peserta->nama} pada Semester {$data['semester']} {$data['tahun_ajaran']}, sehingga grade kehadirannya belum bisa dihitung.",
            ]);
        }

        if (! Penilaian::pernahHadir($hadir)) {
            throw ValidationException::withMessages([
                'nilai_keaktifan' => "{$peserta->nama} tidak pernah hadir pada Semester {$data['semester']} {$data['tahun_ajaran']}, sehingga tidak diberi nilai maupun grade.",
            ]);
        }

        $keaktifan = round((float) $data['nilai_keaktifan'], 2);
        $gradeKeaktifan = Penilaian::gradeDariAngka($keaktifan);

        $atribut = [
            'tahun_ajaran' => $data['tahun_ajaran'],
            'semester' => $data['semester'],
            'nilai' => $keaktifan, // kolom lama, disamakan dengan keaktifan
            'nilai_keaktifan' => $keaktifan,
            'jumlah_hadir' => $hadir['hadir'],
            'jumlah_pertemuan' => $hadir['total'],
            'persen_kehadiran' => $hadir['persen'],
            'grade_kehadiran' => $hadir['grade'],
            'grade_keaktifan' => $gradeKeaktifan,
            'grade_akhir' => Penilaian::gabungkan($hadir['grade'], $gradeKeaktifan),
        ];

        // Catatan hanya diubah kalau memang dikirim dari form.
        if (array_key_exists('catatan_pembina', $data)) {
            $atribut['catatan_pembina'] = $data['catatan_pembina'];
        }

        $nilai->fill($atribut)->save();
    }

    private function pesanHasil(Peserta $peserta, NilaiPeserta $nilai, bool $baru): string
    {
        return sprintf(
            'Nilai %s %s: Kehadiran %s, Keaktifan %s, Grade akhir %s.',
            $peserta->nama,
            $baru ? 'disimpan' : 'diperbarui',
            $nilai->grade_kehadiran,
            $nilai->grade_keaktifan,
            $nilai->grade_akhir
        );
    }

    private function gambarBase64(string $path): ?string
    {
        if (! is_file($path)) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}