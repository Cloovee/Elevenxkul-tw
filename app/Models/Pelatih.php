<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    protected $table = 'pelatih';

    protected $primaryKey = 'id_pelatih';

    protected $fillable = [
        'nama_pelatih',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'alamat',
        'medsos',
        'sertifikat_path',
    ];

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
