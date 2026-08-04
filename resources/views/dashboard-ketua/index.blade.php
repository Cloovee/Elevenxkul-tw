<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Ketua
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Sidebar -->
                <aside class="lg:w-64 shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm p-4 space-y-1">
                        <a href="{{ route('dashboard.ketua') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-periwinkle/20 text-gray-800 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="#"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Absensi Pelatih
                        </a>
                        <a href="#"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                            Absensi Peserta
                        </a>
                        <a href="#"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Kelola Anggota
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Profil
                        </a>
                    </div>
                </aside>

                <!-- Konten utama -->
                <div class="flex-1 space-y-6">

                    <!-- Sapaan -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Halo, {{ auth()->user()->name }} 👋
                        </h3>
                        <p class="text-gray-500 mt-1">
                            Berikut ringkasan aktivitas ekstrakurikulermu hari ini.
                        </p>
                    </div>

                    <!-- Ringkasan angka -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-periwinkle/20 rounded-2xl p-5">
                            <p class="text-sm text-gray-600">Total Anggota</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">32</p>
                        </div>
                        <div class="bg-sky/30 rounded-2xl p-5">
                            <p class="text-sm text-gray-600">Kehadiran Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">27</p>
                        </div>
                        <div class="bg-mint/40 rounded-2xl p-5">
                            <p class="text-sm text-gray-600">Pelatih Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">2</p>
                        </div>
                    </div>

                    <!-- Menu utama -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="#" class="group bg-white hover:shadow-md transition rounded-2xl p-6 flex flex-col gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-periwinkle flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Absensi Pelatih</h4>
                                <p class="text-sm text-gray-500 mt-1">Catat kehadiran pelatih ekskul</p>
                            </div>
                        </a>

                        <a href="#" class="group bg-white hover:shadow-md transition rounded-2xl p-6 flex flex-col gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-sky flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Absensi Peserta</h4>
                                <p class="text-sm text-gray-500 mt-1">Catat kehadiran anggota ekskul</p>
                            </div>
                        </a>

                        <a href="#" class="group bg-white hover:shadow-md transition rounded-2xl p-6 flex flex-col gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-emerald-400 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Kelola Anggota</h4>
                                <p class="text-sm text-gray-500 mt-1">Data anggota ekstrakurikuler</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>