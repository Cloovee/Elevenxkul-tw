<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi {{ $ekskul->nama_ekskul }} - {{ $labelBulan }}</title>
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
        .judul { text-align: center; font-weight: bold; font-size: 14pt; margin: 0 0 8px; }
        .bulan { text-align: center; font-weight: bold; font-size: 13pt; margin: 0 0 14px; }
        table.info { border-collapse: collapse; margin: 0 0 14px 6mm; }
        table.info td { border: 0; padding: 2px 0; font-size: 12pt; vertical-align: top; }
        table.info td.label { width: 52mm; }
        table.info td.titik { width: 4mm; }

        /* ===== Tabel data ===== */
        .sub-judul { text-align: center; font-weight: bold; font-size: 12.5pt; margin: 12px 0 6px; }
        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: 1px solid #000; padding: 3px 5px; font-size: 10pt; vertical-align: middle; }
        table.data th { background: #eef3f6; font-weight: bold; text-align: center; }
        table.data td.tengah { text-align: center; }
        table.data thead { display: table-header-group; }
        table.data tr { page-break-inside: avoid; }
        .kosong { text-align: center; font-style: italic; padding: 8px 0; font-size: 10.5pt; }

        /* ===== Tanda tangan ===== */
        table.ttd { width: 100%; border-collapse: collapse; margin-top: 22px; page-break-inside: avoid; }
        table.ttd td { border: 0; padding: 0; vertical-align: top; font-size: 12pt; }
        table.ttd td.kiri { width: 58%; }
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
    <div class="judul">REKAP ABSENSI EKSTRAKURIKULER</div>
    <div class="bulan">BULAN : {{ $labelBulan }}</div>

    <table class="info">
        <tr><td class="label">Nama Ekskul</td><td class="titik">:</td><td>{{ $ekskul->nama_ekskul }}</td></tr>
        <tr><td class="label">Pelatih</td><td class="titik">:</td><td>{{ $namaPelatih ? \Illuminate\Support\Str::upper($namaPelatih) : '-' }}</td></tr>
        <tr><td class="label">Hari/Tanggal Kegiatan</td><td class="titik">:</td><td>{{ $hariTanggal }}</td></tr>
        <tr><td class="label">Jumlah Anggota Aktif</td><td class="titik">:</td><td>{{ $jumlahAnggotaAktif }} orang</td></tr>
    </table>

    {{-- ===================== TABEL KEHADIRAN PESERTA ===================== --}}
    <div class="sub-judul">KEHADIRAN PESERTA</div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 20%">NIS</th>
                <th style="width: 27%">Nama</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 11%">Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barisPeserta as $b)
                <tr>
                    <td class="tengah">{{ $b['nis'] }}</td>
                    <td>{{ $b['nama'] }}</td>
                    <td class="tengah">{{ $b['tanggal'] }}</td>
                    <td class="tengah">{{ $b['waktu'] }}</td>
                    <td>{{ $b['keterangan'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="kosong">Belum ada data absensi peserta pada bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================== TABEL KEHADIRAN PELATIH ===================== --}}
    <div class="sub-judul" style="margin-top: 18px;">KEHADIRAN PELATIH</div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 26%">Nama</th>
                @foreach ($kolomTanggal as $t)
                    <th>{{ $t->format('d/m') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($kolomTanggal->isEmpty())
                <tr><td colspan="2" class="kosong">Belum ada data kehadiran pelatih pada bulan ini.</td></tr>
            @else
                <tr>
                    <td>{{ $namaPelatih ?? '-' }}</td>
                    @foreach ($kehadiranPelatih as $kode)
                        <td class="tengah">{{ $kode }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>
    <div style="font-size: 9pt; margin-top: 4px;">
        Keterangan: H = Hadir, I = Izin, S = Sakit, A = Alpha, – = tidak ada data.
    </div>

    {{-- ===================== TANDA TANGAN PELATIH ===================== --}}
    <table class="ttd">
        <tr>
            <td class="kiri"></td>
            <td>
                Bandung, {{ $tanggalCetak }}<br>
                Pelatih Ekskul {{ $ekskul->nama_ekskul }}
                <div class="ttd-spasi"></div>
                <div class="ttd-nama">{{ $namaPelatih ?? '(............................)' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
