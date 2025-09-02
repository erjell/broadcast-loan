<!-- resources/views/items/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Aset Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div x-data class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data class="flex items-center justify-between mb-4">
                <div class="text-sm text-slate-600"></div>
                <div class="flex gap-2">
                    <button type="button" @click="$dispatch('open-modal', 'add-item')" class="px-3 py-2 rounded bg-slate-800 text-white">Tambah Barang</button>
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
                            <div class="flex items-end gap-2">
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
                        <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                            <button type="button" @click="$dispatch('close-modal', 'add-item')" class="px-4 py-2 rounded border">Batal</button>
                            <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
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
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="$dispatch('close-modal', 'add-category')" class="px-4 py-2 rounded border">Batal</button>
                        <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                    </div>
                </form>
            </x-modal>
            <div class="bg-white rounded-lg shadow overflow-x-auto p-4">
                <table id="tabelBarang" class="min-w-full">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Serial</th>
                            <th>Tahun</th>
                            <th>Kondisi</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr>
                            <td>{{ $it->code }}</td>
                            <td>{{ $it->name }}</td>
                            <td>{{ $it->category->name }}</td>
                            <td>{{ $it->serial_number }}</td>
                            <td>{{ $it->procurement_year }}</td>
                            <td>{{ str_replace('_',' ',$it->condition) }}</td>

                            <td>{{ $it->details }}</td>
                            <td>
                                @if($it->activeLoanItem && $it->activeLoanItem->loan)
                                <a href="{{ route('loans.show', $it->activeLoanItem->loan) }}" class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-800 hover:bg-red-200">
                                    Dipinjam
                                </a>
                                @else
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="$dispatch('open-modal', 'cetak-{{ $it->id }}')" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cetak</button>
                                    <button type="button" @click="$dispatch('open-modal', 'edit-item-{{ $it->id }}')" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md border border-blue-300 text-blue-700 hover:bg-blue-50">Edit</button>
                                    <button type="button" @click="$dispatch('open-modal', 'delete-item-{{ $it->id }}')" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md border border-red-300 text-red-600 hover:bg-red-50">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        {{-- Modal Cetak Barcode --}}
                        <x-modal name="cetak-{{ $it->id }}" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">Cetak Barcode: <span class="text-lg font-normal">{{ $it->name }}</span></h3>
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" class="px-3 py-2 rounded border" onclick="window.open('{{ route('items.print', ['id' => $it->id, 'type' => 'code']) }}', '_blank')">Kode</button>
                                    <button type="button" class="px-3 py-2 rounded border" onclick="window.open('{{ route('items.print', ['id' => $it->id, 'type' => 'serial']) }}', '_blank')">Serial Number</button>
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
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" @click="$dispatch('close-modal', 'delete-item-{{ $it->id }}')" class="px-4 py-2 rounded border">Batal</button>
                                    <form action="{{ route('items.destroy', $it) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
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
                                        <div class="flex items-end gap-2">
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
                                        <label class="block text-sm">Kode Barang</label>
                                        <input name="code" class="w-full border rounded p-2 bg-slate-100" value="{{ $it->code }}" readonly>
                                    </div>
                                    <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                                        <button type="button" @click="$dispatch('close-modal', 'edit-item-{{ $it->id }}')" class="px-4 py-2 rounded border">Batal</button>
                                        <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
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
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- <div class="mt-3">{{ $items->links() }}</div> --}}
        </div>
    </div>
</x-app-layout>
