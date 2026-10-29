<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Import anggota ke ekskul yang dipimpin Ketua.
 *
 * Beda dengan import Siswa milik Admin: di sini TIDAK membuat siswa baru.
 * Tiap baris dicocokkan ke siswa yang SUDAH ada di Data Siswa lewat NIS
 * (atau NISN kalau NIS kosong), lalu didaftarkan jadi anggota ekskul ini.
 * Kolom lain (Nama, JK, Rombel) hanya informasi dan diabaikan,
 * jadi file cukup berisi dua kolom: NIS dan Nama.
 *
 * Kolom opsional: Status (aktif / nonaktif / keluar, default aktif) dan
 * Tanggal Bergabung (default hari ini).
 */
class AnggotaImport implements OnEachRow, WithHeadingRow
{
    private const STATUS_VALID = ['aktif', 'nonaktif', 'keluar'];

    /** "NIS" => id_siswa, "NISN" => id_siswa (di-load sekali, bukan query per baris) */
    protected array $petaNis = [];
    protected array $petaNisn = [];

    /** id_siswa yang sudah jadi anggota ekskul ini */
    protected array $sudahAnggota = [];

    /** id_siswa yang sudah muncul di file ini (deteksi dobel dalam file) */
    protected array $diFile = [];

    protected int $berhasil = 0;
    protected int $sudahJadiAnggota = 0;
    protected array $barisError = [];

    public function __construct(protected int $idEkskul)
    {
        foreach (Siswa::select('id_siswa', 'NIS', 'NISN')->get() as $s) {
            if ((string) $s->NIS !== '') {
                $this->petaNis[(string) $s->NIS] = $s->id_siswa;
            }
            if ((string) $s->NISN !== '') {
                $this->petaNisn[(string) $s->NISN] = $s->id_siswa;
            }
        }

        $this->sudahAnggota = array_flip(
            Peserta::where('id_ekskul', $idEkskul)->pluck('id_siswa')->all()
        );
    }

    public function getJumlahBerhasil(): int
    {
        return $this->berhasil;
    }

    public function getJumlahSudahAnggota(): int
    {
        return $this->sudahJadiAnggota;
    }

    /** @return array<int, array{baris:int, error:string}> */
    public function getBarisError(): array
    {
        return $this->barisError;
    }

    public function onRow(Row $row): void
    {
        $baris = $row->getIndex();
        $data  = $row->toArray();

        $nis  = $this->teks($data['nis'] ?? null);
        $nisn = $this->teks($data['nisn'] ?? null);

        // NIS & NISN sama-sama kosong: kalau barisnya benar-benar kosong, lewati saja.
        // Kalau ada Nama-nya, laporkan supaya tidak hilang diam-diam.
        if ($nis === '' && $nisn === '') {
            $nama = $this->teks($data['nama'] ?? null);
            if ($nama !== '') {
                $this->gagal($baris, "NIS untuk \"{$nama}\" kosong. Isi NIS-nya supaya siswa bisa dikenali.");
            }
            return;
        }

        // 1) Cari siswa: NIS dulu, kalau tidak ketemu baru NISN.
        $idSiswa = ($nis !== '' ? ($this->petaNis[$nis] ?? null) : null)
            ?? ($nisn !== '' ? ($this->petaNisn[$nisn] ?? null) : null);

        if (! $idSiswa) {
            $this->gagal($baris, 'Siswa dengan ' . ($nis !== '' ? "NIS {$nis}" : "NISN {$nisn}") . ' tidak ditemukan di Data Siswa. Minta Admin menambahkannya dulu.');
            return;
        }

        // 2) Dobel di dalam file ini.
        if (isset($this->diFile[$idSiswa])) {
            $this->gagal($baris, 'Siswa ini dobel di dalam file (sudah muncul di baris sebelumnya).');
            return;
        }
        $this->diFile[$idSiswa] = true;

        // 3) Sudah jadi anggota -> dilewati, bukan error (aman kalau file hasil export diimport ulang).
        if (isset($this->sudahAnggota[$idSiswa])) {
            $this->sudahJadiAnggota++;
            return;
        }

        // 4) Status (opsional)
        $status = strtolower($this->teks($data['status'] ?? null));
        if ($status === '') {
            $status = 'aktif';
        } elseif (! in_array($status, self::STATUS_VALID, true)) {
            $this->gagal($baris, "Status \"{$status}\" tidak valid. Pakai: aktif, nonaktif, atau keluar.");
            return;
        }

        // 5) Tanggal bergabung (opsional)
        $tanggal = $this->tanggal($data['tanggal_bergabung'] ?? null);
        if ($tanggal === false) {
            $this->gagal($baris, 'Tanggal Bergabung tidak terbaca. Pakai format YYYY-MM-DD (contoh 2026-10-29).');
            return;
        }

        Peserta::create([
            'id_siswa'          => $idSiswa,
            'id_ekskul'         => $this->idEkskul,
            'tanggal_bergabung' => $tanggal ?? now()->toDateString(),
            'status'            => $status,
        ]);

        $this->sudahAnggota[$idSiswa] = true;
        $this->berhasil++;
    }

    protected function gagal(int $baris, string $pesan): void
    {
        $this->barisError[] = ['baris' => $baris, 'error' => $pesan];
    }

    /**
     * @return string|null|false  tanggal Y-m-d, null kalau kosong, false kalau tidak terbaca
     */
    protected function tanggal(mixed $nilai): string|null|false
    {
        if ($nilai === null || trim((string) $nilai) === '') {
            return null;
        }

        try {
            // Sel tanggal Excel yang kebaca angka (serial date)
            if (is_numeric($nilai)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $nilai))->toDateString();
            }

            $teks = trim((string) $nilai);

            // Format Indonesia: 29/10/2026 atau 29-10-2026
            if (preg_match('#^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$#', $teks, $m)) {
                return Carbon::createFromDate((int) $m[3], (int) $m[2], (int) $m[1])->toDateString();
            }

            return Carbon::parse($teks)->toDateString();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Nilai sel Excel -> string. Angka bulat (NIS/NISN yang kebaca numeric)
     * ditulis penuh, bukan notasi ilmiah 1.23E+9.
     */
    protected function teks(mixed $nilai): string
    {
        if ($nilai === null) {
            return '';
        }
        if (is_float($nilai) && floor($nilai) === $nilai) {
            return sprintf('%.0f', $nilai);
        }

        return trim((string) $nilai);
    }
}