<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPeserta extends Model
{
    protected $table = 'absensi_peserta';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_anggota',
        'tanggal_absensi',
        'status_kehadiran',
        'deskripsi_kegiatan',
    ];

    protected $casts = [
        'tanggal_absensi' => 'date',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_anggota', 'id_anggota');
    }
}
