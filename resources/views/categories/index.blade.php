<!-- resources/views/categories/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)]">
                <div class="bg-white/95 supports-[backdrop-filter]:bg-white/80 backdrop-blur border border-slate-200 rounded-3xl shadow-sm">
                    <div class="px-6 pt-6 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-800">Tambah Kategori</h3>
                        <p class="text-sm text-slate-500 mt-1">Beri nama dan kode unik untuk mengelompokkan barang.</p>
                    </div>
                    <form action="{{ route('categories.store') }}" method="post" class="px-6 pb-6 pt-4 space-y-4">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-600 uppercase tracking-wide">Nama Kategori</label>
                            <input name="name" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Misal: Kamera" required>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-slate-600 uppercase tracking-wide">Kode Kategori</label>
                            <input name="code_category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm uppercase tracking-widest focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: CAM" required>
                        </div>
                        <div class="flex justify-end">
                            <button class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800">
                                <span>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white/95 supports-[backdrop-filter]:bg-white/80 backdrop-blur border border-slate-200 rounded-3xl shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <form action="{{ route('categories.index') }}" method="get" class="flex w-full flex-col gap-3 sm:flex-row sm:items-center">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-3 inline-flex items-center text-slate-400">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.5 14.5 18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <input type="text" name="q" value="{{ $q }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 pl-9 text-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Cari nama atau kode kategori">
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cari</button>
                                @if($q)
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-transparent px-3 py-2 text-sm text-slate-500 hover:text-slate-700">Reset</a>
                                @endif
                            </div>
                        </form>
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $categories->total() }} Kategori</span>
                    </div>

                    <div class="overflow-hidden">
                        <table data-sortable id="tabelKategori" class="min-w-full text-sm text-slate-700" x-data="tableSorterV2()" x-init="init($el)">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Kode</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" data-nosort>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($categories as $c)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-800">{{ $c->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $c->created_at?->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $c->code_category }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button type="button" @click="$dispatch('open-modal', 'edit-category-{{ $c->id }}')" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Edit</button>
                                            <form action="{{ route('categories.destroy', $c) }}" method="post" onsubmit="return confirm('Yakin hapus kategori ini? Barang pada kategori ini ikut terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                                                <button class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <x-modal name="edit-category-{{ $c->id }}" :show="false" maxWidth="md">
                                    <div class="px-6 pt-6 pb-2 border-b border-slate-100">
                                        <h3 class="text-lg font-semibold text-slate-800">Edit Kategori</h3>
                                    </div>
                                    <form action="{{ route('categories.update', $c) }}" method="post" class="p-6 space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-medium text-slate-600 uppercase tracking-wide">Nama Kategori</label>
                                            <input name="name" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500" value="{{ $c->name }}" required>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-medium text-slate-600 uppercase tracking-wide">Kode Kategori</label>
                                            <input name="code_category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm uppercase tracking-widest focus:border-slate-500 focus:ring-slate-500" value="{{ $c->code_category }}" required>
                                        </div>
                                        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                            <button type="button" @click="$dispatch('close-modal', 'edit-category-{{ $c->id }}')" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                                            <button class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Simpan</button>
                                        </div>
                                    </form>
                                </x-modal>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-sm text-slate-500">Tidak ada kategori yang cocok.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
