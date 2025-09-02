<!-- resources/views/categories/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('categories.store') }}" method="post" class="flex gap-2 bg-white p-4 rounded-2xl shadow mb-4">
                @csrf
                <input name="name" class="flex-1 border rounded p-2" placeholder="Nama kategori" required>
                <input name="code_category" class="flex-1 border rounded p-2" placeholder="Kode Kategori" required>
                <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
            </form>
            <div class="bg-white rounded-2xl shadow overflow-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 text-left">Nama</th>
                            <th class="p-2 text-left">Kode</th>
                            <th class="p-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $c)
                        <tr class="border-t">
                            <td class="p-2">{{ $c->name }}</td>
                            <td class="p-2">{{ $c->code_category }}</td>
                            <td class="p-2">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="$dispatch('open-modal', 'edit-category-{{ $c->id }}')" class="px-2 py-1 text-xs rounded border">Edit</button>
                                    <form action="{{ route('categories.destroy', $c) }}" method="post" onsubmit="return confirm('Yakin hapus kategori ini? Barang pada kategori ini ikut terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 text-xs rounded border border-red-300 text-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <x-modal name="edit-category-{{ $c->id }}" :show="false" maxWidth="md">
                            <div class="px-6 pt-6 pb-2 border-b">
                                <h3 class="text-lg font-semibold">Edit Kategori</h3>
                            </div>
                            <form action="{{ route('categories.update', $c) }}" method="post" class="p-6 flex flex-col gap-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-sm">Nama Kategori</label>
                                    <input name="name" class="w-full border rounded p-2" value="{{ $c->name }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm">Kode Kategori</label>
                                    <input name="code_category" class="w-full border rounded p-2" value="{{ $c->code_category }}" required>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="$dispatch('close-modal', 'edit-category-{{ $c->id }}')" class="px-4 py-2 rounded border">Batal</button>
                                    <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan</button>
                                </div>
                            </form>
                        </x-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $categories->links() }}</div>
        </div>
    </div>
</x-app-layout>
