<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Absensi Peserta - Ekskul Sebelas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
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

                    <div>
                        <p class="text-xs text-inksoft tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-display font-bold text-ink">Absensi Peserta</h1>
                        <p class="text-sm text-inksoft mt-1">Data yang kamu simpan di sini langsung tampil di halaman Absensi Peserta milik pembina.</p>
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
                        @foreach ($pesertas as $p)
                            <input type="hidden" name="ids[]" value="{{ $p->id_anggota }}">
                        @endforeach

                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-5">
                            <h2 class="font-semibold text-ink border-b border-ink/10 pb-3">Data Absensi</h2>

                            <div>
                                <label class="block text-sm font-medium text-ink mb-1.5">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal_absensi" required value="{{ old('tanggal_absensi', now()->toDateString()) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-ink mb-1.5">Deskripsi Kegiatan</label>
                                <input type="text" name="deskripsi_kegiatan" placeholder="Contoh: Latihan rutin mingguan" value="{{ old('deskripsi_kegiatan') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-ink mb-2">Absensi</label>

                                @if ($pesertas->isEmpty())
                                    <p class="text-sm text-inksoft border border-dashed border-ink/10 rounded-xl py-6 text-center">
                                        Belum ada peserta aktif di ekskul ini.
                                    </p>
                                @else
                                    <div class="overflow-x-auto rounded-2xl ring-1 ring-ink/10"
                                        x-data="{
                                            statuses: { @foreach ($pesertas as $p) {{ $p->id_anggota }}: @js(old('status.'.$p->id_anggota)), @endforeach },
                                            get adaIzin() { return Object.values(this.statuses).includes('izin') }
                                        }"
                                    >
                                        <table class="w-full text-sm border-collapse min-w-[560px]">
                                            <thead>
                                                <tr class="bg-periwinkle/10 text-inksoft text-[11px] uppercase tracking-wide">
                                                    <th class="text-left py-2.5 px-3" rowspan="2">Nama</th>
                                                    <th class="text-left py-2.5 px-2" rowspan="2">Kelas</th>
                                                    <th class="text-center py-2 px-2" colspan="3">Keterangan</th>
                                                    <th class="text-left py-2.5 px-2" rowspan="2" x-show="adaIzin" x-cloak>Catatan</th>
                                                </tr>
                                                <tr class="bg-periwinkle/10 text-inksoft text-[11px] uppercase tracking-wide">
                                                    <th class="text-center py-1.5 px-2 w-12 border-t border-periwinkle/20">H</th>
                                                    <th class="text-center py-1.5 px-2 w-12 border-t border-periwinkle/20">S</th>
                                                    <th class="text-center py-1.5 px-2 w-12 border-t border-periwinkle/20">I</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pesertas as $p)
                                                    <tr class="border-t border-ink/10">
                                                        <td class="py-2.5 px-3 font-medium text-ink">{{ $p->nama }}</td>
                                                        <td class="py-2.5 px-2 text-inksoft">{{ $p->kelas ?? '-' }}</td>
                                                        <td class="text-center py-2.5 px-2">
                                                            <input type="radio" name="status[{{ $p->id_anggota }}]" value="hadir"
                                                                x-model="statuses[{{ $p->id_anggota }}]"
                                                                class="w-4 h-4 accent-periwinkle cursor-pointer">
                                                        </td>
                                                        <td class="text-center py-2.5 px-2">
                                                            <input type="radio" name="status[{{ $p->id_anggota }}]" value="sakit"
                                                                x-model="statuses[{{ $p->id_anggota }}]"
                                                                class="w-4 h-4 accent-yellow-500 cursor-pointer">
                                                        </td>
                                                        <td class="text-center py-2.5 px-2">
                                                            <input type="radio" name="status[{{ $p->id_anggota }}]" value="izin"
                                                                x-model="statuses[{{ $p->id_anggota }}]"
                                                                class="w-4 h-4 accent-sky cursor-pointer">
                                                        </td>
                                                        <td class="py-2 px-2" x-show="adaIzin" x-cloak>
                                                            <input type="text" name="catatan[{{ $p->id_anggota }}]" value="{{ old('catatan.'.$p->id_anggota) }}"
                                                                x-show="statuses[{{ $p->id_anggota }}] === 'izin'" x-cloak
                                                                placeholder="Keterangan izin"
                                                                class="w-full px-3 py-1.5 rounded-lg border border-ink/10 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-xs text-inksoft mt-2">Peserta yang tidak dicentang otomatis tercatat sebagai Alpha (tidak hadir).</p>
                                @endif
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30"
                        >
                            Kirim Absensi
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>