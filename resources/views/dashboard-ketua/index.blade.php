<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Ketua
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-lavender/25 via-transparent to-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Sidebar -->
                <!-- Sidebar icon-only -->
                <aside class="lg:w-20 shrink-0">
                    <div class="bg-gradient-to-b from-periwinkle to-sky rounded-3xl p-3 flex flex-col items-center gap-2 sticky top-6 min-h-[520px]">

                        <!-- Logo -->
                        <a href="{{ route('dashboard.ketua') }}" class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center font-bold text-periwinkle mb-4">
                            K
                        </a>

                        <!-- Dashboard (aktif) -->
                        <a href="{{ route('dashboard.ketua') }}"
                           class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-periwinkle shadow-sm"
                           title="Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </a>

                        <!-- Absensi Pelatih -->
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/20 transition" title="Absensi Pelatih">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>

                        <!-- Absensi Peserta -->
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/20 transition" title="Absensi Peserta">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                        </a>

                        <!-- Kelola Anggota -->
                        <a href="#" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/20 transition" title="Kelola Anggota">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </a>

                        <!-- Profil -->
                        <a href="{{ route('profile.edit') }}" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/20 transition" title="Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>

                        <!-- Logout, ditempel di bawah -->
                        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                            @csrf
                            <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/20 transition" title="Keluar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </aside>

                <!-- Konten utama -->
                <div class="flex-1 space-y-5">

                    <!-- Baris atas: sapaan besar + kartu kehadiran -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                        <!-- Sapaan, 2 kolom -->
                        <div class="lg:col-span-2 bg-gradient-to-br from-periwinkle via-periwinkle to-sky rounded-3xl p-8 relative overflow-hidden min-h-[220px] flex flex-col justify-between">
                            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/10 -translate-y-1/3 translate-x-1/4"></div>
                            <div class="absolute bottom-0 right-16 w-24 h-24 rounded-full bg-white/10"></div>
                            <div class="absolute top-10 right-40 w-3 h-3 rounded-full bg-white/40"></div>
                            <div class="absolute top-24 right-24 w-2 h-2 rounded-full bg-white/40"></div>

                            <div class="relative z-10">
                                <p class="text-white/70 text-sm tracking-wide uppercase mb-2">Selamat datang kembali</p>
                                <h3 class="text-3xl font-bold text-white leading-tight">
                                    Halo, {{ auth()->user()->name }} 👋
                                </h3>
                                <p class="text-white/80 mt-2 max-w-sm">
                                    Ada 5 kehadiran yang belum kamu validasi minggu ini.
                                </p>
                            </div>
                            <a href="#" class="relative z-10 inline-flex w-fit items-center gap-2 bg-white text-periwinkle font-semibold text-sm px-5 py-2.5 rounded-xl hover:bg-white/90 transition">
                                Lihat Absensi
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>

                        <!-- Kartu kehadiran hari ini, dengan progress ring -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm flex flex-col items-center justify-center gap-3">
                            <p class="text-sm text-gray-500 self-start">Kehadiran Hari Ini</p>
                            <div class="relative w-28 h-28">
                                <svg class="w-28 h-28 -rotate-90">
                                    <circle cx="56" cy="56" r="48" stroke="#EBEBEB" stroke-width="10" fill="none" />
                                    <circle cx="56" cy="56" r="48" stroke="#9FA1FF" stroke-width="10" fill="none"
                                        stroke-dasharray="301.6" stroke-dashoffset="60" stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-800">27/32</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">84% hadir hari ini</p>
                        </div>
                    </div>

                    <!-- Ringkasan angka kecil -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-periwinkle/20 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-periwinkle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">32</p>
                            <p class="text-xs text-gray-500">Total Anggota</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-sky/40 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">2</p>
                            <p class="text-xs text-gray-500">Pelatih Aktif</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-mint/50 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">5</p>
                            <p class="text-xs text-gray-500">Menunggu Validasi</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-lavender/40 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-periwinkle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">3</p>
                            <p class="text-xs text-gray-500">Kegiatan Bulan Ini</p>
                        </div>
                    </div>

                    <!-- Menu utama + Aktivitas terbaru berdampingan -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                        <!-- Menu utama, 1 kolom vertikal -->
                        <div class="space-y-4">
                            <a href="#" class="flex items-center gap-4 bg-white hover:shadow-md transition rounded-2xl p-4 shadow-sm">
                                <div class="w-11 h-11 rounded-xl bg-periwinkle flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">Absensi Pelatih</h4>
                                    <p class="text-xs text-gray-500">Catat kehadiran pelatih</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center gap-4 bg-white hover:shadow-md transition rounded-2xl p-4 shadow-sm">
                                <div class="w-11 h-11 rounded-xl bg-sky flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">Absensi Peserta</h4>
                                    <p class="text-xs text-gray-500">Catat kehadiran anggota</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center gap-4 bg-white hover:shadow-md transition rounded-2xl p-4 shadow-sm">
                                <div class="w-11 h-11 rounded-xl bg-mint flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">Kelola Anggota</h4>
                                    <p class="text-xs text-gray-500">Data anggota ekskul</p>
                                </div>
                            </a>
                        </div>

                        <!-- Aktivitas terbaru, 2 kolom -->
                        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-800">Aktivitas Terbaru</h3>
                                <a href="#" class="text-xs text-periwinkle font-medium hover:underline">Lihat semua</a>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-4 py-3 border-b border-gray-50">
                                    <div class="w-9 h-9 rounded-full bg-periwinkle/20 flex items-center justify-center text-periwinkle font-semibold text-sm shrink-0">A</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">Aditya mengisi absensi pelatih</p>
                                        <p class="text-xs text-gray-400">10 menit lalu</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 py-3 border-b border-gray-50">
                                    <div class="w-9 h-9 rounded-full bg-sky/40 flex items-center justify-center text-gray-700 font-semibold text-sm shrink-0">R</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">Rani menambahkan anggota baru</p>
                                        <p class="text-xs text-gray-400">1 jam lalu</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 py-3 border-b border-gray-50">
                                    <div class="w-9 h-9 rounded-full bg-mint/50 flex items-center justify-center text-emerald-700 font-semibold text-sm shrink-0">D</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">Dewi memvalidasi 12 absensi peserta</p>
                                        <p class="text-xs text-gray-400">3 jam lalu</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 py-3">
                                    <div class="w-9 h-9 rounded-full bg-lavender/40 flex items-center justify-center text-periwinkle font-semibold text-sm shrink-0">F</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800">Latihan rutin Futsal terjadwal Sabtu</p>
                                        <p class="text-xs text-gray-400">Kemarin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>