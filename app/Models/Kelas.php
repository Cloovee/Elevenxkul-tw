<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'tingkat',
        'jurusan',
        'rombel',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function getNamaKelasAttribute(): string
    {
        return trim("{$this->tingkat} {$this->jurusan} - {$this->rombel}");
    }
}