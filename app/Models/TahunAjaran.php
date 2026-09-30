<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_SELESAI = 'selesai';

    public const STATUSES = [
        self::STATUS_AKTIF,
        self::STATUS_SELESAI,
    ];

    protected $fillable = [
        'tahun_ajaran',
        'status',
    ];

    public function riwayatKelasSiswa()
    {
        return $this->hasMany(RiwayatKelasSiswa::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }
}