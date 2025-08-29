<!-- resources/views/items/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Aset Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('items.store') }}" method="post" class="grid md:grid-cols-2 gap-4 bg-white p-4 rounded-2xl shadow mb-4">
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
                    <label class="block text-sm">Kategori</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm">Kode Barang</label>
                    <input name="code" class="w-full border rounded p-2 bg-slate-100" readonly>
                </div>
                <div class="md:col-span-2 text-right">
                    <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                </div>
            </form>
            <div class="bg-white rounded-2xl shadow overflow-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 text-left">Kode</th>
                            <th class="p-2 text-left">Nama</th>
                            <th class="p-2 text-left">Kategori</th>
                            <th class="p-2 text-left">Serial</th>
                            <th class="p-2 text-left">Tahun</th>
                            <th class="p-2 text-left">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr class="border-t">
                            <td class="p-2">{{ $it->code }}</td>
                            <td class="p-2">{{ $it->name }}</td>
                            <td class="p-2">{{ $it->category->name }}</td>
                            <td class="p-2">{{ $it->serial_number }}</td>
                            <td class="p-2">{{ $it->procurement_year }}</td>
                            <td class="p-2">{{ str_replace('_',' ',$it->condition) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $items->links() }}</div>
        </div>
    </div>

    <script>
        document.querySelector('select[name="category_id"]').addEventListener('change', async function () {
            const catId = this.value;
            const codeInput = document.querySelector('input[name="code"]');
            if (!catId) {
                codeInput.value = '';
                return;
            }
            const res = await fetch(`{{ route('items.code') }}?category_id=${catId}`);
            const data = await res.json();
            codeInput.value = data.code;
        });
    </script>
</x-app-layout>
