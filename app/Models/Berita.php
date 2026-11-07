<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Berita/pengumuman dari Pembina untuk Ketua ekskul.
 * Dibuat lewat web (role Pembina), dibaca Ketua di aplikasi mobile.
 */
class Berita extends Model
{
    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'id_ekskul',
        'id_pembina',
        'judul',
        'isi',
        'penting',
    ];

    protected function casts(): array
    {
        return [
            'penting' => 'boolean',
        ];
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'id_ekskul', 'id_ekskul');
    }

    public function pembina()
    {
        return $this->belongsTo(Pembina::class, 'id_pembina', 'id_pembina');
    }
}
