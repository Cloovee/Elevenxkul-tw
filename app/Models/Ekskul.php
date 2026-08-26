<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    protected $table = 'ekskuls';
    protected $primaryKey = 'id_ekskul';

    protected $fillable = [
        'nama_ekskul',
        'kategori',
        'deskripsi',
        'id_pembina',
        'id_pelatih',
    ];

    /**
     * Relasi ke model Pembina
     */
    public function pembina()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina');
    }

}