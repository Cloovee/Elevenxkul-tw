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
     * Catatan: Absensi Peserta TIDAK bisa ditambahkan/diubah dari sisi Pembina.
     * Data ini murni hasil input dari halaman "Absensi Peserta" milik Ketua.
     * Pembina hanya menampilkan (read-only) riwayat kehadiran peserta di sini.
     * Aksi yang tersisa untuk pembina hanyalah menghapus data yang keliru/ganda.
     */

    /**
     * Hapus data absensi peserta (mis. data ganda/keliru dari input Ketua).
     */
    public function destroy(AbsensiPeserta $absensiPeserta)
    {
        $absensiPeserta->delete();

        return redirect()
            ->route('pembina.absensi.index')
            ->with('success', 'Data absensi peserta berhasil dihapus.');
    }
}