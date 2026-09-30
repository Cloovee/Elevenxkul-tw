@extends('layouts.admin')

@section('title', 'Edit Foto Galeri')
@section('page-title', 'Edit Foto Galeri')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-6 lg:p-8">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6 pb-4 border-b border-ink/10">
            <div>
                <h3 class="text-xl font-extrabold text-[#2b3674]">Edit Foto</h3>
                <p class="text-xs font-medium text-[#a3aed1] mt-1">Perubahan langsung tampil di landing page.</p>
            </div>
            <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-xs font-bold transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.galeri.update', $galeri->id_galeri) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.galeri.form')

            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('admin.galeri.index') }}" class="px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection