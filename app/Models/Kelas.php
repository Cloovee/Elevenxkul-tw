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
        'program_keahlian',
        'rombel',
    ];

    public const TINGKAT_OPTIONS = ['10', '11', '12'];

    public const PROGRAM_KEAHLIAN_PER_TINGKAT = [
        '10' => ['PPLG', 'MPLB', 'AKL', 'TJKT', 'PM', 'DKV'],
        '11' => ['RPL', 'MLOG', 'MP', 'AK', 'TKJ', 'BR', 'DKV'],
        '12' => ['RPL', 'MLOG', 'MP', 'AK', 'TKJ', 'BR', 'DKV'],
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function getNamaKelasAttribute(): string
    {
        return trim("{$this->tingkat} {$this->program_keahlian} - {$this->rombel}");
    }

    /**
     * Kode rombel ringkas, dipakai di kolom "Rombel" file Excel import/export.
     * Contoh: tingkat 10 + TJKT + rombel 1 => "10TJKT1".
     */
    public function getKodeRombelAttribute(): string
    {
        return static::normalisasiKode("{$this->tingkat}{$this->program_keahlian}{$this->rombel}");
    }

    /**
     * Samakan format penulisan rombel supaya "10 TJKT - 1", "10tjkt1",
     * dan "10TJKT1" dianggap sama: huruf besar, hanya huruf & angka.
     */
    public static function normalisasiKode(?string $kode): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $kode));
    }
}