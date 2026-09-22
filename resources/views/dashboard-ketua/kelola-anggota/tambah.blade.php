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
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">

            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">
                            Ketua
                        </p>

                        <h1 class="text-2xl font-extrabold text-ink">
                            Tambah Anggota
                        </h1>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">

                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-6">
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

                            <!-- Pilih Siswa (multi-select dengan pencarian) -->
                            <div
                                x-data="{
                                    open: false,
                                    search: '',
                                    kelasFilter: '',
                                    daftar: @js($daftarSiswa ?? []),
                                    selected: [],
                                    init() {
                                        const oldIds = @js(old('id_siswa', []));
                                        if (oldIds && oldIds.length) {
                                            this.selected = this.daftar.filter(s => oldIds.includes(s.id_siswa));
                                        }
                                    },
                                    get filtered() {
                                        return this.daftar.filter(s => {
                                            const cocokNama = s.nama_siswa.toLowerCase().includes(this.search.toLowerCase())
                                                || (s.nis ?? '').toLowerCase().includes(this.search.toLowerCase());
                                            const cocokKelas = this.kelasFilter === '' || s.nama_kelas === this.kelasFilter;
                                            return cocokNama && cocokKelas;
                                        });
                                    },
                                    isDipilih(s) {
                                        return this.selected.some(x => x.id_siswa === s.id_siswa);
                                    },
                                    toggle(s) {
                                        if (this.isDipilih(s)) {
                                            this.selected = this.selected.filter(x => x.id_siswa !== s.id_siswa);
                                        } else {
                                            this.selected.push(s);
                                        }
                                    },
                                    hapus(s) {
                                        this.selected = this.selected.filter(x => x.id_siswa !== s.id_siswa);
                                    }
                                }"
                                @click.away="open = false"
                                class="relative"
                            >
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Siswa
                                </label>

                                <template x-for="s in selected" :key="s.id_siswa">
                                    <input type="hidden" name="id_siswa[]" :value="s.id_siswa">
                                </template>

                                <!-- Chip siswa yang udah dipilih -->
                                <div x-show="selected.length > 0" x-cloak class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="s in selected" :key="s.id_siswa">
                                        <span class="inline-flex items-center gap-1.5 pl-3 pr-2 py-1.5 rounded-full bg-periwinkle/10 text-ink text-sm">
                                            <span x-text="s.nama_siswa"></span>
                                            <button type="button" @click="hapus(s)" class="w-4 h-4 flex items-center justify-center rounded-full hover:bg-periwinkle/20 text-gray-500">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                <!-- Trigger -->
                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition text-left"
                                >
                                    <span x-show="selected.length > 0" x-cloak class="text-ink font-medium" x-text="selected.length + ' siswa dipilih'"></span>
                                    <span x-show="selected.length === 0" class="text-gray-400">Pilih siswa dari daftar</span>

                                    <svg class="w-4 h-4 text-gray-400 shrink-0 ml-2" :class="open ? 'rotate-180' : ''" style="transition: transform .15s" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Panel dropdown -->
                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition
                                    class="absolute z-20 mt-2 w-full bg-white rounded-xl border border-gray-200 shadow-xl overflow-hidden"
                                >
                                    <!-- Search bar + filter kelas -->
                                    <div class="p-3 border-b border-gray-100 flex flex-col sm:flex-row gap-2">
                                        <div class="relative flex-1">
                                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input
                                                type="text"
                                                x-model="search"
                                                placeholder="Cari nama atau NIS..."
                                                class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-bgsoft text-sm focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                                @click.stop
                                            />
                                        </div>

                                        <select
                                            x-model="kelasFilter"
                                            class="sm:w-40 px-3 py-2 rounded-lg border border-gray-200 bg-bgsoft text-sm focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                            @click.stop
                                        >
                                            <option value="">Semua kelas</option>
                                            @foreach ($daftarKelas ?? [] as $k)
                                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Daftar opsi -->
                                    <ul class="max-h-64 overflow-y-auto py-1">
                                        <template x-for="s in filtered" :key="s.id_siswa">
                                            <li
                                                @click="toggle(s)"
                                                class="px-4 py-2.5 text-sm cursor-pointer hover:bg-periwinkle/10 flex items-center gap-3"
                                                :class="isDipilih(s) ? 'bg-periwinkle/10' : ''"
                                            >
                                                <span
                                                    class="w-4 h-4 shrink-0 rounded border flex items-center justify-center"
                                                    :class="isDipilih(s) ? 'bg-periwinkle border-periwinkle' : 'border-gray-300'"
                                                >
                                                    <svg x-show="isDipilih(s)" x-cloak class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>

                                                <span class="font-medium text-ink flex-1" x-text="s.nama_siswa"></span>
                                                <span class="text-xs text-gray-400 shrink-0" x-text="s.nis + ' · ' + s.nama_kelas"></span>
                                            </li>
                                        </template>

                                        <li x-show="filtered.length === 0" x-cloak class="px-4 py-6 text-center text-sm text-gray-400">
                                            Siswa tidak ditemukan.
                                        </li>
                                    </ul>

                                    <!-- Footer panel -->
                                    <div class="p-3 border-t border-gray-100 flex items-center justify-between bg-bgsoft/50">
                                        <span class="text-xs text-gray-400" x-text="selected.length + ' dipilih'"></span>
                                        <button type="button" @click="open = false" class="text-sm font-semibold text-periwinkle hover:opacity-80">
                                            Selesai
                                        </button>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-400 mt-2">
                                    Bisa pilih lebih dari satu siswa sekaligus. Cuma siswa yang belum jadi anggota ekskul ini yang muncul di daftar.
                                </p>
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
                                    class="px-5 py-2.5 rounded-xl bg-periwinkle text-white font-semibold shadow-lg shadow-periwinkle/30 hover:opacity-90 transition"
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