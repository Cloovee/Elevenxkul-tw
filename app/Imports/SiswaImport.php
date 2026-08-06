<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'nama_siswa' => $row['nama_siswa'],
            'jk' => $row['jk'],
            'agama' => $row['agama'],
            'nomor_hp' => $row['nomor_hp'],
            'email' => $row['email'],
            'alamat' => $row['alamat'],
            'medsos' => $row['medsos'],
        ]);
    }
}