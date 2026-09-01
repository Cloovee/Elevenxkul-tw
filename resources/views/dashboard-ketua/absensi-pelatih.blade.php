<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Pelatih - Ekskul Sebelas</title>
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
                        <h1 class="text-2xl font-extrabold text-gray-800">Absensi Pelatih</h1>
                        <p class="text-sm text-gray-500 mt-1">Catat apakah pelatih hadir/melatih hari ini. Laporan akan divalidasi oleh pembina ekskul terkait.</p>
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

                    <form method="POST" action="{{ route('ketua.absensi-pelatih.store') }}" enctype="multipart/form-data"
                          class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ preview: null }">
                        @csrf

                        <!-- Foto bukti absensi -->
                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Foto Bukti Absensi (opsional)</h2>

                            <label
                                class="block w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-periwinkle/30 bg-periwinkle/5 flex items-center justify-center cursor-pointer overflow-hidden hover:bg-periwinkle/10 transition"
                            >
                                <template x-if="!preview">
                                    <div class="text-center px-4">
                                        <div class="w-12 h-12 rounded-2xl bg-periwinkle/15 flex items-center justify-center mx-auto mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-periwinkle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Upload foto absen</p>
                                        <p class="text-xs text-gray-400 mt-1">PNG atau JPG, maks 2MB</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover" />
                                </template>
                                <input type="file" name="foto_kehadiran" accept="image/*" class="hidden"
                                    @change="preview = URL.createObjectURL($event.target.files[0])"
                                />
                            </label>

                            <label class="mt-4 block w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold text-sm cursor-pointer hover:opacity-90 transition"
                                onclick="this.previousElementSibling.querySelector('input').click()"
                            >
                                Pilih Foto
                            </label>
                        </div>

                        <!-- Data absensi -->
                        <div class="bg-gradient-to-br from-white to-sky/10 rounded-3xl shadow-xl shadow-sky/10 ring-1 ring-black/5 p-6">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Data Absensi</h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pelatih</label>
                                    <select name="id_pelatih" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    >
                                        <option value="">Pilih pelatih</option>
                                        @foreach ($pelatihs as $p)
                                            <option value="{{ $p->id_pelatih }}" @selected(old('id_pelatih') == $p->id_pelatih)>{{ $p->nama_pelatih }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                                    <input type="date" name="tanggal_absensi" required value="{{ old('tanggal_absensi', now()->toDateString()) }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kegiatan</label>
                                    <input type="text" name="kegiatan" value="{{ old('kegiatan') }}" placeholder="Contoh: Latihan rutin Futsal"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kehadiran</label>
                                    <select name="status_kehadiran" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    >
                                        <option value="">Pilih status</option>
                                        <option value="hadir" @selected(old('status_kehadiran') === 'hadir')>Hadir / Melatih</option>
                                        <option value="izin" @selected(old('status_kehadiran') === 'izin')>Izin</option>
                                        <option value="sakit" @selected(old('status_kehadiran') === 'sakit')>Sakit</option>
                                        <option value="alpha" @selected(old('status_kehadiran') === 'alpha')>Tidak Melatih</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol kirim -->
                        <div class="lg:col-span-2">
                            <button type="submit"
                                class="w-full py-3 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30"
                            >
                                Kirim Absensi
                            </button>
                        </div>
                    </form>

                    <!-- Riwayat -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-black/5 ring-1 ring-black/5 p-6">
                        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Riwayat Laporan yang Dikirim</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="text-gray-400 text-[11px] uppercase tracking-wide">
                                        <th class="text-left py-2 px-2">Pelatih</th>
                                        <th class="text-left py-2 px-2">Tanggal</th>
                                        <th class="text-left py-2 px-2">Kehadiran</th>
                                        <th class="text-left py-2 px-2">Status Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayat as $r)
                                        <tr class="border-t border-gray-100">
                                            <td class="py-2.5 px-2 font-medium text-gray-700">{{ $r->pelatih->nama_pelatih ?? '-' }}</td>
                                            <td class="py-2.5 px-2 text-gray-500">{{ optional($r->tanggal_absensi)->translatedFormat('d M Y') }}</td>
                                            <td class="py-2.5 px-2">
                                                @php
                                                    $kClass = match($r->status_kehadiran) {
                                                        'hadir' => 'bg-mint text-[#1F7A3D]',
                                                        'izin' => 'bg-sky text-[#1E6FA8]',
                                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                                        'alpha' => 'bg-red-100 text-red-500',
                                                        default => 'bg-gray-100 text-gray-600',
                                                    };
                                                @endphp
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $kClass }}">{{ ucfirst($r->status_kehadiran) }}</span>
                                            </td>
                                            <td class="py-2.5 px-2">
                                                @php
                                                    $vClass = match($r->status_validasi) {
                                                        'Divalidasi' => 'bg-mint text-[#1F7A3D]',
                                                        'Ditolak' => 'bg-red-100 text-red-500',
                                                        default => 'bg-sky text-[#1E6FA8]',
                                                    };
                                                @endphp
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $vClass }}">{{ $r->status_validasi }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-sm text-gray-400">Belum ada laporan yang dikirim.</td>
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
