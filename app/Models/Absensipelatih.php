<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPelatih extends Model
{
    protected $table = 'absensi_pelatih';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_pelatih',
        'tanggal_absensi',
        'kegiatan',
        'status_kehadiran',
        'foto_kehadiran',
        'status_validasi',
        'id_pembina_validasi',
        'tgl_validasi',
        'catatan_validasi',
    ];

    protected $casts = [
        'tanggal_absensi' => 'date',
        'tgl_validasi' => 'datetime',
    ];

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_pelatih', 'id_pelatih');
    }

    public function validator()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina_validasi', 'id_pembina');
    }
}
