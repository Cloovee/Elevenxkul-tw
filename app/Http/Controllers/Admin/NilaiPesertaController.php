<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;

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
    public function index(Request $request)
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        // Semua ekskul yang dibina (untuk opsi dropdown filter)
        $ekskulList = $pembina->ekskuls()->orderBy('nama_ekskul')->get();

        // Filter ekskul: hanya diterima kalau memang ekskul milik pembina ini
        $filterEkskul = (int) $request->query('ekskul');
        if (! $ekskulList->contains('id_ekskul', $filterEkskul)) {
            $filterEkskul = null;
        }

        $ekskulIds = $filterEkskul
            ? collect([$filterEkskul])
            : $ekskulList->pluck('id_ekskul');

        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        $daftarPeserta = Peserta::with(['siswa.kelas', 'ekskul', 'nilai' => function ($q) {
            $q->latest('id_nilai')->limit(1);
        }])
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy('nama')
            ->values();

        $riwayatNilai = NilaiPeserta::with(['peserta.siswa', 'peserta.ekskul'])
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('id_nilai')
            ->paginate(10)
            ->withQueryString();

        return view('pembina.nilai-peserta.index', [
            'daftarPeserta' => $daftarPeserta,
            'riwayatNilai' => $riwayatNilai,
            'ekskulList' => $ekskulList,
            'filterEkskul' => $filterEkskul,
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

        // Tetap di ekskul yang sedang difilter setelah menyimpan
        return redirect()
            ->route('pembina.nilai.index', array_filter(['ekskul' => $request->input('ekskul')]))
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