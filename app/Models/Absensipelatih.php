<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPelatih extends Model
{
    use HasFactory;

    protected $table = 'absensi_pelatih';

    protected $fillable = [
        'pelatih_id',
        'sesi_id',
        'jam_lapor',
        'status',
        'divalidasi_oleh',
        'divalidasi_pada',
    ];

    protected $casts = [
        'divalidasi_pada' => 'datetime',
    ];

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }
}