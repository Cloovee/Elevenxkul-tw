<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    protected $table = 'pelatih';

    protected $primaryKey = 'id_pelatih';

    protected $fillable = [
        'nama_pelatih',
        'foto',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'alamat',
        'medsos',
        'sertifikat_path',
    ];

    /**
     * URL foto pelatih (diunggah pembina lewat storage disk "public"),
     * atau null kalau belum ada. Pola sama dengan Pembina::getFotoUrlAttribute().
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return asset('storage/' . ltrim($this->foto, '/'));
    }

    /**
     * Inisial nama pelatih, dipakai sebagai fallback avatar kalau belum ada foto.
     */
    public function getInisialAttribute(): string
    {
        $nama = trim((string) $this->nama_pelatih);

        if ($nama === '') {
            return 'P';
        }

        return collect(explode(' ', $nama))
            ->filter()
            ->map(fn ($s) => strtoupper($s[0]))
            ->take(2)
            ->implode('');
    }

    public function ekskuls()
    {
        return $this->hasMany(Ekskul::class, 'id_pelatih', 'id_pelatih');
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiPelatih::class, 'id_pelatih', 'id_pelatih');
    }

    /**
     * Alias supaya kompatibel dengan view yang sebelumnya memakai $pelatih->nama.
     */
    public function getNamaAttribute(): string
    {
        return $this->nama_pelatih;
    }
}