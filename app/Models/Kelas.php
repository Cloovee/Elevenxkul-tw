<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'jurusan',
        'rombel',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Nama kelas gabungan, mis. "RPL XII".
     */
    public function getNamaKelasAttribute(): string
    {
        return trim($this->jurusan.' '.$this->rombel);
    }
}
