@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-lg mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-school"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Edit Kelas</h3>
    </div>

    <form action="{{ route('admin.kelas.update', $kelas->id_kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Tingkat <span class="text-red-500">*</span></label>
            <input type="text" name="tingkat" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('tingkat') ring-2 ring-red-400 @enderror" value="{{ old('tingkat', $kelas->tingkat) }}" required>
            @error('tingkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Jurusan <span class="text-red-500">*</span></label>
            <input type="text" name="program_keahlian" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('program_keahlian') ring-2 ring-red-400 @enderror" value="{{ old('program_keahlian', $kelas->program_keahlian) }}" required>
            @error('program_keahlian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Rombel <span class="text-red-500">*</span></label>
            <input type="text" name="rombel" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('rombel') ring-2 ring-red-400 @enderror" value="{{ old('rombel', $kelas->rombel) }}" required>
            @error('rombel')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection