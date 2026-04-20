{{-- resources/views/loans/edit_items.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Barang Peminjaman') }} {{ $loan->code }}
    </h2>
  </x-slot>
  <x-slot name="breadcrumb">
    <x-breadcrumb :items="[
            ['label' => 'Peminjaman', 'url' => route('loans.index')],
            ['label' => 'Detail', 'url' => route('loans.show', $loan)],
            ['label' => 'Edit Barang']
        ]" />
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <form action="{{ route('loans.items.update', $loan) }}" method="post" x-data="editItemsForm()" x-init='init(JSON.parse($el.dataset.initial || "[]"))' data-initial='@json($initialItems)' class="grid gap-4 bg-white p-4 rounded-2xl shadow">
        @csrf

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <div class="text-xs uppercase text-slate-500">Peminjam</div>
            <div class="text-slate-800 font-medium">{{ $loan->partner->name }}</div>
          </div>
          <div>
            <div class="text-xs uppercase text-slate-500">Lokasi</div>
            <div class="text-slate-800 font-medium">{{ $loan->lokasi }}</div>
          </div>
          <div>
            <div class="text-xs uppercase text-slate-500">Tanggal Pinjam</div>
            <div class="text-slate-800 font-medium">{{ $loan->loan_date }}</div>
          </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
          <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-2">
              <h3 class="font-semibold text-slate-800">Barang dalam Transaksi</h3>
              <span class="text-xs text-slate-500">Centang untuk menghapus</span>
            </div>
            <!-- Desktop table -->
            <div class="hidden md:block overflow-x-auto border border-slate-200 rounded-xl">
              <table class="min-w-[48rem] w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                  <tr>
                    <th class="px-3 py-2 text-center">Hapus</th>
                    <th class="px-3 py-2">Kode</th>
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Serial</th>
                    <th class="px-3 py-2 text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="row in current" :key="row.loan_item_id">
                    <tr class="odd:bg-white even:bg-slate-50/60">
                      <td class="px-3 py-2 text-center">
                        <template x-if="row.returned">
                          <span class="text-xs px-2 py-1 rounded bg-slate-200 text-slate-700" title="Sudah dikembalikan">Terkunci</span>
                        </template>
                        <template x-if="!row.returned">
                          <input type="checkbox" :value="row.loan_item_id" name="remove[]" class="w-4 h-4">
                        </template>
                      </td>
                      <td class="px-3 py-2" x-text="row.code"></td>
                      <td class="px-3 py-2" x-text="row.name"></td>
                      <td class="px-3 py-2" x-text="row.serial || '-' "></td>
                      <td class="px-3 py-2 text-center">
                        <span class="text-xs px-2 py-1 rounded" :class="row.returned ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700'" x-text="row.returned ? 'Dikembalikan' : 'Dipinjam'"></span>
                      </td>
                    </tr>
                  </template>
                  <template x-if="current.length===0">
                    <tr><td colspan="5" class="px-3 py-4 text-center text-sm text-slate-500">Belum ada barang pada transaksi ini.</td></tr>
                  </template>
                </tbody>
              </table>
            </div>
            <!-- Mobile cards -->
            <div class="md:hidden space-y-2">
              <template x-for="row in current" :key="row.loan_item_id">
                <div class="border border-slate-200 rounded-xl p-3 bg-white">
                  <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-slate-800" x-text="row.name"></div>
                    <div>
                      <span class="text-[10px] px-2 py-0.5 rounded" :class="row.returned ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700'" x-text="row.returned ? 'Dikembalikan' : 'Dipinjam'"></span>
                    </div>
                  </div>
                  <div class="mt-1 text-xs text-slate-600">
                    <div><span class="text-slate-500">Kode:</span> <span class="font-mono" x-text="row.code"></span></div>
                    <div><span class="text-slate-500">Serial:</span> <span class="font-mono" x-text="row.serial || '-' "></span></div>
                  </div>
                  <div class="mt-2">
                    <template x-if="row.returned">
                      <span class="text-[10px] px-2 py-0.5 rounded bg-slate-200 text-slate-700">Terkunci</span>
                    </template>
                    <template x-if="!row.returned">
                      <label class="inline-flex items-center gap-2 text-xs text-slate-700">
                        <input type="checkbox" :value="row.loan_item_id" name="remove[]" class="w-4 h-4">
                        Hapus dari transaksi
                      </label>
                    </template>
                  </div>
                </div>
              </template>
              <template x-if="current.length===0">
                <div class="text-center text-sm text-slate-500">Belum ada barang pada transaksi ini.</div>
              </template>
            </div>
          </div>

          <div>
            <h3 class="font-semibold text-slate-800 mb-2">Tambah Barang</h3>
            <div class="grid gap-2">
              <div>
                <label class="block text-sm">Scan Barcode</label>
                <input x-ref="scan" x-model="scan" @keydown.enter.prevent="addByScan()" @keydown.tab.prevent="addByScan()" autocomplete="off" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Scan barcode / QR">
                <p class="text-xs text-slate-500 mt-1">Scanner biasanya mengirim Enter/Tab otomatis setelah scan.</p>
              </div>
              <div class="relative" @click.away="suggestions=[]">
                <label class="block text-sm">Cari Nama / Kode / Serial</label>
                <input x-model="query" autocomplete="off" class="w-full px-3 py-2 border rounded-xl" placeholder="Ketik untuk mencari">
                <div x-show="suggestions.length" class="absolute z-10 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow">
                  <template x-for="s in suggestions" :key="s.id">
                    <button type="button" @click="addItem(s)" class="w-full text-left px-3 py-2 hover:bg-slate-50">
                      <div class="font-medium" x-text="s.name"></div>
                      <div class="text-xs text-slate-500"><span class="font-mono" x-text="s.code"></span> <span x-text="s.serial_number ? '· ' + s.serial_number : ''"></span></div>
                    </button>
                  </template>
                </div>
              </div>

              <div class="mt-2">
                <h4 class="font-semibold text-slate-700">Akan Ditambahkan</h4>
                <!-- Desktop table -->
                <div class="hidden md:block overflow-x-auto border border-slate-200 rounded-xl mt-1" x-show="adds.length">
                  <table class="min-w-[36rem] w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                      <tr>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Serial</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="it in adds" :key="it.id">
                        <tr class="odd:bg-white even:bg-slate-50/60">
                          <td class="px-3 py-2" x-text="it.code"></td>
                          <td class="px-3 py-2" x-text="it.name"></td>
                          <td class="px-3 py-2" x-text="it.serial_number || '-' "></td>
                          <td class="px-3 py-2 text-center">
                            <button type="button" @click="removeAdd(it.id)" class="px-2 py-1 text-xs rounded border border-slate-300">Hapus</button>
                          </td>
                          <input type="hidden" name="add[]" :value="it.id">
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <!-- Mobile cards -->
                <div class="md:hidden space-y-2 mt-1" x-show="adds.length">
                  <template x-for="it in adds" :key="it.id">
                    <div class="border border-slate-200 rounded-xl p-3 bg-white">
                      <div class="text-sm font-medium text-slate-800" x-text="it.name"></div>
                      <div class="mt-1 text-xs text-slate-600">
                        <div><span class="text-slate-500">Kode:</span> <span class="font-mono" x-text="it.code"></span></div>
                        <div><span class="text-slate-500">Serial:</span> <span class="font-mono" x-text="it.serial_number || '-' "></span></div>
                      </div>
                      <div class="mt-2">
                        <button type="button" @click="removeAdd(it.id)" class="px-2 py-1 text-xs rounded border border-slate-300">Hapus</button>
                        <input type="hidden" name="add[]" :value="it.id">
                      </div>
                    </div>
                  </template>
                </div>
                <template x-if="!adds.length">
                  <div class="text-xs text-slate-500">Tidak ada item baru.</div>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2">
          <a href="{{ route('loans.show', $loan) }}" class="inline-flex w-full sm:w-auto justify-center px-4 py-2 rounded border border-slate-300 text-slate-700 bg-white hover:bg-slate-50">Batal</a>
          <button type="submit" class="inline-flex w-full sm:w-auto justify-center px-4 py-2 rounded bg-slate-800 text-white">Simpan Perubahan</button>
        </div>
      </form>

      <div class="mt-4 p-3 text-xs text-slate-500 bg-slate-50 rounded-lg border border-slate-200">
        Batasan: Barang yang sudah dikembalikan tidak dapat dihapus dari transaksi.
      </div>
    </div>
  </div>

  <script>
    function editItemsForm(){
      return {
        current: [], // {loan_item_id, item_id, code, name, serial, returned}
        adds: [],    // items to add (from items.lookup/search)
        suggestions: [],
        query: '',
        scan: '',
        init(initial){
          this.current = Array.isArray(initial) ? initial : [];
          this.$watch('query', (q) => this.fetchSuggestions(q));
          this.$nextTick(()=>{ this.$refs?.scan?.focus?.(); });
        },
        async fetchSuggestions(q){
          q = (q||'').trim();
          if (!q) { this.suggestions = []; return; }
          try {
            const res = await fetch(`{{ route('items.search') }}?q=${encodeURIComponent(q)}`);
            this.suggestions = await res.json();
          } catch(_) {}
        },
        async addByScan(){
          const code = (this.scan||'').trim();
          if (!code) return;
          try {
            const res = await fetch(`{{ route('items.lookup') }}?q=${encodeURIComponent(code)}`);
            if (res.ok){
              const it = await res.json();
              this.addItem(it);
              this.scan='';
            }
          } catch(_) {}
        },
        addItem(it){
          if (!it || !it.id) return;
          // Block duplicates: already in current loan or queued adds
          if (this.current.find(r => r.item_id === it.id)) { return; }
          if (this.adds.find(r => r.id === it.id)) { return; }
          this.adds.push(it);
          this.suggestions = [];
          this.query = '';
        },
        removeAdd(id){ this.adds = this.adds.filter(x => x.id !== id); }
      }
    }
  </script>
</x-app-layout>
