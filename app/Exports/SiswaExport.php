<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export data siswa dengan urutan kolom yang sama persis dengan template
 * import (tanpa kolom Kelas), jadi hasil export bisa langsung diedit lalu
 * diimport lagi. Kelas tujuan dipilih lewat dropdown di form import.
 */
class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected int|string|null $idKelas;

    public function __construct(int|string|null $idKelas = null)
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
        return ['NISN', 'NIS', 'Nama', 'JK', 'Agama', 'No. HP', 'Email', 'Medsos', 'Alamat'];
    }

    public function map($siswa): array
    {
        return [
            (string) $siswa->NISN,
            (string) $siswa->NIS,
            $siswa->nama_siswa,
            $siswa->jk,
            $siswa->agama,
            (string) $siswa->nomor_hp,
            $siswa->email,
            $siswa->medsos,
            $siswa->alamat,
        ];
    }
}