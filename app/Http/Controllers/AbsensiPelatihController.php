<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPelatih;
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

    /**
     * Use case: memvalidasi absensi pelatih -> halaman daftar & validasi.
     *
     * Catatan: laporan absensi pelatih HANYA dibuat oleh Ketua lewat halaman
     * "Absensi Pelatih" milik Ketua (id_pelatih + status_kehadiran diisi di sana).
     * Pembina tidak menambahkan laporan baru, pembina hanya menampilkan &
     * memvalidasi (setuju/tolak) laporan kehadiran yang masuk.
     */
    public function index(Request $request)
    {
        $pembina = Pembina::where('id_user', Auth::id())->first();

        if (! $pembina) {
            return $this->belumTerhubung();
        }

        $pelatihIds = $pembina->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih');

        // Filter status_validasi lewat query string (?status=Menunggu|Divalidasi|Ditolak),
        // default: tampilkan semua tapi laporan "Menunggu" selalu di atas.
        $status = $request->query('status');

        $query = AbsensiPelatih::with('pelatih')
            ->whereIn('id_pelatih', $pelatihIds);

        if (in_array($status, ['Menunggu', 'Divalidasi', 'Ditolak'], true)) {
            $query->where('status_validasi', $status);
        }

        $laporan = $query
            ->orderByRaw("status_validasi = 'Menunggu' desc")
            ->latest('tanggal_absensi')
            ->paginate(15)
            ->withQueryString();

        $pendingCount = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Menunggu')
            ->count();

        $divalidasiCount = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Divalidasi')
            ->count();

        $ditolakCount = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Ditolak')
            ->count();

        // Ringkasan kehadiran (hadir/izin/sakit/alpha) dari laporan yang masuk,
        // supaya pembina langsung lihat pola melatih/tidaknya tanpa buka satu-satu.
        $kehadiranSummary = AbsensiPelatih::whereIn('id_pelatih', $pelatihIds)
            ->selectRaw('status_kehadiran, count(*) as total')
            ->groupBy('status_kehadiran')
            ->pluck('total', 'status_kehadiran');

        return view('pembina.validasi-pelatih.index', [
            'laporan' => $laporan,
            'pendingCount' => $pendingCount,
            'divalidasiCount' => $divalidasiCount,
            'ditolakCount' => $ditolakCount,
            'kehadiranSummary' => $kehadiranSummary,
            'statusAktif' => $status,
        ]);
    }

    /**
     * Use case: memvalidasi absensi pelatih -> lihat detail laporan sebelum
     * memutuskan status validasi. Pembina hanya boleh mengubah status_validasi
     * dan catatan_validasi, bukan isi laporan kehadiran itu sendiri (itu milik Ketua).
     */
    public function edit(AbsensiPelatih $absensiPelatih)
    {
        $absensiPelatih->load('pelatih');

        return view('pembina.validasi-pelatih.edit', [
            'absensiPelatih' => $absensiPelatih,
        ]);
    }

    /**
     * Simpan keputusan validasi (status_validasi + catatan_validasi) untuk sebuah laporan.
     */
    public function update(Request $request, AbsensiPelatih $absensiPelatih)
    {
        $validated = $request->validate([
            'status_validasi' => ['required', 'in:Menunggu,Divalidasi,Ditolak'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ]);

        $pembina = Pembina::where('id_user', Auth::id())->first();

        $absensiPelatih->update($validated + [
            'id_pembina_validasi' => $pembina->id_pembina ?? $absensiPelatih->id_pembina_validasi,
            'tgl_validasi' => now(),
        ]);

        return redirect()
            ->route('pembina.validasi.index')
            ->with('success', 'Validasi absensi pelatih berhasil disimpan.');
    }

    /**
     * Hapus laporan absensi pelatih (mis. laporan ganda/keliru).
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
