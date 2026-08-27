<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Nilai peserta ekskul, disimpan di tabel `penilaian`.
 */
class NilaiPeserta extends Model
{
    protected $table = 'penilaian';

    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_anggota',
        'nilai',
        'tahun_ajaran',
        'semester',
        'catatan_pembina',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_anggota', 'id_anggota');
    }
}
