<?php

namespace App\Http\Controllers;

use App\Models\NilaiPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;

class NilaiPesertaController extends Controller
{
    /**
     * Use case: memberi nilai peserta -> simpan nilai baru.
     */
    public function store(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pembina' => ['nullable', 'string'],
        ]);

        NilaiPeserta::create([
            'id_anggota' => $peserta->id_anggota,
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'nilai' => $validated['nilai'],
            'catatan_pembina' => $validated['catatan_pembina'] ?? null,
        ]);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'nilai'])
            ->with('success', 'Nilai peserta berhasil disimpan.');
    }

    /**
     * Use case: memberi nilai peserta -> arahkan ke halaman/form pengeditan.
     */
    public function edit(NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->load('peserta.siswa');

        return view('pembina.nilai-peserta.edit', [
            'nilaiPeserta' => $nilaiPeserta,
        ]);
    }

    /**
     * Simpan perubahan nilai peserta.
     */
    public function update(Request $request, NilaiPeserta $nilaiPeserta)
    {
        $validated = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pembina' => ['nullable', 'string'],
        ]);

        $nilaiPeserta->update($validated);

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'nilai'])
            ->with('success', 'Nilai peserta berhasil diperbarui.');
    }

    /**
     * Hapus nilai peserta (dikonfirmasi via popup di halaman dashboard).
     */
    public function destroy(NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->delete();

        return redirect()
            ->route('pembina.dashboard', ['tab' => 'nilai'])
            ->with('success', 'Nilai peserta berhasil dihapus.');
    }
}
