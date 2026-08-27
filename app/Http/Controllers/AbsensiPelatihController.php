<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
use App\Models\Pelatih;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AbsensiPelatihController extends Controller
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

    private function pelatihUntukPembina()
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();
        $pelatihIds = $pembina ? $pembina->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih') : collect();

        return Pelatih::whereIn('id_pelatih', $pelatihIds)->orderBy('nama_pelatih')->get();
    }

    /**
     * Use case: memvalidasi absensi pelatih -> halaman daftar & validasi (terpisah dari dashboard).
     */
    public function index()
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $pelatihIds = $pembina->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih');

        $laporan = AbsensiPelatih::with('pelatih')
            ->whereIn('id_pelatih', $pelatihIds)
            ->orderByRaw("status_validasi = 'Menunggu' desc")
            ->latest('tanggal_absensi')
            ->paginate(15);

        $pendingCount = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Menunggu')
            ->count();

        return view('pembina.validasi-pelatih.index', [
            'laporan' => $laporan,
            'pendingCount' => $pendingCount,
        ]);
    }

    /**
     * Use case: memvalidasi absensi pelatih -> tampilkan form tambah laporan.
     */
    public function create()
    {
        return view('pembina.validasi-pelatih.create', [
            'pelatihs' => $this->pelatihUntukPembina(),
        ]);
    }

    /**
     * Simpan laporan absensi pelatih baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelatih' => ['required', 'exists:pelatih,id_pelatih'],
            'tanggal_absensi' => ['required', 'date'],
            'kegiatan' => ['nullable', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
        ], [
            'id_pelatih.required' => 'Pelatih wajib dipilih.',
            'tanggal_absensi.required' => 'Tanggal wajib diisi.',
        ]);

        AbsensiPelatih::create($validated + ['status_validasi' => 'Menunggu']);

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Laporan absensi pelatih berhasil ditambahkan.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> arahkan ke halaman/form pengeditan.
     */
    public function edit(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->load('pelatih');

        return view('pembina.validasi-pelatih.edit', [
            'absensiPelatih' => $absensiPelatih,
            'pelatihs' => $this->pelatihUntukPembina(),
        ]);
    }

    /**
     * Simpan perubahan laporan absensi pelatih.
     */
    public function update(Request $request, AbsensiPelatih $absensiPelatih)
    {
        $validated = $request->validate([
            'id_pelatih' => ['required', 'exists:pelatih,id_pelatih'],
            'tanggal_absensi' => ['required', 'date'],
            'kegiatan' => ['nullable', 'string', 'max:100'],
            'status_validasi' => ['required', 'in:Menunggu,Divalidasi,Ditolak'],
        ], [
            'id_pelatih.required' => 'Pelatih wajib dipilih.',
            'tanggal_absensi.required' => 'Tanggal wajib diisi.',
        ]);

        $absensiPelatih->update($validated);

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Laporan absensi pelatih berhasil diperbarui.');
    }

    /**
     * Hapus laporan absensi pelatih (dikonfirmasi via popup di halaman dashboard).
     */
    public function destroy(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->delete();

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Laporan absensi pelatih berhasil dihapus.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> setujui.
     */
    public function setujui(AbsensiPelatih $absensiPelatih)
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        $absensiPelatih->update([
            'status_validasi' => 'Divalidasi',
            'id_pembina_validasi' => $pembina->id_pembina ?? null,
            'tgl_validasi' => now(),
        ]);

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Absensi pelatih berhasil divalidasi.');
    }

    /**
     * Use case: memvalidasi absensi pelatih -> tolak.
     */
    public function tolak(AbsensiPelatih $absensiPelatih)
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        $absensiPelatih->update([
            'status_validasi' => 'Ditolak',
            'id_pembina_validasi' => $pembina->id_pembina ?? null,
            'tgl_validasi' => now(),
        ]);

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Absensi pelatih ditolak.');
    }
}