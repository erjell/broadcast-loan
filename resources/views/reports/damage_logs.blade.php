<!-- resources/views/reports/damage_logs.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Kerusakan Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :items="[
                ['label' => 'Laporan', 'url' => route('reports.damages')],
                ['label' => 'Log Kerusakan']
            ]" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form method="get" action="{{ route('reports.damages') }}" class="grid md:grid-cols-5 gap-3 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Pencarian</label>
                            <input type="text" name="q" value="{{ $q ?? '' }}" class="w-full border rounded p-2" placeholder="Nama/Kode Barang, Partner, Catatan, Kode Pinjam">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Kondisi Saat Kembali</label>
                            <select name="condition" class="w-full border rounded p-2">
                                <option value="">Semua</option>
                                <option value="rusak_ringan" @selected(($condition ?? '' )==='rusak_ringan' )>rusak ringan</option>
                                <option value="rusak_berat" @selected(($condition ?? '' )==='rusak_berat' )>rusak berat</option>
                                <option value="baik" @selected(($condition ?? '' )==='baik' )>baik</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Dari Tanggal</label>
                            <input type="date" name="from" value="{{ optional($dateFrom)->format('Y-m-d') }}" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Sampai</label>
                            <input type="date" name="to" value="{{ optional($dateTo)->format('Y-m-d') }}" class="w-full border rounded p-2">
                        </div>
                        <div class="md:col-span-5 flex gap-2 justify-end">
                            <a href="{{ route('reports.damages') }}" class="px-3 py-2 border rounded">Reset</a>
                            <button class="px-3 py-2 rounded bg-slate-800 text-white">Terapkan</button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-left text-slate-600 border-b">
                                    <th class="py-2 pr-4">Tanggal Kembali</th>
                                    <th class="py-2 pr-4">Barang</th>
                                    <th class="py-2 pr-4">Kondisi</th>
                                    <th class="py-2 pr-4">Catatan</th>
                                    <th class="py-2 pr-4">Peminjaman</th>
                                    <th class="py-2 pr-4">Partner</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $row)
                                <tr class="odd:bg-white even:bg-gray-50 border-b align-top border-gray-200">
                                    <td class="py-2 pr-4 whitespace-nowrap">{{ $row->updated_at?->format('Y-m-d H:i') }}</td>
                                    <td class="py-2 pr-4">
                                        <div class="font-medium">{{ $row->item?->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $row->item?->code }}</div>
                                    </td>
                                    <td class="py-2 pr-4">
                                        @php $cond = $row->return_condition; @endphp
                                        @if($cond==='rusak_berat')
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-red-100 text-red-800">Rusak Berat</span>
                                        @elseif($cond==='rusak_ringan')
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-amber-100 text-amber-800">Rusak Ringan</span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-emerald-100 text-emerald-800">Baik</span>
                                        @endif
                                    </td>
                                    <td class="py-2 pr-4 max-w-[28rem]">
                                        <div class="whitespace-pre-wrap">{{ $row->return_notes ?: '-' }}</div>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <a href="{{ route('loans.show', $row->loan) }}" class="text-slate-800 hover:underline">{{ $row->loan?->code }}</a>
                                    </td>
                                    <td class="py-2 pr-4">{{ $row->loan?->partner?->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>