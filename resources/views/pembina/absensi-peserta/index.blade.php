<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Peserta — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen">

<div class="flex gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'absensi'])

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

        {{-- HERO — mint theme, sengaja beda dari dashboard utama --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-gradient-to-br from-[#D9F9DF] via-[#EFFDF1] to-white shadow-[0_10px_30px_-18px_rgba(31,122,61,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/40 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="flex items-center justify-between flex-wrap gap-4 relative">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#1F7A3D]">Kehadiran</p>
                    <h1 class="font-display text-2xl font-semibold mt-1">Absensi Peserta</h1>
                    <p class="text-sm text-inksoft mt-1 max-w-md">Riwayat kehadiran seluruh peserta di ekskul yang kamu bina.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/70 rounded-2xl px-4 py-2.5 text-center">
                        <div class="font-display text-xl font-bold text-[#1F7A3D]">{{ $hadirBulanIni }}/{{ $totalBulanIni }}</div>
                        <div class="text-[11px] text-inksoft">Hadir bulan ini</div>
                    </div>
                    <a href="{{ route('pembina.absensi.create') }}"
                       class="inline-flex items-center gap-1.5 bg-[#1F7A3D] hover:bg-[#186531] text-white font-bold text-sm px-4 py-2.5 rounded-xl shadow-md shadow-[#1F7A3D]/20 transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Absensi
                    </a>
                </div>
            </div>
            <a href="{{ route('pembina.validasi.index') }}" class="relative inline-flex items-center gap-1 text-xs font-semibold text-[#1F7A3D] mt-4 hover:underline">
                Lihat validasi absensi pelatih
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- TABLE --}}
        <div class="animate-fade-in-up animate-delay-2 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-3">Riwayat Absensi</h3>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[640px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kelas</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Ekskul</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Tanggal</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Status</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatAbsensi as $a)
                        <tr class="border-b border-[#F5F5FA] last:border-none hover:bg-bgsoft/60 transition-colors">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-periwinkle text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $a->peserta->nama ?? '-'))->map(fn($s) => $s[0] ?? '')->take(2)->implode('') }}
                                    </div>
                                    {{ $a->peserta->nama ?? '-' }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $a->peserta->kelas ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $a->peserta->ekskul->nama_ekskul ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ optional($a->tanggal_absensi)->translatedFormat('d M Y') }}</td>
                            <td class="py-2.5 px-2">
                                @php
                                    $statusClass = match($a->status_kehadiran) {
                                        'hadir' => 'bg-mint text-[#1F7A3D]',
                                        'alpha' => 'bg-red-100 text-red-500',
                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                        'izin' => 'bg-sky text-[#1E6FA8]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $statusClass }}">{{ ucfirst($a->status_kehadiran) }}</span>
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('pembina.absensi.edit', $a) }}"
                                       class="w-[30px] h-[30px] rounded-lg bg-sky text-[#1E6FA8] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form id="delete-absensi-{{ $a->id_absensi }}" method="POST" action="{{ route('pembina.absensi.destroy', $a) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button"
                                            data-delete-form="delete-absensi-{{ $a->id_absensi }}"
                                            data-delete-message="Hapus data absensi {{ $a->peserta->nama ?? '' }} pada tanggal ini? Tindakan ini tidak bisa dibatalkan."
                                            class="w-[30px] h-[30px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-mint/60 text-[#1F7A3D] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6M9 8h1"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-ink">Belum ada data absensi peserta</p>
                                    <p class="text-xs text-inksoft max-w-xs">Data akan muncul di sini setelah kamu mencatat kehadiran peserta.</p>
                                    <a href="{{ route('pembina.absensi.create') }}" class="mt-1 text-xs font-bold text-[#1F7A3D] hover:underline">+ Tambah absensi pertama</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="mt-4">
                {{ $riwayatAbsensi->links() }}
            </div>
        </div>

    </main>
</div>

@include('pembina.partials.delete-modal')

</body>
</html>