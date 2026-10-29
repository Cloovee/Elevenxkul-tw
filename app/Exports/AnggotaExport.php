<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export anggota SATU ekskul (ekskul yang dipimpin Ketua yang login).
 * Kolom NISN & NIS sama persis dengan template import, jadi hasil export
 * bisa diedit lalu diimport lagi.
 */
class AnggotaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected int $idEkskul)
    {
    }

    public function collection()
    {
        return Peserta::query()
            ->with('siswa.kelas')
            ->where('id_ekskul', $this->idEkskul)
            ->get()
            ->sortBy(fn ($p) => mb_strtolower($p->siswa->nama_siswa ?? ''))
            ->values();
    }

    public function headings(): array
    {
        return ['NISN', 'NIS', 'Nama', 'JK', 'Rombel', 'Status', 'Tanggal Bergabung'];
    }

    public function map($peserta): array
    {
        $siswa = $peserta->siswa;

        return [
            // (string) supaya NISN/NIS tidak berubah jadi notasi ilmiah di Excel
            (string) ($siswa->NISN ?? ''),
            (string) ($siswa->NIS ?? ''),
            $siswa->nama_siswa ?? '-',
            $siswa->jk ?? '',
            $siswa->kelas?->kode_rombel ?? '',
            $peserta->status,
            $peserta->tanggal_bergabung?->format('Y-m-d') ?? '',
        ];
    }
}
