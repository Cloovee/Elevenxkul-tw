<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Absensi - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6" x-data="{ tab: 'peserta', preview: false }" @keydown.escape.window="preview = false">

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-extrabold text-ink">Riwayat Absensi</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Seluruh histori absensi sepanjang ekskul ini berjalan — beda dengan "Aktivitas Terbaru"
                            di dashboard yang cuma menampilkan hal-hal terbaru yang kamu lakukan.
                        </p>
                    </div>

                    <!-- Tab switcher -->
                    <div class="inline-flex bg-white rounded-2xl p-1.5 shadow-sm ring-1 ring-black/5">
                        <button
                            type="button"
                            @click="tab = 'peserta'"
                            :class="tab === 'peserta' ? 'bg-periwinkle text-white shadow-md shadow-periwinkle/30' : 'text-gray-500 hover:text-ink'"
                            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all"
                        >
                            Histori Absensi Peserta
                        </button>
                        <button
                            type="button"
                            @click="tab = 'pelatih'"
                            :class="tab === 'pelatih' ? 'bg-periwinkle text-white shadow-md shadow-periwinkle/30' : 'text-gray-500 hover:text-ink'"
                            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all"
                        >
                            Histori Absensi Pelatih
                        </button>
                    </div>

                    <!-- ============== TAB: HISTORI ABSENSI PESERTA ============== -->
                    <div x-show="tab === 'peserta'" x-cloak class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-3 mb-4">
                            <h2 class="font-semibold text-ink">
                                Histori Absensi Peserta
                                <span class="text-xs font-normal text-gray-400">
                                    ({{ $riwayatPeserta->count() }} catatan &middot; {{ $pertemuan->count() }} pertemuan)
                                </span>
                            </h2>

                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Filter bulan: dipakai juga oleh tombol Export PDF --}}
                                <form method="GET" action="{{ route('ketua.riwayat-absensi') }}">
                                    <select
                                        name="bulan"
                                        onchange="this.form.submit()"
                                        class="rounded-xl border-gray-200 text-sm py-2 pl-3 pr-8 focus:border-periwinkle focus:ring-periwinkle"
                                        aria-label="Pilih bulan"
                                    >
                                        @foreach ($daftarBulan as $kode => $label)
                                            <option value="{{ $kode }}" @selected($kode === $bulan)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>

                                <button
                                    type="button"
                                    @click="preview = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-periwinkle ring-1 ring-periwinkle/40 text-sm font-semibold hover:bg-periwinkle/5 transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Preview
                                </button>

                                <a
                                    href="{{ route('ketua.riwayat-absensi.export-pdf', ['bulan' => $bulan]) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-periwinkle text-white text-sm font-semibold shadow-md shadow-periwinkle/30 hover:opacity-90 transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v11m0 0l-4-4m4 4l4-4M4 19h16"/>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>

                        @if ($pertemuan->isEmpty())
                            <p class="text-sm text-gray-400 py-6 text-center">
                                Belum ada riwayat absensi peserta pada {{ $labelBulan }}.
                            </p>
                        @else
                            <div class="overflow-x-auto -mx-2">
                                <table class="w-full text-sm min-w-[640px]">
                                    <thead>
                                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                                            <th class="py-2.5 px-2">NIS</th>
                                            <th class="py-2.5 px-2">Nama</th>
                                            <th class="py-2.5 px-2">Tanggal</th>
                                            <th class="py-2.5 px-2">Waktu</th>
                                            <th class="py-2.5 px-2">Keterangan</th>
                                        </tr>
                                    </thead>

                                    {{-- Satu <tbody> per pertemuan (setiap kali ekskul dilaksanakan) --}}
                                    @foreach ($pertemuan as $sesi)
                                        <tbody>
                                            <tr class="bg-periwinkle/5">
                                                <td colspan="5" class="py-2 px-2">
                                                    <span class="font-semibold text-ink">
                                                        {{ $sesi['tanggal']->locale('id')->translatedFormat('l, d F Y') }}
                                                    </span>
                                                    @if ($sesi['kegiatan'])
                                                        <span class="text-gray-500"> &mdash; {{ $sesi['kegiatan'] }}</span>
                                                    @endif
                                                    <span class="block sm:inline sm:ml-2 text-xs text-gray-400">
                                                        Hadir {{ $sesi['hadir'] }} &middot; Izin {{ $sesi['izin'] }}
                                                        &middot; Sakit {{ $sesi['sakit'] }} &middot; Alpha {{ $sesi['alpha'] }}
                                                    </span>
                                                </td>
                                            </tr>

                                            @foreach ($sesi['baris'] as $absensi)
                                                @php
                                                    $status = strtolower($absensi->status_kehadiran ?? '');
                                                    $badge = match ($status) {
                                                        'hadir' => 'bg-emerald-50 text-emerald-700',
                                                        'izin'  => 'bg-amber-50 text-amber-700',
                                                        'sakit' => 'bg-sky-50 text-sky-700',
                                                        'alpha' => 'bg-red-50 text-red-600',
                                                        default => 'bg-gray-100 text-gray-500',
                                                    };
                                                @endphp
                                                <tr class="border-b border-gray-50 last:border-b-0">
                                                    <td class="py-2.5 px-2 whitespace-nowrap text-gray-500">
                                                        {{ $absensi->peserta->siswa->NIS ?? '-' }}
                                                    </td>
                                                    <td class="py-2.5 px-2 font-medium text-ink">
                                                        {{ $absensi->peserta->siswa->nama_siswa ?? '-' }}
                                                    </td>
                                                    <td class="py-2.5 px-2 whitespace-nowrap">
                                                        {{ optional($absensi->tanggal_absensi)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="py-2.5 px-2 whitespace-nowrap">
                                                        {{ $absensi->updated_at ? $absensi->updated_at->format('H:i') : '-' }}
                                                    </td>
                                                    <td class="py-2.5 px-2">
                                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                                            {{ ucfirst($absensi->status_kehadiran ?? '-') }}
                                                        </span>
                                                        @if ($absensi->catatan)
                                                            <span class="text-gray-500 ml-1">{{ $absensi->catatan }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- ============== MODAL PREVIEW PDF ============== -->
                    <div
                        x-show="preview"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6"
                        role="dialog"
                        aria-modal="true"
                        aria-label="Preview PDF rekap absensi"
                    >
                        <div class="absolute inset-0 bg-ink/50 backdrop-blur-sm" @click="preview = false"></div>

                        <div class="relative w-full max-w-4xl h-[90vh] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden">
                            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-b border-gray-100">
                                <div>
                                    <h3 class="font-semibold text-ink">Preview Rekap Absensi</h3>
                                    <p class="text-xs text-gray-400">{{ $labelBulan }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('ketua.riwayat-absensi.export-pdf', ['bulan' => $bulan]) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-periwinkle text-white text-sm font-semibold shadow-md shadow-periwinkle/30 hover:opacity-90 transition"
                                    >
                                        Download PDF
                                    </a>
                                    <button
                                        type="button"
                                        @click="preview = false"
                                        class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-500 hover:text-ink hover:bg-gray-100 transition"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>

                            {{-- src baru diisi saat modal dibuka, jadi PDF tidak dirender kalau tidak dipakai. --}}
                            <iframe
                                :src="preview ? '{{ route('ketua.riwayat-absensi.export-pdf', ['bulan' => $bulan, 'preview' => 1]) }}' : 'about:blank'"
                                class="flex-1 w-full bg-gray-100"
                                title="Preview PDF"
                            ></iframe>
                        </div>
                    </div>

                    <!-- ============== TAB: HISTORI ABSENSI PELATIH ============== -->
                    <div x-show="tab === 'pelatih'" x-cloak class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">

                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">
                            Histori Absensi Pelatih
                            <span class="text-xs font-normal text-gray-400">({{ $riwayatPelatih->count() }} catatan)</span>
                        </h2>

                        @if ($riwayatPelatih->isEmpty())
                            <p class="text-sm text-gray-400 py-6 text-center">Belum ada riwayat absensi pelatih.</p>
                        @else
                            <div class="overflow-x-auto -mx-2">
                                <table class="w-full text-sm min-w-[640px]">
                                    <thead>
                                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                                            <th class="py-2.5 px-2">Tanggal</th>
                                            <th class="py-2.5 px-2">Nama Pelatih</th>
                                            <th class="py-2.5 px-2">Kegiatan</th>
                                            <th class="py-2.5 px-2">Kehadiran</th>
                                            <th class="py-2.5 px-2">Status Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPelatih as $absensi)
                                            <tr class="border-b border-gray-50 last:border-b-0">
                                                <td class="py-2.5 px-2 whitespace-nowrap">
                                                    {{ optional($absensi->tanggal_absensi)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="py-2.5 px-2 font-medium text-ink">
                                                    {{ $absensi->pelatih->nama_pelatih ?? '-' }}
                                                </td>
                                                <td class="py-2.5 px-2 text-gray-500">
                                                    {{ $absensi->kegiatan ?: '-' }}
                                                </td>
                                                <td class="py-2.5 px-2">
                                                    @php
                                                        $status = strtolower($absensi->status_kehadiran ?? '');
                                                        $badge = match ($status) {
                                                            'hadir' => 'bg-emerald-50 text-emerald-700',
                                                            'izin'  => 'bg-amber-50 text-amber-700',
                                                            'sakit' => 'bg-sky-50 text-sky-700',
                                                            'alpha' => 'bg-red-50 text-red-600',
                                                            default => 'bg-gray-100 text-gray-500',
                                                        };
                                                    @endphp
                                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                                        {{ ucfirst($absensi->status_kehadiran ?? '-') }}
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-2">
                                                    @php
                                                        $validasi = strtolower($absensi->status_validasi ?? '');
                                                        $vBadge = match ($validasi) {
                                                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                                                            'ditolak'   => 'bg-red-50 text-red-600',
                                                            default     => 'bg-amber-50 text-amber-700',
                                                        };
                                                    @endphp
                                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $vBadge }}">
                                                        {{ ucfirst($absensi->status_validasi ?: 'Menunggu') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>
</html>