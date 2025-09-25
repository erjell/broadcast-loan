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
            <form action="{{ route('loans.return.process',$loan) }}" method="post" class="bg-white p-4 rounded-2xl shadow space-y-4">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-[60rem] w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 text-center align-middle w-12">
                                <input id="check-all" type="checkbox" class="rounded" onclick="toggleAllCheckboxes(this)">
                            </th>
                            <th class="p-2 text-left">Kode</th>
                            <th class="p-2 text-left">Barang</th>
                            <th class="p-2 text-left">Serial Number</th>
                            <th class="p-2 text-center">Kondisi</th>
                            <th class="p-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->items as $i => $li)
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="p-2 text-center align-middle w-12">
                                <input type="checkbox" name="returns[{{ $i }}][selected]" value="1" class="rounded itemCheckbox">
                                <input type="hidden" name="returns[{{ $i }}][loan_item_id]" value="{{ $li->id }}">
                            </td>
                            <td class="p-2 text-center">{{ $li->item->code }}</td>
                            <td class="p-2 ">{{ $li->item->name }}</td>
                            <td class="p-2 ">{{ $li->item->serial_number }}</td>
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
                </div>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button class="w-full sm:w-auto px-4 py-2 rounded bg-slate-800 text-white">Simpan Pengembalian</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
