{{-- resources/views/loans/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman') }} {{ $loan->code }}
        </h2>
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
                            <th class="p-2">Barang</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-center">Kembali</th>
                            <th class="p-2 text-center">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->items as $li)
                        <tr class="border-t">
                            <td class="p-2">{{ $li->item->code }} — {{ $li->item->name }}</td>
                            <td class="p-2 text-center">{{ $li->qty }}</td>
                            <td class="p-2 text-center">{{ $li->returned_qty }}</td>
                            <td class="p-2 text-center">{{ max(0, $li->qty - $li->returned_qty) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
