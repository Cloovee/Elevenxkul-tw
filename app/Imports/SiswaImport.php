<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    protected array $barisError = [];
    protected int $totalBaris = 0;
    protected int|string $idKelas;

    /**
     * Semua siswa dari file Excel disimpan ke kelas yang dipilih di form import.
     */
    public function __construct(int|string $idKelas)
    {
        $this->idKelas = $idKelas;
    }

    /**
     * Lewati baris yang NISN, NIS, dan nama siswanya kosong semua.
     */
    public function isEmptyWhen(array $row): bool
    {
        return trim((string) ($row['nisn'] ?? '')) === ''
            && trim((string) ($row['nis'] ?? '')) === ''
            && trim((string) ($row['nama'] ?? '')) === '';
    }

    public function prepareForValidation(array $data, int $index): array
    {
        if (isset($data['nisn'])) {
            $data['nisn'] = (string) $data['nisn'];
        }
        if (isset($data['nis'])) {
            $data['nis'] = (string) $data['nis'];
        }
        if (isset($data['no_hp'])) {
            $data['no_hp'] = (string) $data['no_hp'];
        }
        return $data;
    }

    public function model(array $row)
    {
        $this->totalBaris++;

        $existing = Siswa::where('NISN', $row['nisn'])
                         ->orWhere('NIS', $row['nis'])
                         ->first();

        if ($existing) {
            $this->barisError[] = [
                'baris' => $this->totalBaris + 1,
                'error' => "NISN {$row['nisn']} atau NIS {$row['nis']} sudah terdaftar"
            ];
            return null;
        }

        return new Siswa([
            'id_kelas'   => $this->idKelas,
            'NISN'       => $row['nisn'],
            'NIS'        => $row['nis'],
            'nama_siswa' => $row['nama'],
            'jk'         => $row['jk'],
            'agama'      => $row['agama'] ?? null,
            'nomor_hp'   => $row['no_hp'] ?? null,
            'email'      => $row['email'] ?? null,
            'medsos'     => $row['medsos'] ?? null,
            'alamat'     => $row['alamat'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nisn' => ['required', 'string', 'max:20'],
            '*.nis' => ['required', 'string', 'max:20'],
            '*.nama' => ['required', 'string', 'max:100'],
            '*.jk' => ['required', 'in:L,P'],
            '*.no_hp' => ['nullable', 'string', 'max:15'],
        ];
    }

    public function getBarisError(): array
    {
        return $this->barisError;
    }
}