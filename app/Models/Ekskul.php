<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    protected $table = 'ekskuls';

    protected $primaryKey = 'id_ekskul';

    protected $fillable = [
        'id_pembina',
        'id_pelatih',
        'id_ketua',
        'nama_ekskul',
        'kategori',
        'deskripsi',
        'poster',
        'logo',
    ];

    /**
     * Relasi ke model Pembina
     */
    public function pembina()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina');
    }

    /**
     * Relasi ke model Pelatih (satu ekskul dilatih oleh satu pelatih).
     */
    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'id_pelatih', 'id_pelatih');
    }

    /**
     * Siswa yang jadi Ketua ekskul ini (diisi oleh Admin di CRUD Ekskul).
     */
    public function ketua()
    {
        return $this->belongsTo(Siswa::class, 'id_ketua', 'id_siswa');
    }

    /**
     * Anggota (peserta) yang tergabung di ekskul ini.
     */
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'id_ekskul', 'id_ekskul');
    }

    /**
     * Foto galeri yang diunggah admin untuk ekskul ini.
     */
    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'id_ekskul', 'id_ekskul');
    }

    /**
     * URL poster yang diunggah admin (disk "public"), atau null kalau belum ada.
     */
    public function getPosterUrlAttribute(): ?string
    {
        if (! $this->poster) {
            return null;
        }

        if (str_starts_with($this->poster, 'http://') || str_starts_with($this->poster, 'https://')) {
            return $this->poster;
        }

        return \App\Support\PublicStorage::url($this->poster);
    }

    /**
     * URL logo ekskul yang diunggah admin (disk "public"), atau null kalau belum ada.
     * Dipakai di kop surat laporan cetak.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return \App\Support\PublicStorage::url($this->logo);
    }

    /**
     * Gambar sampul untuk landing page: poster dulu, kalau kosong pakai foto galeri pertama.
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->poster_url ?? $this->galeri->sortBy('urutan')->first()?->foto_url;
    }

}