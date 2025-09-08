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

            <p class="text-sm text-slate-600 mb-4">
                {{ $loan->partner->name }} • {{ $loan->purpose }} • {{ $loan->loan_date }} • Petugas: {{ optional($loan->user)->name }}
            </p>
            <a href="{{ route('loans.return.form', $loan) }}" class="px-3 py-2 rounded bg-slate-800 text-white">
                Proses Pengembalian
            </a>
            <div class="mt-4 bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2">Kode Barang</th>
                            <th class="p-2">Barang</th>
                            <th class="p-2">Serial Number</th>
                            <th class="p-2 text-center">Kondisi</th>
                            <th class="p-2 text-center">Status</th>
                            {{-- <th class="p-2 text-center">Kembali</th>
                            <th class="p-2 text-center">Sisa</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->items as $li)
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="p-2">{{ $li->item->code }}</td>
                            <td class="p-2">{{ $li->item->name }}</td>
                            <td class="p-2">{{ $li->item->serial_number }}</td>
                            <td class="p-2 text-center">
                                <span class="px-2 py-0.5 text-xs rounded-full" @class([ 'bg-emerald-100 text-emerald-700'=> $li->item->condition==='baik',
                                    'bg-amber-100 text-amber-700' => $li->item->condition==='rusak_ringan',
                                    'bg-red-100 text-red-700' => $li->item->condition==='rusak_berat',
                                    ])>{{ str_replace('_',' ',$li->item->condition) }}</span>
                            </td>
                            <td class="p-2 text-center">
                                @if ($li->return_condition)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 text-emerald-700">Dikembalikan</span>
                                @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-slate-200 text-slate-700">Dipinjam</span>
                                @endif
                            </td>
                            {{-- <td class="p-2 text-center">{{ $li->returned_qty }}</td>
                            <td class="p-2 text-center">{{ max(0, $li->qty - $li->returned_qty) }}</td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>