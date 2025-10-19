<!-- resources/views/items/find.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pencarian Barang') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <div class="flex-1">
                        <label for="searchInput" class="block text-sm font-medium text-slate-700 mb-1">Ketik pencarian</label>
                        <input id="searchInput" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400 focus:outline-none" placeholder="Kode, Serial Number, atau Nama Barang..." autocomplete="off">
                        <p class="text-xs text-slate-500 mt-1">Tekan Enter untuk mencari cepat, atau gunakan tombol Scanner.</p>
                    </div>
                    <div class="flex items-end gap-2">
                        <button id="scanToggleBtn" type="button" class="inline-flex items-center gap-2 rounded-lg bg-slate-800 text-white px-4 py-2 text-sm font-medium hover:bg-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5a1 1 0 1 1 0-2h4a1 1 0 1 1 0 2H3Zm14-2a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4ZM2 12a1 1 0 0 1 1-1h18a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1Zm1 8a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2H3Zm14 0a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4Z"/></svg>
                            <span id="scanToggleText">Buka Scanner</span>
                        </button>
                        <button id="clearBtn" type="button" class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Bersihkan</button>
                    </div>
                </div>

                <div id="scannerSection" class="mt-4 hidden">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <div id="qr-region" class="w-full"></div>
                        <div class="text-xs text-slate-500 mt-2">Berikan izin kamera untuk memindai QR/Barcode. Tutup scanner jika tidak digunakan.</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-slate-700">Hasil</h3>
                        <div class="text-xs text-slate-500" id="resultMeta"></div>
                    </div>
                    <div id="results" class="grid gap-3 sm:grid-cols-2"></div>
                    <div id="emptyState" class="hidden text-center text-slate-500 text-sm py-10">Belum ada hasil. Ketik kata kunci atau gunakan scanner.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- html5-qrcode from CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        (function() {
            const elInput = document.getElementById('searchInput');
            const elResults = document.getElementById('results');
            const elEmpty = document.getElementById('emptyState');
            const elMeta = document.getElementById('resultMeta');
            const elScanBtn = document.getElementById('scanToggleBtn');
            const elScanText = document.getElementById('scanToggleText');
            const elScannerSection = document.getElementById('scannerSection');
            const elClear = document.getElementById('clearBtn');

            const searchUrl = @js(route('items.search')); // GET ?q=
            const lookupUrl = @js(route('items.lookup')); // GET ?q=
            const loanShowPrefix = @js(url('/loans'));

            let scanner = null; // Html5QrcodeScanner instance
            let scannerOpen = false;
            let lastScanAt = 0;
            let debounceTimer = null;

            function escapeHtml(str) {
                return String(str || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderResults(items, sourceLabel = '') {
                elResults.innerHTML = '';
                if (!items || items.length === 0) {
                    elEmpty.classList.remove('hidden');
                    elMeta.textContent = '';
                    return;
                }
                elEmpty.classList.add('hidden');
                elMeta.textContent = `${items.length} item${items.length>1?'s':''}${sourceLabel? ' • ' + sourceLabel : ''}`;

                for (const it of items) {
                    const code = escapeHtml(it.code);
                    const name = escapeHtml(it.name);
                    const merk = escapeHtml(it.merk);
                    const tipe = escapeHtml(it.tipe);
                    const sn = escapeHtml(it.serial_number);
                    const cond = escapeHtml(it.condition);
                    const cat = escapeHtml(it.category);
                    const year = escapeHtml(it.procurement_year);
                    const lokasi = escapeHtml(it.lokasi);
                    const details = escapeHtml(it.details);
                    const lastNote = escapeHtml(it.last_return_notes || '');
                    const isMissing = !!it.is_missing;
                    const onLoan = !!it.on_loan;
                    const loanId = it.loan_id;

                    const condBadge = cond === 'baik'
                        ? '<span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-emerald-100 text-emerald-700">Baik</span>'
                        : (cond === 'rusak_ringan'
                            ? '<span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-amber-100 text-amber-700">Rusak Ringan</span>'
                            : '<span class="inline-flex items-center px-2 py-0.5 text-xs rounded bg-red-100 text-red-700">Rusak</span>');

                    const statusChips = [
                        isMissing ? '<span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded bg-amber-100 text-amber-700">Hilang</span>' : '',
                        (!isMissing && onLoan)
                            ? `<a href="${loanId ? (loanShowPrefix + '/' + loanId) : '#'}" class="inline-flex items-center px-2 py-0.5 text-[10px] rounded bg-red-100 text-red-700 hover:bg-red-200">Dipinjam</a>`
                            : (!isMissing ? '<span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded bg-emerald-100 text-emerald-700">Tersedia</span>' : '')
                    ].filter(Boolean).join(' ');

                    const card = document.createElement('div');
                    card.className = 'rounded-lg border border-slate-200 p-4 hover:bg-slate-50 transition-colors';
                    card.innerHTML = `
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-semibold text-slate-800">${code}</div>
                            <div class="flex items-center gap-2">${condBadge} ${statusChips}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-sm">
                            <div><span class="text-slate-500">Nama:</span> <span class="text-slate-800">${name || '-'}</span></div>
                            <div><span class="text-slate-500">Kategori:</span> <span class="text-slate-800">${cat || '-'}</span></div>
                            <div><span class="text-slate-500">Merk:</span> <span class="text-slate-800">${merk || '-'}</span></div>
                            <div><span class="text-slate-500">Tipe:</span> <span class="text-slate-800">${tipe || '-'}</span></div>
                            <div><span class="text-slate-500">Serial Number:</span> <span class="text-slate-800">${sn || '-'}</span></div>
                            <div><span class="text-slate-500">Tahun:</span> <span class="text-slate-800">${year || '-'}</span></div>
                            <div class="sm:col-span-2"><span class="text-slate-500">Lokasi:</span> <span class="text-slate-800">${lokasi || '-'}</span></div>
                            <div class="sm:col-span-2"><span class="text-slate-500">Detail:</span> <span class="text-slate-800 whitespace-pre-line">${details || '-'}</span></div>
                            ${lastNote ? `<div class="sm:col-span-2 text-slate-700"><span class='text-slate-500'>Catatan pengembalian terakhir:</span> ${lastNote}</div>` : ''}
                        </div>
                    `;
                    elResults.appendChild(card);
                }
            }

            async function doLookup(q) {
                const url = `${lookupUrl}?q=${encodeURIComponent(q)}`;
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (res.ok) {
                        const one = await res.json();
                        renderResults([one], 'pencarian tepat');
                        return true;
                    } else if (res.status === 404) {
                        return false; // fallback to fuzzy search
                    }
                } catch (e) {}
                return false;
            }

            async function doSearch(q) {
                const url = `${searchUrl}?q=${encodeURIComponent(q)}`;
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (res.ok) {
                        const items = await res.json();
                        renderResults(items, 'pencarian mirip');
                    } else {
                        renderResults([]);
                    }
                } catch (e) {
                    renderResults([]);
                }
            }

            function triggerSearchImmediate(q) {
                if (!q || q.trim() === '') {
                    renderResults([]);
                    return;
                }
                // Try exact first, then fuzzy
                doLookup(q).then(found => {
                    if (!found) doSearch(q);
                });
            }

            function triggerSearchDebounced(q) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => triggerSearchImmediate(q), 300);
            }

            function openScanner() {
                if (scannerOpen) return;
                elScannerSection.classList.remove('hidden');
                elScanText.textContent = 'Tutup Scanner';
                scannerOpen = true;

                const formatsToSupport = window.Html5QrcodeSupportedFormats
                    ? [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E,
                    ] : undefined;

                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    rememberLastUsedCamera: true,
                    aspectRatio: 1.0,
                };
                if (formatsToSupport) config.formatsToSupport = formatsToSupport;

                const onSuccess = (decodedText, decodedResult) => {
                    const now = Date.now();
                    if (now - lastScanAt < 1200) return; // throttle
                    lastScanAt = now;
                    elInput.value = decodedText;
                    triggerSearchImmediate(decodedText);
                };
                const onError = (errMsg) => { /* no-op */ };

                try {
                    scanner = new Html5QrcodeScanner('qr-region', config, /* verbose= */ false);
                    scanner.render(onSuccess, onError);
                } catch (e) {
                    // Fallback message
                    document.getElementById('qr-region').innerHTML = '<div class="text-sm text-red-600">Gagal memulai kamera. Periksa izin atau perangkat Anda.</div>';
                }
            }

            function closeScanner() {
                if (!scannerOpen) return;
                elScannerSection.classList.add('hidden');
                elScanText.textContent = 'Buka Scanner';
                scannerOpen = false;
                if (scanner && typeof scanner.clear === 'function') {
                    scanner.clear().catch(() => {});
                }
                // Clean up region DOM so it can be re-rendered
                const region = document.getElementById('qr-region');
                region.innerHTML = '';
                scanner = null;
            }

            // Events
            elInput.addEventListener('keyup', (e) => {
                if (e.key === 'Enter') {
                    triggerSearchImmediate(elInput.value);
                } else {
                    triggerSearchDebounced(elInput.value);
                }
            });

            elScanBtn.addEventListener('click', () => {
                if (scannerOpen) closeScanner(); else openScanner();
            });

            elClear.addEventListener('click', () => {
                elInput.value = '';
                renderResults([]);
                elInput.focus();
            });

            // Initial state
            renderResults([]);
        })();
    </script>
</x-app-layout>
