<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    protected $barisError = [];
    protected $totalBaris = 0;
    protected $idKelasDefault;

    public function __construct($idKelasDefault = null)
    {
        $this->idKelasDefault = $idKelasDefault;
    }

    /**
     * Lewati baris yang NISN, NIS, dan nama siswanya kosong semua -- misalnya
     * baris yang cuma berisi daftar referensi ID Kelas di kolom kanan template.
     */
    public function isEmptyWhen(array $row): bool
    {
        return trim((string) ($row['nisn'] ?? '')) === ''
            && trim((string) ($row['nis'] ?? '')) === ''
            && trim((string) ($row['nama'] ?? '')) === '';
    }

    public function prepareForValidation($data, $index)
    {
        if (isset($data['nisn'])) {
            $data['nisn'] = (string) $data['nisn'];
        }
        if (isset($data['nis'])) {
            $data['nis'] = (string) $data['nis'];
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

        // Tentukan kelas: sekarang langsung dari kolom "Kelas" di Excel
        // (harus cocok dengan id_kelas di tabel kelas -- lihat halaman Kelola
        // Kelas atau sheet "Daftar ID Kelas" di template untuk lihat ID-nya).
        // Kalau kolom ID Kelas kosong, pakai kelas default dari form.
        $idKelasCell = trim((string) ($row['kelas'] ?? ''));

        if ($idKelasCell !== '') {
            $kelas = Kelas::find($idKelasCell);

            if (!$kelas) {
                $this->barisError[] = [
                    'baris' => $this->totalBaris + 1,
                    'error' => "Kelas dengan ID {$idKelasCell} tidak ditemukan. Cek lagi di halaman Kelola Kelas."
                ];
                return null;
            }

            $idKelas = $kelas->id_kelas;
        } else {
            $idKelas = $this->idKelasDefault;
        }

        if (!$idKelas) {
            $this->barisError[] = [
                'baris' => $this->totalBaris + 1,
                'error' => "Kelas tidak ditentukan (tingkat/jurusan/rombel kosong di Excel dan tidak ada kelas default dipilih)"
            ];
            return null;
        }

        return new Siswa([
            'id_kelas'   => $idKelas,
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
            '*.kelas' => ['nullable', 'integer'],
        ];
    }

    public function getBarisError()
    {
        return $this->barisError;
    }
}