<!-- resources/views/loans/index.blade.php -->
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-semibold">Daftar Peminjaman</h1>
    <a href="{{ route('loans.create') }}" class="px-3 py-2 rounded bg-slate-800 text-white">Buat Peminjaman</a>
</div>
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-2">Kode</th>
                <th class="p-2">Partner</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $l)
            <tr class="border-t">
                <td class="p-2">{{ $l->code }}</td>
                <td class="p-2">{{ $l->partner->name }}</td>
                <td class="p-2">{{ $l->loan_date }}</td>
                <td class="p-2">
                    <span class="px-2 py-0.5 rounded-full text-xs
        @class([
          'bg-amber-100 text-amber-700' => $l->status==='dipinjam',
          'bg-sky-100 text-sky-700' => $l->status==='sebagian_kembali',
          'bg-emerald-100 text-emerald-700' => $l->status==='selesai',
        ])">{{ str_replace('_',' ',$l->status) }}</span>
                </td>
                <td class="p-2 text-right">
                    <a href="{{ route('loans.show',$l) }}" class="underline">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $loans->links() }}</div>
@endsection