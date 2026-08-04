<?php

namespace App\Http\Controllers;

use App\Models\NilaiPeserta;
use App\Models\Peserta;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiPesertaController extends Controller
{
    /**
     * Use case: memberi nilai peserta -> simpan nilai baru.
     */
    public function store(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'sesi_id' => ['nullable', 'exists:sesis,id'],
        ]);

        NilaiPeserta::create([
            'peserta_id' => $peserta->id,
            'sesi_id' => $validated['sesi_id'] ?? null,
            'kategori' => $validated['kategori'],
            'nilai' => $validated['nilai'],
            'diberikan_oleh' => Auth::id(),
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
        $nilaiPeserta->load(['peserta', 'sesi']);

        return view('pembina.nilai-peserta.edit', [
            'nilaiPeserta' => $nilaiPeserta,
            'sesis' => Sesi::latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan perubahan nilai peserta.
     */
    public function update(Request $request, NilaiPeserta $nilaiPeserta)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'sesi_id' => ['nullable', 'exists:sesis,id'],
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
