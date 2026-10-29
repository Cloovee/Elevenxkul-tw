<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPelatih;
use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanKegiatanController extends Controller
{
    /**
     * GET /laporan-kegiatan
     * Ketua memilih bulan, lalu melihat pratinjau kegiatan pelatih bulan itu.
     */
    public function index(Request $request): View
    {
        $data = $this->dataLaporan($request);

        return view('dashboard-ketua.laporan-kegiatan.index', $data);
    }

    /**
     * GET /laporan-kegiatan/cetak?bulan=2026-10
     * Halaman siap cetak (mengikuti TEMPLATE_LAPORAN_KEGIATAN_PELATIH.docx).
     */
    public function cetak(Request $request): View
    {
        $data = $this->dataLaporan($request);

        return view('dashboard-ketua.laporan-kegiatan.cetak', $data);
    }

    /**
     * Kumpulkan data laporan bulanan dari absensi pelatih.
     *
     * - Ekskul diambil dari middleware 'ketua.ekskul' (ekskul yang dipimpin ketua login),
     *   jadi ketua tidak bisa mencetak laporan ekskul lain.
     * - Satu baris absensi pelatih = satu kegiatan = satu foto + deskripsi + tanggal.
     * - Yang dimasukkan: SEMUA absensi bulan itu, apa pun status kehadirannya (hadir/izin/sakit/alpha),
     *   karena ini laporan kegiatan, bukan rekap kehadiran pelatih. Satu-satunya yang dikeluarkan
     *   adalah yang "Ditolak" oleh pembina (yang masih "Menunggu" tetap ikut).
     */
    private function dataLaporan(Request $request): array
    {
        /** @var Ekskul $ekskul */
        $ekskul = $request->attributes->get('ekskul_ketua');
        $ekskul->loadMissing('pelatih', 'pembina');

        $request->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);

        $bulan = Carbon::createFromFormat('Y-m-d', ($request->input('bulan') ?: now()->format('Y-m')) . '-01')
            ->startOfMonth();

        $kegiatan = collect();
        $dikecualikan = collect();

        if ($ekskul->pelatih) {
            // whereDate (bukan whereBetween string) supaya tanggal 31 tidak ikut terpotong
            // kalau kolom tanggal tersimpan lengkap dengan jam ("2026-10-31 00:00:00").
            $semuaBulanIni = AbsensiPelatih::where('id_pelatih', $ekskul->id_pelatih)
                ->whereDate('tanggal_absensi', '>=', $bulan->copy()->startOfMonth()->toDateString())
                ->whereDate('tanggal_absensi', '<=', $bulan->copy()->endOfMonth()->toDateString())
                ->orderBy('tanggal_absensi')
                ->orderBy('id_absensi')
                ->get();

            // Masuk laporan: semua status kehadiran, kecuali yang ditolak pembina.
            $layak = fn (AbsensiPelatih $a) => $a->status_validasi !== 'Ditolak';

            $kegiatan = $semuaBulanIni->filter($layak)->values();

            // Yang ditolak pembina tidak masuk laporan; ditampilkan di halaman pratinjau
            // supaya ketua tahu kenapa absensinya tidak muncul.
            $dikecualikan = $semuaBulanIni->reject($layak)->values()->map(
                fn (AbsensiPelatih $a) => (object) ['absensi' => $a, 'alasan' => 'ditolak oleh pembina']
            );
        }

        // Penanda tangan laporan: pelatih kalau ekskul sudah punya pelatih,
        // kalau belum maka diganti pembina ekskul tersebut.
        $pelatih = $ekskul->pelatih;
        $pembina = $ekskul->pembina;

        $penandatangan = $pelatih
            ? ['nama' => $pelatih->nama_pelatih, 'jabatan' => 'Pelatih Ekstrakurikuler ' . $ekskul->nama_ekskul]
            : ['nama' => $pembina->nama_pembina ?? null, 'jabatan' => 'Pembina Ekstrakurikuler ' . $ekskul->nama_ekskul];

        return [
            'ekskul'    => $ekskul,
            'pelatih'   => $pelatih,
            'pembina'   => $pembina,
            'penandatangan' => $penandatangan,
            'bulan'     => $bulan,
            'bulanInput' => $bulan->format('Y-m'),
            'namaBulan' => $bulan->locale('id')->translatedFormat('F Y'),
            'kegiatan'  => $kegiatan,
            'dikecualikan' => $dikecualikan,
        ];
    }
}