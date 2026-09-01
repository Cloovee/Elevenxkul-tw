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
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'medsos',
        'alamat',
    ];

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