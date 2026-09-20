<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Ketua - Ekskul Sebelas</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <div class="flex-1 space-y-5">

                    <!-- Judul halaman + identitas user -->
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Dashboard</p>
                            <h1 class="text-2xl font-extrabold text-ink">Ekskul Sebelas</h1>
                        </div>

                        <div class="flex items-center gap-3 bg-white/90 backdrop-blur rounded-2xl pl-4 pr-2 py-2 shadow-lg shadow-black/5 ring-1 ring-black/5">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-ink leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Ketua Ekskul</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-periwinkle flex items-center justify-center text-white font-semibold text-sm shadow-md shadow-periwinkle/30">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>

                    <!-- Sapaan -->
                    <div class="bg-periwinkle rounded-3xl shadow-xl shadow-periwinkle/20 p-6 sm:p-8 relative overflow-hidden">
                        <div class="relative z-10 flex items-center gap-4">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-extrabold text-white">
                                    Halo, {{ auth()->user()->name }}
                                </h3>
                                <p class="text-white/85 text-sm mt-1">
                                    Semoga harimu menyenangkan, Ketua! Berikut ringkasan ekskulmu hari ini.
                                </p>
                            </div>
                        </div>
                    </div>

                   <!-- Statistik ringkas -->
                    <div class="grid grid-cols-2 gap-4 w-full">
    
                        <!-- Total Peserta -->
                        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-black/5 p-5 w-full">
                            <div class="w-10 h-10 rounded-xl bg-mint/60 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-navy-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>

                            <p class="text-3xl font-extrabold text-ink">
                                {{ $jumlahPeserta }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1 font-medium">
                                Total Peserta
                            </p>
                        </div>

                        <!-- Peserta Aktif -->
                        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-black/5 p-5 w-full">
                            <div class="w-10 h-10 rounded-xl bg-mint/60 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-navy"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <p class="text-3xl font-extrabold text-ink">
                                {{ $totalPeserta }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1 font-medium">
                                Peserta Aktif
                            </p>
                        </div>

                    </div>

                    <!-- Akses cepat -->
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-black/5 p-6">
                        <h2 class="font-semibold text-ink mb-4">Akses Cepat</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <a href="{{ route('ketua.absensi-pelatih') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-periwinkle/10 hover:bg-periwinkle/20 transition">
                                <div class="w-10 h-10 rounded-xl bg-periwinkle flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-ink">Absensi Pelatih</p>
                                    <p class="text-xs text-gray-500">Catat kehadiran pelatih</p>
                                </div>
                            </a>

                            <a href="{{ route('ketua.absensi-peserta') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-mint/50 hover:bg-mint/70 transition">
                                <div class="w-10 h-10 rounded-xl bg-sky flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-ink">Absensi Peserta</p>
                                    <p class="text-xs text-gray-500">Catat kehadiran anggota</p>
                                </div>
                            </a>

                            <a href="{{ route('ketua.kelola-anggota') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-periwinkle/10 hover:bg-periwinkle/20 transition">
                                <div class="w-10 h-10 rounded-xl bg-navy flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-ink">Kelola Anggota</p>
                                    <p class="text-xs text-gray-500">Lihat & tambah anggota</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Riwayat aktivitas -->
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-black/5 p-6">
                        <h2 class="font-semibold text-ink mb-4">Aktivitas Terbaru</h2>

                        @if ($riwayat->isEmpty())
                            <p class="text-sm text-gray-400 text-center py-6">Belum ada aktivitas tercatat.</p>
                        @else
                            <div class="space-y-2.5">
                                @foreach ($riwayat as $item)
                                    <div class="flex items-center gap-3 bg-gray-50 rounded-2xl px-4 py-3">
                                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $item['warna'] }} shrink-0"></span>
                                        <p class="text-sm text-gray-700 flex-1">{{ $item['teks'] }}</p>
                                        <p class="text-xs text-gray-400 shrink-0">{{ $item['waktu']->diffForHumans() }}</p>
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