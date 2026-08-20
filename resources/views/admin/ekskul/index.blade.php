@extends('layouts.admin')

@section('title', 'Kelola Ekskul')
@section('page-title', 'Kelola Ekstrakurikuler')

@section('content')
<div class="animate-fade-in-up bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-display text-lg font-semibold text-ink">Daftar Ekskul</h3>
            <p class="text-sm text-inksoft">Kelola semua data ekstrakurikuler</p>
        </div>
        <a href="{{ route('admin.ekskul.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-lavender to-periwinkle text-white text-sm font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-periwinkle/30">
            <i class="fas fa-plus mr-2"></i>Tambah Ekskul
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-bgsoft border-b border-[#EFEFF7]">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">No</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Nama Ekskul</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-inksoft uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0F0F8]">
                @forelse($ekskuls as $index => $ekskul)
                <tr class="hover:bg-bgsoft/60 transition">
                    <td class="px-4 py-3 text-sm text-inksoft">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-ink">{{ $ekskul->nama_ekskul }}</td>
                    <td class="px-4 py-3">
                        @php
                            $colors = [
                                'organisasi' => 'bg-periwinkle/20 text-[#5E5CC7]',
                                'ekstrakulikuler' => 'bg-mint/60 text-emerald-700',
                                'komunitas' => 'bg-lavender/25 text-[#5E5CC7]'
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colors[$ekskul->kategori] ?? 'bg-bgsoft text-inksoft' }}">
                            {{ ucfirst($ekskul->kategori) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.ekskul.show', $ekskul->id_ekskul) }}" class="text-[#1E6FA8] hover:opacity-70" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="text-[#5E5CC7] hover:opacity-70" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.ekskul.destroy', $ekskul->id_ekskul) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekskul ini?')">
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
                    <td colspan="4" class="px-4 py-8 text-center text-inksoft">
                        <i class="fas fa-inbox text-2xl block mb-2"></i>
                        Belum ada data ekskul
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ekskuls->links() }}
    </div>
</div>
@endsection