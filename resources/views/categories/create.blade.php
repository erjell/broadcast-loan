<!-- resources/views/categories/create.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Kategori</h1>
<form action="{{ route('categories.store') }}" method="post" class="bg-white p-4 rounded-2xl shadow max-w-md">
    @csrf
    <div class="mb-3">
        <label class="block text-sm">Kode Kategori</label>
        <input name="code" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block text-sm">Nama Kategori</label>
        <input name="name" class="w-full border rounded p-2" required>
    </div>
    <div class="text-right">
        <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
    </div>
</form>
@endsection
