@extends('layouts.admin')

@section('title', 'Edit Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#868dfb] to-[#4318FF] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-edit"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#2b3674]">Edit Data Siswa</h3>
    </div>

    <form action="{{ route('admin.siswa.update', $siswa->id_siswa) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">NISN <span class="text-red-500">*</span></label>
                <input type="text" name="NISN" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('NISN') ring-2 ring-red-400 @enderror" value="{{ old('NISN', $siswa->NISN) }}" required>
                @error('NISN')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">NIS <span class="text-red-500">*</span></label>
                <input type="text" name="NIS" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('NIS') ring-2 ring-red-400 @enderror" value="{{ old('NIS', $siswa->NIS) }}" required>
                @error('NIS')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Nama Siswa <span class="text-red-500">*</span></label>
                <input type="text" name="nama_siswa" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('nama_siswa') ring-2 ring-red-400 @enderror" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
                @error('nama_siswa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jk" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('jk') ring-2 ring-red-400 @enderror" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Kelas <span class="text-red-500">*</span></label>
                <select name="id_kelas" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('id_kelas') ring-2 ring-red-400 @enderror" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->jurusan }} - {{ $k->rombel }}
                        </option>
                    @endforeach
                </select>
                @error('id_kelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Agama</label>
                <input type="text" name="agama" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('agama') ring-2 ring-red-400 @enderror" value="{{ old('agama', $siswa->agama) }}">
                @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Nomor HP</label>
                <input type="text" name="nomor_hp" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('nomor_hp') ring-2 ring-red-400 @enderror" value="{{ old('nomor_hp', $siswa->nomor_hp) }}">
                @error('nomor_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email', $siswa->email) }}">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">MedSos</label>
                <input type="text" name="medsos" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('medsos') ring-2 ring-red-400 @enderror" value="{{ old('medsos', $siswa->medsos) }}">
                @error('medsos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('alamat') ring-2 ring-red-400 @enderror">{{ old('alamat', $siswa->alamat) }}</textarea>
                @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection