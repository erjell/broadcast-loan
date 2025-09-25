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
                        <label class="block text-sm">Keperluan / Lokasi</label>
                        <input name="purpose" class="w-full border rounded p-2" placeholder="Liputan, Talkshow, dsb" required>
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
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h2m14 0h2M5 7v10m14-10v10M8 7h8m-8 10h8" />
                                </svg>
                            </span>
                            <input x-ref="scan" x-model="scan" @keydown.enter.prevent="addByScan()" @keydown.tab.prevent="addByScan()" autocomplete="off" class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Arahkan scanner ke sini dan scan barcode">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" d="M2 5h1v14H2m3-14h1v14H5m3-14h2v14H8m5-14h1v14h-1m3-14h2v14h-2m3-14h1v14h-1" />
                                </svg>
                            </span>
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
                    <table class="min-w-[60rem] w-full text-sm border rounded">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="p-2 text-left">Kode</th>
                                <th class="p-2 text-left">Nama</th>
                                <th class="p-2 text-left">Serial Number</th>
                                <th class="p-2 text-center">Kondisi</th>
                                <th class="p-2">Catatan Kondisi</th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row,idx) in items" :key="row.id">
                                <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                                    <td class="p-2" x-text="row.code"></td>
                                    <td class="p-2" x-text="row.name"></td>
                                    <td class="p-2" x-text="row.serial_number ?? '-'"></td>
                                    <td class="p-2 text-center">
                                        <span class="px-2 py-0.5 text-xs rounded-full" :class="row.condition==='rusak_berat' ? 'bg-red-100 text-red-700' : (row.condition==='rusak_ringan' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700')" x-text="row.condition.replace('_',' ')"></span>
                                        <input type="hidden" :name="`items[${idx}][id]`" :value="row.id">
                                        <input type="hidden" :name="`items[${idx}][qty]`" :value="row.qty">
                                    </td>
                                    <td class="p-2 whitespace-pre-line text-slate-600" x-text="row.last_return_notes ? row.last_return_notes : '-'"></td>
                                    <td class="p-2 text-center">
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
            </form>

        </div>
    </div>

    <script>
        function loanForm(){
  return {
    query: '', scan: '', items: [], suggestions: [], lastDuplicate: null,
    init(){
      this.$watch('query', q => this.fetchSuggestions(q));
      // Autofocus to scanner input for quick scanning
      this.$nextTick(() => { this.$refs.scan && this.$refs.scan.focus(); });
    },
    async fetchSuggestions(q){
      q = q ? q.trim() : '';
      if(!q){ this.suggestions = []; return; }
      const res = await fetch(`{{ route('items.search') }}?q=${encodeURIComponent(q)}`);
      this.suggestions = await res.json();
    },
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
