<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPeserta extends Model
{
    use HasFactory;

    protected $table = 'absensi_peserta';

    protected $fillable = [
        'peserta_id',
        'sesi_id',
        'jam_hadir',
        'status',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }
}