{{--
    Pagination bertema ElevenXkul (biru #0B409C / navy #10316B / cloud #F2F7FF).

    Dipakai lewat:  {{ $paginator->links('vendor.pagination.tema') }}

    CSS ditulis mentah (prefix .pgt-) supaya tombol nomor halaman tetap rapi walau
    hasil `npm run build` belum diperbarui. Bagian atas menampilkan ringkasan
    "Menampilkan 1-20 dari N", bagian bawah tombol nomor halaman.

    Variabel opsional: $satuan (mis. "peserta", "nilai") untuk teks ringkasan.
--}}
@once
    <style>
        .pgt { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem 1rem; margin-top: 1.25rem; }
        .pgt-info { margin: 0; font-size: .8125rem; color: #5B6E96; }
        .pgt-info b { color: #10316B; font-weight: 700; }
        .pgt-list { display: flex; flex-wrap: wrap; align-items: center; gap: .375rem; margin: 0; padding: 0; list-style: none; }

        .pgt-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 2.25rem; height: 2.25rem; padding: 0 .625rem;
            border: 0; border-radius: .75rem;
            background: #F2F7FF; color: #10316B;
            font-size: .8125rem; font-weight: 700; line-height: 1; text-decoration: none;
            cursor: pointer; user-select: none;
            transition: background-color .15s, color .15s, box-shadow .15s, transform .15s;
        }
        a.pgt-btn:hover { background: #DCE8FB; color: #0B409C; transform: translateY(-1px); }
        a.pgt-btn:focus-visible { outline: 2px solid #0B409C; outline-offset: 2px; }
        .pgt-btn svg { width: 1rem; height: 1rem; }

        .pgt-active { background: #0B409C; color: #fff; box-shadow: 0 6px 14px -6px rgba(11, 64, 156, .65); cursor: default; }
        .pgt-disabled { opacity: .4; cursor: not-allowed; }
        .pgt-dots { background: transparent; color: #5B6E96; min-width: 1.5rem; padding: 0 .25rem; cursor: default; }

        /* HP: ringkasan di atas, tombol di tengah */
        @media (max-width: 640px) {
            .pgt { justify-content: center; }
            .pgt-info { width: 100%; text-align: center; }
            .pgt-btn { min-width: 2.125rem; height: 2.125rem; }
        }
    </style>
@endonce

@if ($paginator->hasPages())
    <nav class="pgt" role="navigation" aria-label="Navigasi halaman">
        <p class="pgt-info">
            Menampilkan <b>{{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }}</b>
            dari <b>{{ $paginator->total() }}</b> {{ $satuan ?? 'data' }}
        </p>

        <ul class="pgt-list">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li><span class="pgt-btn pgt-disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </span></li>
            @else
                <li><a class="pgt-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </a></li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pgt-btn pgt-dots" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="pgt-btn pgt-active" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="pgt-btn" href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a class="pgt-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a></li>
            @else
                <li><span class="pgt-btn pgt-disabled" aria-disabled="true" aria-label="Halaman berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </span></li>
            @endif
        </ul>
    </nav>
@endif
