<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Kegiatan - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-extrabold text-ink">Laporan Kegiatan</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Rekap kegiatan pelatih per bulan (foto, deskripsi, dan tanggal) yang diambil dari absensi pelatih.
                            Pilih bulan, cek pratinjaunya, lalu cetak.
                        </p>
                    </div>

                    @if (! $pelatih)
                        <div class="bg-amber-50 text-amber-700 text-sm font-medium px-4 py-3 rounded-2xl">
                            Ekskul <span class="font-semibold">{{ $ekskul->nama_ekskul }}</span> belum punya pelatih.
                            Minta pembina menentukan pelatihnya dulu.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Pilih bulan -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">
                        <form method="GET" action="{{ route('ketua.laporan-kegiatan') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
                            <div class="flex-1 max-w-xs">
                                <label for="bulan" class="block text-sm font-medium text-ink mb-1.5">Bulan</label>
                                <input type="month" id="bulan" name="bulan" value="{{ $bulanInput }}" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition">
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-white ring-1 ring-periwinkle/40 text-periwinkle font-semibold text-sm hover:bg-periwinkle/5 transition">
                                    Tampilkan
                                </button>
                                @if ($pelatih && $kegiatan->isNotEmpty())
                                    <a href="{{ route('ketua.laporan-kegiatan.cetak', ['bulan' => $bulanInput]) }}" target="_blank" rel="noopener"
                                       class="px-5 py-2.5 rounded-xl bg-periwinkle text-white font-semibold text-sm shadow-md shadow-periwinkle/30 hover:opacity-90 transition">
                                        Cetak Laporan
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if ($dikecualikan->isNotEmpty())
                        <div class="bg-amber-50 text-amber-700 text-sm px-4 py-3 rounded-2xl">
                            <p class="font-semibold">{{ $dikecualikan->count() }} absensi bulan ini tidak masuk laporan:</p>
                            <ul class="list-disc list-inside mt-1 space-y-0.5">
                                @foreach ($dikecualikan as $d)
                                    <li>
                                        {{ $d->absensi->tanggal_absensi->locale('id')->translatedFormat('d F Y') }}
                                        @if ($d->absensi->kegiatan) ({{ $d->absensi->kegiatan }}) @endif
                                        &mdash; {{ $d->alasan }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Pratinjau -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">
                            Kegiatan {{ $namaBulan }}
                            <span class="text-xs font-normal text-gray-400">({{ $kegiatan->count() }} kegiatan)</span>
                        </h2>

                        @if ($kegiatan->isEmpty())
                            <p class="text-sm text-gray-400 py-6 text-center">
                                Belum ada absensi pelatih di bulan ini.
                            </p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach ($kegiatan as $k)
                                    <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden bg-white shadow-sm">
                                        @if ($k->foto_url)
                                            <img src="{{ $k->foto_url }}" alt="Foto kegiatan" class="w-full aspect-[4/3] object-cover">
                                        @else
                                            <div class="w-full aspect-[4/3] bg-gray-50 flex items-center justify-center text-xs text-gray-400">
                                                Tidak ada foto
                                            </div>
                                        @endif
                                        <div class="p-3">
                                            <p class="text-xs font-semibold text-periwinkle">{{ $k->tanggal_absensi->locale('id')->translatedFormat('l, d F Y') }}</p>
                                            <p class="text-sm text-ink mt-1">{{ $k->kegiatan ?: 'Kegiatan ekstrakurikuler' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>