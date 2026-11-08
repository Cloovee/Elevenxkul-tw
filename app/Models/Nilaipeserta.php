<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Nilai peserta ekskul per semester, disimpan di tabel `penilaian`.
 *
 * Penilaian berbentuk grade (A/B/C): grade kehadiran (dari absensi) digabung
 * dengan grade keaktifan (dari angka yang diisi pembina) -> grade akhir.
 * Aturannya ada di config/penilaian.php dan App\Support\Penilaian.
 */
class NilaiPeserta extends Model
{
    protected $table = 'penilaian';

    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_anggota',
        'nilai',
        'nilai_keaktifan',
        'jumlah_hadir',
        'jumlah_pertemuan',
        'persen_kehadiran',
        'grade_kehadiran',
        'grade_keaktifan',
        'grade_akhir',
        'tahun_ajaran',
        'semester',
        'catatan_pembina',
    ];

    protected function casts(): array
    {
        return [
            'nilai'            => 'decimal:2',
            'nilai_keaktifan'  => 'decimal:2',
            'persen_kehadiran' => 'decimal:2',
        ];
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_anggota', 'id_anggota');
    }
}
