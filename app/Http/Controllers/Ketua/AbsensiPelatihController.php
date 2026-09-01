<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\Pelatih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiPelatihController extends Controller
{
    /**
     * Use case: Ketua mencatat/mengirim laporan absensi pelatih.
     * Menampilkan form input + riwayat laporan yang sudah dikirim.
     */
    public function index()
    {
        $pelatihs = Pelatih::orderBy('nama_pelatih')->get();

        $riwayat = AbsensiPelatih::with('pelatih')
            ->latest('tanggal_absensi')
            ->paginate(10);

        return view('dashboard-ketua.absensi-pelatih', [
            'pelatihs' => $pelatihs,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Simpan laporan absensi pelatih baru.
     * status_validasi otomatis "Menunggu" dan akan divalidasi oleh Pembina
     * ekskul terkait di halaman validasi-pelatih.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelatih' => ['required', 'exists:pelatih,id_pelatih'],
            'tanggal_absensi' => ['required', 'date'],
            'kegiatan' => ['nullable', 'string', 'max:100'],
            'status_kehadiran' => ['required', 'in:hadir,izin,sakit,alpha'],
            'foto_kehadiran' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_kehadiran')) {
            $validated['foto_kehadiran'] = $request->file('foto_kehadiran')
                ->store('absensi-pelatih', 'public');
        }

        $validated['status_validasi'] = 'Menunggu';

        AbsensiPelatih::create($validated);

        return redirect()
            ->route('ketua.absensi-pelatih')
            ->with('success', 'Absensi pelatih berhasil dikirim, menunggu validasi pembina.');
    }
}