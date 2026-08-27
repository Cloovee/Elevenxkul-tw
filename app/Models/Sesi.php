<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelatih_id',
        'nama_sesi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function absensiPeserta()
    {
        return $this->hasMany(AbsensiPeserta::class);
    }

    public function absensiPelatih()
    {
        return $this->hasMany(AbsensiPelatih::class);
    }

    public function nilai()
    {
        return $this->hasMany(NilaiPeserta::class);
    }
}