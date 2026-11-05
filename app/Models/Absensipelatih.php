<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPelatih extends Model
{
    protected $table = 'absensi_pelatih';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_pelatih',
        'id_pembina',
        'id_ekskul',
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

        return \App\Support\PublicStorage::url($this->foto_kehadiran);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_pelatih', 'id_pelatih');
    }

    /**
     * Pembina pengunggah, hanya terisi bila ekskul tidak punya pelatih
     * (pembina menggantikan pelatih untuk upload foto kegiatan).
     */
    public function pembina()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina', 'id_pembina');
    }

    /** True bila baris ini diunggah pembina sebagai pengganti pelatih. */
    public function getOlehPembinaAttribute(): bool
    {
        return $this->id_pelatih === null && $this->id_pembina !== null;
    }

    /** Nama petugas yang tercatat: pelatih, atau pembina bila menggantikan pelatih. */
    public function getNamaPetugasAttribute(): string
    {
        return $this->pelatih->nama_pelatih
            ?? $this->pembina->nama_pembina
            ?? '-';
    }

    /**
     * Semua absensi/foto kegiatan milik satu ekskul:
     *  - baris pelatih ekskul saat ini (id_pelatih), dan
     *  - baris pembina untuk ekskul ini (saat ekskul belum/tidak punya pelatih).
     * Baris pembina tetap ikut laporan walau kelak ekskul mendapat pelatih.
     */
    public function scopeUntukEkskul($query, Ekskul $ekskul)
    {
        return $query->where(function ($q) use ($ekskul) {
            if ($ekskul->id_pelatih) {
                $q->where('id_pelatih', $ekskul->id_pelatih);
                $q->orWhere(function ($q2) use ($ekskul) {
                    $q2->whereNull('id_pelatih')->where('id_ekskul', $ekskul->id_ekskul);
                });
            } else {
                $q->whereNull('id_pelatih')->where('id_ekskul', $ekskul->id_ekskul);
            }
        });
    }

    public function validator()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina_validasi', 'id_pembina');
    }
}