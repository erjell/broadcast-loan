@props(['type' => 'success', 'message'])

@props(['type' => 'success', 'message'])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'error'   => 'bg-red-50 border-red-400 text-red-800',
    ];
    $paths = [
        'success' => 'M5 13l4 4L19 7',
        'error'   => 'M6 18L18 6M6 6l12 12',
    ];
@endphp

<div x-data="{show: true}" x-show="show" x-transition class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="flex items-start justify-between p-4 border-l-4 rounded shadow-sm {{ $styles[$type] ?? $styles['success'] }}">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $paths[$type] ?? $paths['success'] }}" />
            </svg>
            <span>{{ $message }}</span>
        </div>
        <button @click="show=false" class="ml-4 text-xl leading-none focus:outline-none">&times;</button>
    </div>
</div>
