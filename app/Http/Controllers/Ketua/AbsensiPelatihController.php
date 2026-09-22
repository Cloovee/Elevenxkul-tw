<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\Pelatih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
     * Riwayat/validasi laporan ditampilkan di halaman Pembina, bukan di sini.
     */
    public function index()
    {
        $idEkskul = $this->idEkskulAktif();

        if (! $idEkskul) {
            return redirect()->route('dashboard.ketua')
                ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        // Cuma pelatih yang terhubung ke ekskul yang dipimpin Ketua ini.
        $pelatihs = Pelatih::whereHas('ekskuls', function ($q) use ($idEkskul) {
                $q->where('id_ekskul', $idEkskul);
            })
            ->orderBy('nama_pelatih')
            ->get();

        return view('dashboard-ketua.absensi-pelatih', [
            'pelatihs' => $pelatihs,
        ]);
    }

    /**
     * Simpan laporan absensi pelatih baru.
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

        $validated = $request->validate([
            'id_pelatih' => [
                'required',
                'exists:pelatih,id_pelatih',
                // Pastikan pelatih yang dipilih beneran pelatih ekskul ini, bukan ekskul lain.
                function ($attribute, $value, $fail) use ($idEkskul) {
                    $milikEkskulIni = Pelatih::where('id_pelatih', $value)
                        ->whereHas('ekskuls', function ($q) use ($idEkskul) {
                            $q->where('id_ekskul', $idEkskul);
                        })
                        ->exists();
                    if (! $milikEkskulIni) {
                        $fail('Pelatih tersebut bukan pelatih ekskul kamu.');
                    }
                },
            ],
            'tanggal_absensi' => ['required', 'date'],
            'kegiatan' => ['nullable', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'foto_kehadiran' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_kehadiran')) {
            $validated['foto_kehadiran'] = $request->file('foto_kehadiran')
                ->store('absensi-pelatih', 'public');
        }

        $validated['status_validasi'] = 'Menunggu';

        AbsensiPelatih::create($validated);

        return redirect()
            ->route('ketua.absensi-pelatih')
            ->with('success', 'Absensi pelatih berhasil dikirim, menunggu validasi pembina.');
    }
}