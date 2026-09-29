<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pembina extends Model
{
    protected $table = 'pembina';
    protected $primaryKey = 'id_pembina';

    protected $fillable = [
        'id_user',
        'nama_pembina',
        'foto',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'medsos',
        'alamat',
    ];

    protected $appends = ['foto_url', 'inisial'];

    /**
     * URL foto profil pembina (disimpan admin lewat storage disk "public").
     * Dipakai di navbar/sidebar & halaman profil untuk menggantikan huruf
     * inisial ("P") begitu admin mengunggah foto.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        // Sudah berupa URL lengkap (mis. disimpan sebagai link eksternal)
        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return asset('storage/' . ltrim($this->foto, '/'));
    }

    /**
     * Inisial dari nama pembina, dipakai sebagai fallback avatar
     * selama admin belum mengunggah foto.
     */
    public function getInisialAttribute(): string
    {
        $nama = trim((string) $this->nama_pembina);

        if ($nama === '') {
            return 'P';
        }

        return collect(explode(' ', $nama))
            ->filter()
            ->map(fn ($s) => strtoupper($s[0]))
            ->take(2)
            ->implode('');
    }

    // Akun login milik pembina ini
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Ekskul yang dibina oleh pembina ini
    public function ekskuls()
    {
        return $this->hasMany(Ekskul::class, 'id_pembina', 'id_pembina');
    }

    /**
     * Semua pelatih dari ekskul-ekskul yang dibina oleh pembina ini.
     * Dipakai untuk membatasi data pada halaman validasi absensi pelatih.
     */
    public function pelatihs()
    {
        return Pelatih::whereIn(
            'id_pelatih',
            $this->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih')
        );
    }

    /**
     * Laporan absensi pelatih yang perlu/​sudah divalidasi oleh pembina ini
     * (absensi milik pelatih dari ekskul yang dibina).
     */
    public function absensiPelatih()
    {
        return AbsensiPelatih::whereIn(
            'id_pelatih',
            $this->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih')
        );
    }

    /**
     * Kumpulan notifikasi absensi untuk popup lonceng di dashboard pembina.
     * Semua data dibatasi hanya untuk ekskul yang dibina pembina ini.
     *
     * Struktur hasil:
     *  - total            : jumlah item yang perlu ditindaklanjuti (angka di badge lonceng)
     *  - hari_ini         : ringkasan absensi peserta hari ini (hadir/izin/sakit/alpha)
     *  - menunggu         : laporan absensi pelatih berstatus "Menunggu" (max 5) + jumlah totalnya
     *  - absensi_terbaru  : absensi peserta yang baru masuk 7 hari terakhir, per ekskul & tanggal
     *  - perlu_perhatian  : peserta dengan alpha >= 3x dalam 30 hari terakhir
     *  - belum_absen      : ekskul yang belum punya absensi peserta dalam 7 hari terakhir
     */
    public function notifikasiAbsensi(): array
    {
        $ekskuls = $this->ekskuls()->with('pelatih')->get();
        $ekskulIds = $ekskuls->pluck('id_ekskul');
        $pelatihIds = $ekskuls->pluck('id_pelatih')->filter()->unique()->values();

        // ---- 1. Laporan absensi pelatih yang menunggu validasi ----
        $menungguQuery = AbsensiPelatih::with('pelatih')
            ->whereIn('id_pelatih', $pelatihIds)
            ->where('status_validasi', 'Menunggu');

        $menungguTotal = (clone $menungguQuery)->count();

        $menunggu = $menungguQuery
            ->latest('tanggal_absensi')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id_absensi,
                'pelatih' => $a->pelatih->nama_pelatih ?? '-',
                'ekskul' => $ekskuls->where('id_pelatih', $a->id_pelatih)->pluck('nama_ekskul')->implode(', ') ?: '-',
                'kegiatan' => $a->kegiatan,
                'status' => $a->status_kehadiran,
                'ada_foto' => (bool) $a->foto_kehadiran,
                'tanggal' => $a->tanggal_absensi,
                'dikirim' => $a->created_at,
                'url' => route('pembina.validasi.edit', $a->id_absensi),
            ]);

        // ---- 2. Ringkasan absensi peserta hari ini ----
        $hariIni = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        AbsensiPeserta::query()
            ->join('data_anggota', 'data_anggota.id_anggota', '=', 'absensi_peserta.id_anggota')
            ->whereIn('data_anggota.id_ekskul', $ekskulIds)
            ->whereDate('absensi_peserta.tanggal_absensi', Carbon::today())
            ->selectRaw('absensi_peserta.status_kehadiran as status, COUNT(*) as total')
            ->groupBy('absensi_peserta.status_kehadiran')
            ->pluck('total', 'status')
            ->each(function ($total, $status) use (&$hariIni) {
                if (isset($hariIni[$status])) {
                    $hariIni[$status] = (int) $total;
                }
            });

        // ---- 3. Absensi peserta terbaru (7 hari), dikelompokkan per ekskul + tanggal ----
        $absensiTerbaru = AbsensiPeserta::query()
            ->join('data_anggota', 'data_anggota.id_anggota', '=', 'absensi_peserta.id_anggota')
            ->join('ekskuls', 'ekskuls.id_ekskul', '=', 'data_anggota.id_ekskul')
            ->whereIn('data_anggota.id_ekskul', $ekskulIds)
            ->whereDate('absensi_peserta.tanggal_absensi', '>=', Carbon::today()->subDays(7))
            ->selectRaw("
                ekskuls.nama_ekskul as ekskul,
                absensi_peserta.tanggal_absensi as tanggal,
                COUNT(*) as total,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'izin'  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensi_peserta.status_kehadiran = 'alpha' THEN 1 ELSE 0 END) as alpha
            ")
            ->groupBy('ekskuls.id_ekskul', 'ekskuls.nama_ekskul', 'absensi_peserta.tanggal_absensi')
            ->orderByDesc('absensi_peserta.tanggal_absensi')
            ->limit(5)
            ->get()
            ->map(fn ($r) => [
                'ekskul' => $r->ekskul,
                'tanggal' => Carbon::parse($r->tanggal),
                'total' => (int) $r->total,
                'hadir' => (int) $r->hadir,
                'izin' => (int) $r->izin,
                'sakit' => (int) $r->sakit,
                'alpha' => (int) $r->alpha,
            ]);

        // ---- 4. Peserta yang perlu perhatian: alpha >= 3x dalam 30 hari ----
        $batasAlpha = 3;
        $perluPerhatian = Peserta::with('siswa.kelas', 'ekskul')
            ->whereIn('id_ekskul', $ekskulIds)
            ->withCount(['absensi as alpha_count' => fn ($q) => $q
                ->where('status_kehadiran', 'alpha')
                ->whereDate('tanggal_absensi', '>=', Carbon::today()->subDays(30)),
            ])
            ->having('alpha_count', '>=', $batasAlpha)
            ->orderByDesc('alpha_count')
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'nama' => $p->nama,
                'kelas' => $p->kelas,
                'ekskul' => $p->ekskul->nama_ekskul ?? '-',
                'alpha' => (int) $p->alpha_count,
            ]);

        // ---- 5. Ekskul yang belum ada absensi peserta 7 hari terakhir ----
        $belumAbsen = $ekskuls->filter(function ($e) {
            return ! AbsensiPeserta::query()
                ->join('data_anggota', 'data_anggota.id_anggota', '=', 'absensi_peserta.id_anggota')
                ->where('data_anggota.id_ekskul', $e->id_ekskul)
                ->whereDate('absensi_peserta.tanggal_absensi', '>=', Carbon::today()->subDays(7))
                ->exists();
        })->map(fn ($e) => [
            'ekskul' => $e->nama_ekskul,
            'anggota' => Peserta::where('id_ekskul', $e->id_ekskul)->count(),
        ])->values();

        return [
            'total' => $menungguTotal + $perluPerhatian->count() + $belumAbsen->count(),
            'batas_alpha' => $batasAlpha,
            'hari_ini' => $hariIni,
            'menunggu_total' => $menungguTotal,
            'menunggu' => $menunggu,
            'absensi_terbaru' => $absensiTerbaru,
            'perlu_perhatian' => $perluPerhatian,
            'belum_absen' => $belumAbsen,
        ];
    }
}