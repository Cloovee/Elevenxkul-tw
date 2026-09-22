@extends('layouts.admin')

@section('title', 'Kelola Ekskul')
@section('page-title', 'Kelola Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5 p-6">

    <!-- Header: Judul + Search + Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-display font-bold text-[#2E2B55]">Kelola Ekskul</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#6B6795] text-sm"></i>
                <input type="text" name="search" placeholder="Cari nama ekskul..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F3F4FC] border-none rounded-full text-sm text-[#2E2B55] placeholder-[#6B6795] focus:ring-2 focus:ring-[#9FA1FF] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#F3F4FC] hover:bg-[#EEF0FD] text-[#2E2B55] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.ekskul.index') }}" class="px-4 py-2 bg-[#F3F4FC] hover:bg-[#EEF0FD] text-[#6B6795] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-ink/10">
        <a href="{{ route('admin.ekskul.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-tr from-[#9FA1FF] to-[#7B77E0] text-white rounded-full text-sm font-bold shadow-md shadow-[#9FA1FF]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-plus"></i> Tambah Ekskul
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-[#6B6795] text-[11px] font-bold uppercase tracking-wider border-b border-ink/10">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">Nama Ekskul</th>
                    <th class="px-3 py-3">Kategori</th>
                    <th class="px-3 py-3">Pembina</th>
                    <th class="px-3 py-3">Pelatih</th>
                    <th class="px-3 py-3">Deskripsi</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse($ekskuls as $key => $ekskul)
                @php $initial = strtoupper(substr($ekskul->nama_ekskul, 0, 1)); @endphp
                <tr class="hover:bg-[#F3F4FC]/60 transition-colors">
                    <td class="px-3 py-3 text-[#6B6795] font-medium">{{ $ekskuls->firstItem() + $key }}</td>

                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-display font-bold text-sm ring-2 ring-teal-100 flex-shrink-0">
                                {{ $initial }}
                            </div>
                            <p class="text-[#2E2B55] font-bold whitespace-nowrap">{{ $ekskul->nama_ekskul }}</p>
                        </div>
                    </td>

                    <td class="px-3 py-3">
                        @php
                            $colors = [
                                'organisasi' => 'bg-sky/15 text-sky-500',
                                'ekstrakulikuler' => 'bg-green-50 text-green-500',
                                'komunitas' => 'bg-periwinkle/10 text-periwinkle',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $colors[$ekskul->kategori] ?? 'bg-bgsoft text-inksoft' }}">
                            {{ ucfirst($ekskul->kategori) }}
                        </span>
                    </td>

                    <td class="px-3 py-3 text-[#6B6795] whitespace-nowrap">
                        @if($ekskul->pembina)
                            <span class="text-[#2E2B55] font-semibold">{{ $ekskul->pembina->nama_pembina }}</span>
                        @else
                            <span class="text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">Belum ada</span>
                        @endif
                    </td>

                    <td class="px-3 py-3 text-[#6B6795] whitespace-nowrap">
                        {{ $ekskul->pelatih->nama_pelatih ?? '-' }}
                    </td>

                    <td class="px-3 py-3 text-[#6B6795] max-w-xs truncate" title="{{ $ekskul->deskripsi }}">
                        {{ $ekskul->deskripsi ?? '-' }}
                    </td>

                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.ekskul.show', $ekskul->id_ekskul) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky/15 text-sky-500 hover:bg-sky-500 hover:text-white transition-colors" title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.ekskul.destroy', $ekskul->id_ekskul) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekskul ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#6B6795]">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada data ekskul.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ekskuls->withQueryString()->links() }}
    </div>
</div>
@endsection