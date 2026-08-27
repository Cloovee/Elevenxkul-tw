<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use App\Models\Pembina;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AbsensiPesertaController extends Controller
{
    private function pembinaAktif(): ?Pembina
    {
        return Pembina::where('id_user', Auth::id())->first();
    }

    /**
     * Redirect ramah (bukan 404) ketika akun yang login belum
     * terhubung ke data pembina manapun.
     */
    private function belumTerhubung(): RedirectResponse
    {
        return redirect()->route('pembina.dashboard')
            ->with('error', 'Akun Anda belum terhubung dengan data pembina. Hubungi admin untuk menghubungkan akun Anda.');
    }

    private function pesertaUntukPembina()
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();
        $ekskulIds = $pembina ? $pembina->ekskuls()->pluck('id_ekskul') : collect();

        return Peserta::with('siswa.kelas')
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->sortBy('nama')
            ->values();
    }

    /**
     * Use case: melihat absensi peserta -> halaman daftar absensi (terpisah dari dashboard).
     */
    public function index()
    {
        $pembina = $this->pembinaAktif();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $ekskulIds = Ekskul::where('id_pembina', $pembina->id_pembina)->pluck('id_ekskul');
        $anggotaIds = Peserta::whereIn('id_ekskul', $ekskulIds)->pluck('id_anggota');

        $riwayatAbsensi = AbsensiPeserta::with('peserta.siswa.kelas', 'peserta.ekskul')
            ->whereIn('id_anggota', $anggotaIds)
            ->latest('tanggal_absensi')
            ->paginate(15);

        $hadirBulanIni = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
            ->where('status_kehadiran', 'hadir')
            ->whereMonth('tanggal_absensi', now()->month)
            ->count();

        $totalBulanIni = AbsensiPeserta::whereIn('id_anggota', $anggotaIds)
            ->whereMonth('tanggal_absensi', now()->month)
            ->count();

        return view('pembina.absensi-peserta.index', [
            'riwayatAbsensi' => $riwayatAbsensi,
            'hadirBulanIni' => $hadirBulanIni,
            'totalBulanIni' => $totalBulanIni,
        ]);
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
            ->route('pembina.absensi.index')
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
            ->route('pembina.absensi.index')
            ->with('success', 'Data absensi peserta berhasil diperbarui.');
    }

    /**
     * Hapus data absensi peserta.
     */
    public function destroy(AbsensiPeserta $absensiPeserta)
    {
        $absensiPeserta->delete();

        return redirect()
            ->route('pembina.absensi.index')
            ->with('success', 'Data absensi peserta berhasil dihapus.');
    }
}