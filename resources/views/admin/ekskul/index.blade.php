@extends('layouts.admin')

@section('title', 'Kelola Ekskul')
@section('page-title', 'Kelola Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header: Judul + Search + Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#10316B]">Kelola Ekskul</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
                <input type="text" name="search" placeholder="Cari nama ekskul..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.ekskul.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.ekskul.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
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
                <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">Nama Ekskul</th>
                    <th class="px-3 py-3">Kategori</th>
                    <th class="px-3 py-3">Pembina</th>
                    <th class="px-3 py-3">Pelatih</th>
                    <th class="px-3 py-3">Deskripsi</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($ekskuls as $key => $ekskul)
                @php $initial = strtoupper(substr($ekskul->nama_ekskul, 0, 1)); @endphp
                <tr class="hover:bg-[#F2F7FF]/60 transition-colors">
                    <td class="px-3 py-3 text-[#7C8DB5] font-medium">{{ $ekskuls->firstItem() + $key }}</td>

                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-extrabold text-sm ring-2 ring-teal-100 flex-shrink-0">
                                {{ $initial }}
                            </div>
                            <p class="text-[#10316B] font-bold whitespace-nowrap">{{ $ekskul->nama_ekskul }}</p>
                        </div>
                    </td>

                    <td class="px-3 py-3">
                        @php
                            $colors = [
                                'organisasi' => 'bg-blue-50 text-blue-500',
                                'ekstrakulikuler' => 'bg-green-50 text-green-500',
                                'komunitas' => 'bg-purple-50 text-purple-500',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $colors[$ekskul->kategori] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($ekskul->kategori) }}
                        </span>
                    </td>

                    <td class="px-3 py-3 text-[#7C8DB5] whitespace-nowrap">
                        @if($ekskul->pembina)
                            <span class="text-[#10316B] font-semibold">{{ $ekskul->pembina->nama_pembina }}</span>
                        @else
                            <span class="text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">Belum ada</span>
                        @endif
                    </td>

                    <td class="px-3 py-3 text-[#7C8DB5] whitespace-nowrap">
                        {{ $ekskul->pelatih->nama_pelatih ?? '-' }}
                    </td>

                    <td class="px-3 py-3 text-[#7C8DB5] max-w-xs truncate" title="{{ $ekskul->deskripsi }}">
                        {{ $ekskul->deskripsi ?? '-' }}
                    </td>

                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.ekskul.show', $ekskul->id_ekskul) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors" title="Detail">
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
                        <div class="flex flex-col items-center text-[#7C8DB5]">
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