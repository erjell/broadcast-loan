<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                                <path d="M6.75 3a.75.75 0 01.75.75V5h9V3.75a.75.75 0 011.5 0V5h.75A2.25 2.25 0 0121 7.25v10.5A2.25 2.25 0 0118.75 20H5.25A2.25 2.25 0 013 17.75V7.25A2.25 2.25 0 015.25 5H6V3.75A.75.75 0 016.75 3zM4.5 9v8.75c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75V9H4.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Barang yang Belum Dikembalikan -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Total Barang yang Belum Dikembalikan</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalUnreturned ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg p-2 bg-amber-50 text-amber-600">
                            <!-- Clock Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.75 5.25a.75.75 0 10-1.5 0v5.19l3.22 1.932a.75.75 0 10.76-1.296l-2.48-1.49V7.5z" clip-rule="evenodd"/>
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
                                <path d="M11.7 1.514a.75.75 0 01.6 0l8.25 3.75a.75.75 0 010 1.372l-8.25 3.75a.75.75 0 01-.6 0L3.45 6.636a.75.75 0 010-1.372l8.25-3.75z"/>
                                <path d="M2.25 9.114a.75.75 0 011.05-.342L12 13.5l8.7-4.728a.75.75 0 11.708 1.318l-8.25 4.485v7.425a.75.75 0 01-1.5 0v-7.425L2.592 9.99a.75.75 0 01-.342-.876z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
