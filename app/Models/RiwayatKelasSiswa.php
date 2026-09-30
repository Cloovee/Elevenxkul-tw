<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satu baris = kondisi satu siswa pada satu Tahun Ajaran (hasil Finalisasi
 * Perubahan Tahun Ajaran). Ini adalah histori -- tidak pernah diubah lagi
 * setelah dibuat, kecuali oleh proses finalisasi ulang Tahun Ajaran yang sama.
 */
class RiwayatKelasSiswa extends Model
{
    protected $table = 'riwayat_kelas_siswa';
    protected $primaryKey = 'id_riwayat';

    public const KEPUTUSAN_NAIK_KELAS = 'naik_kelas';
    public const KEPUTUSAN_TIDAK_NAIK = 'tidak_naik';
    public const KEPUTUSAN_LULUS = 'lulus';
    public const KEPUTUSAN_KELUAR = 'keluar';

    public const KEPUTUSANS = [
        self::KEPUTUSAN_NAIK_KELAS,
        self::KEPUTUSAN_TIDAK_NAIK,
        self::KEPUTUSAN_LULUS,
        self::KEPUTUSAN_KELUAR,
    ];

    protected $fillable = [
        'id_siswa',
        'id_tahun_ajaran',
        'id_kelas',
        'status',
        'keputusan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}