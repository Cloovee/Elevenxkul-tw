@extends('layouts.admin')

@section('title', 'Tambah Ekskul')
@section('page-title', 'Tambah Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.ekskul.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_ekskul" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Ekskul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_ekskul" id="nama_ekskul" value="{{ old('nama_ekskul') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_ekskul') border-red-500 @enderror"
                    placeholder="Contoh: Pramuka, OSIS, Futsal, dll">
                @error('nama_ekskul')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="kategori" id="kategori"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    <option value="organisasi" {{ old('kategori') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="ekstrakulikuler" {{ old('kategori') == 'ekstrakulikuler' ? 'selected' : '' }}>Ekstrakulikuler</option>
                    <option value="komunitas" {{ old('kategori') == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsi kegiatan ekstrakurikuler">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection