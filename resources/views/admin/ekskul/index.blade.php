@extends('layouts.admin')

@section('title', 'Kelola Ekskul')
@section('page-title', 'Kelola Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Ekskul</h3>
            <p class="text-sm text-gray-500">Kelola semua data ekstrakurikuler</p>
        </div>
        <a href="{{ route('admin.ekskul.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Ekskul
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ekskul</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ekskuls as $index => $ekskul)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $ekskul->nama_ekskul }}</td>
                    <td class="px-4 py-3">
                        @php
                            $colors = [
                                'organisasi' => 'bg-blue-100 text-blue-700',
                                'ekstrakulikuler' => 'bg-green-100 text-green-700',
                                'komunitas' => 'bg-purple-100 text-purple-700'
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $colors[$ekskul->kategori] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($ekskul->kategori) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.ekskul.show', $ekskul->id_ekskul) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.ekskul.destroy', $ekskul->id_ekskul) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekskul ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
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