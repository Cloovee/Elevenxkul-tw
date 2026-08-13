<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiPesertaController extends Controller
{
    private function pesertaUntukPembina()
    {
        $pembina = \App\Models\Pembina::where('id_user', Auth::id())->first();
        $ekskulIds = $pembina ? $pembina->ekskuls()->pluck('id_ekskul') : collect();

        return Peserta::with('siswa.kelas')
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy('nama')
            ->values();
    }

    /**
     * Use case: melihat absensi peserta -> tampilkan form tambah data absensi.
     */
    public function create()
    {
        return view('pembina.absensi-peserta.create', [
            'pesertas' => $this->pesertaUntukPembina(),
        ]);
    }

    /**
     * Simpan data absensi peserta baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => ['required', 'exists:data_anggota,id_anggota'],
            'tanggal_absensi' => ['required', 'date'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
        ], [
            'id_anggota.required' => 'Peserta wajib dipilih.',
            'tanggal_absensi.required' => 'Tanggal wajib diisi.',
        ]);

        AbsensiPeserta::updateOrCreate(
            [
                'id_anggota' => $validated['id_anggota'],
                'tanggal_absensi' => $validated['tanggal_absensi'],
            ],
            [
                'status_kehadiran' => $validated['status_kehadiran'],
                'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'] ?? null,
            ]
        );

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'absensi'])
            ->with('success', 'Data absensi peserta berhasil ditambahkan.');
    }

    /**
     * Use case: melihat absensi peserta -> arahkan ke halaman/form pengeditan.
     */
    public function edit(AbsensiPeserta $absensiPeserta)
    {
        $absensiPeserta->load('peserta.siswa.kelas');

        return view('pembina.absensi-peserta.edit', [
            'absensiPeserta' => $absensiPeserta,
            'pesertas' => $this->pesertaUntukPembina(),
        ]);
    }

    /**
     * Simpan perubahan data absensi peserta.
     */
    public function update(Request $request, AbsensiPeserta $absensiPeserta)
    {
        $validated = $request->validate([
            'id_anggota' => ['required', 'exists:data_anggota,id_anggota'],
            'tanggal_absensi' => ['required', 'date'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
        ], [
            'id_anggota.required' => 'Peserta wajib dipilih.',
            'tanggal_absensi.required' => 'Tanggal wajib diisi.',
        ]);

        $absensiPeserta->update($validated);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'absensi'])
            ->with('success', 'Data absensi peserta berhasil diperbarui.');
    }

    /**
     * Hapus data absensi peserta (dikonfirmasi via popup di halaman dashboard).
     */
    public function destroy(AbsensiPeserta $absensiPeserta)
    {
        $absensiPeserta->delete();

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'absensi'])
            ->with('success', 'Data absensi peserta berhasil dihapus.');
    }
}
