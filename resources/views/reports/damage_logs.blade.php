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

            <div class="bg-white/90 supports-[backdrop-filter]:bg-white/70 backdrop-blur border border-slate-200 overflow-hidden shadow-sm rounded-xl" x-data="damageLogsFilter({
                search: @js($q ?? ''),
                condition: @js($condition ?? ''),
                from: @js(optional($dateFrom)->format('Y-m-d')),
                to: @js(optional($dateTo)->format('Y-m-d')),
            })" x-init="init()">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid md:grid-cols-5 gap-3 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Pencarian</label>
                            <input type="text" x-model.debounce.200ms="search" class="w-full border rounded p-2" placeholder="Nama/Kode Barang, Partner, Catatan, Kode Pinjam">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Kondisi Saat Kembali</label>
                            <select x-model="condition" class="w-full border rounded p-2">
                                <option value="">Semua</option>
                                <option value="rusak_ringan">rusak ringan</option>
                                <option value="rusak_berat">rusak berat</option>
                                <option value="baik">baik</option>
                                <option value="hilang">hilang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Dari Tanggal</label>
                            <input type="date" x-model="from" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Sampai</label>
                            <input type="date" x-model="to" class="w-full border rounded p-2">
                        </div>
                        <div class="md:col-span-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-between sm:items-center pt-2">
                            <span class="text-sm text-slate-500" x-text="countText"></span>
                            <div class="flex gap-2 sm:justify-end no-print">
                                <a href="{{ route('reports.damages.export', request()->query()) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 rounded border border-slate-300 text-sm text-slate-700 hover:bg-slate-50">Export Excel</a>
                                <button type="button" @click="reset()" class="w-full sm:w-auto px-3 py-2 border rounded text-center">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table data-sortable class="min-w-full text-sm text-slate-700" x-data="tableSorterV2()" x-init="init($el)">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr class="text-left text-slate-600 border-b">
                                    <th class="px-4 py-3 font-semibold">Tanggal Kembali</th>
                                    <th class="px-4 py-3 font-semibold">Barang</th>
                                    <th class="px-4 py-3 font-semibold">Kondisi</th>
                                    <th class="px-4 py-3 font-semibold">Catatan</th>
                                    <th class="px-4 py-3 font-semibold">Peminjaman</th>
                                    <th class="px-4 py-3 font-semibold">Partner</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tbody">
                                @forelse($logs as $row)
                                @php
                                $dateVal = optional($row->updated_at)->format('Y-m-d');
                                $cond = $row->return_condition;
                                @endphp
                                <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors align-top" data-row data-date="{{ $dateVal }}" data-cond="{{ strtolower($cond ?? '') }}">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $row->updated_at?->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $row->item?->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $row->item?->code }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                    @if($cond==='rusak_berat')
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-red-100 text-red-800">Rusak Berat</span>
                                    @elseif($cond==='rusak_ringan')
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-amber-100 text-amber-800">Rusak Ringan</span>
                                    @elseif($cond==='hilang')
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-amber-100 text-amber-800">Hilang</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-emerald-100 text-emerald-800">Baik</span>
                                    @endif
                                    </td>
                                    <td class="px-4 py-3 max-w-[28rem]">
                                        <div class="whitespace-pre-wrap">{{ $row->return_notes ?: '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('loans.show', $row->loan) }}" class="text-slate-800 hover:underline">{{ $row->loan?->code }}</a>
                                    </td>
                                    <td class="px-4 py-3">{{ $row->loan?->partner?->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada data</td>
                                </tr>
                                @endforelse
                                <tr x-ref="emptyRow" class="hidden">
                                    <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada data yang cocok dengan filter saat ini.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function damageLogsFilter(initial){
            return {
                search: initial.search || '',
                condition: initial.condition || '',
                from: initial.from || '',
                to: initial.to || '',
                count: 0,
                get countText(){
                    return this.count ? `${this.count} log ditampilkan` : 'Tidak ada data yang cocok';
                },
                init(){
                    this.$nextTick(() => {
                        this.apply();
                        this.$watch('search', () => this.apply());
                        this.$watch('condition', () => this.apply());
                        this.$watch('from', () => this.apply());
                        this.$watch('to', () => this.apply());
                    });
                },
                normalize(v){ return (v || '').toString().toLowerCase().trim(); },
                parseDate(v){ if(!v) return null; const d = new Date(v); return Number.isNaN(d.getTime()) ? null : d; },
                rowMatches(row){
                    const term = this.normalize(this.search);
                    if (term) {
                        if (!this.normalize(row.textContent).includes(term)) return false;
                    }
                    const cond = this.normalize(this.condition);
                    if (cond) {
                        const rowCond = this.normalize(row.dataset.cond || '');
                        if (!rowCond.includes(cond)) return false;
                    }
                    const start = this.parseDate(this.from);
                    const end = this.parseDate(this.to);
                    if (start || end) {
                        const rowDate = this.parseDate(row.dataset.date);
                        if (!rowDate) return false;
                        if (start && rowDate < start) return false;
                        if (end) {
                            const inclusiveEnd = new Date(this.to);
                            inclusiveEnd.setHours(23,59,59,999);
                            if (rowDate > inclusiveEnd) return false;
                        }
                    }
                    return true;
                },
                apply(){
                    const rows = Array.from(this.$refs.tbody?.querySelectorAll('tr[data-row]') || []);
                    let shown = 0;
                    for(const r of rows){
                        const ok = this.rowMatches(r);
                        r.style.display = ok ? '' : 'none';
                        if (ok) shown++;
                    }
                    this.count = shown;
                    if (this.$refs.emptyRow) this.$refs.emptyRow.style.display = shown ? 'none' : '';
                },
                reset(){
                    this.search = '';
                    this.condition = '';
                    this.from = '';
                    this.to = '';
                    this.apply();
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
    </x-app-layout>
