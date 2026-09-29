<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ekskul {{ $ekskul->nama_ekskul }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; color: #000; margin: 0; background: #f3f4f6; font-size: 12pt; }
        .toolbar { background: #fff; padding: 12px 24px; display: flex; gap: 8px; border-bottom: 1px solid #ddd; font-family: Arial, sans-serif; }
        .toolbar a, .toolbar button { padding: 8px 16px; border-radius: 999px; border: 0; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-back { background: #eef2fb; color: #10316B; }
        .btn-print { background: #0B409C; color: #fff; }
        .page { background: #fff; max-width: 210mm; margin: 16px auto; padding: 18mm 16mm; box-shadow: 0 2px 12px rgba(0,0,0,.1); }
        h1 { text-align: center; font-size: 16pt; margin: 0 0 4px; letter-spacing: 1px; }
        .sub { text-align: center; margin: 0 0 16px; font-size: 11pt; }
        hr { border: 0; border-top: 2px solid #000; margin: 0 0 16px; }
        h2 { font-size: 12pt; margin: 20px 0 6px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; font-size: 11pt; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background: #e5e7eb; }
        .info td { border: 0; padding: 2px 6px 2px 0; }
        .info td:first-child { width: 150px; }
        .kosong { font-style: italic; margin: 4px 0; }
        .ttd { margin-top: 32px; display: flex; justify-content: flex-end; }
        .ttd div { width: 220px; text-align: center; }
        .ttd .spasi { height: 70px; }
        tr { page-break-inside: avoid; }
        h2 { page-break-after: avoid; }

        @media print {
            @page { size: A4; margin: 15mm; }
            body { background: #fff; }
            .toolbar, .no-print { display: none !important; }
            .page { box-shadow: none; margin: 0; padding: 0; max-width: none; }
            th { background: #e5e7eb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('admin.monitoring-ekskul.show', $ekskul->id_ekskul) }}" class="btn-back">&larr; Kembali</a>
        <button type="button" class="btn-print" onclick="window.print()">Cetak Laporan</button>
    </div>

    <div class="page">
        <h1>LAPORAN EKSTRAKURIKULER</h1>
        <p class="sub">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>
        <hr>

        <table class="info">
            <tr><td>Nama Ekskul</td><td>: {{ $ekskul->nama_ekskul }}</td></tr>
            <tr><td>Kategori</td><td>: {{ ucfirst($ekskul->kategori) }}</td></tr>
            <tr><td>Pembina</td><td>: {{ $ekskul->pembina->nama_pembina ?? '-' }}</td></tr>
            <tr><td>Ketua</td><td>: {{ $ekskul->ketua ? $ekskul->ketua->nama_siswa . ' (' . $ekskul->ketua->nama_kelas . ')' : '-' }}</td></tr>
            <tr><td>Jumlah Peserta</td><td>: {{ $peserta->count() }}</td></tr>
        </table>

        <h2>Daftar Anggota</h2>
        @if($peserta->count())
            <table>
                <thead><tr><th style="width:36px">No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($peserta as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->siswa->NIS ?? '-' }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->siswa->nama_kelas ?? '-' }}</td>
                        <td>{{ ucfirst($p->status ?? '-') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="kosong">Belum ada anggota.</p>
        @endif

        <h2>Daftar Pelatih</h2>
        @if($ekskul->pelatih)
            <table>
                <thead><tr><th style="width:36px">No</th><th>Nama</th><th>No. HP</th><th>Email</th></tr></thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $ekskul->pelatih->nama_pelatih }}</td>
                        <td>{{ $ekskul->pelatih->nomor_hp ?: '-' }}</td>
                        <td>{{ $ekskul->pelatih->email ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="kosong">Belum ada pelatih.</p>
        @endif

        <h2>Laporan Kegiatan</h2>
        @if($kegiatan->count())
            <table>
                <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpha</th></tr></thead>
                <tbody>
                @foreach($kegiatan as $k)
                    <tr>
                        <td style="white-space:nowrap">{{ \Illuminate\Support\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $k->kegiatan ?: '-' }}</td>
                        <td>{{ $k->hadir }}</td><td>{{ $k->izin }}</td><td>{{ $k->sakit }}</td><td>{{ $k->alpha }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="kosong">Belum ada laporan kegiatan.</p>
        @endif

        <h2>Penilaian</h2>
        @if($penilaian->count())
            <table>
                <thead><tr><th style="width:36px">No</th><th>Nama</th><th>Tahun Ajaran</th><th>Semester</th><th>Nilai</th><th>Catatan Pembina</th></tr></thead>
                <tbody>
                @foreach($penilaian as $i => $n)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $n->peserta->nama ?? '-' }}</td>
                        <td>{{ $n->tahun_ajaran ?: '-' }}</td>
                        <td>{{ $n->semester ?: '-' }}</td>
                        <td>{{ $n->nilai !== null ? rtrim(rtrim(number_format($n->nilai, 2), '0'), '.') : '-' }}</td>
                        <td>{{ $n->catatan_pembina ?: '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="kosong">Belum ada penilaian.</p>
        @endif

        <div class="ttd">
            <div>
                Pembina Ekskul
                <div class="spasi"></div>
                <strong>{{ $ekskul->pembina->nama_pembina ?? '(............................)' }}</strong>
            </div>
        </div>
    </div>
</body>
</html>