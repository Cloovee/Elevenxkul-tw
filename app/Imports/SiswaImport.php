<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * Import siswa. Kelas TIDAK dipilih di form lagi: tiap baris Excel membawa
 * kolom "Rombel" (contoh: 10TJKT1, 11RPL2, 12AK3) dan rombel itu WAJIB sudah
 * terdaftar di tabel `kelas`. Kalau belum terdaftar, baris tersebut ditolak
 * dan dilaporkan lengkap dengan nomor barisnya.
 */
class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithBatchInserts
{
    use SkipsFailures;

    /** Nama kolom (hasil slug heading) yang dikenali sebagai kolom rombel. */
    private const KOLOM_ROMBEL = ['rombel', 'rombel_saat_ini', 'kelas'];

    /** "10TJKT1" => id_kelas */
    protected array $petaKelas = [];

    /** "10TJKT" => [id_kelas, ...]  (dipakai kalau di Excel nomor rombel tidak ditulis) */
    protected array $petaProgram = [];

    /** Set NISN / NIS yang sudah ada di database (di-load sekali, bukan query per baris). */
    protected array $nisnAda = [];
    protected array $nisAda = [];

    /** Set NISN / NIS yang sudah muncul di file ini (deteksi duplikat dalam file). */
    protected array $nisnDiFile = [];
    protected array $nisDiFile = [];

    protected int $jumlahBerhasil = 0;

    public function __construct()
    {
        // Seluruh kelas & siswa di-load sekali di awal, jadi import ribuan baris
        // tidak memicu ribuan query lookup.
        foreach (Kelas::all() as $k) {
            $this->petaKelas[$k->kode_rombel] = $k->id_kelas;

            $kodeProgram = Kelas::normalisasiKode("{$k->tingkat}{$k->program_keahlian}");
            $this->petaProgram[$kodeProgram][] = $k->id_kelas;
        }

        $this->nisnAda = array_flip(array_map('strval', Siswa::pluck('NISN')->all()));
        $this->nisAda  = array_flip(array_map('strval', Siswa::pluck('NIS')->all()));
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function getJumlahBerhasil(): int
    {
        return $this->jumlahBerhasil;
    }

    /**
     * Lewati baris yang NISN, NIS, dan nama siswanya kosong semua.
     */
    public function isEmptyWhen(array $row): bool
    {
        return $this->teks($row['nisn'] ?? null) === ''
            && $this->teks($row['nis'] ?? null) === ''
            && $this->teks($row['nama'] ?? null) === '';
    }

    public function prepareForValidation(array $data, int $index): array
    {
        foreach (['nisn', 'nis', 'no_hp'] as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = $this->teks($data[$kolom]);
            }
        }

        // Apa pun nama kolomnya (Rombel / Rombel Saat Ini / Kelas), disatukan ke 'rombel'.
        $data['rombel'] = $this->ambilRombel($data);

        return $data;
    }

    public function model(array $row)
    {
        [$idKelas] = $this->cariKelas($this->ambilRombel($row));

        // Seharusnya tidak pernah null di sini karena sudah lolos rules(), ini cuma pengaman.
        if (! $idKelas) {
            return null;
        }

        $this->jumlahBerhasil++;

        return new Siswa([
            'id_kelas'   => $idKelas,
            'NISN'       => $this->teks($row['nisn']),
            'NIS'        => $this->teks($row['nis']),
            'nama_siswa' => $row['nama'],
            'jk'         => $row['jk'],
            'agama'      => $row['agama'] ?? null,
            'nomor_hp'   => isset($row['no_hp']) ? $this->teks($row['no_hp']) : null,
            'email'      => $row['email'] ?? null,
            'medsos'     => $row['medsos'] ?? null,
            'alamat'     => $row['alamat'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.rombel' => [
                'required',
                function ($attribute, $value, $fail) {
                    [, $error] = $this->cariKelas((string) $value);
                    if ($error) {
                        $fail($error);
                    }
                },
            ],
            '*.nisn' => [
                'required', 'string', 'max:20',
                function ($attribute, $value, $fail) {
                    $nisn = $this->teks($value);
                    if (isset($this->nisnAda[$nisn])) {
                        $fail("NISN {$nisn} sudah terdaftar di database.");
                    } elseif (isset($this->nisnDiFile[$nisn])) {
                        $fail("NISN {$nisn} dobel di dalam file ini.");
                    }
                    $this->nisnDiFile[$nisn] = true;
                },
            ],
            '*.nis' => [
                'required', 'string', 'max:20',
                function ($attribute, $value, $fail) {
                    $nis = $this->teks($value);
                    if (isset($this->nisAda[$nis])) {
                        $fail("NIS {$nis} sudah terdaftar di database.");
                    } elseif (isset($this->nisDiFile[$nis])) {
                        $fail("NIS {$nis} dobel di dalam file ini.");
                    }
                    $this->nisDiFile[$nis] = true;
                },
            ],
            '*.nama' => ['required', 'string', 'max:100'],
            '*.jk' => ['required', 'in:L,P'],
            '*.no_hp' => ['nullable', 'string', 'max:15'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.rombel.required' => 'Kolom Rombel wajib diisi (contoh: 10TJKT1).',
            '*.nisn.required'   => 'NISN wajib diisi.',
            '*.nis.required'    => 'NIS wajib diisi.',
            '*.nama.required'   => 'Nama wajib diisi.',
            '*.jk.required'     => 'JK wajib diisi (L / P).',
            '*.jk.in'           => 'JK harus L atau P.',
        ];
    }

    /**
     * Cocokkan teks rombel dari Excel ke tabel kelas.
     *
     * @return array{0: int|null, 1: string|null} [id_kelas, pesan_error]
     */
    protected function cariKelas(?string $teksRombel): array
    {
        $kunci = Kelas::normalisasiKode($teksRombel);

        if ($kunci === '') {
            return [null, 'Kolom Rombel wajib diisi (contoh: 10TJKT1).'];
        }

        // 1) Cocok persis dengan kode lengkap: tingkat + program keahlian + rombel.
        if (isset($this->petaKelas[$kunci])) {
            return [$this->petaKelas[$kunci], null];
        }

        // 2) Nomor rombel tidak ditulis (mis. "10TJKT"): boleh HANYA kalau program
        //    itu cuma punya satu rombel terdaftar, jadi tidak mungkin salah tebak.
        if (isset($this->petaProgram[$kunci])) {
            $ids = $this->petaProgram[$kunci];
            if (count($ids) === 1) {
                return [$ids[0], null];
            }

            return [null, "Rombel \"{$teksRombel}\" ambigu: ada " . count($ids) . " rombel untuk program ini. Tulis lengkap dengan nomor rombel (contoh: {$kunci}1)."];
        }

        return [null, "Rombel \"{$teksRombel}\" belum terdaftar. Tambahkan dulu di menu Kelas, lalu import ulang."];
    }

    protected function ambilRombel(array $row): string
    {
        foreach (self::KOLOM_ROMBEL as $kolom) {
            $nilai = $this->teks($row[$kolom] ?? null);
            if ($nilai !== '') {
                return $nilai;
            }
        }

        return '';
    }

    /**
     * Nilai sel Excel -> string. Angka bulat (NISN/NIS yang kebaca numeric)
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