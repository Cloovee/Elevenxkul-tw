<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelolaAnggotaController extends Controller
{
    /**
     * Sementara: ketua mengelola ekskul dengan id_ekskul = 1.
     * (Sama seperti pola hardcode yang dipakai di controller ketua lainnya.)
     */
    private const ID_EKSKUL = 1;

    /**
     * GET /kelola-anggota
     * Daftar semua anggota (peserta) pada ekskul yang dikelola ketua ini.
     */
    public function index(): View
    {
        $anggota = Peserta::with('siswa')
            ->where('id_ekskul', self::ID_EKSKUL)
            ->latest('tanggal_bergabung')
            ->get();

        return view('dashboard-ketua.kelola-anggota.index', compact('anggota'));
    }

    /**
     * GET /kelola-anggota/tambah
     * Form tambah anggota baru.
     */
    public function create(): View
    {
        return view('dashboard-ketua.kelola-anggota.tambah');
    }

    /**
     * POST /kelola-anggota/tambah
     * Validasi nama+NIS terhadap tabel siswa, cegah duplikat, lalu simpan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string'],
            'nis'  => ['required', 'string'],
        ]);

        // Cari siswa berdasarkan nama dan NIS (cross-validasi ke tabel siswa)
        $siswa = Siswa::where('nama_siswa', $validated['nama'])
            ->where('NIS', $validated['nis'])
            ->first();

        if (! $siswa) {
            return back()
                ->withInput()
                ->with('error', 'Siswa dengan nama dan NIS tersebut tidak ditemukan.');
        }

        // Cegah duplikat: siswa tidak boleh terdaftar dua kali di ekskul yang sama
        $sudahAnggota = Peserta::where('id_siswa', $siswa->id_siswa)
            ->where('id_ekskul', self::ID_EKSKUL)
            ->exists();

        if ($sudahAnggota) {
            return back()
                ->withInput()
                ->with('error', 'Siswa tersebut sudah menjadi anggota ekskul ini.');
        }

        Peserta::create([
            'id_siswa'          => $siswa->id_siswa,
            'id_ekskul'         => self::ID_EKSKUL,
            'tanggal_bergabung' => now(),
            'status'            => 'aktif',
        ]);

        return redirect()
            ->route('ketua.kelola-anggota')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * GET /kelola-anggota/{id}
     * Detail satu anggota.
     */
    public function show(int $id): View
    {
        $anggota = Peserta::with('siswa')
            ->where('id_anggota', $id)
            ->where('id_ekskul', self::ID_EKSKUL)
            ->firstOrFail();

        return view('dashboard-ketua.kelola-anggota.detail', compact('anggota'));
    }

    /**
     * DELETE /kelola-anggota/{id}
     * Keluarkan anggota dari ekskul (hapus baris peserta).
     * NOTE: kalau route ini belum ada di routes/web.php, tambahkan dulu
     * sebelum dipakai — lihat contoh route di bawah.
     */
    public function destroy(int $id): RedirectResponse
    {
        $anggota = Peserta::where('id_anggota', $id)
            ->where('id_ekskul', self::ID_EKSKUL)
            ->firstOrFail();

        $anggota->delete();

        return redirect()
            ->route('ketua.kelola-anggota')
            ->with('success', 'Anggota berhasil dihapus dari ekskul.');
    }
}