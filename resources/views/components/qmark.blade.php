@props([
    'size' => 16, // px, ikon info melingkar
    // Properti legacy untuk kompatibilitas (tidak dipakai):
    'bg' => null,
    'fg' => null,
    'label'=> 'Info',
    'strokeWidth' => 2,
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center align-middle leading-none']) }} role="img" aria-label="{{ $label }}">
    <!-- Ikon info sederhana: lingkaran outline + huruf "i" -->
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display:block">
        <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="{{ $strokeWidth }}" />
        <circle cx="12" cy="8" r="1.5" fill="currentColor" />
        <rect x="11" y="10" width="2" height="6" rx="1" fill="currentColor" />
    </svg>
</span>
