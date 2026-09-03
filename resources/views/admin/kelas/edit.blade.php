@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-lg mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#868dfb] to-[#4318FF] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-school"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#2b3674]">Edit Kelas</h3>
    </div>

    <form action="{{ route('admin.kelas.update', $kelas->id_kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Tingkat <span class="text-red-500">*</span></label>
            <input type="text" name="tingkat" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('tingkat') ring-2 ring-red-400 @enderror" value="{{ old('tingkat', $kelas->tingkat) }}" required>
            @error('tingkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Jurusan <span class="text-red-500">*</span></label>
            <input type="text" name="jurusan" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('jurusan') ring-2 ring-red-400 @enderror" value="{{ old('jurusan', $kelas->jurusan) }}" required>
            @error('jurusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Rombel <span class="text-red-500">*</span></label>
            <input type="text" name="rombel" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('rombel') ring-2 ring-red-400 @enderror" value="{{ old('rombel', $kelas->rombel) }}" required>
            @error('rombel')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection