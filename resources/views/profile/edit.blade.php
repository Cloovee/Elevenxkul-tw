<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil - Ekskul Sebelas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/25 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Sidebar icon-only -->
                <aside class="lg:w-20 shrink-0">
                    <div class="bg-gradient-to-b from-periwinkle to-sky rounded-3xl p-3 flex flex-col items-center gap-2 sticky top-6 min-h-[540px] shadow-2xl shadow-periwinkle/40 ring-1 ring-white/20">
                        <a href="{{ route('dashboard.ketua') }}" class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center font-bold text-periwinkle mb-4 shadow-md">
                            K
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-periwinkle shadow-lg shadow-black/10"
                           title="Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                        <a href="{{ route('dashboard.ketua') }}" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Absensi Pelatih">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Absensi Peserta">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Kelola Anggota">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                            @csrf
                            <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Keluar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Konten profil -->
                <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Data akun, read-only, 2 kolom -->
                    <div class="lg:col-span-2 bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-8 space-y-6">

                        <div>
                            <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Pengaturan</p>
                            <h1 class="text-2xl font-extrabold text-gray-800">Profil</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Data akun dikelola oleh Admin. Hubungi Admin untuk perubahan data.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1.5">Nama</label>
                            <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1.5">Email</label>
                            <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1.5">Kata Sandi</label>
                            <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-400 tracking-widest">
                                ••••••••••
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1.5">Peran</label>
                            <div class="inline-flex px-4 py-1.5 rounded-full bg-periwinkle/15 text-periwinkle text-sm font-semibold">
                                Ketua Ekskul
                            </div>
                        </div>
                    </div>

                    <!-- Panel foto, 1 kolom -->
                    <div class="bg-gradient-to-br from-sky to-periwinkle rounded-3xl shadow-xl shadow-periwinkle/20 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden min-h-[400px]">
                        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-16 -left-10 w-48 h-48 rounded-full bg-white/10"></div>

                        <div class="relative z-10">
                            <div class="relative w-24 h-24 mx-auto mb-4">
                                <div class="w-24 h-24 rounded-full bg-white/25 flex items-center justify-center ring-4 ring-white/30">
                                    <span class="text-4xl font-extrabold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <button type="button" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-md" title="Ganti foto (segera hadir)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-periwinkle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 13a3 3 0 100 6 3 3 0 000-6z" />
                                    </svg>
                                </button>
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                            <p class="text-white/80 text-sm mt-1">{{ $user->email }}</p>
                            <span class="inline-block mt-4 px-4 py-1.5 bg-white/20 rounded-full text-white text-sm font-medium">
                                Ketua Ekskul
                            </span>
                            <p class="text-white/60 text-xs mt-4">Fitur ganti foto segera hadir</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>