<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Detail Anggota - Ekskul Sebelas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink antialiased">

    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/25 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div class="flex items-center gap-3">
                        <a href="{{ route('ketua.kelola-anggota') }}" class="w-9 h-9 rounded-xl bg-white shadow-sm flex items-center justify-center text-inksoft hover:text-periwinkle transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div>
                            <p class="text-xs text-inksoft tracking-wide uppercase font-semibold">Ketua</p>
                            <h1 class="text-2xl font-display font-bold text-ink">Kelola Anggota</h1>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-8">
                        <div class="flex flex-col md:flex-row gap-8">

                            <!-- Foto -->
                            <div class="shrink-0 flex flex-col items-center gap-3">
                                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-periwinkle to-sky flex items-center justify-center text-white text-5xl font-display font-bold shadow-lg shadow-periwinkle/30">
                                    {{ strtoupper(substr($anggota['nama'], 0, 1)) }}
                                </div>
                            </div>

                            <!-- Form data -->
                            <form class="flex-1 space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-inksoft mb-1.5">Nama</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-ink/10 bg-bgsoft text-ink">
                                        {{ $anggota['nama'] }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-inksoft mb-1.5">NIS</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-ink/10 bg-bgsoft text-ink">
                                        {{ $anggota['nis'] }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-inksoft mb-1.5">Tanggal Bergabung</label>
                                    <div class="w-full px-4 py-2.5 rounded-xl border border-ink/10 bg-bgsoft text-ink">
                                        {{ \Carbon\Carbon::parse($anggota['tanggal_bergabung'])->translatedFormat('d F Y') }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-ink mb-1.5">Status</label>
                                    <select name="status"
                                        class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
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