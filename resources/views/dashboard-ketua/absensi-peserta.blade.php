<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Peserta - Ekskul Sebelas</title>
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
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-extrabold text-gray-800">Absensi Peserta</h1>
                        <p class="text-sm text-gray-500 mt-1">Data yang kamu simpan di sini langsung tampil di halaman Absensi Peserta milik pembina.</p>
                    </div>

                    @if (session('success'))
                        <div class="bg-mint/40 text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ketua.absensi-peserta.store') }}" class="space-y-6">
                        @csrf

                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-4">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3">Data Absensi</h2>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Peserta</label>
                                <select name="id_anggota" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                >
                                    <option value="">Pilih peserta</option>
                                    @foreach ($pesertas as $p)
                                        <option value="{{ $p->id_anggota }}" @selected(old('id_anggota') == $p->id_anggota)>
                                            {{ $p->nama }} — {{ $p->kelas ?? '-' }} ({{ $p->ekskul->nama_ekskul ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Kehadiran</label>
                                <input type="date" name="tanggal_absensi" required value="{{ old('tanggal_absensi', now()->toDateString()) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Kegiatan</label>
                                <textarea name="deskripsi_kegiatan" rows="3" placeholder="Ceritakan kegiatan hari ini..."
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition resize-none"
                                >{{ old('deskripsi_kegiatan') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kehadiran</label>
                                <select name="status_kehadiran" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                >
                                    <option value="">Pilih status</option>
                                    <option value="hadir" @selected(old('status_kehadiran') === 'hadir')>Hadir</option>
                                    <option value="izin" @selected(old('status_kehadiran') === 'izin')>Izin</option>
                                    <option value="sakit" @selected(old('status_kehadiran') === 'sakit')>Sakit</option>
                                    <option value="alpha" @selected(old('status_kehadiran') === 'alpha')>Tidak Hadir</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30"
                        >
                            Kirim Absensi
                        </button>
                    </form>

                    <!-- Riwayat -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-black/5 ring-1 ring-black/5 p-6">
                        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Riwayat Absensi yang Dikirim</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="text-gray-400 text-[11px] uppercase tracking-wide">
                                        <th class="text-left py-2 px-2">Peserta</th>
                                        <th class="text-left py-2 px-2">Ekskul</th>
                                        <th class="text-left py-2 px-2">Tanggal</th>
                                        <th class="text-left py-2 px-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayat as $r)
                                        <tr class="border-t border-gray-100">
                                            <td class="py-2.5 px-2 font-medium text-gray-700">{{ $r->peserta->nama ?? '-' }}</td>
                                            <td class="py-2.5 px-2 text-gray-500">{{ $r->peserta->ekskul->nama_ekskul ?? '-' }}</td>
                                            <td class="py-2.5 px-2 text-gray-500">{{ optional($r->tanggal_absensi)->translatedFormat('d M Y') }}</td>
                                            <td class="py-2.5 px-2">
                                                @php
                                                    $sClass = match($r->status_kehadiran) {
                                                        'hadir' => 'bg-mint text-[#1F7A3D]',
                                                        'izin' => 'bg-sky text-[#1E6FA8]',
                                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                                        'alpha' => 'bg-red-100 text-red-500',
                                                        default => 'bg-gray-100 text-gray-600',
                                                    };
                                                @endphp
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $sClass }}">{{ ucfirst($r->status_kehadiran) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-sm text-gray-400">Belum ada absensi yang dikirim.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $riwayat->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
