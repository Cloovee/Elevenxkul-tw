@extends('layouts.admin')

@section('title', 'Detail Ekskul')
@section('page-title', 'Detail Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    @php
        $colors = [
            'organisasi' => 'bg-blue-100 text-blue-700',
            'ekstrakulikuler' => 'bg-green-100 text-green-700',
            'komunitas' => 'bg-purple-100 text-purple-700'
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Ekskul</label>
            <p class="text-lg font-semibold text-gray-800">{{ $ekskul->nama_ekskul }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
            <span class="px-3 py-1 text-sm rounded-full {{ $colors[$ekskul->kategori] ?? 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($ekskul->kategori) }}
            </span>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
            <p class="text-gray-700">{{ $ekskul->deskripsi ?: '-' }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat Pada</label>
            <p class="text-gray-700">{{ $ekskul->created_at->format('d F Y H:i') }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
            <p class="text-gray-700">{{ $ekskul->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
@endsection