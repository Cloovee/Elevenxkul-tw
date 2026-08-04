<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'spesialisasi',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sesi()
    {
        return $this->hasMany(Sesi::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiPelatih::class);
    }
}