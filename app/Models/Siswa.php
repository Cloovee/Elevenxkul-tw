<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_kelas',
        'id_user',
        'NISN',
        'NIS',
        'nama_siswa',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'medsos',
        'alamat',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function dataAnggota()
    {
        return $this->hasMany(Peserta::class, 'id_siswa', 'id_siswa');
    }
}
