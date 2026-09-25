<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $barisError = [];
    protected $totalBaris = 0;
    protected $idKelasDefault;

    public function __construct($idKelasDefault = null)
    {
        $this->idKelasDefault = $idKelasDefault;
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

        // Tentukan kelas: cari dari tingkat+jurusan+rombel di Excel.
        // WAJIB sudah ada di database (tidak lagi auto-create) — kalau belum
        // terdaftar, baris ini gagal diimport.
        // Kalau tingkat/jurusan/rombel kosong semua di Excel, pakai kelas
        // default dari form.
        $idKelas = $this->idKelasDefault;

        $tingkat = trim($row['tingkat'] ?? '');
        $jurusan = trim($row['jurusan'] ?? '');
        $rombel  = trim($row['rombel'] ?? '');

        $adaSebagian = $tingkat !== '' || $jurusan !== '' || $rombel !== '';
        $lengkap = $tingkat !== '' && $jurusan !== '' && $rombel !== '';

        if ($adaSebagian && !$lengkap) {
            $this->barisError[] = [
                'baris' => $this->totalBaris + 1,
                'error' => "Kolom tingkat/jurusan/rombel harus diisi lengkap semua atau dikosongkan semua (pakai kelas default)"
            ];
            return null;
        }

        if ($lengkap) {
            // Catatan: header kolom Excel tetap "jurusan" (baca App\Http\Controllers\Admin\SiswaController::downloadTemplate)
            // supaya template yang sudah beredar tidak rusak. Yang berubah cuma nama kolom di tabel kelas.
            $kelas = Kelas::where('tingkat', $tingkat)
                           ->where('program_keahlian', $jurusan)
                           ->where('rombel', $rombel)
                           ->first();

            if (!$kelas) {
                $this->barisError[] = [
                    'baris' => $this->totalBaris + 1,
                    'error' => "Kelas {$tingkat} {$jurusan} - {$rombel} belum terdaftar. Tambahkan dulu di menu Kelola Kelas sebelum import."
                ];
                return null;
            }

            $idKelas = $kelas->id_kelas;
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
            'nama_siswa' => $row['nama_siswa'],
            'jk'         => $row['jenis_kelamin'],
            'agama'      => $row['agama'] ?? null,
            'nomor_hp'   => $row['nomor_hp'] ?? null,
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
            '*.nama_siswa' => ['required', 'string', 'max:100'],
            '*.jenis_kelamin' => ['required', 'in:L,P'],
        ];
    }

    public function getBarisError()
    {
        return $this->barisError;
    }
}