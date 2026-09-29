@extends('layouts.admin')

@section('title', 'Semua Ekskul')
@section('page-title', 'Lihat Semua Ekskul')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header + Filter -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6 pb-6 border-b border-gray-100">
        <div>
            <h3 class="text-xl font-extrabold text-[#10316B]">Semua Ekskul</h3>
            <p class="text-xs text-[#7C8DB5] mt-1">Monitoring daftar ekskul, detail, dan cetak laporan (hanya lihat).</p>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
                <input type="text" name="search" placeholder="Cari nama ekskul..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C] w-52">
            </div>

            <select name="kategori"
                class="px-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                <option value="">Semua Kategori</option>
                @foreach(['organisasi' => 'Organisasi', 'ekstrakulikuler' => 'Ekstrakulikuler', 'komunitas' => 'Komunitas'] as $nilai => $teks)
                    <option value="{{ $nilai }}" @selected(request('kategori') === $nilai)>{{ $teks }}</option>
                @endforeach
            </select>

            <select name="pembina"
                class="px-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                <option value="">Semua Pembina</option>
                <option value="none" @selected(request('pembina') === 'none')>Belum ada pembina</option>
                @foreach($pembinas as $p)
                    <option value="{{ $p->id_pembina }}" @selected((string) request('pembina') === (string) $p->id_pembina)>
                        {{ $p->nama_pembina }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.monitoring-ekskul.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-5 px-4 py-3 rounded-2xl bg-green-50 text-green-700 text-sm font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 px-4 py-3 rounded-2xl bg-red-50 text-red-700 text-sm font-semibold">{{ session('error') }}</div>
    @endif

    @if($ekskuls->count())
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-3 py-3">No</th>
                        <th class="px-3 py-3">Nama Ekskul</th>
                        <th class="px-3 py-3">Kategori</th>
                        <th class="px-3 py-3">Pembina</th>
                        <th class="px-3 py-3">Ketua</th>
                        <th class="px-3 py-3">Peserta</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                @foreach($ekskuls as $key => $ekskul)
                    @php
                        $initial = strtoupper(substr($ekskul->nama_ekskul, 0, 1));
                        $colors = [
                            'organisasi' => 'bg-sky/15 text-sky-500',
                            'ekstrakulikuler' => 'bg-green-50 text-green-500',
                            'komunitas' => 'bg-periwinkle/10 text-periwinkle',
                        ];
                    @endphp
                    <tr class="hover:bg-[#F2F7FF]/60 transition-colors">
                        <td class="px-3 py-3 text-[#7C8DB5] font-medium">{{ $ekskuls->firstItem() + $key }}</td>

                        <td class="px-3 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-display font-bold text-sm ring-2 ring-teal-100 flex-shrink-0">
                                    {{ $initial }}
                                </div>
                                <p class="text-[#10316B] font-bold whitespace-nowrap">{{ $ekskul->nama_ekskul }}</p>
                            </div>
                        </td>

                        <td class="px-3 py-3">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $colors[$ekskul->kategori] ?? 'bg-bgsoft text-inksoft' }}">
                                {{ ucfirst($ekskul->kategori) }}
                            </span>
                        </td>

                        <td class="px-3 py-3 whitespace-nowrap">
                            @if($ekskul->pembina)
                                <span class="text-[#10316B] font-semibold">{{ $ekskul->pembina->nama_pembina }}</span>
                            @else
                                <span class="text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">Belum ada</span>
                            @endif
                        </td>

                        <td class="px-3 py-3 text-[#7C8DB5] whitespace-nowrap">
                            {{ $ekskul->ketua->nama_siswa ?? '-' }}
                        </td>

                        <td class="px-3 py-3 text-[#7C8DB5]">{{ $ekskul->peserta_count }} orang</td>

                        <td class="px-3 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.monitoring-ekskul.show', $ekskul->id_ekskul) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky/15 text-sky-500 hover:bg-sky-500 hover:text-white transition-colors"
                                   title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.monitoring-ekskul.cetak', $ekskul->id_ekskul) }}" target="_blank"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#F2F7FF] text-[#10316B] hover:bg-[#0B409C] hover:text-white transition-colors"
                                   title="Cetak Laporan">
                                    <i class="fas fa-print text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $ekskuls->links() }}</div>
    @else
        <div class="text-center py-12 flex flex-col items-center text-[#7C8DB5]">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p class="font-bold text-sm">Tidak ada ekskul yang cocok.</p>
        </div>
    @endif
</div>
@endsection