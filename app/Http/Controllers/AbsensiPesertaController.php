<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPeserta;
use App\Models\Peserta;
use App\Models\Sesi;
use Illuminate\Http\Request;

class AbsensiPesertaController extends Controller
{
    /**
     * Use case: melihat absensi peserta -> tampilkan form tambah data absensi.
     */
    public function create()
    {
        return view('pembina.absensi-peserta.create', [
            'pesertas' => Peserta::orderBy('nama')->get(),
            'sesis' => Sesi::latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan data absensi peserta baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_id' => ['required', 'exists:pesertas,id'],
            'sesi_id' => ['required', 'exists:sesis,id'],
            'jam_hadir' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:Hadir,Tidak Hadir,Terlambat,Izin'],
        ], [
            'peserta_id.required' => 'Peserta wajib dipilih.',
            'sesi_id.required' => 'Sesi wajib dipilih.',
        ]);

        AbsensiPeserta::updateOrCreate(
            [
                'peserta_id' => $validated['peserta_id'],
                'sesi_id' => $validated['sesi_id'],
            ],
            [
                'jam_hadir' => $validated['jam_hadir'] ?? null,
                'status' => $validated['status'],
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
        $absensiPeserta->load(['peserta', 'sesi']);

        return view('pembina.absensi-peserta.edit', [
            'absensiPeserta' => $absensiPeserta,
            'pesertas' => Peserta::orderBy('nama')->get(),
            'sesis' => Sesi::latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan perubahan data absensi peserta.
     */
    public function update(Request $request, AbsensiPeserta $absensiPeserta)
    {
        $validated = $request->validate([
            'peserta_id' => ['required', 'exists:pesertas,id'],
            'sesi_id' => ['required', 'exists:sesis,id'],
            'jam_hadir' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:Hadir,Tidak Hadir,Terlambat,Izin'],
        ], [
            'peserta_id.required' => 'Peserta wajib dipilih.',
            'sesi_id.required' => 'Sesi wajib dipilih.',
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
