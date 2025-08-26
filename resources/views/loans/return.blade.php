<!-- resources/views/loans/return.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-2">Pengembalian — {{ $loan->code }}</h1>
<p class="text-sm text-slate-600 mb-4">{{ $loan->partner->name }} • {{ $loan->purpose }}</p>

<form action="{{ route('loans.return.process',$loan) }}" method="post" class="bg-white p-4 rounded-2xl shadow">
    @csrf
    <table class="w-full text-sm">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-2 text-left">Barang</th>
                <th class="p-2 text-center">Dipinjam</th>
                <th class="p-2 text-center">Sudah Kembali</th>
                <th class="p-2 text-center">Kembalikan</th>
                <th class="p-2 text-center">Kondisi</th>
                <th class="p-2">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->items as $i => $li)
            @php $sisa = max(0,$li->qty - $li->returned_qty); @endphp
            <tr class="border-t">
                <td class="p-2">{{ $li->item->barcode }} — {{ $li->item->name }}</td>
                <td class="p-2 text-center">{{ $li->qty }}</td>
                <td class="p-2 text-center">{{ $li->returned_qty }}</td>
                <td class="p-2 text-center">
                    <input type="number" name="returns[{{ $i }}][qty]" min="0" max="{{ $sisa }}" value="{{ $sisa }}" class="w-20 border rounded p-1 text-center">
                    <input type="hidden" name="returns[{{ $i }}][loan_item_id]" value="{{ $li->id }}">
                </td>
                <td class="p-2 text-center">
                    <select name="returns[{{ $i }}][condition]" class="border rounded p-1">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak ringan</option>
                        <option value="rusak_berat">Rusak berat</option>
                    </select>
                </td>
                <td class="p-2">
                    <input name="returns[{{ $i }}][notes]" class="w-full border rounded p-1" placeholder="Catatan (opsional)">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right mt-4">
        <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan Pengembalian</button>
    </div>
</form>
@endsection