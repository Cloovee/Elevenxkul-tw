@extends('layouts.admin')

@section('title', 'Kelola Pembina')
@section('page-title', 'Kelola Pembina')

@section('content')
<div class="animate-fade-in-up bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-display text-lg font-semibold text-ink">Daftar Pembina</h3>
            <p class="text-sm text-inksoft">Kelola akun & profil pembina (guru pendamping ekskul)</p>
        </div>
        <a href="{{ route('admin.pembina.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-lavender to-periwinkle text-white text-sm font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-periwinkle/30">
            <i class="fas fa-plus mr-2"></i>Tambah Pembina
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-bgsoft border-b border-[#EFEFF7]">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">No. HP</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0F0F8]">
                @forelse($pembinas as $pembina)
                <tr class="hover:bg-bgsoft/60 transition">
                    <td class="px-4 py-3">
                        @if($pembina->foto_url)
                            <img src="{{ $pembina->foto_url }}" class="w-10 h-10 rounded-xl object-cover" alt="{{ $pembina->nama_pembina }}">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-lavender to-sky flex items-center justify-center text-white font-bold text-xs">
                                {{ $pembina->inisial }}
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-ink">{{ $pembina->nama_pembina }}</td>
                    <td class="px-4 py-3 text-sm text-inksoft">{{ $pembina->email }}</td>
                    <td class="px-4 py-3 text-sm text-inksoft">{{ $pembina->nomor_hp ?: '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.pembina.edit', $pembina->id_pembina) }}" class="text-[#5E5CC7] hover:opacity-70" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pembina.destroy', $pembina->id_pembina) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pembina ini? Akun login-nya juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:opacity-70" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-inksoft">
                        <i class="fas fa-user-tie text-2xl block mb-2"></i>
                        Belum ada data pembina
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pembinas->links() }}
    </div>
</div>
@endsection
