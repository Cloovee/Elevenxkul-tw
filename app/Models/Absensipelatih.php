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

    /**
     * URL foto kehadiran (disk "public"), atau null kalau tidak ada foto.
     * Dipakai di laporan kegiatan bulanan Ketua.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto_kehadiran) {
            return null;
        }

        if (str_starts_with($this->foto_kehadiran, 'http://') || str_starts_with($this->foto_kehadiran, 'https://')) {
            return $this->foto_kehadiran;
        }

        return asset('storage/' . ltrim($this->foto_kehadiran, '/'));
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_pelatih', 'id_pelatih');
    }

    public function validator()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina_validasi', 'id_pembina');
    }
}