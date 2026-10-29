<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Anggota - ElevenXkul</title>
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
                        <h1 class="text-2xl font-extrabold text-ink">Kelola Anggota</h1>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 relative min-h-[420px]">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3 mb-4">Daftar Anggota</h2>

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

                        <div class="overflow-x-auto rounded-xl ring-1 ring-black/5">
                            <table class="min-w-[1050px] w-full text-sm text-left">
                                <thead class="bg-blue-50 text-ink">
                                    <tr>
                                        <th scope="col" class="px-4 py-4 font-semibold">NISN</th>
                                        <th scope="col" class="px-4 py-4 font-semibold">NIS</th>
                                        <th scope="col" class="px-4 py-4 font-semibold">Nama</th>
                                        <th scope="col" class="px-4 py-4 font-semibold">Kelas</th>
                                        <th scope="col" class="px-4 py-4 font-semibold">JK</th>
                                        <th scope="col" class="px-4 py-4 font-semibold">Status</th>
                                        <th scope="col" class="px-4 py-4 font-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($anggota as $item)
                                        <tr class="hover:bg-blue-50/40 transition">
                                            <td class="px-4 py-4 whitespace-nowrap">{{ $item->siswa->NISN ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap">{{ $item->siswa->NIS ?? '-' }}</td>
                                            <td class="px-4 py-4 font-medium text-ink">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if (($item->siswa->jk ?? '') === 'L')
                                                    Laki-laki
                                                @elseif (($item->siswa->jk ?? '') === 'P')
                                                    Perempuan
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $item->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $item->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('ketua.kelola-anggota.detail', $item->id_anggota) }}"
                                                       class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-2 font-medium text-blue-700 hover:bg-blue-100 transition">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('ketua.kelola-anggota.destroy', $item->id_anggota) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($item->siswa->nama_siswa ?? 'anggota ini') }} dari daftar anggota?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center rounded-lg bg-red-50 px-3 py-2 font-medium text-red-600 hover:bg-red-100 transition">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-10 text-center text-inksoft">Belum ada anggota.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol tambah, melayang tetap di kanan bawah layar (di atas nav bawah pada mobile) -->
                        <a href="{{ route('ketua.kelola-anggota.tambah') }}"
                           class="fixed bottom-24 right-5 md:bottom-8 md:right-8 z-40 w-14 h-14 rounded-full bg-periwinkle flex items-center justify-center text-white shadow-xl shadow-periwinkle/40 ring-4 ring-white hover:opacity-90 hover:scale-105 transition"
                           title="Tambah anggota" aria-label="Tambah anggota"
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