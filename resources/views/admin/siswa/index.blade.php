@extends('layouts.admin')

@section('title', 'Kelola Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header: Judul + Search + Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#2b3674]">Kelola Siswa</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#a3aed1] text-sm"></i>
                <input type="text" name="search" placeholder="Cari NISN/NIS/Nama..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] placeholder-[#a3aed1] focus:ring-2 focus:ring-[#868dfb] w-56">
            </div>

            <select name="kelas" class="px-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb]">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->jurusan }} - {{ $k->rombel }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#a3aed1] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-plus"></i> Tambah
        </a>
        <a href="{{ route('admin.siswa.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-file-import"></i> Import
        </a>
        <a href="{{ route('admin.siswa.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-file-export"></i> Export
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
                <tr class="text-left text-[#a3aed1] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">NISN</th>
                    <th class="px-3 py-3">NIS</th>
                    <th class="px-3 py-3">Nama</th>
                    <th class="px-3 py-3">JK</th>
                    <th class="px-3 py-3">Kelas</th>
                    <th class="px-3 py-3">Agama</th>
                    <th class="px-3 py-3">No. HP</th>
                    <th class="px-3 py-3">Email</th>
                    <th class="px-3 py-3">MedSos</th>
                    <th class="px-3 py-3">Alamat</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($siswa as $key => $s)
                <tr class="hover:bg-[#f4f7fe]/60 transition-colors">
                    <td class="px-3 py-3 text-[#a3aed1] font-medium">{{ $siswa->firstItem() + $key }}</td>
                    <td class="px-3 py-3 text-[#2b3674] font-semibold whitespace-nowrap">{{ $s->NISN }}</td>
                    <td class="px-3 py-3 text-[#2b3674] whitespace-nowrap">{{ $s->NIS }}</td>
                    <td class="px-3 py-3 text-[#2b3674] font-bold whitespace-nowrap">{{ $s->nama_siswa }}</td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $s->jk == 'L' ? 'bg-blue-50 text-blue-500' : 'bg-pink-50 text-pink-500' }}">
                            {{ $s->jk }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-[#2b3674] whitespace-nowrap">{{ $s->nama_kelas }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $s->agama ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $s->nomor_hp ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $s->email ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $s->medsos ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] max-w-xs truncate" title="{{ $s->alamat }}">{{ $s->alamat ?? '-' }}</td>
                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.siswa.edit', $s->id_siswa) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.siswa.destroy', $s->id_siswa) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
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
                    <td colspan="12" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#a3aed1]">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada data siswa.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $siswa->withQueryString()->links() }}
    </div>
</div>
@endsection