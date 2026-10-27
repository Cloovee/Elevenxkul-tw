<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan {{ $namaBulan }} - {{ $ekskul->nama_ekskul }}</title>
    @include('partials.favicon')
    <style>
        * { box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; color: #000; margin: 0; background: #f3f4f6; font-size: 12pt; }

        .toolbar { background: #fff; padding: 12px 24px; display: flex; gap: 8px; border-bottom: 1px solid #ddd; font-family: Arial, sans-serif; }
        .toolbar a, .toolbar button { padding: 8px 16px; border-radius: 999px; border: 0; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-back { background: #eef2fb; color: #10316B; }
        .btn-print { background: #0B409C; color: #fff; }

        /* Kertas F4 (215 x 330 mm), sama dengan template Word */
        .page { background: #fff; width: 215mm; min-height: 330mm; margin: 16px auto; padding: 14mm 18mm; box-shadow: 0 2px 12px rgba(0,0,0,.1); }

        /* Kop surat */
        .kop { display: flex; align-items: center; gap: 14px; padding-bottom: 6px; border-bottom: 4px solid #000; }
        .kop-logo { width: 26mm; flex: none; }
        .kop-logo img { width: 100%; display: block; }
        .kop-teks { flex: 1; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 1.25; }
        .kop-teks .l1 { font-size: 10.5pt; }
        .kop-teks .l-nama { font-size: 18pt; font-weight: 700; }
        .kop-teks .l-prog { font-size: 9.5pt; font-weight: 700; }
        .kop-teks .l-alamat { font-size: 8.5pt; }

        h1 { text-align: center; font-size: 13pt; margin: 18px 0 4px; text-decoration: underline; }
        .bulan { text-align: center; margin: 0 0 14px; font-size: 12pt; }
        .info { border-collapse: collapse; margin-bottom: 10px; }
        .info td { padding: 3px 0; vertical-align: top; }
        .info td:first-child { width: 28mm; }

        /* Kotak besar = tabel kegiatan: kolom Foto | Deskripsi Kegiatan | Tanggal, satu kegiatan satu baris */
        /* Garis pemisah kolom digambar sebagai background kotak (bukan border sel) supaya
           membentang penuh sampai dasar kotak, walaupun barisnya sedikit. Posisinya harus sama
           dengan lebar kolom: Foto = 62mm (kiri), Tanggal = 36mm (kanan). */
        .kotak {
            border: 1.5px solid #000; min-height: 170mm;
            background:
                linear-gradient(#000, #000) 62mm 0 / 1px 100% no-repeat,
                linear-gradient(#000, #000) calc(100% - 36mm) 0 / 1px 100% no-repeat;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
            box-decoration-break: clone; -webkit-box-decoration-break: clone; /* kotak tertutup rapi di tiap halaman */
        }
        .tabel { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .tabel th { border-bottom: 1px solid #000; padding: 2mm 3mm; font-size: 11pt; text-align: center; }
        .tabel td { padding: 3mm; vertical-align: middle; font-size: 11pt; line-height: 1.35; }
        .tabel tbody tr + tr td { border-top: 1px solid #000; }
        .tabel tr { break-inside: avoid; page-break-inside: avoid; }
        .tabel .c-foto { width: 62mm; }
        .tabel .c-tgl { width: 36mm; text-align: center; }
        .foto { width: 100%; aspect-ratio: 4 / 3; border: 1px solid #000; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fafafa; }
        .foto img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto span { font-size: 10pt; font-style: italic; color: #555; }
        .kosong { text-align: center; font-style: italic; margin: 60mm 0; color: #444; }

        /* Tanda tangan */
        .ttd { margin-top: 8mm; margin-left: 52%; break-inside: avoid; page-break-inside: avoid; }
        .ttd .spasi { height: 24mm; }
        .ttd .nama { display: inline-block; border-top: 1px solid #000; min-width: 70mm; padding-top: 2px; font-weight: 700; }

        @media print {
            /* Margin diatur di @page (bukan padding .page) supaya tiap halaman punya margin sendiri kalau laporannya >1 halaman */
            @page { size: 215mm 330mm; margin: 14mm 18mm; }
            body { background: #fff; }
            .toolbar { display: none !important; }
            .page { box-shadow: none; margin: 0; width: auto; min-height: 0; padding: 0; }
            .kop, .kop-logo img, .foto img, .foto { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('ketua.laporan-kegiatan', ['bulan' => $bulanInput]) }}" class="btn-back">&larr; Kembali</a>
        <button type="button" class="btn-print" onclick="window.print()">Cetak Laporan</button>
    </div>

    <div class="page">

        {{-- Kop surat SMKN 11 Bandung (sesuai template) --}}
        <div class="kop">
            <div class="kop-logo">
                <img src="{{ asset('images/logojabar-removebg-preview.png') }}" alt="Logo SMKN 11 Bandung" onerror="this.style.display='none'">
            </div>
            <div class="kop-teks">
                <div class="l1">PEMERINTAH DAERAH PROVINSI JAWA BARAT</div>
                <div class="l1">DINAS PENDIDIKAN</div>
                <div class="l1">CABANG DINAS PENDIDIKAN WILAYAH VII</div>
                <div class="l-nama">SMK NEGERI 11 BANDUNG</div>
                <div class="l-prog">Bisnis dan Manajemen - Teknologi Informasi - Seni dan Ekonomi Kreatif</div>
                <div class="l-alamat">Jl. Budi Cilember – Sukaraja – Cicendo - Bandung 40175 &nbsp; NPSN : 20219175 &nbsp; NSS: 34.1.02.60.03.001</div>
                <div class="l-alamat">http://smkn11bdg.sch.id &nbsp;&nbsp; E-mail : official@smkn11bdg.sch.id</div>
            </div>
        </div>

        <h1>LAPORAN KEGIATAN</h1>
        <p class="bulan">BULAN : {{ strtoupper($namaBulan) }}</p>

        <table class="info">
            <tr><td>Nama</td><td>: {{ $pelatih->nama_pelatih ?? '……………………………………………………………' }}</td></tr>
            <tr><td>Jabatan</td><td>: Pelatih Ekstrakurikuler {{ $ekskul->nama_ekskul }}</td></tr>
        </table>

        <div class="kotak">
            @if ($kegiatan->isEmpty())
                <p class="kosong">Belum ada kegiatan pelatih pada bulan ini.</p>
            @else
                <table class="tabel">
                    <thead>
                        <tr>
                            <th class="c-foto">Foto</th>
                            <th>Deskripsi Kegiatan</th>
                            <th class="c-tgl">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kegiatan as $k)
                            <tr>
                                <td class="c-foto">
                                    <div class="foto">
                                        @if ($k->foto_url)
                                            <img src="{{ $k->foto_url }}" alt="Foto kegiatan {{ $k->tanggal_absensi->format('d/m/Y') }}">
                                        @else
                                            <span>Tidak ada foto</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $k->kegiatan ?: 'Kegiatan ekstrakurikuler ' . $ekskul->nama_ekskul }}</td>
                                <td class="c-tgl">{{ $k->tanggal_absensi->locale('id')->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="ttd">
            Bandung, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
            Dibuat Oleh,
            <div class="spasi"></div>
            <span class="nama">{{ $pelatih->nama_pelatih ?? '' }}</span>
        </div>
    </div>

</body>
</html>