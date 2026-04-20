<!-- resources/views/items/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Aset Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div x-data="{}" class="max-w-[110rem] mx-auto sm:px-6 lg:px-8">
            @php $filters = $filters ?? []; @endphp
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="text-sm text-slate-600 w-full sm:w-auto"></div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto sm:justify-end">
                    <a id="exportLink" data-export href="{{ route('items.export', $exportParams) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-100">Export Excel</a>
                    <button type="button" @click="$dispatch('open-modal', 'add-item')" class="inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-900">Tambah Barang</button>
                </div>
            </div>

            <!-- Modal: Tambah Barang -->
            <x-modal name="add-item" :show="false" maxWidth="2xl">
                <div id="add-item-modal">
                    <div class="px-6 pt-6 pb-2 border-b">
                        <h3 class="text-lg font-semibold">Tambah Barang</h3>
                    </div>
                    <form action="{{ route('items.store') }}" method="post" class="grid md:grid-cols-2 gap-4 p-6">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="block text-sm">Nama Barang</label>
                            <input name="name" class="w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm">Merk</label>
                            <input name="merk" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm">Tipe</label>
                            <input name="tipe" class="w-full border rounded p-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm">Detail Barang</label>
                            <textarea name="details" class="w-full border rounded p-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm">Serial Number</label>
                            <input name="serial_number" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm">Tahun Pengadaan</label>
                            <input type="number" name="procurement_year" class="w-full border rounded p-2" value="{{ now()->year }}">
                        </div>
                        <div>
                            <label class="block text-sm">Kondisi</label>
                            <select name="condition" class="w-full border rounded p-2" required>
                                <option value="baik">baik</option>
                                <option value="rusak_ringan">rusak ringan</option>
                                <option value="rusak_berat">rusak berat</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                <div class="flex-1">
                                    <label class="block text-sm">Kategori</label>
                                    <select name="category_id" class="w-full border rounded p-2" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm">Lokasi</label>
                            <input name="lokasi" class="w-full border rounded p-2" placeholder="Lokasi penyimpanan / penempatan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm">Kode Barang</label>
                            <input name="code" class="w-full border rounded p-2 bg-slate-100" readonly>
                        </div>
                        <div class="md:col-span-2 flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:justify-end">
                            <button type="button" @click="$dispatch('close-modal', 'add-item')" class="w-full sm:w-auto px-4 py-2 rounded border">Batal</button>
                            <button class="w-full sm:w-auto px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                        </div>
                    </form>
                </div>
                <script>
                    (function(){
                        const root = document.getElementById('add-item-modal');
                        if (!root) return;
                        root.addEventListener('change', async function(e){
                            const sel = e.target.closest('select[name="category_id"]');
                            if (!sel) return;
                            const codeInput = root.querySelector('input[name="code"]');
                            const catId = sel.value;
                            if (!catId) { codeInput.value = ''; return; }
                            const res = await fetch(`{{ route('items.code') }}?category_id=${catId}`);
                            const data = await res.json();
                            codeInput.value = data.code;
                        });
                    })();
                </script>
            </x-modal>

            <!-- Modal: Tambah Kategori -->
            <x-modal name="add-category" :show="false" maxWidth="xl">
                <div class="px-6 pt-6 pb-2 border-b">
                    <h3 class="text-lg font-semibold">Tambah Kategori</h3>
                </div>
                <form action="{{ route('categories.store') }}" method="post" class="p-6 flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ route('items.index') }}">
                    <div>
                        <label class="block text-sm">Nama Kategori</label>
                        <input name="name" class="w-full border rounded p-2" placeholder="Nama kategori" required>
                    </div>
                    <div>
                        <label class="block text-sm">Kode Kategori</label>
                        <input name="code_category" class="w-full border rounded p-2" placeholder="Kode Kategori" required>
                    </div>
                    <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="button" @click="$dispatch('close-modal', 'add-category')" class="w-full sm:w-auto px-4 py-2 rounded border">Batal</button>
                        <button class="w-full sm:w-auto px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                    </div>
                </form>
            </x-modal>
            <div class="bg-white/90 supports-[backdrop-filter]:bg-white/70 backdrop-blur border border-slate-200 rounded-xl shadow-sm overflow-hidden" id="itemTableWrapper">
                <form method="GET" action="{{ route('items.index') }}" class="border-b border-slate-200 p-4 space-y-4 bg-slate-50" id="itemTableFilters">
                    <div>
                        <label for="itemFilterSearch" class="block text-xs font-medium text-slate-500">Pencarian</label>
                        <input id="itemFilterSearch" type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30 placeholder:text-slate-400" placeholder="Cari Barang...">
                    </div>
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex flex-col gap-1">
                            <label for="filterCategory" class="text-xs font-medium text-slate-600">Kategori</label>
                            <select id="filterCategory" name="category" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua kategori</option>
                                @foreach($categories as $categoryOption)
                                <option value="{{ $categoryOption->id }}" @selected((string)($filters['category'] ?? '' )===(string) $categoryOption->id)>{{ $categoryOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="filterCondition" class="text-xs font-medium text-slate-600">Kondisi</label>
                            <select id="filterCondition" name="condition" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua kondisi</option>
                                <option value="baik" @selected(($filters['condition'] ?? '' )==='baik' )>Baik</option>
                                <option value="rusak_ringan" @selected(($filters['condition'] ?? '' )==='rusak_ringan' )>Rusak Ringan</option>
                                <option value="rusak_berat" @selected(($filters['condition'] ?? '' )==='rusak_berat' )>Rusak Berat</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="filterStatus" class="text-xs font-medium text-slate-600">Status</label>
                            <select id="filterStatus" name="status" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua status</option>
                                <option value="tersedia" @selected(($filters['status'] ?? '' )==='tersedia' )>Tersedia</option>
                                <option value="dipinjam" @selected(($filters['status'] ?? '' )==='dipinjam' )>Dipinjam</option>
                                <option value="hilang" @selected(($filters['status'] ?? '' )==='hilang' )>Hilang</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-600">Rentang Tahun Pengadaan</label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <input type="number" inputmode="numeric" min="1900" max="{{ now()->year }}" step="1" placeholder="Awal" name="year_start" value="{{ $filters['year_start'] ?? '' }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <input type="number" inputmode="numeric" min="1900" max="{{ now()->year }}" step="1" placeholder="Akhir" name="year_end" value="{{ $filters['year_end'] ?? '' }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-sm text-slate-500">
                            @if($items->total())
                            Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }} barang
                            @else
                            Tidak ada data yang cocok dengan filter saat ini.
                            @endif
                        </span>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-100">Reset Filter</a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-800 bg-slate-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-900">Terapkan Filter</button>
                        </div>
                    </div>
                </form>
                <div id="itemsList">
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
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">
                                    Kondisi
                                </th>
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
                                    <a href="{{ route('loans.show', $it->activeLoanItem->loan) }}" class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800 hover:bg-red-200">
                                        Dipinjam
                                    </a>
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
                            @push('modals')
                            {{-- Modal Cetak Barcode --}}
                            <x-modal name="cetak-{{ $it->id }}" :show="false" maxWidth="md">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold">Cetak Barcode</h3>
                                    <p class="text-sm text-slate-600 mt-1">{{ $it->name }}</p>
                                    <div class="flex flex-col-reverse gap-2 mt-6 sm:flex-row sm:justify-end">
                                        <button type="button" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-3 py-2 rounded-md text-sm font-medium bg-slate-800 text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500" onclick="window.open('{{ route('items.print',['id' => $it->code, 'type' => 'code']) }}', '_blank')">
                                            Kode
                                        </button>
                                        @php $hasSerial = !empty($it->serial_number); @endphp
                                        <button type="button" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-3 py-2 rounded-md text-sm font-medium border border-slate-300 text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 disabled:opacity-50 disabled:cursor-not-allowed" @disabled(!$hasSerial) onclick="if(!this.disabled){ window.open('{{ route('items.print',['id' => $it->serial_number, 'type' => 'serial_number']) }}', '_blank'); }">
                                            Serial Number
                                        </button>
                                    </div>
                                </div>
                            </x-modal>

                            <!-- Modal: Konfirmasi Hapus Barang -->
                            <x-modal name="delete-item-{{ $it->id }}" :show="false" maxWidth="md">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold mb-2">Konfirmasi Hapus</h3>
                                    <p class="text-sm text-slate-600">Yakin ingin menghapus barang
                                        <span class="font-semibold">"{{ $it->name }}"</span> dengan kode <span class="font-mono">{{ $it->code }}</span>?
                                        Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                    <div class="flex flex-col-reverse gap-2 mt-6 sm:flex-row sm:justify-end">
                                        <button type="button" @click="$dispatch('close-modal', 'delete-item-{{ $it->id }}')" class="w-full sm:w-auto px-4 py-2 rounded border">Batal</button>
                                        <form action="{{ route('items.destroy', $it) }}" method="post" class="w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="w-full sm:w-auto px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </x-modal>

                            {{-- Modal Edit Barang --}}
                            <x-modal name="edit-item-{{ $it->id }}" :show="false" maxWidth="2xl">
                                <div id="edit-item-modal-{{ $it->id }}">
                                    <div class="px-6 pt-6 pb-2 border-b">
                                        <h3 class="text-lg font-semibold">Edit Barang</h3>
                                    </div>
                                    <form action="{{ route('items.update', $it) }}" method="post" class="grid md:grid-cols-2 gap-4 p-6">
                                        @csrf
                                        @method('PUT')
                                        <div class="md:col-span-2">
                                            <label class="block text-sm">Nama Barang</label>
                                            <input name="name" class="w-full border rounded p-2" value="{{ $it->name }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm">Merk</label>
                                            <input name="merk" class="w-full border rounded p-2" value="{{ $it->merk }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Tipe</label>
                                            <input name="tipe" class="w-full border rounded p-2" value="{{ $it->tipe }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm">Detail Barang</label>
                                            <textarea name="details" class="w-full border rounded p-2">{{ $it->details }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm">Serial Number</label>
                                            <input name="serial_number" class="w-full border rounded p-2" value="{{ $it->serial_number }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Tahun Pengadaan</label>
                                            <input type="number" name="procurement_year" class="w-full border rounded p-2" value="{{ $it->procurement_year }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Kondisi</label>
                                            <select name="condition" class="w-full border rounded p-2" required>
                                                <option value="baik" @selected($it->condition==='baik')>baik</option>
                                                <option value="rusak_ringan" @selected($it->condition==='rusak_ringan')>rusak ringan</option>
                                                <option value="rusak_berat" @selected($it->condition==='rusak_berat')>rusak berat</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                                <div class="flex-1">
                                                    <label class="block text-sm">Kategori</label>
                                                    <select name="category_id" class="w-full border rounded p-2" required>
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($categories as $c)
                                                        <option value="{{ $c->id }}" @selected($it->category_id==$c->id)>{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm">Lokasi</label>
                                            <input name="lokasi" class="w-full border rounded p-2" value="{{ $it->lokasi }}" placeholder="Lokasi penyimpanan / penempatan">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="checkbox" name="is_missing" value="1" class="rounded border-slate-300" @checked($it->is_missing)>
                                                <span>Tandai sebagai hilang</span>
                                            </label>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm">Kode Barang</label>
                                            <input name="code" class="w-full border rounded p-2 bg-slate-100" value="{{ $it->code }}" readonly>
                                        </div>
                                        <div class="md:col-span-2 flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:justify-end">
                                            <button type="button" @click="$dispatch('close-modal', 'edit-item-{{ $it->id }}')" class="w-full sm:w-auto px-4 py-2 rounded border">Batal</button>
                                            <button class="w-full sm:w-auto px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                                <script>
                                    (function(){
                                 const root = document.getElementById('edit-item-modal-{{ $it->id }}');
                                 if (!root) return;
                                  root.addEventListener('change', async function(e){
                                 const sel = e.target.closest('select[name="category_id"]');
                                  if (!sel) return;
                                  const codeInput = root.querySelector('input[name="code"]');
                                   const catId = sel.value;
                                    if (!catId) { codeInput.value = ''; return; }
                                     const res = await fetch(`{{ route('items.code') }}?category_id=${catId}`);
                                       const data = await res.json();
                                      codeInput.value = data.code;
                                     });
                                })();
                                </script>
                            </x-modal>
                            @endpush
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
                </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    (function(){
      const baseIndex = @json(route('items.index'));
      const exportBase = @json(route('items.export'));
      const form = document.getElementById('itemTableFilters');
      const list = document.getElementById('itemsList');
      const exportLink = document.getElementById('exportLink');
      function serializeForm(f){
        const fd = new FormData(f);
        const params = new URLSearchParams();
        for (const [k,v] of fd.entries()){
          if (v !== null && String(v).trim() !== '') params.append(k, v);
        }
        return params.toString();
      }
      function updateExport(){
        if (!exportLink || !form) return;
        const qs = serializeForm(form);
        exportLink.href = exportBase + (qs ? ('?' + qs) : '');
      }
      async function loadList(url){
        try{
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
          if(!res.ok){ return; }
          const html = await res.text();
          if (list) list.innerHTML = html;
          window.history.replaceState({}, '', url);
        }catch(e){ /* ignore */ }
      }
      function buildUrl(pageUrl){
        if (pageUrl) return pageUrl;
        const qs = serializeForm(form);
        return baseIndex + (qs ? ('?' + qs) : '');
      }
      function debounce(fn, wait){
        let t; return function(){ const args=arguments; clearTimeout(t); t=setTimeout(()=>fn.apply(this,args), wait); };
      }
      if (form && list){
        const onChange = debounce(function(){ updateExport(); loadList(buildUrl()); }, 400);
        form.addEventListener('input', onChange);
        form.addEventListener('change', onChange);
        form.addEventListener('submit', function(e){ e.preventDefault(); updateExport(); loadList(buildUrl()); });
        list.addEventListener('click', function(e){
          const a = e.target.closest('a');
          if (!a || !a.href) return;
          const url = new URL(a.href, window.location.origin);
          if (url.origin !== window.location.origin) return;
          e.preventDefault();
          loadList(url.toString());
        });
        window.addEventListener('popstate', function(){ loadList(window.location.href); });
        updateExport();
      }
    })();
    </script>

</x-app-layout>

