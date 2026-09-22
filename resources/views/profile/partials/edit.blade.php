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
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten profil -->
                <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Data akun, read-only, 2 kolom -->
                    <div class="lg:col-span-2 bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-8 space-y-6">

                        <div>
                            <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Pengaturan</p>
                            <h1 class="text-2xl font-extrabold text-ink">Profil</h1>
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