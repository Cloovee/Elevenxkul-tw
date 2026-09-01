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
        'nama_ekskul',
        'kategori',
        'deskripsi',
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
     * Anggota (peserta) yang tergabung di ekskul ini.
     */
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'id_ekskul', 'id_ekskul');
    }

}
