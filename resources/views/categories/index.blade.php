<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Kategori Barang</h1>
<div class="mb-4 text-right">
    <a href="{{ route('categories.create') }}" class="px-4 py-2 rounded bg-slate-800 text-white">Tambah Kategori</a>
</div>
<div class="bg-white rounded-2xl shadow overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-2 text-left">Kode</th>
                <th class="p-2 text-left">Nama</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $c)
            <tr class="border-t">
                <td class="p-2">{{ $c->code }}</td>
                <td class="p-2">{{ $c->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
