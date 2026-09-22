@extends('layouts.admin')

@section('title', 'Edit Ekskul')
@section('page-title', 'Edit Ekstrakurikuler')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6 lg:p-8">

        <!-- Header Form -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-ink/10">
            <div>
                <h3 class="text-xl font-extrabold text-[#10316B]">Edit Ekskul</h3>
                <p class="text-xs font-medium text-[#7C8DB5] mt-1">Ubah data ekstrakurikuler {{ $ekskul->nama_ekskul }}.</p>
            </div>
            <a href="{{ route('admin.ekskul.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-xs font-bold transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Edit -->
        <form action="{{ route('admin.ekskul.update', $ekskul->id_ekskul) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Ekskul -->
                <div>
                    <label for="nama_ekskul" class="block text-xs font-bold text-[#10316B] uppercase tracking-wider mb-2">
                        Nama Ekskul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_ekskul" id="nama_ekskul" value="{{ old('nama_ekskul', $ekskul->nama_ekskul) }}"
                        class="w-full px-4 py-3 bg-[#F2F7FF] border @error('nama_ekskul') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#10316B] placeholder-[#7C8DB5] focus:outline-none focus:bg-white focus:border-[#0B409C] focus:ring-2 focus:ring-[#0B409C]/20 transition-all">
                    @error('nama_ekskul')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-xs font-bold text-[#10316B] uppercase tracking-wider mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="kategori"
                        class="w-full px-4 py-3 bg-[#F2F7FF] border @error('kategori') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#10316B] focus:outline-none focus:bg-white focus:border-[#0B409C] focus:ring-2 focus:ring-[#0B409C]/20 transition-all">
                        <option value="">Pilih Kategori</option>
                        <option value="organisasi" {{ old('kategori', $ekskul->kategori) == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                        <option value="ekstrakulikuler" {{ old('kategori', $ekskul->kategori) == 'ekstrakulikuler' ? 'selected' : '' }}>Ekstrakulikuler</option>
                        <option value="komunitas" {{ old('kategori', $ekskul->kategori) == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Pembina -->
                <div class="md:col-span-2">
                    <label for="id_pembina" class="block text-xs font-bold text-[#10316B] uppercase tracking-wider mb-2">
                        Pembina Penanggung Jawab
                    </label>
                    <select name="id_pembina" id="id_pembina"
                        class="w-full px-4 py-3 bg-[#F2F7FF] border @error('id_pembina') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#10316B] focus:outline-none focus:bg-white focus:border-[#0B409C] focus:ring-2 focus:ring-[#0B409C]/20 transition-all">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($pembinas as $p)
                            <option value="{{ $p->id_pembina }}" {{ old('id_pembina', $ekskul->id_pembina) == $p->id_pembina ? 'selected' : '' }}>
                                {{ $p->nama_pembina }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-[#7C8DB5]">
                        Pembina yang dipilih akan bisa mengelola data pelatih untuk ekskul ini.
                        @if($ekskul->pelatih)
                            Mengganti pembina akan melepas pelatih ({{ $ekskul->pelatih->nama_pelatih }}) yang saat ini terkait.
                        @endif
                    </p>
                    @error('id_pembina')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-xs font-bold text-[#10316B] uppercase tracking-wider mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-4 py-3 bg-[#F2F7FF] border @error('deskripsi') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#10316B] placeholder-[#7C8DB5] focus:outline-none focus:bg-white focus:border-[#0B409C] focus:ring-2 focus:ring-[#0B409C]/20 transition-all">{{ old('deskripsi', $ekskul->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity flex items-center gap-2">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>

        </form>

    </div>

</div>
@endsection