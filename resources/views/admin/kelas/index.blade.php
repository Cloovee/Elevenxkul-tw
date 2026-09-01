@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header: Judul + Search -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#2b3674]">Kelola Kelas</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#a3aed1] text-sm"></i>
                <input type="text" name="search" placeholder="Cari jurusan/rombel..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] placeholder-[#a3aed1] focus:ring-2 focus:ring-[#868dfb] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#a3aed1] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-[#a3aed1] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">Jurusan</th>
                    <th class="px-3 py-3">Rombel</th>
                    <th class="px-3 py-3">Jumlah Siswa</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($kelas as $key => $k)
                <tr class="hover:bg-[#f4f7fe]/60 transition-colors">
                    <td class="px-3 py-3 text-[#a3aed1] font-medium">{{ $kelas->firstItem() + $key }}</td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center font-extrabold text-sm ring-2 ring-orange-100 flex-shrink-0">
                                <i class="fas fa-school text-xs"></i>
                            </div>
                            <p class="text-[#2b3674] font-bold">{{ $k->jurusan }}</p>
                        </div>
                    </td>
                    <td class="px-3 py-3 text-[#2b3674] font-semibold">{{ $k->rombel }}</td>
                    <td class="px-3 py-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600">
                            <i class="fas fa-user-graduate text-[10px]"></i> {{ $k->siswa_count }} siswa
                        </span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.kelas.edit', $k->id_kelas) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?')">
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
                    <td colspan="5" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#a3aed1]">
                            <i class="fas fa-school text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada data kelas.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $kelas->withQueryString()->links() }}
    </div>
</div>
@endsection