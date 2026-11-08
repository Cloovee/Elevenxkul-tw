<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export grade peserta ekskul untuk satu semester (hasil App\Support\Penilaian::rekap).
 * Peserta yang belum dinilai tetap muncul dengan keterangan "Belum dinilai".
 */
class NilaiPesertaExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected Collection $baris,
        protected string $labelEkskul,
        protected string $tahunAjaran,
        protected int $semester,
    ) {
    }

    public function headings(): array
    {
        return [
            'No', 'NIS', 'Nama', 'Kelas', 'Ekskul',
            'Hadir', 'Total Pertemuan', '% Kehadiran', 'Grade Kehadiran',
            'Nilai Keaktifan', 'Grade Keaktifan', 'Grade Akhir', 'Catatan Pembina',
        ];
    }

    public function array(): array
    {
        return $this->baris->values()->map(function (array $b, int $i) {
            $dinilai = $b['dinilai'];

            return [
                $i + 1,
                // (string) supaya NIS tidak berubah jadi notasi ilmiah di Excel
                (string) $b['nis'],
                $b['nama'],
                $b['kelas'],
                $b['ekskul'],
                $b['hadir'] ?? '',
                $b['pertemuan'] ?? '',
                $b['persen'] !== null ? $b['persen'] : '',
                $b['grade_kehadiran'] ?? '',
                $b['nilai_keaktifan'] !== null ? $b['nilai_keaktifan'] : '',
                $b['grade_keaktifan'] ?? '',
                $dinilai ? $b['grade_akhir'] : 'Belum dinilai',
                $b['catatan'],
            ];
        })->all();
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        // Nama sheet maks. 31 karakter dan tanpa karakter terlarang (/ \ ? * [ ] :).
        return mb_substr('Sem ' . $this->semester . ' ' . str_replace('/', '-', $this->tahunAjaran), 0, 31);
    }
}
