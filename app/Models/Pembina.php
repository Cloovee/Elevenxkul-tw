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

    /**
     * URL publik foto profil, atau null jika belum ada foto yang diunggah.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto)
            ? asset('storage/'.$this->foto)
            : null;
    }

    /**
     * Inisial nama, dipakai sebagai avatar cadangan saat belum ada foto.
     */
    public function getInisialAttribute(): string
    {
        $nama = trim((string) $this->nama_pembina);

        if ($nama === '') {
            return 'P';
        }

        $kata = preg_split('/\s+/', $nama);
        $inisial = strtoupper(substr($kata[0], 0, 1));

        if (count($kata) > 1) {
            $inisial .= strtoupper(substr(end($kata), 0, 1));
        }

        return $inisial;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function ekskuls()
    {
        return $this->hasMany(Ekskul::class, 'id_pembina', 'id_pembina');
    }

    public function absensiPelatihDivalidasi()
    {
        return $this->hasMany(AbsensiPelatih::class, 'id_pembina_validasi', 'id_pembina');
    }
}
