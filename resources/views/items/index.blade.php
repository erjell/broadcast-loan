<!-- resources/views/items/index.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Master Aset Barang</h1>

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
    <div class="md:col-span-2">
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
                <th class="p-2 text-left">Kode</th>
                <th class="p-2 text-left">Serial</th>
                <th class="p-2 text-left">Tahun</th>
                <th class="p-2 text-left">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
                @foreach($it->assets as $as)
                <tr class="border-t">
                    <td class="p-2">{{ $as->code }}</td>
                    <td class="p-2">{{ $as->serial_number }}</td>
                    <td class="p-2">{{ $as->procurement_year }}</td>
                    <td class="p-2">{{ str_replace('_',' ',$as->condition) }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
