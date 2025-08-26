<!-- resources/views/items/index.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Master Barang</h1>

<form action="{{ route('items.store') }}" method="post" class="grid md:grid-cols-2 gap-4 bg-white p-4 rounded-2xl shadow mb-4">
    @csrf
    <div class="md:col-span-2">
        <label class="block text-sm">Nama Barang</label>
        <input name="name" class="w-full border rounded p-2" required>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm">Detail Barang</label>
        <textarea name="details" class="w-full border rounded p-2"></textarea>
    </div>
    <div>
        <label class="block text-sm">Kategori</label>
        <select name="category_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih --</option>
            @foreach($categories as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2 text-right">
        <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
    </div>
</form>

<div class="bg-white rounded-2xl shadow overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-2 text-left">Kode Barang</th>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Kategori</th>
                <th class="p-2 text-left">Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
            <tr class="border-t">
                <td class="p-2">{{ $it->code }}</td>
                <td class="p-2">{{ $it->name }}</td>
                <td class="p-2">{{ $it->category->name }}</td>
                <td class="p-2">{{ $it->details }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
