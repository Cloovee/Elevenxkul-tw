<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Kelola Anggota - Ekskul Sebelas</title>
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
                        <h1 class="text-2xl font-extrabold text-ink">Kelola Anggota</h1>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 relative min-h-[420px]">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">Daftar Anggota</h2>

                        <div class="space-y-2">
                            @forelse ($anggota as $item)
                                <a href="{{ route('ketua.kelola-anggota.detail', $item->id_anggota) }}"
                                   class="flex items-center justify-between gap-4 bg-white rounded-2xl px-4 py-3 shadow-sm ring-1 ring-black/5 hover:shadow-md transition"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-periwinkle flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($item->siswa->nama_siswa, 0, 1)) }}
                                        </div>

                                        <p class="font-medium text-ink">
                                            {{ $item->siswa->nama_siswa }}
                                        </p>
                                    </div>

                                    <span class="text-xs font-medium px-3 py-1 rounded-full {{ $item->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </a>
                            @empty
                                <div class="text-center py-10 text-inksoft">
                                    Belum ada anggota.
                                </div>
                            @endforelse
                        </div>

                        <!-- Tombol tambah, mengambang di kanan bawah -->
                        <a href="{{ route('ketua.kelola-anggota.tambah') }}"
                           class="absolute bottom-6 right-6 w-14 h-14 rounded-full bg-periwinkle flex items-center justify-center text-white shadow-lg shadow-periwinkle/30 hover:opacity-90 transition"
                           title="Tambah anggota"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>