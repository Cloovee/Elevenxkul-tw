@extends('layouts.admin')

@section('title', 'Import Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-8 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-file-import"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Import Data Siswa</h3>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
            @if(session('baris_error'))
                <ul class="list-disc pl-8 mt-2 space-y-1">
                    @foreach(session('baris_error') as $e)
                        <li>Baris {{ $e['baris'] }}: {{ $e['error'] }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Pilih File Excel <span class="text-red-500">*</span></label>
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:bg-[#0B409C] file:text-white file:text-xs file:font-bold hover:file:opacity-90 @error('file') ring-2 ring-red-400 @enderror">
            <p class="text-xs text-[#7C8DB5] mt-2">Format: .xlsx, .xls, .csv &middot; Maks 5MB</p>
            @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Kelas Tujuan <span class="text-red-500">*</span></label>
            <select name="id_kelas" required
                class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('id_kelas') ring-2 ring-red-400 @enderror">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id_kelas }}" @selected(old('id_kelas') == $k->id_kelas)>
                        {{ $k->tingkat }} {{ $k->program_keahlian }} {{ $k->rombel }}
                    </option>
                @endforeach
            </select>
            @error('id_kelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-[#7C8DB5] mt-2">Semua siswa di file Excel akan dimasukkan ke kelas ini. Excel tidak perlu kolom Kelas.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-rocket"></i> Import
            </button>
            <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-file-download"></i> Download Template
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection