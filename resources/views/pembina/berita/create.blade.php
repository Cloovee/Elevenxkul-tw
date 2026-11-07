<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Berita - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen flex items-center justify-center p-5"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(217,249,223,0.45), transparent 45%), radial-gradient(circle at 0% 100%, rgba(174,226,255,0.35), transparent 40%);">

<div class="w-full max-w-lg">
    <a href="{{ route('pembina.berita.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Berita
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <h1 class="font-display text-xl font-semibold mb-1">Tulis Berita</h1>
        <p class="text-sm text-inksoft mb-6">Berita langsung tampil di aplikasi ketua dan memunculkan titik notifikasi.</p>

        <form method="POST" action="{{ route('pembina.berita.store') }}" class="flex flex-col gap-4">
            @csrf
            @include('pembina.berita._form')

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.berita.index') }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit"
                        class="flex-1 bg-lavender hover:bg-[#8385f0] text-white font-bold text-sm py-2.5 rounded-xl">
                    Kirim Berita
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
