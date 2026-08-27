<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Merepresentasikan "peserta" ekskul dari sisi pembina, yaitu satu baris
 * keanggotaan siswa pada sebuah ekskul (tabel data_anggota).
 */
class Peserta extends Model
{
    protected $table = 'data_anggota';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_siswa',
        'id_ekskul',
        'tanggal_bergabung',
        'status',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'id_ekskul', 'id_ekskul');
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiPeserta::class, 'id_anggota', 'id_anggota');
    }

    public function nilai()
    {
        return $this->hasMany(NilaiPeserta::class, 'id_anggota', 'id_anggota');
    }

    /**
     * Nama siswa, dipakai luas di view dashboard pembina.
     */
    public function getNamaAttribute(): string
    {
        return $this->siswa->nama_siswa ?? '-';
    }

    /**
     * Nama kelas siswa (jurusan + rombel), dipakai di view dashboard pembina.
     */
    public function getKelasAttribute(): ?string
    {
        $kelas = $this->siswa->kelas ?? null;

        return $kelas ? $kelas->nama_kelas : null;
    }
}
