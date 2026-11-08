{{--
    Lencana grade A/B/C.
    Variabel: $g (A|B|C|null)
--}}
@php
    $warnaGrade = [
        'A' => 'bg-mint text-[#1F7A3D]',
        'B' => 'bg-sky text-[#1E6FA8]',
        'C' => 'bg-amber-100 text-amber-700',
    ][$g ?? ''] ?? 'bg-bgsoft text-inksoft';
@endphp
<span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-lg text-xs font-extrabold {{ $warnaGrade }}">{{ $g ?: '—' }}</span>
