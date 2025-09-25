{{-- resources/views/loans/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman') }} {{ $loan->code }}
        </h2>
    </x-slot>
    <x-slot name="breadcrumb">
        <x-breadcrumb :items="[
            ['label' => 'Peminjaman', 'url' => route('loans.index')],
            ['label' => 'Detail']
        ]" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <p class="text-sm text-slate-600 mb-2 sm:mb-0 break-words">
                    {{ $loan->partner->name }} • {{ $loan->purpose }} • {{ $loan->loan_date }} • Petugas: {{ optional($loan->user)->name }}
                </p>
                <div class="flex flex-wrap gap-2 no-print">
                    <button type="button" onclick="window.print()" class="inline-flex w-full sm:w-auto justify-center px-3 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700 transition">
                        Cetak Detail
                    </button>
                    <a href="{{ route('loans.return.form', $loan) }}" class="inline-flex w-full sm:w-auto justify-center px-3 py-2 rounded bg-slate-800 text-white">
                        Proses Pengembalian
                    </a>
                </div>
            </div>
            <div class="mt-4 bg-white/90 supports-[backdrop-filter]:bg-white/70 backdrop-blur border border-slate-200 rounded-xl shadow-sm overflow-hidden print-surface">
                <div class="overflow-x-auto">
                    <table data-sortable class="min-w-[60rem] w-full text-sm text-slate-700 print-table" x-data="tableSorterV2()" x-init="init($el)">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="font-semibold px-4 py-3">Kode Barang</th>
                            <th class="font-semibold px-4 py-3">Barang</th>
                            <th class="font-semibold px-4 py-3">Serial Number</th>
                            <th class="text-center font-semibold px-4 py-3">Kondisi</th>
                            <th class="text-center font-semibold px-4 py-3">Status</th>
                            <th class="font-semibold px-4 py-3">Catatan Kondisi</th>
                            {{-- <th class="p-2 text-center">Kembali</th>
                            <th class="p-2 text-center">Sisa</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->items as $li)
                        <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">{{ $li->item->code }}</td>
                            <td class="px-4 py-3">{{ $li->item->name }}</td>
                            <td class="px-4 py-3">{{ $li->item->serial_number }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 text-xs rounded-full" @class([ 'bg-emerald-100 text-emerald-700'=> $li->item->condition==='baik',
                                    'bg-amber-100 text-amber-700' => $li->item->condition==='rusak_ringan',
                                    'bg-red-100 text-red-700' => $li->item->condition==='rusak_berat',
                                    ])>{{ str_replace('_',' ',$li->item->condition) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($li->return_condition)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 text-emerald-700">Dikembalikan</span>
                                @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-slate-200 text-slate-700">Dipinjam</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-pre-line text-slate-600">{{ $li->return_notes ?: '-' }}</td>
                            {{-- <td class="p-2 text-center">{{ $li->returned_qty }}</td>
                            <td class="p-2 text-center">{{ max(0, $li->qty - $li->returned_qty) }}</td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body { background: #fff !important; color: #000 !important; }
            nav, .no-print { display: none !important; }
            header.bg-white { box-shadow: none !important; }
            main { padding: 0 1.5rem !important; }
            .print-surface { border: 1px solid #94a3b8 !important; box-shadow: none !important; background: #fff !important; }
            .print-table { border-collapse: collapse !important; width: 100% !important; }
            .print-table th, .print-table td { border: 1px solid #cbd5f5 !important; padding: 8px 12px !important; background: transparent !important; color: #0f172a !important; }
            .print-table thead th { background: #e2e8f0 !important; }
        }
    </style>
</x-app-layout>
