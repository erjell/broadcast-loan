<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Kategori Barang</h1>
<form action="{{ route('categories.store') }}" method="post" class="flex gap-2 bg-white p-4 rounded-2xl shadow mb-4">
    @csrf
    <input name="name" class="flex-1 border rounded p-2" placeholder="Nama kategori" required>
    <input name="code" class="w-32 border rounded p-2" placeholder="Kode" required>
    <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
</form>
<div class="bg-white rounded-2xl shadow overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100">
            <tr>

                <th class="p-2 text-left">Nama Kategori</th>
                <th class="p-2 text-left">Kode Kategori</th>

            </tr>
        </thead>
        <tbody>
            @foreach($categories as $c)
            <tr class="border-t">
                <td class="p-2">{{ $c->code }}</td>
                <td class="p-2">{{ $c->name }}</td>
                <td class="p-2">{{ $c->code }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
