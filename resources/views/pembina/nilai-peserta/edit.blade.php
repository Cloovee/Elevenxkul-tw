<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai Peserta - Xkul11</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen flex items-center justify-center p-5"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(174,226,255,0.35), transparent 45%), radial-gradient(circle at 0% 100%, rgba(217,249,223,0.4), transparent 40%);">

@php
    $minimum = \App\Support\Penilaian::nilaiMinimum();
    $angka = fn ($v) => $v === null ? '' : rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');
@endphp

<div class="w-full max-w-lg">
    <a href="{{ route('pembina.nilai.index', ['tahun_ajaran' => $nilaiPeserta->tahun_ajaran, 'semester' => $nilaiPeserta->semester]) }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Nilai Peserta
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <div class="w-11 h-11 rounded-2xl bg-periwinkle text-[#3F41B0] flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Edit Nilai Peserta</h1>
        <p class="text-sm text-inksoft mb-5">
            Perbarui nilai untuk <span class="font-semibold text-ink">{{ $nilaiPeserta->peserta->nama }}</span>.
            Menyimpan menghitung ulang grade dari absensi terbaru.
        </p>

        {{-- Kehadiran semester ini (acuan, dihitung otomatis) --}}
        <div class="rounded-2xl bg-bgsoft px-4 py-3 mb-5">
            <p class="text-[11px] font-bold uppercase tracking-wide text-inksoft mb-1.5">Kehadiran Semester {{ $nilaiPeserta->semester }} TA {{ $nilaiPeserta->tahun_ajaran }}</p>
            @if($kehadiran['total'] > 0)
                <div class="flex items-center gap-2">
                    @include('pembina.nilai-peserta._grade', ['g' => $kehadiran['grade']])
                    <span class="font-semibold">{{ $angka($kehadiran['persen']) }}%</span>
                    <span class="text-xs text-inksoft">Hadir {{ $kehadiran['hadir'] }}/{{ $kehadiran['total'] }} &middot; I {{ $kehadiran['izin'] }} &middot; S {{ $kehadiran['sakit'] }} &middot; A {{ $kehadiran['alpha'] }}</span>
                </div>
            @else
                <p class="text-xs text-amber-600 font-semibold">Belum ada data absensi pada semester ini.</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.nilai.update', $nilaiPeserta) }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Semester</label>
                    <select name="semester" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                        @foreach (['1', '2'] as $semester)
                            <option value="{{ $semester }}" @selected(old('semester', $nilaiPeserta->semester) == $semester)>Semester {{ $semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" required placeholder="2026/2027" pattern="\d{4}/\d{4}"
                           value="{{ old('tahun_ajaran', $nilaiPeserta->tahun_ajaran) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nilai Keaktifan ({{ $minimum }}-100)</label>
                <input type="number" name="nilai_keaktifan" min="{{ $minimum }}" max="100" step="0.01" required
                       value="{{ old('nilai_keaktifan', $angka($nilaiPeserta->nilai_keaktifan ?? $nilaiPeserta->nilai)) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                <p class="mt-1 text-xs text-inksoft">Sistem mengubah angka ini jadi grade keaktifan, lalu menggabungkannya dengan grade kehadiran.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Catatan Pembina (opsional)</label>
                <textarea name="catatan_pembina" rows="2" maxlength="1000"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">{{ old('catatan_pembina', $nilaiPeserta->catatan_pembina) }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.nilai.index', ['tahun_ajaran' => $nilaiPeserta->tahun_ajaran, 'semester' => $nilaiPeserta->semester]) }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit"
                        class="flex-1 bg-lavender hover:bg-[#8385f0] text-white font-bold text-sm py-2.5 rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>