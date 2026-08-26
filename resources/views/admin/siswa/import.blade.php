@extends('layouts.admin')

@section('title', 'Import Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#868dfb] to-[#4318FF] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-file-import"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#2b3674]">Import Data Siswa</h3>
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
            @if(session('errors'))
                <ul class="list-disc pl-8 mt-2 space-y-1">
                    @foreach(session('errors') as $e)
                        <li>Baris {{ $e['baris'] }}: {{ $e['error'] }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Pilih File Excel</label>
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:bg-[#868dfb] file:text-white file:text-xs file:font-bold hover:file:opacity-90">
            <p class="text-xs text-[#a3aed1] mt-2">Format: .xlsx, .xls, .csv &middot; Maks 5MB</p>
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Kelas Default (Opsional)</label>
            <select name="id_kelas" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb]">
                <option value="">-- Gunakan dari file --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id_kelas }}">{{ $k->jurusan }} - {{ $k->rombel }}</option>
                @endforeach
            </select>
            <p class="text-xs text-[#a3aed1] mt-2">Kalau jurusan/rombel tidak ditemukan di database, akan pakai kelas ini.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-rocket"></i> Import
            </button>
            <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-file-download"></i> Download Template
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#a3aed1] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection