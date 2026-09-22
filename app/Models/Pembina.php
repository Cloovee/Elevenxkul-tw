<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembina extends Model
{
    protected $table = 'pembina';
    protected $primaryKey = 'id_pembina';

    protected $fillable = [
        'id_user',
        'nama_pembina',
        'foto',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'medsos',
        'alamat',
    ];

    protected $appends = ['foto_url', 'inisial'];

    /**
     * URL foto profil pembina (disimpan admin lewat storage disk "public").
     * Dipakai di navbar/sidebar & halaman profil untuk menggantikan huruf
     * inisial ("P") begitu admin mengunggah foto.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        // Sudah berupa URL lengkap (mis. disimpan sebagai link eksternal)
        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return asset('storage/' . ltrim($this->foto, '/'));
    }

    /**
     * Inisial dari nama pembina, dipakai sebagai fallback avatar
     * selama admin belum mengunggah foto.
     */
    public function getInisialAttribute(): string
    {
        $nama = trim((string) $this->nama_pembina);

        if ($nama === '') {
            return 'P';
        }

        return collect(explode(' ', $nama))
            ->filter()
            ->map(fn ($s) => strtoupper($s[0]))
            ->take(2)
            ->implode('');
    }

    // Akun login milik pembina ini
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Ekskul yang dibina oleh pembina ini
    public function ekskuls()
    {
        return $this->hasMany(Ekskul::class, 'id_pembina', 'id_pembina');
    }

    /**
     * Semua pelatih dari ekskul-ekskul yang dibina oleh pembina ini.
     * Dipakai untuk membatasi data pada halaman validasi absensi pelatih.
     */
    public function pelatihs()
    {
        return Pelatih::whereIn(
            'id_pelatih',
            $this->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih')
        );
    }

    /**
     * Laporan absensi pelatih yang perlu/​sudah divalidasi oleh pembina ini
     * (absensi milik pelatih dari ekskul yang dibina).
     */
    public function absensiPelatih()
    {
        return AbsensiPelatih::whereIn(
            'id_pelatih',
            $this->ekskuls()->whereNotNull('id_pelatih')->pluck('id_pelatih')
        );
    }
}