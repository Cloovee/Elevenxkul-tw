<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Peserta - Ekskul Sebelas</title>
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
                        <h1 class="text-2xl font-extrabold text-gray-800">Absensi Peserta</h1>
                    </div>

                    <form class="space-y-6">
                        @csrf

                        <div class="bg-gradient-to-br from-white to-periwinkle/10 rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-4">
                            <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3">Data Absensi</h2>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Peserta</label>
                                <input type="text" name="nama_peserta" placeholder="Nama peserta"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                                <input type="text" name="kelas" placeholder="Contoh: X-A"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Kehadiran</label>
                                <input type="date" name="tanggal_kehadiran"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Kegiatan</label>
                                <textarea name="deskripsi_kegiatan" rows="3" placeholder="Ceritakan kegiatan hari ini..."
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Kehadiran</label>
                                <select name="status_kehadiran"
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

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-periwinkle to-sky text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30"
                        >
                            Kirim Absensi
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>