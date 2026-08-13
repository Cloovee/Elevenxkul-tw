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
