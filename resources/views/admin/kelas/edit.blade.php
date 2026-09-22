@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5 p-8 max-w-lg mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#9FA1FF] to-[#7B77E0] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-school"></i>
        </div>
        <h3 class="text-xl font-display font-bold text-[#2E2B55]">Edit Kelas</h3>
    </div>

    <form action="{{ route('admin.kelas.update', $kelas->id_kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Tingkat <span class="text-red-500">*</span></label>
            <input type="text" name="tingkat" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('tingkat') ring-2 ring-red-400 @enderror" value="{{ old('tingkat', $kelas->tingkat) }}" required>
            @error('tingkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Jurusan <span class="text-red-500">*</span></label>
            <input type="text" name="jurusan" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('jurusan') ring-2 ring-red-400 @enderror" value="{{ old('jurusan', $kelas->jurusan) }}" required>
            @error('jurusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Rombel <span class="text-red-500">*</span></label>
            <input type="text" name="rombel" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('rombel') ring-2 ring-red-400 @enderror" value="{{ old('rombel', $kelas->rombel) }}" required>
            @error('rombel')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#9FA1FF] to-[#7B77E0] text-white rounded-full text-sm font-bold shadow-md shadow-[#9FA1FF]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F3F4FC] hover:bg-[#EEF0FD] text-[#2E2B55] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection