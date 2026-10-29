<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Import Anggota - ElevenXkul</title>
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
                        <h1 class="text-2xl font-extrabold text-ink">Import Anggota</h1>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 max-w-2xl mx-auto">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">
                            Import ke {{ $ekskul->nama_ekskul }}
                        </h2>

                        @if (session('success'))
                            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3 mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="rounded-xl bg-red-50 text-red-600 text-sm px-4 py-3 mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="rounded-xl bg-amber-50 text-amber-700 text-sm px-4 py-3 mb-4">
                                {{ session('warning') }}
                                @if (session('baris_error'))
                                    <ul class="list-disc pl-6 mt-2 space-y-1">
                                        @foreach (session('baris_error') as $e)
                                            <li>Baris {{ $e['baris'] }}: {{ $e['error'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('ketua.kelola-anggota.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-inksoft uppercase tracking-wider mb-2">
                                    Pilih File Excel <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="w-full px-4 py-2.5 bg-bgsoft ring-1 ring-ink/10 rounded-xl text-sm text-ink file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:bg-periwinkle file:text-white file:text-xs file:font-bold hover:file:opacity-90 @error('file') ring-2 ring-red-400 @enderror">
                                <p class="text-xs text-inksoft mt-2">Format: .xlsx, .xls, .csv &middot; Maks 5MB</p>
                                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-6 p-4 bg-bgsoft ring-1 ring-ink/10 rounded-2xl text-sm text-ink">
                                <p class="font-bold mb-1">Isi tiga kolom: NIS, Nama, Kelas</p>
                                <ul class="text-xs text-inksoft list-disc pl-5 space-y-1">
                                    <li>Siswa dicocokkan lewat <span class="font-mono">NIS</span>. NISN, JK, dan data lain diambil otomatis dari Data Siswa.</li>
                                    <li>Siswa harus <b>sudah ada</b> di Data Siswa (ditambahkan Admin). Import ini tidak membuat siswa baru.</li>
                                    <li><span class="font-mono">Nama</span> dan <span class="font-mono">Kelas</span> hanya sebagai pengingat, tidak dipakai untuk mencocokkan. Anggota otomatis berstatus aktif dengan tanggal bergabung hari ini.</li>
                                    <li>Siswa yang sudah jadi anggota dilewati, jadi file hasil Export aman diimport ulang.</li>
                                </ul>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-periwinkle text-white rounded-full text-sm font-bold shadow-md shadow-periwinkle/30 hover:opacity-90 transition">
                                    Import
                                </button>
                                <a href="{{ route('ketua.kelola-anggota.template') }}"
                                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-white ring-1 ring-periwinkle/40 text-periwinkle hover:bg-periwinkle/5 rounded-full text-sm font-bold transition">
                                    Download Template
                                </a>
                                <a href="{{ route('ketua.kelola-anggota.tambah') }}"
                                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-white ring-1 ring-ink/20 text-inksoft hover:bg-bgsoft rounded-full text-sm font-bold transition">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>