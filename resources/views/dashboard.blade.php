<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toolbar: Tampilkan Barcode -->
            <div class="mb-6">
                <x-primary-button x-data @click="$dispatch('open-modal', 'barcode')">
                    Feedback & Saran
                </x-primary-button>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Transaksi Peminjaman -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Total Transaksi Peminjaman</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalLoans ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-indigo-50 text-indigo-600">
                            <!-- Calendar Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path d="M6.75 3a.75.75 0 01.75.75V5h9V3.75a.75.75 0 011.5 0V5h.75A2.25 2.25 0 0121 7.25v10.5A2.25 2.25 0 0118.75 20H5.25A2.25 2.25 0 013 17.75V7.25A2.25 2.25 0 015.25 5H6V3.75A.75.75 0 016.75 3zM4.5 9v8.75c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75V9H4.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Transaksi Aktif -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Transaksi Aktif</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalActiveLoans ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-sky-50 text-sky-600">
                            <!-- Play Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path d="M4.5 5.653c0-1.46 1.584-2.366 2.847-1.606l10.163 6.097c1.275.766 1.275 2.446 0 3.212L7.347 19.453C6.084 20.213 4.5 19.307 4.5 17.847V5.653z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Selesai -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Transaksi Selesai</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalCompletedLoans ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-green-50 text-green-600">
                            <!-- Check Badge Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.603 2.25c-.866 0-1.65.498-2.028 1.276l-.768 1.54-1.7.247c-.93.135-1.672.877-1.807 1.807l-.247 1.7-1.54.768A2.25 2.25 0 00.75 12c0 .866.498 1.65 1.276 2.028l1.54.768.247 1.7c.135.93.877 1.672 1.807 1.807l1.7.247.768 1.54a2.25 2.25 0 002.028 1.276c.866 0 1.65-.498 2.028-1.276l.768-1.54 1.7-.247c.93-.135 1.672-.877 1.807-1.807l.247-1.7 1.54-.768A2.25 2.25 0 0023.25 12c0-.866-.498-1.65-1.276-2.028l-1.54-.768-.247-1.7a2.25 2.25 0 00-1.807-1.807l-1.7-.247-.768-1.54A2.25 2.25 0 0015.397 2.25h-6.794zm6.72 7.28a.75.75 0 10-1.146-.96l-4.032 4.81-1.614-1.614a.75.75 0 10-1.06 1.06l2.25 2.25a.75.75 0 001.1-.04l4.502-5.506z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Total Barang yang Belum Dikembalikan -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Barang Belum Dikembalikan</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalUnreturned ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-amber-50 text-amber-600">
                            <!-- Clock Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.75 5.25a.75.75 0 10-1.5 0v5.19l3.22 1.932a.75.75 0 10.76-1.296l-2.48-1.49V7.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Total Barang Rusak -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Barang Rusak</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalDamagedItems ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-rose-50 text-rose-600">
                            <!-- Exclamation Triangle Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-.772 1.572-.772 2.02 0l8.68 14.97c.447.772-.112 1.74-1.01 1.74H3.118c-.898 0-1.457-.968-1.01-1.74l8.68-14.97zM12 8.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100 1.5.75.75 0 000-1.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Total Barang -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Total Barang</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalItems ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-emerald-50 text-emerald-600">
                            <!-- Cube Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path d="M11.7 1.514a.75.75 0 01.6 0l8.25 3.75a.75.75 0 010 1.372l-8.25 3.75a.75.75 0 01-.6 0L3.45 6.636a.75.75 0 010-1.372l8.25-3.75z" />
                                <path d="M2.25 9.114a.75.75 0 011.05-.342L12 13.5l8.7-4.728a.75.75 0 11.708 1.318l-8.25 4.485v7.425a.75.75 0 01-1.5 0v-7.425L2.592 9.99a.75.75 0 01-.342-.876z" />
                            </svg>
                        </div>
                    </div>
                </div>




            </div>

            <!-- Top 5 Barang Paling Sering Dipinjam -->
            <div class="mt-8">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Top 5 Barang Paling Sering Dipinjam</div>
                        <div class="rounded-lg p-2 bg-amber-50 text-amber-600">
                            <!-- Trophy Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path d="M16.5 3.75a.75.75 0 01.75.75h1.5A2.25 2.25 0 0121 6.75v.75a4.5 4.5 0 01-4.5 4.5h-.621a6.004 6.004 0 01-4.629 3.75V18h3.75a.75.75 0 010 1.5H9a.75.75 0 010-1.5h3.75v-2.25a6.004 6.004 0 01-4.629-3.75H7.5A4.5 4.5 0 013 7.5V6.75A2.25 2.25 0 015.25 4.5h1.5a.75.75 0 01.75-.75h9zm1.5 3h1.5v.75a3 3 0 01-3 3h-.394a6.034 6.034 0 00.356-2.25V6.75zm-12 0V8.25c0 .78.13 1.53.356 2.25H6.75a3 3 0 01-3-3V6.75h1.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse($topItems as $rank => $row)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold">{{ $rank + 1 }}</span>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ optional($row->item)->name ?? 'Item #' . $row->item_id }}</div>
                                    @if(optional($row->item)->code)
                                    <div class="text-xs text-gray-500">Kode: {{ $row->item->code }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-800">{{ $row->total }}x</div>
                        </div>
                        @empty
                        <div class="py-6 text-sm text-gray-500">Belum ada data peminjaman.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top 20 Barang Paling Sering Rusak (paginate 5 per halaman) -->
            <div class="mt-8">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Top 20 Barang Paling Sering Rusak</div>
                        <div class="rounded-lg p-2 bg-rose-50 text-rose-600">
                            <!-- Exclamation Triangle Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-.772 1.572-.772 2.02 0l8.68 14.97c.447.772-.112 1.74-1.01 1.74H3.118c-.898 0-1.457-.968-1.01-1.74l8.68-14.97zM12 8.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100 1.5.75.75 0 000-1.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 divide-y divide-gray-100">
                        @php($offset = ($topDamaged->currentPage() - 1) * $topDamaged->perPage())
                        @forelse($topDamaged as $index => $row)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold">{{ $offset + $index + 1 }}</span>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ optional($row->item)->name ?? ('Item #' . $row->item_id) }}</div>
                                    @if(optional($row->item)->code)
                                    <div class="text-xs text-gray-500">Kode: {{ $row->item->code }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-800">{{ $row->total_damages }}x</div>
                        </div>
                        @empty
                        <div class="py-6 text-sm text-gray-500">Belum ada data kerusakan.</div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $topDamaged->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Barcode Image -->
    <x-modal name="barcode" :show="false" maxWidth="sm">
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Feedback dan Saran</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl leading-none" x-on:click="$dispatch('close-modal', 'barcode')">&times;</button>
        </div>
        <div class="flex justify-center">
            <img src="{{ Vite::asset('resources/images/qrcode.png') }}" alt="qrcode" class="max-h-96 w-auto">
        </div>
    </x-modal>
</x-app-layout>