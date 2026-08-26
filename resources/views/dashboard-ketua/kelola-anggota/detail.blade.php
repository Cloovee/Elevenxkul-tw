<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Anggota - Ekskul Sebelas</title>
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

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div class="flex items-center gap-3">
                        <a href="{{ route('ketua.kelola-anggota') }}" class="w-9 h-9 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-500 hover:text-periwinkle transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div>
                            <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                            <h1 class="text-2xl font-extrabold text-gray-800">Kelola Anggota</h1>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-8">
                        <div class="flex flex-col md:flex-row gap-8">

                            <!-- Foto -->
                            <div class="shrink-0 flex flex-col items-center gap-3">
                                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-periwinkle to-sky flex items-center justify-center text-white text-5xl font-extrabold shadow-lg shadow-periwinkle/30">
                                    {{ strtoupper(substr($anggota['nama'], 0, 1)) }}
                                </div>
                            </div>

                            <!-- Form data -->
                            <form class="flex-1 space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1.5">Nama</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700">
                                        {{ $anggota['nama'] }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1.5">NIS</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700">
                                        {{ $anggota['nis'] }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1.5">Tanggal Bergabung</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700">
                                        {{ \Carbon\Carbon::parse($anggota['tanggal_bergabung'])->translatedFormat('d F Y') }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                                    <select name="status"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    >
                                        <option value="aktif" {{ $anggota['status'] === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak aktif" {{ $anggota['status'] === 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition"
                                >
                                    Simpan Status
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>