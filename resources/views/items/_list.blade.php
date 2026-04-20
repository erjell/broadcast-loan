<div class="overflow-x-auto">
    <table data-sortable id="tabelBarang" class="min-w-[96rem] w-full text-sm text-slate-700 border border-slate-300 ">
        <thead class="sticky top-0 z-10 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 text-slate-700">
            <tr class="border-b border-slate-200">
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Kode</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Nama</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Merk</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Tipe</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Kategori</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Serial</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Tahun</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Kondisi</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Detail</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Status</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Lokasi</th>
                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3" data-nosort></th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $it)
            @php
            $statusLabel = ($it->is_missing)
                ? 'Hilang'
                : (($it->activeLoanItem && $it->activeLoanItem->loan) ? 'Dipinjam' : 'Tersedia');
            $procurementDate = $it->procurement_year ? $it->procurement_year . '-01-01' : null;
            @endphp
            <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors " data-status="{{ strtolower($statusLabel) }}" data-condition="{{ strtolower($it->condition) }}" data-procurement-date="{{ $procurementDate ?? '' }}" data-procurement-year="{{ $it->procurement_year }}">
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->code }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->name }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->merk }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->tipe }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->category->name }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->serial_number }}</td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->procurement_year }}</td>
                <td class="px-4 py-3 border-r border-slate-100">
                    @php $conditionLabel = str_replace('_', ' ', $it->condition); @endphp
                    @if($it->condition === 'baik')
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Baik</span>
                    @elseif($it->condition === 'rusak_ringan')
                    <div class="relative inline-flex items-center">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200">Rusak Ringan</span>
                        <div class="relative group ml-1">
                            <x-qmark size="16" label="Info" class="text-amber-600 hover:text-amber-700" />
                            @if(optional($it->lastReturn)->return_notes)
                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-150 absolute z-20 top-full left-1/2 -translate-x-1/2 mt-2 w-72 max-w-[18rem] rounded-lg bg-slate-800 text-white text-xs p-3 shadow-xl">
                                <div class="font-semibold mb-1">Catatan terakhir</div>
                                <div class="whitespace-pre-line">{{ $it->lastReturn->return_notes }}</div>
                                <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-3 h-3 rotate-45 bg-slate-800"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="relative inline-flex items-center">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800 hover:bg-red-200">{{ ucfirst($conditionLabel) }}</span>
                        @if(optional($it->lastReturn)->return_notes)
                        <div class="relative group ml-1">
                            <x-qmark size="16" label="Info" class="text-red-600 hover:text-red-700" />
                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-150 absolute z-20 top-full left-1/2 -translate-x-1/2 mt-2 w-72 max-w-[18rem] rounded-lg bg-slate-800 text-white text-xs p-3 shadow-xl">
                                <div class="font-semibold mb-1">Catatan terakhir</div>
                                <div class="whitespace-pre-line">{{ $it->lastReturn->return_notes }}</div>
                                <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-3 h-3 rotate-45 bg-slate-800"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->details }}</td>
                <td class="px-4 py-3 border-r border-slate-100">
                    @if($it->is_missing)
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-amber-100 text-amber-800">Hilang</span>
                    @elseif($it->activeLoanItem && $it->activeLoanItem->loan)
                    <a href="{{ route('loans.show', $it->activeLoanItem->loan) }}" class="inline-flex items-center px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 hover:bg-blue-200">Dipinjam</a>
                    @else
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Tersedia</span>
                    @endif
                </td>
                <td class="px-4 py-3 border-r border-slate-100">{{ $it->lokasi }}</td>
                <td class="px-4 py-3 ">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="$dispatch('open-modal', 'cetak-{{ $it->id }}')" class="inline-flex w-full sm:w-auto items-center justify-center px-3 py-1.5 text-xs rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cetak</button>
                        <button type="button" @click="$dispatch('open-modal', 'edit-item-{{ $it->id }}')" class="inline-flex w-full sm:w-auto items-center justify-center px-3 py-1.5 text-xs rounded-md border border-blue-300 text-blue-700 hover:bg-blue-50">Edit</button>
                        <button type="button" @click="$dispatch('open-modal', 'delete-item-{{ $it->id }}')" class="inline-flex w-full sm:w-auto items-center justify-center px-3 py-1.5 text-xs rounded-md border border-red-300 text-red-600 hover:bg-red-50">Hapus</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada data yang cocok dengan filter saat ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="border-t border-slate-200 px-4 py-4 bg-white">
    {{ $items->links() }}
</div>

