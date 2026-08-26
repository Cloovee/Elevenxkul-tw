@extends('layouts.admin')

@section('title', 'Kelola Pembina')
@section('page-title', 'Data Pembina')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header: Judul + Search + Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#2b3674]">Kelola Pembina</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#a3aed1] text-sm"></i>
                <input type="text" name="search" placeholder="Cari nama pembina..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] placeholder-[#a3aed1] focus:ring-2 focus:ring-[#868dfb] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.pembina.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#a3aed1] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.pembina.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-user-plus"></i> Tambah Biodata Pembina
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
                    <th class="px-3 py-3">Pembina</th>
                    <th class="px-3 py-3">Akun Login</th>
                    <th class="px-3 py-3">JK</th>
                    <th class="px-3 py-3">Agama</th>
                    <th class="px-3 py-3">No. HP</th>
                    <th class="px-3 py-3">Email</th>
                    <th class="px-3 py-3">MedSos</th>
                    <th class="px-3 py-3">Alamat</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pembina as $key => $p)
                @php $initial = strtoupper(substr($p->nama_pembina, 0, 1)); @endphp
                <tr class="hover:bg-[#f4f7fe]/60 transition-colors">
                    <td class="px-3 py-3 text-[#a3aed1] font-medium">{{ $pembina->firstItem() + $key }}</td>

                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-extrabold text-sm ring-2 ring-teal-100 flex-shrink-0">
                                {{ $initial }}
                            </div>
                            <p class="text-[#2b3674] font-bold whitespace-nowrap">{{ $p->nama_pembina }}</p>
                        </div>
                    </td>

                    <td class="px-3 py-3">
                        @if($p->user)
                            <p class="text-[#2b3674] text-xs font-semibold">{{ $p->user->name }}</p>
                            <p class="text-[#a3aed1] text-xs">{{ $p->user->email }}</p>
                        @else
                            <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">
                                <i class="fas fa-triangle-exclamation"></i> Akun terhapus
                            </span>
                        @endif
                    </td>

                    <td class="px-3 py-3">
                        @if($p->jk)
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $p->jk == 'L' ? 'bg-blue-50 text-blue-500' : 'bg-pink-50 text-pink-500' }}">
                            {{ $p->jk }}
                        </span>
                        @else
                            <span class="text-[#a3aed1]">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $p->agama ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $p->nomor_hp ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $p->email ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] whitespace-nowrap">{{ $p->medsos ?? '-' }}</td>
                    <td class="px-3 py-3 text-[#a3aed1] max-w-xs truncate" title="{{ $p->alamat }}">{{ $p->alamat ?? '-' }}</td>

                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.pembina.edit', $p->id_pembina) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.pembina.destroy', $p->id_pembina) }}" method="POST" onsubmit="return confirm('Yakin hapus biodata ini? Akun login pembina tetap ada, cuma biodatanya yang hilang.')">
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
                    <td colspan="10" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#a3aed1]">
                            <i class="fas fa-user-tie text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada biodata pembina.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pembina->withQueryString()->links() }}
    </div>
</div>
@endsection