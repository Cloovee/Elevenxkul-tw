<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiPesertaController extends Controller
{
    /**
     * Id ekskul yang dipimpin oleh Ketua yang sedang login, diambil dari
     * relasi Siswa->ekskulDipimpin (diisi Admin lewat CRUD Ekskul).
     * Null kalau akun ini belum ditugaskan memimpin ekskul manapun.
     */
    private function idEkskulAktif(): ?int
    {
        $siswa = Auth::user()->siswa;

        return optional(optional($siswa)->ekskulDipimpin)->id_ekskul;
    }

    /**
     * Use case: Ketua mengisi absensi SEMUA peserta sekaligus untuk satu
     * tanggal kegiatan (satu form = satu tabel Nama + H/S/I per peserta).
     */
    public function index()
    {
        $idEkskul = $this->idEkskulAktif();

        if (! $idEkskul) {
            return redirect()->route('dashboard.ketua')
                ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        $pesertas = Peserta::with(['siswa.kelas', 'ekskul'])
            ->where('id_ekskul', $idEkskul)
            ->where('status', 'aktif')
            ->get();

        return view('dashboard-ketua.absensi-peserta', [
            'pesertas' => $pesertas,
        ]);
    }

    /**
     * Simpan absensi untuk semua peserta yang tampil di tabel dalam satu
     * kali kirim. Peserta yang tidak dicentang H/S/I otomatis dianggap
     * "alpha". Data ini langsung tampil sebagai riwayat read-only di
     * halaman Absensi Peserta milik Pembina.
     */
    public function store(Request $request)
    {
        $idEkskul = $this->idEkskulAktif();

        if (! $idEkskul) {
            return redirect()->route('dashboard.ketua')
                ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        $validated = $request->validate([
            'tanggal_absensi' => ['required', 'date'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
            'ids' => ['required', 'array', 'min:1'],
            // Batasi id_anggota yang boleh dikirim cuma yang beneran anggota ekskul ini,
            // biar Ketua nggak bisa isi absensi buat anggota ekskul lain.
            'ids.*' => [
                'exists:data_anggota,id_anggota',
                function ($attribute, $value, $fail) use ($idEkskul) {
                    $milikEkskulIni = Peserta::where('id_anggota', $value)
                        ->where('id_ekskul', $idEkskul)
                        ->exists();
                    if (! $milikEkskulIni) {
                        $fail('Salah satu peserta bukan anggota ekskul kamu.');
                    }
                },
            ],
            'status' => ['nullable', 'array'],
            'status.*' => ['in:hadir,sakit,izin'],
            'catatan' => ['nullable', 'array'],
            'catatan.*' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated['ids'] as $idAnggota) {
            AbsensiPeserta::updateOrCreate(
                [
                    'id_anggota' => $idAnggota,
                    'tanggal_absensi' => $validated['tanggal_absensi'],
                ],
                [
                    'status_kehadiran' => $validated['status'][$idAnggota] ?? 'alpha',
                    'catatan' => $validated['catatan'][$idAnggota] ?? null,
                    'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('ketua.absensi-peserta')
            ->with('success', 'Absensi peserta berhasil dikirim.');
    }
}