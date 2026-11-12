<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Grade Peserta {{ $labelEkskul }} - Semester {{ $semester }} {{ $tahunAjaran }}</title>
    {{-- CSS dibuat sederhana (tabel, tanpa flex/grid) supaya aman dirender DomPDF. --}}
    <style>
        @page { margin: 14mm 16mm 16mm 16mm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; color: #000; }

        /* ===== Kop surat ===== */
        table.kop { width: 100%; border-collapse: collapse; }
        table.kop td { border: 0; padding: 0; vertical-align: middle; }
        table.kop td.logo { width: 30mm; text-align: left; }
        table.kop td.logo img { width: 24mm; }
        table.kop td.teks { text-align: center; }
        .kop-1 { font-size: 12pt; line-height: 1.45; }
        .kop-nama { font-size: 17pt; font-weight: bold; margin: 3px 0 2px; }
        .kop-jurusan { font-size: 11.5pt; font-weight: bold; }
        .kop-alamat { font-size: 10pt; line-height: 1.4; }
        .garis { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin: 8px 0 14px; }

        /* ===== Judul & identitas ===== */
        .judul { text-align: center; font-weight: bold; font-size: 14pt; margin: 0 0 4px; }
        .periode { text-align: center; font-weight: bold; font-size: 12pt; margin: 0 0 12px; }
        table.info { border-collapse: collapse; margin: 0 0 12px 6mm; }
        table.info td { border: 0; padding: 2px 0; font-size: 11.5pt; vertical-align: top; }
        table.info td.label { width: 40mm; }
        table.info td.titik { width: 4mm; }

        /* ===== Tabel data ===== */
        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: 1px solid #000; padding: 3px 5px; font-size: 9.5pt; vertical-align: middle; }
        table.data th { background: #eef3f6; font-weight: bold; text-align: center; }
        table.data td.tengah { text-align: center; }
        table.data td.grade { text-align: center; font-weight: bold; }
        table.data thead { display: table-header-group; }
        table.data tr { page-break-inside: avoid; }
        .kosong-nilai { color: #555; font-style: italic; }
        .catatan { font-size: 9pt; margin-top: 6px; line-height: 1.4; }

        /* ===== Tanda tangan ===== */
        table.ttd { width: 100%; border-collapse: collapse; margin-top: 20px; page-break-inside: avoid; }
        table.ttd td { border: 0; padding: 0; vertical-align: top; font-size: 12pt; }
        table.ttd td.kiri { width: 62%; }
        .ttd-spasi { height: 20mm; }
        .ttd-nama { font-weight: bold; font-size: 13pt; }
    </style>
</head>
<body>

    {{-- ===================== KOP SURAT ===================== --}}
    <table class="kop">
        <tr>
            <td class="logo">
                @if ($logoJabar)
                    <img src="{{ $logoJabar }}" alt="Logo Jawa Barat">
                @endif
            </td>
            <td class="teks">
                <div class="kop-1">
                    PEMERINTAH DAERAH PROVINSI JAWA BARAT<br>
                    DINAS PENDIDIKAN<br>
                    CABANG DINAS PENDIDIKAN WILAYAH VII
                </div>
                <div class="kop-nama">SMK NEGERI 11 BANDUNG</div>
                <div class="kop-jurusan">Bisnis dan Manajemen – Teknologi Informasi – Seni dan Ekonomi Kreatif</div>
                <div class="kop-alamat">
                    Jl. Budi Cilember Sukaraja Cicendo (022) 6652442 Fax. (022) 6613508 Bandung 40175<br>
                    http://smkn11bdg.sch.id • E-mail: smkn11bdg@gmail.com NPSN: 20219175 NSS: 34.1.02.60.03.001
                </div>
            </td>
            {{-- Sel kosong di kanan supaya teks kop tetap tepat di tengah halaman. --}}
            <td class="logo"></td>
        </tr>
    </table>
    <div class="garis"></div>

    {{-- ===================== JUDUL & IDENTITAS ===================== --}}
    <div class="judul">REKAP GRADE PESERTA EKSTRAKURIKULER</div>
    <div class="periode">SEMESTER {{ $semester }} TAHUN AJARAN {{ $tahunAjaran }}</div>

    <table class="info">
        <tr><td class="label">Ekskul</td><td class="titik">:</td><td>{{ $labelEkskul }}</td></tr>
        <tr><td class="label">Pembina</td><td class="titik">:</td><td>{{ $namaPembina ?: '-' }}</td></tr>
        <tr><td class="label">Jumlah Peserta</td><td class="titik">:</td><td>{{ $baris->count() }} orang ({{ $baris->where('dinilai', true)->count() }} sudah dinilai{{ ($tanpaHadir = $baris->where('status', 'tanpa_hadir')->count()) ? ', '.$tanpaHadir.' tidak pernah hadir' : '' }})</td></tr>
    </table>

    {{-- ===================== TABEL GRADE ===================== --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">NIS</th>
                <th style="width: 20%">Nama</th>
                <th style="width: 9%">Kelas</th>
                @if ($labelEkskul === 'Semua Ekskul')
                    <th style="width: 12%">Ekskul</th>
                @endif
                <th style="width: 9%">Hadir / Pertemuan</th>
                <th style="width: 7%">% Hadir</th>
                <th style="width: 7%">Grade Kehadiran</th>
                <th style="width: 7%">Nilai Keaktifan</th>
                <th style="width: 7%">Grade Keaktifan</th>
                <th style="width: 8%">Grade Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($baris as $i => $b)
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td class="tengah">{{ $b['nis'] ?: '-' }}</td>
                    <td>{{ $b['nama'] }}</td>
                    <td class="tengah">{{ $b['kelas'] ?: '-' }}</td>
                    @if ($labelEkskul === 'Semua Ekskul')
                        <td>{{ $b['ekskul'] }}</td>
                    @endif
                    @if ($b['dinilai'])
                        <td class="tengah">{{ $b['hadir'] }} / {{ $b['pertemuan'] }}</td>
                        <td class="tengah">{{ rtrim(rtrim(number_format($b['persen'], 2, ',', ''), '0'), ',') }}%</td>
                        <td class="grade">{{ $b['grade_kehadiran'] }}</td>
                        <td class="tengah">{{ rtrim(rtrim(number_format($b['nilai_keaktifan'], 2, ',', ''), '0'), ',') }}</td>
                        <td class="grade">{{ $b['grade_keaktifan'] }}</td>
                        <td class="grade">{{ $b['grade_akhir'] }}</td>
                    @elseif (($b['status'] ?? '') === 'tanpa_hadir')
                        <td class="tengah">{{ $b['hadir'] }} / {{ $b['pertemuan'] }}</td>
                        <td class="tengah">{{ rtrim(rtrim(number_format((float) $b['persen'], 2, ',', ''), '0'), ',') ?: '0' }}%</td>
                        <td colspan="4" class="tengah kosong-nilai">Tidak pernah hadir (tanpa grade)</td>
                    @else
                        <td colspan="6" class="tengah kosong-nilai">Belum dinilai</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        Keterangan: Grade A = nilai {{ $batas['A'] }} ke atas, B = {{ $batas['B'] }}–{{ $batas['A'] - 1 }}, C = {{ $batas['C'] }}–{{ $batas['B'] - 1 }}
        (nilai {{ $batas['C'] }} adalah yang terendah). Peserta yang tidak pernah hadir pada semester ini tidak diberi nilai (tanpa grade). Grade kehadiran dihitung dari persentase kehadiran pada semester ini,
        grade keaktifan dari nilai yang diberikan pembina. Grade akhir adalah gabungan keduanya
        (kehadiran {{ $bobot['kehadiran'] }}% : keaktifan {{ $bobot['keaktifan'] }}%).
    </div>

    {{-- ===================== TANDA TANGAN PEMBINA ===================== --}}
    <table class="ttd">
        <tr>
            <td class="kiri"></td>
            <td>
                Bandung, {{ $tanggalCetak }}<br>
                Pembina Ekskul
                <div class="ttd-spasi"></div>
                <div class="ttd-nama">{{ $namaPembina ?: '(............................)' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
