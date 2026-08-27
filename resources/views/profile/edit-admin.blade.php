@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 bg-gradient-to-br from-[#868dfb] to-[#4318FF] rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#2b3674]">Informasi Profil</h3>
                <p class="text-xs text-[#a3aed1]">Update nama dan alamat email akunmu.</p>
            </div>
        </div>
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#2b3674]">Ubah Password</h3>
                <p class="text-xs text-[#a3aed1]">Pastikan pakai password yang panjang dan acak biar lebih aman.</p>
            </div>
        </div>
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-red-100 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#2b3674]">Hapus Akun</h3>
                <p class="text-xs text-[#a3aed1]">Sekali dihapus, semua data akun ini hilang permanen.</p>
            </div>
        </div>
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection