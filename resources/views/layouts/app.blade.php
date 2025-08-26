<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="id" x-data="{flash: true, modal:false}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Broadcast Loan' }}</title>
    <script defer src="https://unpkg.com/alpinejs"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800">
    <nav class="bg-white shadow sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 py-3 flex gap-4 items-center">
            <a href="{{ route('loans.index') }}" class="font-semibold">Peminjaman</a>
            <a href="{{ route('items.index') }}">Master Barang</a>
            <a href="{{ route('assets.index') }}">Aset</a>
        </div>
    </nav>

    @if(session('ok'))
    <div x-show="flash" x-transition class="max-w-3xl mx-auto mt-4 p-3 rounded-lg bg-green-100 text-green-800 flex justify-between">
        <span>{{ session('ok') }}</span>
        <button @click="flash=false">&times;</button>
    </div>
    @endif

    <main class="max-w-6xl mx-auto p-4">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Modal generic -->
    <div x-show="modal" x-transition class="fixed inset-0 bg-black/50 grid place-items-center">
        <div class="bg-white p-4 rounded-2xl w-[32rem]">
            <div class="font-semibold mb-2">Peringatan</div>
            <div id="modal-body" class="text-sm"></div>
            <div class="mt-4 text-right">
                <button class="px-3 py-1 rounded bg-slate-700 text-white" @click="modal=false">Tutup</button>
            </div>
        </div>
    </div>
</body>

</html>