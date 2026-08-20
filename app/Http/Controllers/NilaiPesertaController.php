<?php

namespace App\Http\Controllers;

use App\Models\NilaiPeserta;
use App\Models\Pembina;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NilaiPesertaController extends Controller
{
    /**
     * Redirect ramah (bukan 404) ketika akun yang login belum
     * terhubung ke data pembina manapun.
     */
    private function belumTerhubung(): RedirectResponse
    {
        return redirect()->route('pembina.dashboard')
            ->with('error', 'Akun Anda belum terhubung dengan data pembina. Hubungi admin untuk menghubungkan akun Anda.');
    }

    /**
     * Use case: memberi nilai peserta -> halaman daftar & form beri nilai (terpisah dari dashboard).
     */
    public function index()
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $ekskulIds = $pembina->ekskuls()->pluck('id_ekskul');
        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        $daftarPeserta = Peserta::with(['siswa.kelas', 'ekskul', 'nilai' => function ($q) {
            $q->latest('id_nilai')->limit(1);
        }])
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy('nama')
            ->values();

        $riwayatNilai = NilaiPeserta::with('peserta.siswa')
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('id_nilai')
            ->paginate(10);

        return view('pembina.nilai-peserta.index', [
            'daftarPeserta' => $daftarPeserta,
            'riwayatNilai' => $riwayatNilai,
        ]);
    }

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
            ->route('pembina.nilai.index')
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
            ->route('pembina.nilai.index')
            ->with('success', 'Nilai peserta berhasil diperbarui.');
    }

    /**
     * Hapus nilai peserta (dikonfirmasi via popup di halaman dashboard).
     */
    public function destroy(NilaiPeserta $nilaiPeserta)
    {
        $nilaiPeserta->delete();

        return redirect()
            ->route('pembina.nilai.index')
            ->with('success', 'Nilai peserta berhasil dihapus.');
    }
}