<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri_ekskuls';

    protected $primaryKey = 'id_galeri';

    protected $fillable = [
        'id_ekskul',
        'judul',
        'keterangan',
        'foto',
        'urutan',
    ];

    protected $appends = ['foto_url'];

    /**
     * URL foto galeri (disimpan admin lewat storage disk "public").
     */
    public function getFotoUrlAttribute(): string
    {
        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return asset('storage/' . ltrim($this->foto, '/'));
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'id_ekskul', 'id_ekskul');
    }
}