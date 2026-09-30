@extends('layouts.admin')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')

@section('content')
@php
    $labelKeputusan = [
        'naik_kelas' => ['Naik Kelas', 'bg-green-50 text-green-600'],
        'tidak_naik' => ['Tidak Naik', 'bg-amber-50 text-amber-600'],
        'lulus' => ['Lulus', 'bg-sky/15 text-sky-500'],
        'keluar' => ['Keluar', 'bg-red-50 text-red-600'],
    ];
@endphp

<div class="space-y-5">

    @if(session('success'))
        <div class="px-4 py-3 rounded-2xl bg-green-50 text-green-700 text-sm font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="px-4 py-3 rounded-2xl bg-red-50 text-red-700 text-sm font-semibold">{{ session('error') }}</div>
    @endif

    {{-- Tahun Ajaran Aktif --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">
        @if($aktif)
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-inksoft uppercase tracking-wide">Tahun Ajaran Aktif</p>
                    <p class="text-3xl font-extrabold text-[#10316B] mt-1">{{ $aktif->tahun_ajaran }}</p>
                </div>
                <a href="{{ route('admin.tahun-ajaran.ganti.form') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                    <i class="fas fa-right-left"></i> Ganti Tahun Ajaran
                </a>
            </div>
        @else
            {{-- Belum pernah ada Tahun Ajaran sama sekali -- tetapkan sekali di awal saja. --}}
            <div>
                <p class="text-xs font-bold text-inksoft uppercase tracking-wide mb-1">Belum ada Tahun Ajaran aktif</p>
                <p class="text-xs text-[#7C8DB5] mb-4">Tetapkan Tahun Ajaran aktif untuk sistem saat ini. Langkah ini hanya dilakukan sekali di awal.</p>

                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-2xl bg-red-50 text-red-700 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tahun-ajaran.bootstrap') }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', '2026/2027') }}" required
                            class="px-4 py-2.5 bg-[#F2F7FF] border-none rounded-2xl text-sm font-extrabold text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                        Tetapkan sebagai Aktif
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Riwayat Tahun Ajaran --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
            <h3 class="text-xl font-extrabold text-[#10316B]">Riwayat Tahun Ajaran</h3>

            @if($daftarTahunAjaran->count())
                <form method="GET" class="flex items-center gap-2">
                    <select name="tahun_ajaran_id" onchange="this.form.submit()"
                        class="py-2 pl-4 pr-8 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                        @foreach($daftarTahunAjaran as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" @selected($tahunAjaranTerpilih && $tahunAjaranTerpilih->id_tahun_ajaran === $ta->id_tahun_ajaran)>
                                {{ $ta->tahun_ajaran }} @if($ta->status === 'aktif')(Aktif)@endif
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        @if(!$tahunAjaranTerpilih)
            <div class="text-center py-10 flex flex-col items-center text-[#7C8DB5]">
                <i class="fas fa-clock-rotate-left text-3xl mb-2"></i>
                <p class="font-bold text-sm">Belum ada riwayat Tahun Ajaran.</p>
            </div>
        @elseif($histori->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="px-3 py-3">NIS</th>
                            <th class="px-3 py-3">Nama</th>
                            <th class="px-3 py-3">Kelas pada {{ $tahunAjaranTerpilih->tahun_ajaran }}</th>
                            <th class="px-3 py-3">Keputusan</th>
                            <th class="px-3 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink/5">
                    @foreach($histori as $h)
                        <tr>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $h->siswa->NIS ?? '-' }}</td>
                            <td class="px-3 py-3 font-semibold text-[#10316B] whitespace-nowrap">{{ $h->siswa->nama_siswa ?? '-' }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $h->kelas->nama_kelas ?? ($h->keputusan === 'lulus' ? 'Alumni' : 'Tidak Aktif') }}</td>
                            <td class="px-3 py-3">
                                <span class="px-2 py-1 rounded-full text-[11px] font-bold {{ $labelKeputusan[$h->keputusan][1] }}">
                                    {{ $labelKeputusan[$h->keputusan][0] }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-[#7C8DB5]">{{ ucfirst($h->status) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $histori->links() }}</div>
        @else
            <div class="text-center py-10 flex flex-col items-center text-[#7C8DB5]">
                <i class="fas fa-clock-rotate-left text-3xl mb-2"></i>
                <p class="font-bold text-sm">Belum ada perubahan siswa tercatat untuk {{ $tahunAjaranTerpilih->tahun_ajaran }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection