{{--
    Tombol lonceng notifikasi absensi (topbar dashboard pembina).
    Gunakan: @include('pembina.partials.notifikasi-button', ['notifikasi' => $notifikasi])
    Pasangannya: notifikasi-modal.blade.php (taruh di akhir <body>, di luar elemen
    ber-animasi supaya position: fixed tidak "terjebak" oleh transform parent).
--}}
@php $notifTotal = $notifikasi['total'] ?? 0; @endphp

<button type="button"
        id="ekk-notif-trigger"
        aria-haspopup="dialog"
        aria-controls="ekk-notif-modal"
        aria-expanded="false"
        aria-label="Buka notifikasi absensi{{ $notifTotal > 0 ? ' ('.$notifTotal.' perlu perhatian)' : '' }}"
        class="relative w-[44px] h-[44px] rounded-2xl bg-lavender/10 flex items-center justify-center text-lavender hover:bg-lavender/20 hover:-translate-y-0.5 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-lavender/40">
    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
    @if($notifTotal > 0)
        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-bgsoft">
            {{ $notifTotal > 9 ? '9+' : $notifTotal }}
        </span>
    @endif
</button>
