<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tambah Anggota - Ekskul Sebelas</title>

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

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">
                            Ketua
                        </p>

                        <h1 class="text-2xl font-extrabold text-gray-800">
                            Tambah Anggota
                        </h1>
                    </div>

                    <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">

                        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-6">
                            Data Anggota Baru
                        </h2>

                        <form action="{{ route('ketua.kelola-anggota.store') }}" method="POST" class="space-y-5">

                            @csrf

                            @if ($errors->any())
                                <div class="rounded-xl bg-red-50 p-4 text-sm text-red-600">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="rounded-xl bg-red-50 p-4 text-sm text-red-600">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Siswa
                                </label>

                                <input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    placeholder="Masukkan nama siswa"
                                    class="w-full rounded-xl border-gray-300 focus:border-periwinkle focus:ring-periwinkle"
                                >
                            </div>

                            <!-- NIS -->
                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIS
                                </label>

                                <input
                                    type="text"
                                    id="nis"
                                    name="nis"
                                    placeholder="Masukkan NIS siswa"
                                    class="w-full rounded-xl border-gray-300 focus:border-periwinkle focus:ring-periwinkle"
                                >
                            </div>

                            <!-- Tombol -->
                            <div class="flex items-center justify-end gap-3 pt-3">

                                <a
                                    href="{{ route('ketua.kelola-anggota') }}"
                                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition"
                                >
                                    Batal
                                </a>

                                <button
                                    type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold shadow-lg shadow-periwinkle/30 hover:opacity-90 transition"
                                >
                                    Tambah Anggota
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>