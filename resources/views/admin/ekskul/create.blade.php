@extends('layouts.admin')

@section('title', 'Tambah Ekskul')
@section('page-title', 'Tambah Ekstrakurikuler')

@section('content')
<div class="animate-fade-in-up bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
    <form action="{{ route('admin.ekskul.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_ekskul" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Nama Ekskul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_ekskul" id="nama_ekskul" value="{{ old('nama_ekskul') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('nama_ekskul') border-red-500 @enderror"
                    placeholder="Contoh: Pramuka, OSIS, Futsal, dll">
                @error('nama_ekskul')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kategori" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="kategori" id="kategori"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('kategori') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    <option value="organisasi" {{ old('kategori') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="ekstrakulikuler" {{ old('kategori') == 'ekstrakulikuler' ? 'selected' : '' }}>Ekstrakulikuler</option>
                    <option value="komunitas" {{ old('kategori') == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsi kegiatan ekstrakurikuler">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-[#EFEFF7]">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-lavender to-periwinkle text-white font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-periwinkle/30">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2.5 bg-bgsoft text-ink font-semibold rounded-xl hover:bg-[#E9EAF9] transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection