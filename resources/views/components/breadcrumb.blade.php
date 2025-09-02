@props([
// Array of items: [['label' => 'Peminjaman', 'url' => route('loans.index')], ...]
'items' => [],
// Optional home URL; defaults to dashboard
'home' => route('dashboard', absolute: false),
'homeLabel' => 'Home',
])

<nav aria-label="Breadcrumb" class="w-full">
    <ol class="inline-flex flex-wrap items-center gap-2 rounded-full bg-blue-50 px-4 py-2">
        <li>
            <a href="{{ $home }}" class="text-sm text-blue-600 hover:text-blue-700">{{ $homeLabel }}</a>
        </li>
        @foreach ($items as $i => $it)
        <li aria-hidden="true" class="text-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M9.22 4.22a.75.75 0 0 1 1.06 0l6.5 6.5a.75.75 0 0 1 0 1.06l-6.5 6.5a.75.75 0 1 1-1.06-1.06L15.94 12 9.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </li>
        @php $isLast = $i === count($items) - 1; @endphp
        <li class="truncate">
            @if(!$isLast && !empty($it['url']))
            <a href="{{ $it['url'] }}" class="text-sm text-blue-600 hover:text-blue-700">{{ $it['label'] }}</a>
            @else
            <span class="text-sm font-semibold text-blue-800">{{ $it['label'] }}</span>
            @endif
        </li>
        @endforeach
    </ol>
</nav>