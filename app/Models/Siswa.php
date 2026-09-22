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
        return $this->kelas ? $this->kelas->nama_kelas : '-';
    }

    /**
     * Akun login siswa ini (kalau dia dijadikan Ketua oleh Admin di CRUD User).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Ekskul yang dipimpin siswa ini sebagai Ketua (kalau ada).
     * Dipakai buat nentuin ekskul mana yang boleh dikelola pas dia login sebagai Ketua.
     */
    public function ekskulDipimpin()
    {
        return $this->hasOne(Ekskul::class, 'id_ketua', 'id_siswa');
    }
}