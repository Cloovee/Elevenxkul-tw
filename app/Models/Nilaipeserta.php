<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPeserta extends Model
{
    use HasFactory;

    protected $table = 'nilai_peserta';

    protected $fillable = [
        'peserta_id',
        'sesi_id',
        'kategori',
        'nilai',
        'diberikan_oleh',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }

    public function pemberi()
    {
        return $this->belongsTo(User::class, 'diberikan_oleh');
    }
}