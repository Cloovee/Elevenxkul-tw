<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Pelatih - Ekskul Sebelas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/25 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-extrabold text-gray-800">Absensi Pelatih</h1>
                    </div>

                    <form class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ preview: null }">
                        @csrf

                        <!-- Foto bukti absensi -->
                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Foto Bukti Absensi</h2>

                            <label
                                class="block w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-periwinkle/30 bg-periwinkle/5 flex items-center justify-center cursor-pointer overflow-hidden hover:bg-periwinkle/10 transition"
                            >
                                <template x-if="!preview">
                                    <div class="text-center px-4">
                                        <div class="w-12 h-12 rounded-2xl bg-periwinkle/15 flex items-center justify-center mx-auto mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-periwinkle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Upload foto absen</p>
                                        <p class="text-xs text-gray-400 mt-1">PNG atau JPG, maks 2MB</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover" />
                                </template>
                                <input type="file" name="foto_absen" accept="image/*" class="hidden"
                                    @change="preview = URL.createObjectURL($event.target.files[0])"
                                />
                            </label>

                            <label class="mt-4 block w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold text-sm cursor-pointer hover:opacity-90 transition"
                                onclick="this.previousElementSibling.querySelector('input').click()"
                            >
                                Pilih Foto
                            </label>
                        </div>

                        <!-- Data absensi -->
                        <div class="bg-gradient-to-br from-white to-sky/10 rounded-3xl shadow-xl shadow-sky/10 ring-1 ring-black/5 p-6">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Data Absensi</h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kegiatan</label>
                                    <input type="text" name="kegiatan" placeholder="Contoh: Latihan rutin Futsal"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kehadiran</label>
                                    <select name="status"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                    >
                                        <option value="">Pilih status</option>
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="alpha">Tidak Hadir</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol kirim -->
                        <div class="lg:col-span-2">
                            <button type="submit"
                                class="w-full py-3 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30"
                            >
                                Kirim Absensi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>