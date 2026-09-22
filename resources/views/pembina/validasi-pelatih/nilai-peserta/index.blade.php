<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Peserta — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('pembina.partials.sidebar', ['active' => 'nilai'])

                <main class="flex-1 min-w-0 space-y-5">

        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO — periwinkle theme --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-[#5B7FC7] shadow-[0_10px_30px_-18px_rgba(63,65,176,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/30 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-wide text-[#10316B]">Penilaian</p>
                <h1 class="font-display text-2xl font-semibold mt-1">Nilai Peserta</h1>
                <p class="text-sm text-inksoft mt-1 max-w-md">Beri dan kelola nilai performa peserta di ekskul yang kamu bina.</p>
            </div>
        </div>

        {{-- FORM BERI NILAI --}}
        <div class="animate-fade-in-up animate-delay-2 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-3">Beri Nilai Baru</h3>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[640px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Ekskul</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Nilai terakhir</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Beri nilai baru</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarPeserta as $p)
                        @php $terakhir = $p->nilai->first(); @endphp
                        <tr class="border-b border-[#F2F7FF] last:border-none hover:bg-bgsoft/60 transition-colors">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-periwinkle text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $p->nama))->map(fn($s) => $s[0] ?? '')->take(2)->implode('') }}
                                    </div>
                                    {{ $p->nama }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $p->ekskul->nama_ekskul ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">
                                {{ $terakhir->nilai ?? '—' }}
                                @if($terakhir) <span class="text-[11px]">(Sem {{ $terakhir->semester }}, {{ $terakhir->tahun_ajaran }})</span> @endif
                            </td>
                            <td class="py-2.5 px-2">
                                <form method="POST" action="{{ route('pembina.nilai.simpan', $p) }}" class="flex items-center gap-2 flex-wrap">
                                    @csrf
                                    <input type="text" name="tahun_ajaran" placeholder="2026/2027" required
                                           value="{{ now()->month >= 7 ? now()->year.'/'.(now()->year+1) : (now()->year-1).'/'.now()->year }}"
                                           class="w-24 px-2.5 py-1.5 rounded-lg border border-[#DDE8FB] text-xs focus:outline-none focus:border-lavender">
                                    <select name="semester" class="px-2.5 py-1.5 rounded-lg border border-[#DDE8FB] text-xs focus:outline-none focus:border-lavender">
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                    </select>
                                    <input type="number" name="nilai" min="0" max="100" placeholder="0-100" required
                                           class="w-20 text-center px-2.5 py-1.5 rounded-lg border border-[#DDE8FB] focus:outline-none focus:border-lavender">
                                    <button type="submit" class="bg-lavender hover:bg-[#0B409C] text-white font-bold text-xs px-3.5 py-1.5 rounded-lg">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-lavender/30 text-[#10316B] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-ink">Belum ada data peserta aktif</p>
                                    <p class="text-xs text-inksoft max-w-xs">Peserta aktif di ekskul yang kamu bina akan tampil di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- RIWAYAT --}}
        <div class="animate-fade-in-up animate-delay-3 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-3">Riwayat Nilai</h3>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[640px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Tahun Ajaran</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Semester</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Nilai</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatNilai as $n)
                        <tr class="border-b border-[#F2F7FF] last:border-none hover:bg-bgsoft/60 transition-colors">
                            <td class="py-2.5 px-2 font-semibold">{{ $n->peserta->nama ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $n->tahun_ajaran }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $n->semester }}</td>
                            <td class="py-2.5 px-2">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-periwinkle/30 text-[#10316B]">{{ $n->nilai ?? '—' }}</span>
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('pembina.nilai.edit', $n) }}" title="Edit"
                                       class="w-[30px] h-[30px] rounded-lg bg-sky text-[#0B409C] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form id="delete-nilai-{{ $n->id_nilai }}" method="POST" action="{{ route('pembina.nilai.destroy', $n) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" title="Hapus"
                                            data-delete-form="delete-nilai-{{ $n->id_nilai }}"
                                            data-delete-message="Hapus nilai semester {{ $n->semester }} milik {{ $n->peserta->nama ?? '' }} ini? Tindakan ini tidak bisa dibatalkan."
                                            class="w-[30px] h-[30px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-lavender/30 text-[#10316B] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-ink">Belum ada riwayat nilai</p>
                                    <p class="text-xs text-inksoft max-w-xs">Nilai yang sudah kamu simpan akan muncul di riwayat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="mt-4">
                {{ $riwayatNilai->links() }}
            </div>
        </div>

                    </main>
            </div>
        </div>
    </div>

@include('pembina.partials.delete-modal')

</body>
</html>