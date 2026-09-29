<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export data siswa dengan urutan kolom yang sama persis dengan template
 * import, jadi hasil export bisa langsung diedit lalu diimport lagi.
 * Kolom "Kelas" berisi id_kelas.
 */
class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $idKelas;

    public function __construct($idKelas = null)
    {
        $this->idKelas = $idKelas;
    }

    public function collection()
    {
        return Siswa::query()
            ->when($this->idKelas, fn ($q) => $q->where('id_kelas', $this->idKelas))
            ->orderBy('nama_siswa')
            ->get();
    }

    public function headings(): array
    {
        return ['NISN', 'NIS', 'Nama', 'JK', 'Agama', 'Kelas', 'No. HP', 'Email', 'Medsos', 'Alamat'];
    }

    public function map($siswa): array
    {
        return [
            (string) $siswa->NISN,
            (string) $siswa->NIS,
            $siswa->nama_siswa,
            $siswa->jk,
            $siswa->agama,
            $siswa->id_kelas,
            $siswa->nomor_hp,
            $siswa->email,
            $siswa->medsos,
            $siswa->alamat,
        ];
    }
}