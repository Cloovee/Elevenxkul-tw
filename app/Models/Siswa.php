<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    //
    protected $table = 'siswas';
    protected $primaryKey = 'id_siswa';
    
    protected $fillable = [
        'nisn',
        'nis',
        'nama_siswa',
        'jk',
        'agama',
        'nomor_hp',
        'email',
        'alamat',
        'medsos'
    ];
}
