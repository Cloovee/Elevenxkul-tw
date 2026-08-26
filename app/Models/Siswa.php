<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    
    protected $fillable = [
        'id_kelas',
        'NISN',
        'NIS',
        'nama_siswa',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'alamat',
        'medsos'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function getNamaKelasAttribute()
    {
        return $this->kelas ? $this->kelas->jurusan . ' - ' . $this->kelas->rombel : '-';
    }
}