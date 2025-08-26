<!-- resources/views/items/index.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Daftar Aset</h1>

<form action="{{ route('items.store') }}" method="post" class="grid md:grid-cols-2 gap-4 bg-white p-4 rounded-2xl shadow mb-4">
    @csrf
    <div class="md:col-span-2">
        <label class="block text-sm">Jenis Barang</label>
        <select name="item_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih --</option>
            @foreach($items as $it)
            <option value="{{ $it->id }}">{{ $it->name }} ({{ $it->category->name }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm">Serial Number</label>
        <input name="serial_number" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block text-sm">Tahun Pengadaan</label>
        <input type="number" name="procurement_year" min="1900" max="{{ date('Y') }}" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block text-sm">Kondisi</label>
        <select name="condition" class="w-full border rounded p-2" required>
            <option value="baik">Baik</option>
            <option value="rusak_ringan">Rusak Ringan</option>
            <option value="rusak_berat">Rusak Berat</option>
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
                <th class="p-2 text-left">Serial</th>
                <th class="p-2 text-left">Tahun</th>
                <th class="p-2 text-left">Kategori</th>
                <th class="p-2 text-left">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $a)
            <tr class="border-t">
                <td class="p-2">{{ $a->code }}</td>
                <td class="p-2">{{ $a->item->name }}</td>
                <td class="p-2">{{ $a->serial_number }}</td>
                <td class="p-2">{{ $a->procurement_year }}</td>
                <td class="p-2">{{ $a->item->category->name }}</td>
                <td class="p-2">{{ str_replace('_',' ',$a->condition) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $assets->links() }}</div>
@endsection
