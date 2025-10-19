<!-- resources/views/loans/create.blade.php -->
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Buat Peminjaman') }}
    </h2>
  </x-slot>
  <x-slot name="breadcrumb">
    <x-breadcrumb :items="[
            ['label' => 'Peminjaman', 'url' => route('loans.index')],
            ['label' => 'Buat']
        ]" />
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <form action="{{ route('loans.store') }}" method="post" x-data="loanForm()" x-init="init()" class="grid gap-4 bg-white p-4 rounded-2xl shadow">
        @csrf

        <div class="grid md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm">Nama Peminjam</label>
            <input name="partner_name" class="w-full border rounded p-2" placeholder="Nama Peminjam" value="{{ old('partner_name') }}" required>

          </div>
          <div>
            <label class="block text-sm">Nomor Telepon</label>
            <input type="tel" name="partner_phone" class="w-full border rounded p-2" placeholder="Nomor Telepon" value="{{ old('partner_phone') }}">
          </div>
          <div>
            <label class="block text-sm">Unit (Satuan Kerja)</label>
            <input name="partner_unit" class="w-full border rounded p-2" placeholder="Misal: Program, Pemberitaan, Produksi" value="{{ old('partner_unit') }}">
          </div>
          <div>
            <label class="block text-sm">Lokasi</label>
            <input name="lokasi" class="w-full border rounded p-2" placeholder="Lokasi barang dipinjam" required>
          </div>
          <div>
            <label class="block text-sm">Tanggal Pinjam</label>
            <input type="datetime-local" name="loan_date" class="w-full border rounded p-2" value="{{ now()->format('Y-m-d\TH:i') }}" required>
          </div>
          <div>
            <label class="block text-sm">Petugas</label>
            <input value="{{ auth()->user()->name }}" class="w-full border rounded p-2" disabled>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm">Scan Barcode</label>
            <div class="relative mt-1">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <!-- bar-bar sederhana -->
                  <rect x="3" y="6" width="1.5" height="12" rx=".25" />
                  <rect x="6" y="6" width="0.75" height="12" rx=".25" />
                  <rect x="8" y="6" width="1.25" height="12" rx=".25" />
                  <rect x="11" y="6" width="0.75" height="12" rx=".25" />
                  <rect x="13" y="6" width="1.5" height="12" rx=".25" />
                  <rect x="16" y="6" width="0.75" height="12" rx=".25" />
                  <rect x="18" y="6" width="1.25" height="12" rx=".25" />
                </svg>
              </span>
              <input x-ref="scan" x-model="scan" @keydown.enter.prevent="addByScan()" @keydown.tab.prevent="addByScan()" autocomplete="off" class="w-full pl-10 pr-12 md:pr-28 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Arahkan scanner ke sini dan scan barcode">
              <!-- Trigger manual camera scanner (minimal, high-contrast) -->
              <button type="button" @click="$dispatch('open-modal', 'qr-scanner')" title="Scan dengan kamera" aria-label="Scan dengan kamera" class="absolute inset-y-0 right-2 my-1 z-10 inline-flex items-center justify-center md:justify-start md:gap-1.5 w-9 h-9 md:w-auto md:h-auto p-0 md:px-2.5 md:py-1.5 rounded-full md:rounded-lg bg-sky-600 text-white text-xs font-medium shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-sky-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 7h2m14 0h2M5 7v10m14-10v10M8 7h8m-8 10h8" />
                </svg>
                <span class="hidden md:inline">Kamera Scan</span>
              </button>
            </div>
            <p class="text-xs text-slate-500 mt-1">Scanner biasanya mengirim Enter/Tab otomatis setelah scan.</p>
          </div>
          <div>
            <label class="block text-sm">Cari Nama / Kode / Serial</label>
            <div class="relative mt-1" @click.away="suggestions=[]">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1016.65 16.65z" />
                </svg>
              </span>
              <input x-ref="search" x-model.debounce.300ms="query" @keydown.enter.prevent="addByQuery()" @keydown.tab.prevent="addByQuery()" class="w-full pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Ketik untuk mencari nama / kode / serial...">
              <button type="button" @click="addByQuery()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">

              </button>
              <ul class="absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-auto z-10" x-show="suggestions.length">
                <template x-for="s in suggestions" :key="s.id">
                  <li>
                    <button type="button" @click="addItem(s)" class="w-full px-4 py-2 text-left hover:bg-slate-50">
                      <div class="font-medium text-slate-700" x-text="s.name"></div>
                      <div class="text-xs text-slate-500">
                        <span x-text="s.code"></span>
                        <template x-if="s.serial_number">
                          <span x-text="` • SN: ${s.serial_number}`"></span>
                        </template>
                      </div>

                    </button>
                  </li>
                </template>
              </ul>

            </div>
            <p class="text-xs text-slate-500 mt-1">Tekan Enter untuk menambahkan pilihan teratas.</p>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table data-sortable class="min-w-[60rem] w-full text-sm text-slate-700" x-data="tableSorterV2()" x-init="init($el)">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="text-left font-semibold px-4 py-3">Kode</th>
                <th class="text-left font-semibold px-4 py-3">Nama</th>
                <th class="text-left font-semibold px-4 py-3">Serial Number</th>
                <th class="text-center font-semibold px-4 py-3">Kondisi</th>
                <th class="font-semibold px-4 py-3">Catatan Kondisi</th>
                <th class="px-4 py-3" data-nosort></th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(row,idx) in items" :key="row.id">
                <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition-colors">
                  <td class="px-4 py-3" x-text="row.code"></td>
                  <td class="px-4 py-3" x-text="row.name"></td>
                  <td class="px-4 py-3" x-text="row.serial_number ?? '-'"></td>
                  <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 text-xs rounded-full" :class="row.condition==='rusak_berat' ? 'bg-red-100 text-red-700' : (row.condition==='rusak_ringan' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700')" x-text="row.condition.replace('_',' ')"></span>
                    <input type="hidden" :name="`items[${idx}][id]`" :value="row.id">
                    <input type="hidden" :name="`items[${idx}][qty]`" :value="row.qty">
                  </td>
                  <td class="px-4 py-3 whitespace-pre-line text-slate-600" x-text="row.last_return_notes ? row.last_return_notes : '-'"></td>
                  <td class="px-4 py-3 text-center">
                    <button type="button" class="px-2 py-1 rounded bg-rose-100 text-rose-700" @click="remove(idx)">Hapus</button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <div class="text-right">
          <button class="px-4 py-2 rounded bg-slate-800 text-white">Simpan Peminjaman</button>
        </div>

        <!-- Modal: Duplikasi Barang (dalam scope form/x-data) -->
        <x-modal name="duplicate-item" :show="false" maxWidth="md">
          <div class="p-6">
            <h3 class="text-lg font-semibold mb-2 text-red-700">Barang sudah ada di daftar</h3>
            <p class="text-sm text-slate-700">Barang yang Anda coba tambahkan sudah ada pada daftar peminjaman.</p>
            <div class="mt-3 p-3 rounded bg-red-50 border border-red-200 text-sm">
              <div><span class="text-slate-500">Nama:</span> <span class="font-medium" id="dup-name">-</span></div>
              <div><span class="text-slate-500">Kode:</span> <span class="font-mono" id="dup-code">-</span></div>
              <div id="dup-serial-row" style="display:none;"><span class="text-slate-500">Serial:</span> <span class="font-mono" id="dup-serial"></span></div>
            </div>
            <div class="flex flex-col-reverse gap-2 mt-6 sm:flex-row sm:justify-end">
              <button type="button" @click="$dispatch('close-modal', 'duplicate-item')" class="px-4 py-2 rounded bg-slate-800 text-white">OK</button>
            </div>
          </div>
        </x-modal>

        <!-- Modal: QR Scanner -->
        <x-modal name="qr-scanner" :show="false" maxWidth="xl">
          <div class="p-4">
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-lg font-semibold">Scan dengan Kamera</h3>
              <button type="button" class="text-slate-500 hover:text-slate-700" @click="$dispatch('close-modal','qr-scanner')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="space-y-3">
              <div id="qr-reader" class="w-full rounded-lg overflow-hidden bg-black/5"></div>
              <div class="flex items-center justify-between">
                <div class="text-xs text-slate-500">Arahkan kamera ke QR/Barcode barang.</div>
                <div class="flex gap-2">
                  <button type="button" class="px-3 py-2 rounded bg-slate-100 hover:bg-slate-200 text-slate-700" @click="restartQr()">Reload</button>
                  <button type="button" class="px-3 py-2 rounded bg-slate-800 text-white" @click="stopQr(); $dispatch('close-modal','qr-scanner')">Tutup</button>
                </div>
              </div>
            </div>
          </div>
        </x-modal>
      </form>

    </div>
  </div>

  <script>
    // html5-qrcode loader
        (function ensureHtml5Qrcode(){
          if (window.Html5Qrcode) return;
          var s=document.createElement('script');
          s.src='https://unpkg.com/html5-qrcode';
          s.async=true;
          document.head.appendChild(s);
        })();

        function loanForm(){
  return {
    query: '', scan: '', items: [], suggestions: [], lastDuplicate: null,
    qr: null, qrStarted: false, qrDeviceId: null, qrOnce: false,
    init(){
      this.$watch('query', q => this.fetchSuggestions(q));
      // Autofocus to scanner input for quick scanning
      this.$nextTick(() => { this.$refs.scan && this.$refs.scan.focus(); });
      // Handle open/close modal events to start/stop camera
      window.addEventListener('open-modal', (e)=>{ if(e.detail==='qr-scanner'){ this.startQr(); } });
      window.addEventListener('close-modal', (e)=>{ if(e.detail==='qr-scanner'){ this.stopQr(); } });
    },
    async fetchSuggestions(q){
      q = q ? q.trim() : '';
      if(!q){ this.suggestions = []; return; }
      const res = await fetch(`{{ route('items.search') }}?q=${encodeURIComponent(q)}`);
      this.suggestions = await res.json();
    },
    async processScanned(code){
      code = (code||'').trim();
      if(!code) return;
      try {
        const res = await fetch(`{{ route('items.lookup') }}?q=${encodeURIComponent(code)}`);
        if(res.ok){
          const it = await res.json();
          this.addItem(it);
          // After successful add, stop and close
          this.stopQr();
          this.$dispatch('close-modal','qr-scanner');
        }
      } catch(e) {
        // ignore
      }
    },
    async startQr(){
      try {
        // Wait for library
        let tries=0; while(!window.Html5Qrcode && tries++<50){ await new Promise(r=>setTimeout(r,100)); }
        if(!window.Html5Qrcode) return;
        if(this.qrStarted){ return; }
        this.qrOnce = false;
        // Pick back camera if available
        const cams = await Html5Qrcode.getCameras();
        if (!cams || !cams.length) return;
        const back = cams.find(c=>/back|rear|environment/i.test(c.label)) || cams[0];
        this.qrDeviceId = back.id;
        if(!this.qr){ this.qr = new Html5Qrcode('qr-reader', { formatsToSupport: [
          Html5QrcodeSupportedFormats.QR_CODE,
          Html5QrcodeSupportedFormats.CODE_128,
          Html5QrcodeSupportedFormats.CODE_39,
          Html5QrcodeSupportedFormats.EAN_13
        ]}); }
        await this.qr.start({ deviceId: { exact: this.qrDeviceId } }, { fps: 10, qrbox: 250, aspectRatio: 1.333, experimentalFeatures: { useBarCodeDetectorIfSupported: true } }, (decodedText)=>{
          if (this.qrOnce) return;
          this.qrOnce = true;
          this.processScanned(decodedText);
        }, (err)=>{ /* no-op */ });
        this.qrStarted = true;
      } catch(e){
        // fallback try facingMode
        try {
          if(!this.qr){ this.qr = new Html5Qrcode('qr-reader', { formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.EAN_13
          ]}); }
          await this.qr.start({ facingMode: 'environment' }, { fps: 10, qrbox: 250, aspectRatio: 1.333, experimentalFeatures: { useBarCodeDetectorIfSupported: true } }, (decodedText)=>{
            if (this.qrOnce) return;
            this.qrOnce = true;
            this.processScanned(decodedText);
          });
          this.qrStarted = true;
        } catch(_){}
      }
    },
    async stopQr(){
      try {
        if(this.qr && this.qrStarted){ await this.qr.stop(); }
      } catch(e){}
      try { if(this.qr){ this.qr.clear(); } } catch(e){}
      this.qrStarted=false;
      this.qrOnce=false;
    },
    async restartQr(){ await this.stopQr(); await this.startQr(); },
    addItem(it){
      const exist = this.items.find(x=>x.id===it.id);
      if(exist){
        // Isi konten modal duplikat tanpa mengandalkan scope Alpine
        const nameEl = document.getElementById('dup-name');
        const codeEl = document.getElementById('dup-code');
        const serialRow = document.getElementById('dup-serial-row');
        const serialEl = document.getElementById('dup-serial');
        if(nameEl) nameEl.textContent = exist.name || '-';
        if(codeEl) codeEl.textContent = exist.code || '-';
        if(exist.serial_number){
          if(serialEl) serialEl.textContent = exist.serial_number;
          if(serialRow) serialRow.style.display = '';
        } else {
          if(serialRow) serialRow.style.display = 'none';
        }
        this.$dispatch('open-modal', 'duplicate-item');
        return;
      }
      this.items.push({...it, qty: 1, last_return_notes: it.last_return_notes || ""});
      this.query=''; this.suggestions=[];
    },
    async addByScan(){
      const code = (this.scan || '').trim();
      if(!code) return;
      try {
        const res = await fetch(`{{ route('items.lookup') }}?q=${encodeURIComponent(code)}`);
        if(res.ok){
          const it = await res.json();
          this.addItem(it);
        }
      } finally {
        this.scan='';
        this.$nextTick(() => { this.$refs.scan && this.$refs.scan.focus(); });
      }
    },
    async addByQuery(){
      const q = (this.query || '').trim();
      if(!q){ return; }
      // Try exact, fast lookup first (best for barcode scans)
      try {
        const res = await fetch(`{{ route('items.lookup') }}?q=${encodeURIComponent(q)}`);
        if(res.ok){
          const it = await res.json();
          this.addItem(it);
          return;
        }
      } catch(e) {}
      // Fallback: take the first suggestion if available
      if(this.suggestions.length){ this.addItem(this.suggestions[0]); }
    },
    remove(i){ this.items.splice(i,1); },
    openDamagedInfo(){
      this.showModal(`Barang dengan status <b>rusak</b> tetap bisa dipinjam berdasarkan kebijakan gudang.
      Catat pada kolom catatan transaksi.`);

    },
    showModal(html){
      document.getElementById('modal-body').innerHTML = html;
      document.querySelector('[x-data]').__x.$data.modal = true;
    }
  }
}
  </script>
</x-app-layout>