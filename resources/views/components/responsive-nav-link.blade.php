@props(['active'])

@php
$classes = 'nav-link'.(($active ?? false) ? ' active' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
