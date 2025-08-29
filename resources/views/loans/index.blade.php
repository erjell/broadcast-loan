<!-- resources/views/loans/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div></div>
                <a href="{{ route('loans.create') }}" class="px-3 py-2 rounded bg-slate-800 text-white">Buat Peminjaman</a>
            </div>
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 text-left">Nama Peminjam</th>
                            <th class="p-2 text-left">Keperluan Acara/Lokasi</th>
                            <th class="p-2 text-left">Tanggal Pinjam</th>
                            <th class="p-2 text-left">Petugas</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $l)
                        <tr class="border-t">
                            <td class="p-2">{{ $l->partner->name }}</td>
                            <td class="p-2">{{ $l->purpose }}</td>
                            <td class="p-2">{{ $l->loan_date }}</td>
                            <td class="p-2">{{ optional($l->user)->name }}</td>
                            <td class="p-2">
                                <span class="px-2 py-0.5 rounded-full text-xs"
                @class([
                    'bg-amber-100 text-amber-700' => $l->status==='dipinjam',
                    'bg-sky-100 text-sky-700' => $l->status==='sebagian_kembali',
                    'bg-emerald-100 text-emerald-700' => $l->status==='selesai',
                ])>{{ str_replace('_',' ',$l->status) }}</span>
                            </td>
                            <td class="p-2 text-right">
                                <a href="{{ route('loans.show',$l) }}" class="px-2 py-1 rounded bg-slate-800 text-white">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $loans->links() }}</div>
        </div>
    </div>
</x-app-layout>
