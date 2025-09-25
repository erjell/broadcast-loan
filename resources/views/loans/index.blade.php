<!-- resources/views/loans/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-slot name="breadcrumb">
                <x-breadcrumb :items="[
                                ['label' => 'Peminjaman']
                            ]" />
            </x-slot>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="w-full sm:w-auto"></div>
                <a href="{{ route('loans.create') }}" class="w-full sm:w-auto inline-flex justify-center px-3 py-2 rounded bg-slate-800 text-white">Buat Peminjaman</a>
            </div>
            <div class="bg-white rounded-2xl shadow">
                <div class="p-4 overflow-x-auto">
                    <table id="tabelPeminjaman" class="min-w-[60rem] w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 text-center">Nama Peminjam</th>
                            <th class="p-2 text-center">Keperluan Acara/Lokasi</th>
                            <th class="p-2 text-center">Tanggal Pinjam</th>
                            <th class="p-2 text-center">Petugas</th>
                            <th class="p-2 text-center">Status</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $l)
                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                            <td class="p-2">{{ $l->partner->name }}</td>
                            <td class="p-2">{{ $l->purpose }}</td>
                            <td class="p-2">{{ $l->loan_date }}</td>
                            <td class="p-2">{{ optional($l->user)->name }}</td>
                            <td class="p-2 text-center">
                                @if ($l->status==='dipinjam')
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800">Dipinjam</span>
                                @elseif($l->status==='sebagian_kembali')
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-amber-100 text-amber-800">Kembali Sebagian</span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Selesai</span>
                                @endif
                                {{-- <span class="px-2 py-0.5 rounded-full text-xs" @class([ 'bg-amber-100 text-amber-700'=> $l->status==='dipinjam',
                                    'bg-sky-100 text-sky-700' => $l->status==='sebagian_kembali',
                                    'inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800' => $l->status==='selesai',
                                    ])>{{ str_replace('_',' ',$l->status) }}
                                </span> --}}
                            </td>
                            <td class="p-2 text-center sm:text-right">
                                <a href="{{ route('loans.show',$l) }}" class="inline-flex w-full sm:w-auto justify-center px-3 py-1.5 rounded bg-slate-800 text-white">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $loans->links() }}</div>
        </div>
    </div>
</x-app-layout>
