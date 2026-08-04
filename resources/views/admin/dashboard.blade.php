@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Ekskul</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalEkskul ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pembina</p>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pelatih</p>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-clock mr-2 text-gray-400"></i>Ekskul Terbaru
            </h3>
            <a href="{{ route('admin.ekskul.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if(isset($recentEkskuls) && $recentEkskuls->isNotEmpty())
            <div class="divide-y divide-gray-100">
                @foreach($recentEkskuls as $ekskul)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $ekskul->nama_ekskul }}</p>
                            <p class="text-sm text-gray-500">
                                <span class="inline-block px-2 py-0.5 rounded text-xs 
                                    @if($ekskul->kategori == 'organisasi') bg-blue-100 text-blue-700
                                    @elseif($ekskul->kategori == 'ekstrakulikuler') bg-green-100 text-green-700
                                    @else bg-purple-100 text-purple-700 @endif">
                                    {{ ucfirst($ekskul->kategori) }}
                                </span>
                            </p>
                        </div>
                        <span class="text-sm text-gray-400">
                            {{ $ekskul->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Belum ada data ekskul.</p>
        @endif
    </div>
@endsection