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

<body class="font-sans antialiased bg-gray-100">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <!-- Dekorasi background -->
        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/25 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-5">

                    <!-- Judul halaman + identitas user -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">
                                Dashboard
                            </p>

                            <h1 class="text-2xl font-extrabold text-gray-800">
                                Ekskul Sebelas
                            </h1>
                        </div>

                        <div class="flex items-center gap-3 bg-white/90 backdrop-blur rounded-2xl pl-4 pr-2 py-2 shadow-lg shadow-black/5 ring-1 ring-black/5">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-800 leading-none">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="text-xs text-gray-400 mt-0.5">
                                    Ketua Ekskul
                                </p>
                            </div>

                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-periwinkle to-sky flex items-center justify-center text-white font-semibold text-sm shadow-md shadow-periwinkle/40">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>

                    <!-- Grid 2x2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- Sapaan Ketua -->
                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-8 flex flex-col items-center justify-center text-center min-h-[220px] relative overflow-hidden hover:shadow-2xl hover:shadow-periwinkle/20 transition-shadow">

                            <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full bg-periwinkle/20 blur-2xl"></div>
                            <div class="absolute -bottom-8 -left-8 w-28 h-28 rounded-full bg-sky/20 blur-2xl"></div>

                            <div class="relative z-10">

                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-periwinkle to-lavender flex items-center justify-center mx-auto mb-4 shadow-lg shadow-periwinkle/40">
                                    <span class="text-2xl">👋</span>
                                </div>

                                <p class="text-gray-400 text-sm font-medium">
                                    Selamat datang
                                </p>

                                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
                                    Halo, {{ auth()->user()->name }}
                                </h3>

                                <p class="text-gray-500 text-sm mt-2">
                                    Semoga harimu menyenangkan, Ketua!
                                </p>

                            </div>
                        </div>

                        <!-- Statistik hadir minggu ini -->
                        <div class="bg-gradient-to-br from-white to-sky/10 rounded-3xl shadow-xl shadow-sky/10 ring-1 ring-black/5 p-8 flex flex-col justify-center min-h-[220px] hover:shadow-2xl hover:shadow-sky/20 transition-shadow relative overflow-hidden">

                            <div class="absolute -top-12 -left-12 w-40 h-40 rounded-full bg-sky/20 blur-2xl"></div>

                            <div class="relative z-10">

                                <div class="flex items-center gap-2.5 mb-5">

                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky to-periwinkle/60 flex items-center justify-center shadow-md shadow-sky/40">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-4.5 h-4.5 text-white"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2.5">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>

                                    <p class="text-sm text-gray-600 font-medium">
                                        Statistik hadir minggu ini
                                    </p>

                                </div>

                                <div class="flex items-end gap-3 h-24">
                                    <div class="flex-1 bg-gradient-to-t from-periwinkle/40 to-periwinkle/10 rounded-t-lg shadow-inner" style="height: 40%"></div>
                                    <div class="flex-1 bg-gradient-to-t from-periwinkle/60 to-periwinkle/20 rounded-t-lg shadow-inner" style="height: 65%"></div>
                                    <div class="flex-1 bg-gradient-to-t from-periwinkle to-periwinkle/50 rounded-t-lg shadow-lg shadow-periwinkle/40" style="height: 90%"></div>
                                    <div class="flex-1 bg-gradient-to-t from-sky to-sky/40 rounded-t-lg shadow-md shadow-sky/30" style="height: 55%"></div>
                                    <div class="flex-1 bg-gradient-to-t from-sky/70 to-sky/20 rounded-t-lg shadow-inner" style="height: 30%"></div>
                                </div>

                                <div class="flex justify-between text-xs text-gray-400 mt-2 font-medium">
                                    <span>Sen</span>
                                    <span>Sel</span>
                                    <span>Rab</span>
                                    <span>Kam</span>
                                    <span>Jum</span>
                                </div>

                            </div>
                        </div>

                        <!-- Jumlah peserta ekskul -->
                        <div class="bg-gradient-to-br from-white to-mint/20 rounded-3xl shadow-xl shadow-emerald-900/5 ring-1 ring-black/5 p-8 flex flex-col items-center justify-center text-center min-h-[220px] hover:shadow-2xl hover:shadow-emerald-900/10 transition-shadow relative overflow-hidden">

                            <div class="absolute -bottom-10 -right-10 w-36 h-36 rounded-full bg-mint/30 blur-2xl"></div>

                            <div class="relative z-10">

                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-mint to-emerald-200 flex items-center justify-center mb-4 mx-auto shadow-lg shadow-emerald-900/10">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-7 h-7 text-emerald-700"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>

                                <p class="text-4xl font-extrabold text-gray-800">
                                    32
                                </p>

                                <p class="text-sm text-gray-500 mt-1 font-medium">
                                    Jumlah Peserta Ekskul
                                </p>

                            </div>
                        </div>

                        <!-- Riwayat aktivitas ketua -->
                        <div class="bg-gradient-to-br from-white to-lavender/15 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 min-h-[220px] flex flex-col hover:shadow-2xl hover:shadow-periwinkle/15 transition-shadow relative overflow-hidden">

                            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-lavender/25 blur-2xl"></div>

                            <div class="relative z-10 flex flex-col h-full">

                                <div class="flex items-center gap-2.5 mb-4">

                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-lavender to-periwinkle/70 flex items-center justify-center shadow-md shadow-periwinkle/30">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-4.5 h-4.5 text-white"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2.5">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <p class="text-sm text-gray-600 font-medium">
                                        Riwayat yang dilakukan ketua
                                    </p>

                                </div>

                                <div class="flex-1 flex flex-col gap-2.5">

                                    <div class="flex-1 bg-white rounded-2xl flex items-center px-4 gap-3 shadow-sm ring-1 ring-black/5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-periwinkle shrink-0 shadow-sm shadow-periwinkle/60"></span>
                                        <p class="text-sm text-gray-700">
                                            Menginput absensi pelatih Futsal
                                        </p>
                                    </div>

                                    <div class="flex-1 bg-white rounded-2xl flex items-center px-4 gap-3 shadow-sm ring-1 ring-black/5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-sky shrink-0 shadow-sm shadow-sky/60"></span>
                                        <p class="text-sm text-gray-700">
                                            Menambahkan anggota baru
                                        </p>
                                    </div>

                                    <div class="flex-1 bg-white rounded-2xl flex items-center px-4 gap-3 shadow-sm ring-1 ring-black/5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-mint shrink-0 shadow-sm shadow-emerald-400/60"></span>
                                        <p class="text-sm text-gray-700">
                                            Memvalidasi 5 absensi peserta
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>