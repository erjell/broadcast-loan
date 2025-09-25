<!-- resources/views/items/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Aset Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div x-data="{}" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="itemExport({ exportBase: @js(route('items.export')) })" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="text-sm text-slate-600 w-full sm:w-auto"></div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto sm:justify-end">
                    <a :href="buildExportUrl()" @click.prevent="window.location = buildExportUrl()" href="{{ route('items.export') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-100">Export Excel</a>
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
                <div class="border-b border-slate-200 p-4 space-y-4 bg-slate-50" id="itemTableFilters">
                    <div>
                        <label for="itemFilterSearch" class="block text-xs font-medium text-slate-500">Pencarian</label>
                        <input id="itemFilterSearch" type="text" data-filter-global class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30 placeholder:text-slate-400" placeholder="Cari Barang...">
                    </div>
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex flex-col gap-1">
                            <label for="filterCategory" class="text-xs font-medium text-slate-600">Kategori</label>
                            <select id="filterCategory" data-filter-select-column="2" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua kategori</option>
                                @foreach($categories as $categoryOption)
                                <option value="{{ $categoryOption->name }}">{{ $categoryOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="filterCondition" class="text-xs font-medium text-slate-600">Kondisi</label>
                            <select id="filterCondition" data-filter-condition class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="filterStatus" class="text-xs font-medium text-slate-600">Status</label>
                            <select id="filterStatus" data-filter-status class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <option value="">Semua status</option>
                                <option value="tersedia">Tersedia</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-600">Rentang Tahun Pengadaan</label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <input type="number" inputmode="numeric" min="1900" max="{{ now()->year }}" step="1" placeholder="Awal" data-filter-year="start" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                                <input type="number" inputmode="numeric" min="1900" max="{{ now()->year }}" step="1" placeholder="Akhir" data-filter-year="end" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/30">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-sm text-slate-500" data-filter-count></span>
                        <button type="button" data-filter-reset class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-100">Reset Filter</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table data-sortable id="tabelBarang" class="min-w-[64rem] w-full text-sm text-slate-700">
                        <thead class="sticky top-0 z-10 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 text-slate-700">
                            <tr class="border-b border-slate-200">
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Kode</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Nama</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Kategori</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Serial</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Tahun</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">
                                    Kondisi
                                </th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Detail</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3">Status</th>
                                <th class="text-left uppercase text-[11px] tracking-[0.08em] font-semibold px-4 py-3" data-nosort></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $it)
                            @php
                            $statusLabel = ($it->is_missing)
                            ? 'Hilang'
                            : (($it->activeLoanItem && $it->activeLoanItem->loan) ? 'Dipinjam' : 'Tersedia');
                            $procurementDate = $it->procurement_year ? $it->procurement_year . '-01-01' : null;
                            @endphp
                            <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors" data-status="{{ strtolower($statusLabel) }}" data-condition="{{ strtolower($it->condition) }}" data-procurement-date="{{ $procurementDate ?? '' }}" data-procurement-year="{{ $it->procurement_year }}">
                                <td class="px-4 py-3">{{ $it->code }}</td>
                                <td class="px-4 py-3">{{ $it->name }}</td>
                                <td class="px-4 py-3">{{ $it->category->name }}</td>
                                <td class="px-4 py-3">{{ $it->serial_number }}</td>
                                <td class="px-4 py-3">{{ $it->procurement_year }}</td>
                                <td class="px-4 py-3">
                                    @if(str_replace('_',' ',$it->condition == "baik"))
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Baik</span>
                                    @elseif(str_replace('_',' ',$it->condition == "rusak_ringan"))
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
                                        <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800 hover:bg-red-200">Rusak</span>
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

                                <td class="px-4 py-3">{{ $it->details }}</td>
                                <td class="px-4 py-3">
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
                                <td class="px-4 py-3">
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
                            @endforeach
                            <tr data-empty-row class="hidden">
                                <td colspan="9" class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada data yang cocok dengan filter saat ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="mt-3">{{ $items->links() }}</div>
        </div>
    </div>

    <script>
        function itemExport(initial){
            return {
                exportBase: initial.exportBase || '',
                getFilters(){
                    const w = document.getElementById('itemTableWrapper');
                    if (!w) return {};
                    return {
                        q: w.querySelector('[data-filter-global]')?.value || '',
                        category: document.getElementById('filterCategory')?.value || '',
                        condition: w.querySelector('[data-filter-condition]')?.value || '',
                        status: w.querySelector('[data-filter-status]')?.value || '',
                        year_start: w.querySelector('[data-filter-year="start"]')?.value || '',
                        year_end: w.querySelector('[data-filter-year="end"]')?.value || '',
                    };
                },
                buildExportUrl(){
                    const params = new URLSearchParams();
                    const f = this.getFilters();
                    if (f.q) params.set('q', f.q);
                    if (f.category) params.set('category', f.category);
                    if (f.condition) params.set('condition', f.condition);
                    if (f.status) params.set('status', f.status);
                    if (f.year_start) params.set('year_start', f.year_start);
                    if (f.year_end) params.set('year_end', f.year_end);
                    const qs = params.toString();
                    return this.exportBase + (qs ? `?${qs}` : '');
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.getElementById('itemTableWrapper');
            const table = document.getElementById('tabelBarang');
            if (!wrapper || !table) {
                return;
            }

            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => !row.hasAttribute('data-empty-row'));
            const emptyRow = tbody.querySelector('[data-empty-row]');

            const filters = {
                global: wrapper.querySelector('[data-filter-global]'),
                columns: Array.from(wrapper.querySelectorAll('[data-filter-column]')),
                selectColumns: Array.from(wrapper.querySelectorAll('[data-filter-select-column]')),
                condition: wrapper.querySelector('[data-filter-condition]'),
                status: wrapper.querySelector('[data-filter-status]'),
                yearStart: wrapper.querySelector('[data-filter-year="start"]'),
                yearEnd: wrapper.querySelector('[data-filter-year="end"]'),
                reset: wrapper.querySelector('[data-filter-reset]'),
                count: wrapper.querySelector('[data-filter-count]'),
            };

            const inputs = [
                filters.global,
                ...filters.columns,
                ...filters.selectColumns,
                filters.condition,
                filters.status,
                filters.yearStart,
                filters.yearEnd,
            ].filter(Boolean);

            const normalize = (value) => (value || '').toString().toLowerCase().trim();

            const getCellText = (row, index) => {
                const cell = row.cells[index];
                return cell ? normalize(cell.textContent) : '';
            };

            const getYearFromRow = (row) => {
                const raw = row.dataset.procurementYear || getCellText(row, 4);
                const year = parseInt(raw, 10);
                return Number.isNaN(year) ? null : year;
            };

            const rowMatches = (row) => {
                if (filters.global) {
                    const searchTerm = normalize(filters.global.value);
                    if (searchTerm && !normalize(row.textContent).includes(searchTerm)) {
                        return false;
                    }
                }

                for (const input of filters.columns) {
                    const value = normalize(input.value);
                    if (!value) {
                        continue;
                    }
                    const columnIndex = Number(input.dataset.filterColumn);
                    if (!Number.isInteger(columnIndex)) {
                        continue;
                    }
                    const cellValue = getCellText(row, columnIndex);
                    if (!cellValue.includes(value)) {
                        return false;
                    }
                }

                for (const select of filters.selectColumns) {
                    const value = normalize(select.value);
                    if (!value) {
                        continue;
                    }
                    const columnIndex = Number(select.dataset.filterSelectColumn);
                    if (!Number.isInteger(columnIndex)) {
                        continue;
                    }
                    const cellValue = getCellText(row, columnIndex);
                    if (!cellValue.includes(value)) {
                        return false;
                    }
                }

                if (filters.condition) {
                    const conditionValue = normalize(filters.condition.value);
                    if (conditionValue) {
                        const rowCondition = normalize(row.dataset.condition || getCellText(row, 5));
                        if (!rowCondition.includes(conditionValue)) {
                            return false;
                        }
                    }
                }

                if (filters.status) {
                    const statusValue = normalize(filters.status.value);
                    if (statusValue) {
                        const rowStatus = normalize(row.dataset.status || getCellText(row, 7));
                        if (!rowStatus.includes(statusValue)) {
                            return false;
                        }
                    }
                }

                const startYear = filters.yearStart && filters.yearStart.value ? parseInt(filters.yearStart.value, 10) : null;
                const endYear = filters.yearEnd && filters.yearEnd.value ? parseInt(filters.yearEnd.value, 10) : null;
                if (startYear || endYear) {
                    const itemYear = getYearFromRow(row);
                    if (itemYear === null) {
                        return false;
                    }
                    if (startYear && itemYear < startYear) {
                        return false;
                    }
                    if (endYear && itemYear > endYear) {
                        return false;
                    }
                }

                return true;
            };

            const applyFilters = () => {
                let visibleCount = 0;
                for (const row of rows) {
                    const isMatch = rowMatches(row);
                    row.style.display = isMatch ? '' : 'none';
                    if (isMatch) {
                        visibleCount += 1;
                    }
                }

                if (emptyRow) {
                    emptyRow.style.display = visibleCount ? 'none' : '';
                }

                if (filters.count) {
                    filters.count.textContent = visibleCount ? `${visibleCount} barang ditampilkan` : 'Tidak ada data yang cocok';
                }
            };

            inputs.forEach((input) => {
                const handler = () => window.requestAnimationFrame(applyFilters);
                input.addEventListener('input', handler);
                if (input.tagName === 'SELECT' || input.type === 'date') {
                    input.addEventListener('change', handler);
                }
            });

            if (filters.reset) {
                filters.reset.addEventListener('click', () => {
                    inputs.forEach((input) => {
                        if (input.tagName === 'SELECT' || input.type === 'date') {
                            input.value = '';
                        } else if ('value' in input) {
                            input.value = '';
                        }
                    });
                    window.requestAnimationFrame(applyFilters);
                });
            }

            applyFilters();
        });
    </script>
    <script>
        if(!window.__tableSorterDefined){
      window.__tableSorterDefined = true;
      window.tableSorter = function(){
        return {
          table: null,
          sortKey: null,
          sortDir: 'asc',
          init(el){
            this.table = el;
            const head = el.tHead && el.tHead.rows[0];
            if(!head) return;
            Array.from(head.cells).forEach((th, idx) => {
              const noSort = th.hasAttribute('data-nosort');
              th.style.cursor = noSort ? 'default' : 'pointer';
              if (noSort) return;
              const icon = document.createElement('span');
              icon.className = 'ml-1 inline-flex items-center';
              icon.innerHTML = '<svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n  <path d="M10 3.5v13" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="opacity-40" data-stem></path>\n  <path data-icon-up d="M6.5 8l3.5-3.5 3.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-40"></path>\n  <path data-icon-down d="M6.5 12l3.5 3.5 3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-40"></path>\n</svg>'
              th.appendChild(icon);
              th.addEventListener('click', () => this.sortBy(idx));
            });
          },
          getRows(){
            const tb = this.table.tBodies[0];
            return Array.from(tb.querySelectorAll('tr')).filter(r => !r.hasAttribute('data-empty-row'));
          },
          val(row, idx){
            const cell = row.cells[idx];
            return cell ? cell.textContent.trim() : '';
          },
          cmp(a,b){
            const ax = this.val(a.row, this.sortKey);
            const bx = this.val(b.row, this.sortKey);
            const na = parseFloat(ax.replace(/[^0-9.-]/g, ''));
            const nb = parseFloat(bx.replace(/[^0-9.-]/g, ''));
            const an = !Number.isNa(na) && String(na) === ax;
            const bn = !Number.isNa(nb) && String(nb) === bx;
            if (an && bn) return na - nb;
            const da = new Date(ax), db = new Date(bx);
            if (!Number.isNa(da) && !Number.isNa(db)) return da - db;
            return ax.localeCompare(bx, undefined, { numeric: true, sensitivity: 'base' });
          },
          updateIcons(){
            const head = this.table.tHead && this.table.tHead.rows[0];
            if(!head) return;
            Array.from(head.cells).forEach((th, idx) => {
              const up = th.querySelector('[data-icon-up]');
              const dn = th.querySelector('[data-icon-down]');
              if (!up || !dn) return;
              up.classList.remove('opacity-100');
              dn.classList.remove('opacity-100');
              up.classList.add('opacity-30');
              dn.classList.add('opacity-30');
              if (idx === this.sortKey) {
                if (this.sortDir === 'asc') up.classList.replace('opacity-30', 'opacity-100');
                else dn.classList.replace('opacity-30', 'opacity-100');
              }
            });
          },
          sortBy(idx){
            this.sortKey === idx ? this.sortDir = (this.sortDir === 'asc' ? 'desc' : 'asc') : (this.sortKey = idx, this.sortDir = 'asc');
            const body = this.table.tBodies[0];
            const rows = this.getRows().map((r,i) => ({ row: r, idx: i }));
            rows.sort((a,b) => {
              const res = this.cmp(a,b);
              return this.sortDir === 'asc' ? res : -res;
            });
            rows.forEach(({row}) => body.appendChild(row));
            this.updateIcons();
          }
        }
      }
    }
    </script>
</x-app-layout>