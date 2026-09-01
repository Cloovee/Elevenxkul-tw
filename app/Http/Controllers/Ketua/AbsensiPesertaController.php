<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;

class AbsensiPesertaController extends Controller
{
    /**
     * Use case: Ketua mencatat/mengirim absensi peserta (anggota ekskul).
     * Menampilkan form input + riwayat absensi yang sudah dikirim.
     */
    public function index()
    {
        $pesertas = Peserta::with(['siswa.kelas', 'ekskul'])
            ->get();

        $riwayat = AbsensiPeserta::with(['peserta.siswa.kelas', 'peserta.ekskul'])
            ->latest('tanggal_absensi')
            ->paginate(10);

        return view('dashboard-ketua.absensi-peserta', [
            'pesertas' => $pesertas,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Simpan absensi peserta baru. Data ini langsung tampil sebagai riwayat
     * read-only di halaman Absensi Peserta milik Pembina.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => ['required', 'exists:data_anggota,id_anggota'],
            'tanggal_absensi' => ['required', 'date'],
            'deskripsi_kegiatan' => ['nullable', 'string', 'max:1000'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
        ]);

        AbsensiPeserta::create($validated);

        return redirect()
            ->route('ketua.absensi-peserta')
            ->with('success', 'Absensi peserta berhasil disimpan.');
    }
}