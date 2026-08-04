<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
use App\Models\Pelatih;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiPelatihController extends Controller
{
    /**
     * Use case: memvalidasi absensi pelatih -> tampilkan form tambah laporan.
     */
    public function create()
    {
        return view('pembina.validasi-pelatih.create', [
            'pelatihs' => Pelatih::orderBy('nama')->get(),
            'sesis' => Sesi::latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan laporan absensi pelatih baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatih_id' => ['required', 'exists:pelatihs,id'],
            'sesi_id' => ['required', 'exists:sesis,id'],
            'jam_lapor' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:Menunggu,Divalidasi,Ditolak'],
        ], [
            'pelatih_id.required' => 'Pelatih wajib dipilih.',
            'sesi_id.required' => 'Sesi wajib dipilih.',
        ]);

        AbsensiPelatih::create($validated);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Laporan absensi pelatih berhasil ditambahkan.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> arahkan ke halaman/form pengeditan.
     */
    public function edit(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->load(['pelatih', 'sesi']);

        return view('pembina.validasi-pelatih.edit', [
            'absensiPelatih' => $absensiPelatih,
            'pelatihs' => Pelatih::orderBy('nama')->get(),
            'sesis' => Sesi::latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan perubahan laporan absensi pelatih.
     */
    public function update(Request $request, AbsensiPelatih $absensiPelatih)
    {
        $validated = $request->validate([
            'pelatih_id' => ['required', 'exists:pelatihs,id'],
            'sesi_id' => ['required', 'exists:sesis,id'],
            'jam_lapor' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:Menunggu,Divalidasi,Ditolak'],
        ], [
            'pelatih_id.required' => 'Pelatih wajib dipilih.',
            'sesi_id.required' => 'Sesi wajib dipilih.',
        ]);

        $absensiPelatih->update($validated);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Laporan absensi pelatih berhasil diperbarui.');
    }

    /**
     * Hapus laporan absensi pelatih (dikonfirmasi via popup di halaman dashboard).
     */
    public function destroy(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->delete();

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Laporan absensi pelatih berhasil dihapus.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> setujui.
     */
    public function setujui(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->update([
            'status' => 'Divalidasi',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Absensi pelatih berhasil divalidasi.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> tolak.
     */
    public function tolak(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->update([
            'status' => 'Ditolak',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'validasi'])
            ->with('success', 'Absensi pelatih ditolak.');
    }
}
