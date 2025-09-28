<!-- resources/views/loans/return.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengembalian') }} - {{ $loan->code }}
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

            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <p class="text-sm text-slate-600 mb-2 sm:mb-0">{{ $loan->partner->name }} • {{ $loan->purpose }} • Petugas: {{ optional($loan->user)->name }}</p>

            </div>
            <form action="{{ route('loans.return.process',$loan) }}" method="post" class="bg-white/90 supports-[backdrop-filter]:bg-white/70 backdrop-blur border border-slate-200 p-4 rounded-xl shadow-sm space-y-4 overflow-hidden print-surface">
                @csrf
                <div class="overflow-x-auto">
                    <table data-sortable class="min-w-[60rem] w-full text-sm text-slate-700 print-table" x-data="tableSorterV2()" x-init="init($el)">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-center font-semibold px-4 py-3 align-middle w-12" data-nosort>
                                    <input id="check-all" type="checkbox" class="rounded" onclick="toggleAllCheckboxes(this)">
                                </th>
                                <th class="text-left font-semibold px-4 py-3">Kode</th>
                                <th class="text-left font-semibold px-4 py-3">Barang</th>
                                <th class="text-left font-semibold px-4 py-3">Serial Number</th>
                                <th class="text-center font-semibold px-4 py-3">Kondisi</th>
                                <th class="font-semibold px-4 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loan->items as $i => $li)
                            <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-center align-middle w-12">
                                    <input type="checkbox" name="returns[{{ $i }}][selected]" value="1" class="rounded itemCheckbox">
                                    <input type="hidden" name="returns[{{ $i }}][loan_item_id]" value="{{ $li->id }}">
                                </td>
                                <td class="px-4 py-3 text-center">{{ $li->item->code }}</td>
                                <td class="px-4 py-3 ">{{ $li->item->name }}</td>
                                <td class="px-4 py-3 ">{{ $li->item->serial_number }}</td>
                                <td class="px-4 py-3 text-center">
                                    <select name="returns[{{ $i }}][condition]" class="border rounded p-1">
                                        <option value="baik">Baik</option>
                                        <option value="rusak_ringan">Rusak ringan</option>
                                        <option value="rusak_berat">Rusak berat</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input name="returns[{{ $i }}][notes]" class="w-full border rounded p-1" placeholder="Catatan (opsional)">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end no-print">
                    <button class="w-full sm:w-auto px-4 py-2 rounded bg-slate-800 text-white">Simpan Pengembalian</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
            }

            nav,
            .no-print {
                display: none !important;
            }

            header.bg-white {
                box-shadow: none !important;
            }

            main {
                padding: 0 1.5rem !important;
            }

            .print-surface {
                border: 1px solid #94a3b8 !important;
                box-shadow: none !important;
                background: #fff !important;
            }

            .print-table {
                border-collapse: collapse !important;
                width: 100% !important;
            }

            .print-table th,
            .print-table td {
                border: 1px solid #cbd5f5 !important;
                padding: 8px 12px !important;
                background: transparent !important;
                color: #0f172a !important;
            }

            .print-table thead th {
                background: #e2e8f0 !important;
            }
        }
    </style>
</x-app-layout>