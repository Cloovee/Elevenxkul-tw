<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pelatih — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen">

<div class="flex gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'pelatih'])

    <main class="flex-1 min-w-0 flex flex-col gap-5 pb-24 md:pb-0">

        @if (session('success'))
            <div class="bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-gradient-to-br from-[#B5BAFF] via-[#E4E5FF] to-white shadow-[0_10px_30px_-18px_rgba(63,65,176,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/40 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="flex items-center justify-between flex-wrap gap-4 relative">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#3F41B0]">Data Pelatih</p>
                    <h1 class="font-display text-2xl font-semibold mt-1">Kelola Pelatih</h1>
                    <p class="text-sm text-inksoft mt-1 max-w-md">Tambah, ubah, atau hapus data pelatih untuk ekskul yang kamu bina.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/70 rounded-2xl px-4 py-2.5 text-center">
                        <div class="font-display text-xl font-bold text-[#3F41B0]">{{ $pelatih->total() }}</div>
                        <div class="text-[11px] text-inksoft">Pelatih terdaftar</div>
                    </div>
                </div>
            </div>

            @if($adaEkskulTanpaPembina)
                <p class="relative text-xs text-amber-700 bg-amber-50 mt-4 px-3 py-2 rounded-xl flex items-center gap-1.5 max-w-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" class="shrink-0"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Kamu belum ditugaskan membina ekskul manapun. Hubungi Admin agar kamu dikaitkan ke sebuah ekskul dulu.
                </p>
            @endif
        </div>

        {{-- TABEL --}}
        <div class="animate-fade-in-up animate-delay-1 bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">

            <div class="flex flex-wrap justify-between items-center gap-4 mb-5">
                <form method="GET" class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" placeholder="Cari nama pelatih..." value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 bg-bgsoft border-none rounded-full text-sm text-ink placeholder-inksoft focus:ring-2 focus:ring-lavender w-56">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-ink rounded-full text-sm font-bold transition-colors">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('pembina.pelatih.index') }}" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-inksoft rounded-full text-sm font-bold transition-colors">
                            Reset
                        </a>
                    @endif
                </form>

                <a href="{{ route('pembina.pelatih.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-lavender text-white rounded-full text-sm font-bold shadow-md shadow-lavender/30 hover:bg-[#8385f0] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Pelatih
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-inksoft text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="px-3 py-3">No</th>
                            <th class="px-3 py-3">Pelatih</th>
                            <th class="px-3 py-3">Ekskul</th>
                            <th class="px-3 py-3">JK</th>
                            <th class="px-3 py-3">No. HP</th>
                            <th class="px-3 py-3">Email</th>
                            <th class="px-3 py-3">Alamat</th>
                            <th class="px-3 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pelatih as $key => $p)
                        @php $initial = strtoupper(substr($p->nama_pelatih, 0, 1)); @endphp
                        <tr class="hover:bg-bgsoft/60 transition-colors">
                            <td class="px-3 py-3 text-inksoft font-medium">{{ $pelatih->firstItem() + $key }}</td>

                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-lavender text-white flex items-center justify-center font-extrabold text-sm flex-shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <p class="text-ink font-bold whitespace-nowrap">{{ $p->nama_pelatih }}</p>
                                </div>
                            </td>

                            <td class="px-3 py-3 whitespace-nowrap">
                                @forelse($p->ekskuls as $e)
                                    <span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold bg-periwinkle/20 text-[#5E5CC7] mr-1">{{ $e->nama_ekskul }}</span>
                                @empty
                                    <span class="text-inksoft">-</span>
                                @endforelse
                            </td>

                            <td class="px-3 py-3">
                                @if($p->jk)
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $p->jk == 'L' ? 'bg-blue-50 text-blue-500' : 'bg-pink-50 text-pink-500' }}">
                                    {{ $p->jk }}
                                </span>
                                @else
                                    <span class="text-inksoft">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-inksoft whitespace-nowrap">{{ $p->nomor_hp ?? '-' }}</td>
                            <td class="px-3 py-3 text-inksoft whitespace-nowrap">{{ $p->email ?? '-' }}</td>
                            <td class="px-3 py-3 text-inksoft max-w-xs truncate" title="{{ $p->alamat }}">{{ $p->alamat ?? '-' }}</td>

                            <td class="px-3 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('pembina.pelatih.edit', $p->id_pelatih) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form action="{{ route('pembina.pelatih.destroy', $p->id_pelatih) }}" method="POST" onsubmit="return confirm('Yakin hapus/lepas pelatih ini dari ekskul kamu?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center text-inksoft">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mb-2"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23Z"/></svg>
                                    <p class="font-bold text-sm">Belum ada data pelatih.</p>
                                    <p class="text-xs mt-1">Klik "Tambah Pelatih" untuk menambahkan pelatih ke ekskul kamu.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $pelatih->links() }}
            </div>
        </div>
    </main>
</div>

</body>
</html>