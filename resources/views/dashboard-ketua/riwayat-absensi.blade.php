<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Riwayat Absensi - Ekskul Sebelas</title>
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
                <div class="flex-1 space-y-6" x-data="{ tab: 'peserta' }">

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

                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">
                            Histori Absensi Peserta
                            <span class="text-xs font-normal text-gray-400">({{ $riwayatPeserta->count() }} catatan)</span>
                        </h2>

                        @if ($riwayatPeserta->isEmpty())
                            <p class="text-sm text-gray-400 py-6 text-center">Belum ada riwayat absensi peserta.</p>
                        @else
                            <div class="overflow-x-auto -mx-2">
                                <table class="w-full text-sm min-w-[640px]">
                                    <thead>
                                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                                            <th class="py-2.5 px-2">Tanggal</th>
                                            <th class="py-2.5 px-2">Nama Peserta</th>
                                            <th class="py-2.5 px-2">Kegiatan</th>
                                            <th class="py-2.5 px-2">Status</th>
                                            <th class="py-2.5 px-2">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPeserta as $absensi)
                                            <tr class="border-b border-gray-50 last:border-b-0">
                                                <td class="py-2.5 px-2 whitespace-nowrap">
                                                    {{ optional($absensi->tanggal_absensi)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="py-2.5 px-2 font-medium text-ink">
                                                    {{ $absensi->peserta->siswa->nama_siswa ?? '-' }}
                                                </td>
                                                <td class="py-2.5 px-2 text-gray-500">
                                                    {{ $absensi->deskripsi_kegiatan ?: '-' }}
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
                                                <td class="py-2.5 px-2 text-gray-500">
                                                    {{ $absensi->catatan ?: '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
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