<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiPelatihController extends Controller
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
     * Use case: Ketua mencatat/mengirim laporan absensi pelatih.
     * Pelatih TIDAK dipilih/diketik oleh Ketua: otomatis pelatih yang sudah
     * ditentukan Pembina untuk ekskul ini (ekskuls.id_pelatih).
     * Riwayat/validasi laporan ditampilkan di halaman Pembina, bukan di sini.
     */
    public function index()
    {
        $idEkskul = $this->idEkskulAktif();

        if (! $idEkskul) {
            return redirect()->route('dashboard.ketua')
                ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        $ekskul = Ekskul::with('pelatih')->find($idEkskul);

        return view('dashboard-ketua.absensi-pelatih', [
            'ekskul'  => $ekskul,
            'pelatih' => optional($ekskul)->pelatih,
        ]);
    }

    /**
     * Simpan laporan absensi pelatih baru.
     * id_pelatih diambil dari ekskul yang dipimpin Ketua (bukan dari input form),
     * jadi Ketua tidak bisa mengabsen pelatih ekskul lain.
     * status_validasi otomatis "Menunggu" dan akan divalidasi oleh Pembina
     * ekskul terkait di halaman validasi-pelatih.
     */
    public function store(Request $request)
    {
        $idEkskul = $this->idEkskulAktif();

        if (! $idEkskul) {
            return redirect()->route('dashboard.ketua')
                ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        $pelatih = optional(Ekskul::with('pelatih')->find($idEkskul))->pelatih;

        if (! $pelatih) {
            return redirect()->route('ketua.absensi-pelatih')
                ->with('error', 'Ekskul kamu belum punya pelatih. Minta pembina menentukan pelatihnya dulu.');
        }

        $validated = $request->validate([
            'tanggal_absensi' => ['required', 'date'],
            'kegiatan' => ['nullable', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'foto_kehadiran' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_kehadiran')) {
            $validated['foto_kehadiran'] = $request->file('foto_kehadiran')
                ->store('absensi-pelatih', 'public');
        }

        $validated['id_pelatih'] = $pelatih->id_pelatih;
        $validated['status_validasi'] = 'Menunggu';

        AbsensiPelatih::create($validated);

        return redirect()
            ->route('ketua.absensi-pelatih')
            ->with('success', 'Absensi pelatih berhasil dikirim, menunggu validasi pembina.');
    }
}