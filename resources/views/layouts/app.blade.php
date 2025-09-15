<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



    {{-- DataTables.net - Tailwind --}}

    {{--
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.tailwindcss.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.tailwindcss.js"></script> --}}

    {{-- DataTables.net --}}

    <!-- Scripts -->
    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.3/b-3.2.4/b-colvis-3.2.4/b-html5-3.2.4/b-print-3.2.4/cr-2.1.1/cc-1.0.7/date-1.5.6/fc-5.0.4/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.5.2/rr-1.5.0/sc-2.4.3/sb-1.8.3/sp-2.3.5/sl-3.1.0/datatables.min.css" rel="stylesheet" integrity="sha384-oeXCSIAxz6d8DmNeNcgpyWtlX9T0AgmIY8GJYCDbndbpOjRueK5s/E9ZRKbUkwY9" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.3/b-3.2.4/b-colvis-3.2.4/b-html5-3.2.4/b-print-3.2.4/cr-2.1.1/cc-1.0.7/date-1.5.6/fc-5.0.4/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.5.2/rr-1.5.0/sc-2.4.3/sb-1.8.3/sp-2.3.5/sl-3.1.0/datatables.min.js" integrity="sha384-I5Yk3WapAverYvLPpr3zdvtneVgglE6H5Tnj55Np8ppRFkRe9982KsDjN+9wfEkC" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite([
    
    ])
    
    {{-- @vite([
    'resources/css/app.css',
    'resources/css/dataTables.tailwindcss.css',
    'resources/js/app.js',
    'resources/js/dataTables.js',
    'resources/js/dataTables.tailwindcss.js'
    ]) --}}
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
                @isset($breadcrumb)
                <div class="mt-2">
                    {{ $breadcrumb }}
                </div>
                @endisset
            </div>
        </header>
        @endisset

        @if (session('ok'))
        <x-alert type="success" :message="session('ok')" />
        @elseif (session('error'))
        <x-alert type="error" :message="session('error')" />
        @endif

        @if ($errors->any())
        <x-alert type="error" :message="$errors->first()" />
        @endif

        <!-- Page Content -->
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @vite([
    'resources/js/Tables.js',
    'resources/css/app.css',
    'resources/js/app.js'
    ])
</body>

</html>