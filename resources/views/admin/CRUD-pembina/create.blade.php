@extends('layouts.admin')

@section('title', 'Tambah Pembina')
@section('page-title', 'Data Pembina')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5 p-8 max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#9FA1FF] to-[#7B77E0] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3 class="text-xl font-display font-bold text-[#2E2B55]">Tambah Biodata Pembina</h3>
    </div>

    <form action="{{ route('admin.pembina.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Akun Login <span class="text-red-500">*</span></label>
            <select name="id_user" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('id_user') ring-2 ring-red-400 @enderror" required>
                <option value="">-- Pilih Akun --</option>
                @foreach($availableUsers as $u)
                    <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            @error('id_user')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            @if($availableUsers->isEmpty())
                <p class="text-amber-600 text-xs mt-2 bg-amber-50 px-3 py-2 rounded-lg">
                    <i class="fas fa-triangle-exclamation mr-1"></i>
                    Belum ada akun role Pembina yang tersedia. Buat dulu akunnya lewat menu <strong>Kelola Users</strong>.
                </p>
            @else
                <p class="text-xs text-[#6B6795] mt-2">Cuma akun role Pembina yang belum ada biodatanya yang muncul di sini.</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Nama Pembina <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pembina" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('nama_pembina') ring-2 ring-red-400 @enderror" value="{{ old('nama_pembina') }}" required>
                @error('nama_pembina')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                <select name="jk" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('jk') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Agama</label>
                <input type="text" name="agama" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('agama') ring-2 ring-red-400 @enderror" value="{{ old('agama') }}">
                @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Nomor HP</label>
                <input type="text" name="nomor_hp" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('nomor_hp') ring-2 ring-red-400 @enderror" value="{{ old('nomor_hp') }}">
                @error('nomor_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Email <span class="text-xs normal-case font-normal text-[#6B6795]">(kontak, boleh beda dari email akun)</span></label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email') }}">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">MedSos</label>
                <input type="text" name="medsos" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('medsos') ring-2 ring-red-400 @enderror" value="{{ old('medsos') }}">
                @error('medsos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('alamat') ring-2 ring-red-400 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#9FA1FF] to-[#7B77E0] text-white rounded-full text-sm font-bold shadow-md shadow-[#9FA1FF]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.pembina.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F3F4FC] hover:bg-[#EEF0FD] text-[#2E2B55] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection