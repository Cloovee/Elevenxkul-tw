<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use App\Models\NilaiPeserta;
use App\Models\Pembina;
use Illuminate\Http\Request;

class EkskulMonitoringController extends Controller
{
    /** Jumlah baris laporan kegiatan yang ditampilkan di halaman detail (cetak = semua). */
    private const BATAS_KEGIATAN_DETAIL = 10;

    public function index(Request $request)
    {
        $ekskuls = Ekskul::with(['pembina', 'ketua'])
            ->withCount('peserta')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_ekskul', 'like', "%{$search}%");
            })
            ->when($request->kategori, function ($query, $kategori) {
                $query->where('kategori', $kategori);
            })
            ->when($request->pembina, function ($query, $pembina) {
                $pembina === 'none'
                    ? $query->whereNull('id_pembina')
                    : $query->where('id_pembina', $pembina);
            })
            ->orderBy('nama_ekskul')
            ->paginate(9)
            ->withQueryString();

        $pembinas = Pembina::orderBy('nama_pembina')->get(['id_pembina', 'nama_pembina']);

        return view('admin.monitoring-ekskul.index', compact('ekskuls', 'pembinas'));
    }

    public function show(Ekskul $ekskul)
    {
        $data = $this->dataEkskul($ekskul, self::BATAS_KEGIATAN_DETAIL);

        return view('admin.monitoring-ekskul.show', $data);
    }

    public function cetak(Ekskul $ekskul)
    {
        $data = $this->dataEkskul($ekskul, null);

        return view('admin.monitoring-ekskul.cetak', $data);
    }

    /**
     * Kumpulkan seluruh data satu ekskul (dipakai halaman detail & cetak).
     *
     * @param  int|null  $batasKegiatan  null = semua tanggal kegiatan
     */
    private function dataEkskul(Ekskul $ekskul, ?int $batasKegiatan): array
    {
        $ekskul->load(['pembina', 'pelatih', 'ketua.kelas', 'peserta.siswa.kelas']);

        $peserta = $ekskul->peserta
            ->sortBy(fn ($p) => mb_strtolower($p->nama))
            ->values();

        // Laporan kegiatan: belum ada modul/tabel khusus. Sumber yang ada adalah
        // absensi_peserta (deskripsi_kegiatan per tanggal) -> diringkas per tanggal.
        $kegiatanQuery = AbsensiPeserta::query()
            ->join('data_anggota', 'data_anggota.id_anggota', '=', 'absensi_peserta.id_anggota')
            ->where('data_anggota.id_ekskul', $ekskul->id_ekskul)
            ->selectRaw("
                absensi_peserta.tanggal_absensi as tanggal,
                MAX(absensi_peserta.deskripsi_kegiatan) as kegiatan,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'izin'  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'alpha' THEN 1 ELSE 0 END) as alpha
            ")
            ->groupBy('absensi_peserta.tanggal_absensi')
            ->orderByDesc('tanggal');

        $totalKegiatan = AbsensiPeserta::query()
            ->join('data_anggota', 'data_anggota.id_anggota', '=', 'absensi_peserta.id_anggota')
            ->where('data_anggota.id_ekskul', $ekskul->id_ekskul)
            ->distinct()
            ->count('absensi_peserta.tanggal_absensi');

        $kegiatan = $batasKegiatan
            ? $kegiatanQuery->limit($batasKegiatan)->get()
            : $kegiatanQuery->get();

        // Penilaian peserta ekskul ini (tabel penilaian, lewat data_anggota).
        $penilaian = NilaiPeserta::with('peserta.siswa.kelas')
            ->whereHas('peserta', fn ($q) => $q->where('id_ekskul', $ekskul->id_ekskul))
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->sortBy(fn ($n) => mb_strtolower($n->peserta->nama ?? ''))
            ->values();

        return [
            'ekskul' => $ekskul,
            'peserta' => $peserta,
            'kegiatan' => $kegiatan,
            'totalKegiatan' => $totalKegiatan,
            'batasKegiatan' => $batasKegiatan,
            'penilaian' => $penilaian,
        ];
    }
}