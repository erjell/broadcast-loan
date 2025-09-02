@props(['active'])

@php
$classes = 'nav-link'.(($active ?? false) ? ' active' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} aria-current="page">
    {{ $slot }}
</a>
