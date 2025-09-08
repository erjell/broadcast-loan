<!-- resources/views/loans/return.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengembalian') }} — {{ $loan->code }}
        </h2>
    </x-slot>
    <x-slot name="breadcrumb">
        <x-breadcrumb :items="[
            ['label' => 'Peminjaman', 'url' => route('loans.index')],
            ['label' => 'Detail', 'url' => route('loans.show', $loan)],
            ['label' => 'Pengembalian']
        ]" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <p class="text-sm text-slate-600 mb-4">{{ $loan->partner->name }} • {{ $loan->purpose }} • Petugas: {{ optional($loan->user)->name }}</p>
            <form action="{{ route('loans.return.process',$loan) }}" method="post" class="bg-white p-4 rounded-2xl shadow">
                @csrf
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2"></th>
                            <th class="p-2 text-left">Kode</th>
                            <th class="p-2 text-left">Barang</th>
                            <th class="p-2 text-left">Serial Number</th>
                            {{-- <th class="p-2 text-center">Dipinjam</th>
                            <th class="p-2 text-center">Sudah Kembali</th>
                            <th class="p-2 text-center">Kembalikan</th> --}}
                            <th class="p-2 text-center">Kondisi</th>
                            <th class="p-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->items as $i => $li)
                        @php $sisa = max(0,$li->qty - $li->returned_qty); @endphp
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td>
                                <input type="checkbox" name="returns[{{ $i }}][selected]" value="1">
                                <input type="hidden" name="returns[{{ $i }}][loan_item_id]" value="{{ $li->id }}">
                            </td>
                            <td class="p-2 text-center">{{ $li->item->code }}</td>
                            <td class="p-2 ">{{ $li->item->name }}</td>
                            <td class="p-2 ">{{ $li->item->serial_number }}</td>
                            {{-- <td class="p-2 text-center">{{ $li->qty }}</td>
                            <td class="p-2 text-center">{{ $li->returned_qty }}</td>
                            <td class="p-2 text-center">
                                <input type="number" name="returns[{{ $i }}][qty]" min="0" max="{{ $sisa }}" value="{{ $sisa }}" class="w-20 border rounded p-1 text-center">
                                <input type="hidden" name="returns[{{ $i }}][loan_item_id]" value="{{ $li->id }}">
                            </td> --}}
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
        </div>
    </div>
</x-app-layout>