<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;

class AbsensiPesertaController extends Controller
{
    /**
     * Sementara: Ketua mengelola ekskul dengan id_ekskul = 1
     * (samain kayak halaman Kelola Anggota).
     */
    private const ID_EKSKUL = 1;

    /**
     * Use case: Ketua mengisi absensi SEMUA peserta sekaligus untuk satu
     * tanggal kegiatan (satu form = satu tabel Nama + H/S/I per peserta).
     */
    public function index()
    {
        $pesertas = Peserta::with(['siswa.kelas', 'ekskul'])
            ->where('id_ekskul', self::ID_EKSKUL)
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
        $validated = $request->validate([
            'tanggal_absensi' => ['required', 'date'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:data_anggota,id_anggota'],
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