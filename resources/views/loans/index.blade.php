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
                <div class="text-sm text-slate-600">Menampilkan {{ $loans->count() }} dari {{ method_exists($loans, 'total') ? $loans->total() : $loans->count() }} peminjaman</div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto sm:justify-end no-print">
                    <button type="button" onclick="window.print()" class="w-full sm:w-auto inline-flex justify-center px-3 py-2 rounded border border-slate-300 text-slate-700 hover:bg-slate-50">Cetak Daftar</button>
                    <a href="{{ route('loans.create') }}" class="w-full sm:w-auto inline-flex justify-center px-3 py-2 rounded bg-slate-800 text-white">Buat Peminjaman</a>
                </div>
            </div>
            <div class="bg-white/90 supports-[backdrop-filter]:bg-white/70 backdrop-blur border border-slate-200 rounded-xl shadow-sm overflow-hidden print-surface" id="loanTableWrapper">
                <div class="border-b border-slate-200 p-4 space-y-4 bg-slate-50 no-print" id="loanTableFilters">
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex flex-col gap-1">
                            <label for="loanFilterSearch" class="text-xs font-medium text-slate-600">Pencarian</label>
                            <input id="loanFilterSearch" type="text" data-filter-global class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Cari nama peminjam, keperluan, petugas...">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="loanFilterStatus" class="text-xs font-medium text-slate-600">Status</label>
                            <select id="loanFilterStatus" data-filter-status class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500">
                                <option value="">Semua status</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="sebagian_kembali">Kembali Sebagian</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-600">Rentang Tanggal</label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <input type="date" data-filter-date="start" class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500">
                                <input type="date" data-filter-date="end" class="rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-sm text-slate-500" data-filter-count></span>
                        <button type="button" data-filter-reset class="inline-flex items-center justify-center rounded border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Reset</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table data-sortable id="tabelPeminjaman" class="min-w-[60rem] w-full text-sm text-slate-700 print-table" x-data="tableSorterV2()" x-init="init($el)">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-center font-semibold px-4 py-3">Nama Peminjam</th>
                                <th class="text-center font-semibold px-4 py-3">Keperluan Acara/Lokasi</th>
                                <th class="text-center font-semibold px-4 py-3">Tanggal Pinjam</th>
                                <th class="text-center font-semibold px-4 py-3">Petugas</th>
                                <th class="text-center font-semibold px-4 py-3">Status</th>
                                <th class="px-4 py-3" data-nosort></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $l)
                            @php
                            $loanDate = $l->loan_date instanceof \Carbon\Carbon ? $l->loan_date->format('Y-m-d') : (string) $l->loan_date;
                            @endphp
                            <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors" data-status="{{ strtolower($l->status) }}" data-loan-date="{{ $loanDate }}">
                                <td class="px-4 py-3">{{ $l->partner->name }}</td>
                                <td class="px-4 py-3">{{ $l->purpose }}</td>
                                <td class="px-4 py-3">{{ $l->loan_date }}</td>
                                <td class="px-4 py-3">{{ optional($l->user)->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($l->status==='dipinjam')
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800">Dipinjam</span>
                                    @elseif($l->status==='sebagian_kembali')
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-amber-100 text-amber-800">Kembali Sebagian</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Selesai</span>
                                    @endif

                                </td>
                                <td class="p-2 text-center sm:text-right">
                                    <a href="{{ route('loans.show',$l) }}" class="inline-flex w-full sm:w-auto justify-center px-3 py-1.5 rounded bg-slate-800 text-white">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                            <tr data-empty-row class="hidden">
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada data yang cocok dengan filter saat ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $loans->links() }}</div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const wrapper = document.getElementById('loanTableWrapper');
            const table = document.getElementById('tabelPeminjaman');
            if (!wrapper || !table) return;

            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.hasAttribute('data-empty-row'));
            const emptyRow = tbody.querySelector('[data-empty-row]');

            const filters = {
                global: wrapper.querySelector('[data-filter-global]'),
                status: wrapper.querySelector('[data-filter-status]'),
                dateStart: wrapper.querySelector('[data-filter-date="start"]'),
                dateEnd: wrapper.querySelector('[data-filter-date="end"]'),
                reset: wrapper.querySelector('[data-filter-reset]'),
                count: wrapper.querySelector('[data-filter-count]'),
            };

            const inputs = [filters.global, filters.status, filters.dateStart, filters.dateEnd].filter(Boolean);
            const normalize = (v) => (v || '').toString().toLowerCase().trim();

            const getCellText = (row, idx) => {
                const cell = row.cells[idx];
                return cell ? normalize(cell.textContent) : '';
            };

            const getDateFromRow = (row) => {
                const raw = row.dataset.loanDate || getCellText(row, 2);
                const d = new Date(raw);
                return Number.isNaN(d.getTime()) ? null : d;
            };

            const rowMatches = (row) => {
                if (filters.global) {
                    const term = normalize(filters.global.value);
                    if (term && !normalize(row.textContent).includes(term)) return false;
                }

                if (filters.status) {
                    const val = normalize(filters.status.value);
                    if (val) {
                        const rowStatus = normalize(row.dataset.status || getCellText(row, 4));
                        if (!rowStatus.includes(val)) return false;
                    }
                }

                const startDate = filters.dateStart && filters.dateStart.value ? new Date(filters.dateStart.value) : null;
                const endDate = filters.dateEnd && filters.dateEnd.value ? new Date(filters.dateEnd.value) : null;
                if (startDate || endDate) {
                    const d = getDateFromRow(row);
                    if (!d) return false;
                    if (startDate && d < startDate) return false;
                    if (endDate) {
                        const inclusiveEnd = new Date(filters.dateEnd.value);
                        inclusiveEnd.setHours(23,59,59,999);
                        if (d > inclusiveEnd) return false;
                    }
                }

                return true;
            };

            const apply = () => {
                let count = 0;
                for (const r of rows) {
                    const ok = rowMatches(r);
                    r.style.display = ok ? '' : 'none';
                    if (ok) count++;
                }
                if (emptyRow) emptyRow.style.display = count ? 'none' : '';
                if (filters.count) filters.count.textContent = count ? `${count} peminjaman ditampilkan` : 'Tidak ada data yang cocok';
            };

            inputs.forEach((el) => {
                const h = () => window.requestAnimationFrame(apply);
                el.addEventListener('input', h);
                el.addEventListener('change', h);
            });

            if (filters.reset) {
                filters.reset.addEventListener('click', () => {
                    inputs.forEach((el) => { el.value = ''; });
                    window.requestAnimationFrame(apply);
                });
            }

            apply();
        });
    </script>
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
