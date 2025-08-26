<!-- resources/views/loans/create.blade.php -->
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Buat Peminjaman</h1>

<form action="{{ route('loans.store') }}" method="post" x-data="loanForm()" x-init="init()" class="grid gap-4 bg-white p-4 rounded-2xl shadow">
    @csrf

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm">Partner</label>
            <select name="partner_id" class="w-full border rounded p-2" required>
                <option value="">-- Pilih --</option>
                @foreach($partners as $p)
                <option value="{{ $p->id }}">{{ $p->name }} @if($p->unit) ({{ $p->unit }}) @endif</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm">Keperluan</label>
            <input name="purpose" class="w-full border rounded p-2" placeholder="Liputan, Talkshow, dsb" required>
        </div>
        <div>
            <label class="block text-sm">Tanggal Pinjam</label>
            <input type="datetime-local" name="loan_date" class="w-full border rounded p-2" value="{{ now()->format('Y-m-d\TH:i') }}" required>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">Scan Kode / Cari Nama</label>
            <input x-model="query" @keydown.enter.prevent="addByQuery()" class="w-full border rounded p-2" placeholder="Scan code atau ketik nama barang...">
            <div class="mt-2 bg-slate-50 border rounded max-h-48 overflow-auto" x-show="suggestions.length">
                <template x-for="s in suggestions" :key="s.id">
                    <button type="button" @click="addItem(s)" class="w-full text-left px-3 py-2 hover:bg-white flex justify-between">
                        <span x-text="`${s.code} — ${s.name}`"></span>
                        <span class="text-xs" x-text="`Stok: ${s.stock}`"></span>
                    </button>
                </template>
            </div>
            <p class="text-xs text-slate-500 mt-1">Tekan Enter untuk menambahkan berdasarkan input.</p>
        </div>
        <div class="self-end">
            <button type="button" @click="openDamagedInfo()" class="px-3 py-2 rounded bg-amber-100 text-amber-700 border border-amber-300">
                Info: Penanganan barang rusak
            </button>
        </div>
    </div>

    <div class="overflow-auto">
        <table class="w-full text-sm border rounded">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-2 text-left">Code</th>
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-center">Jumlah</th>
                    <th class="p-2 text-center">Stok Tersisa</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row,idx) in items" :key="row.id">
                    <tr class="border-t">
                        <td class="p-2" x-text="row.code"></td>
                        <td class="p-2">
                            <span x-text="row.name"></span>
                            <template x-if="row.condition !== 'baik'">
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full" :class="row.condition==='rusak_berat' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' " x-text="row.condition.replace('_',' ')"></span>
                            </template>
                        </td>
                        <td class="p-2 text-center">
                            <input type="number" min="1" :max="row.stock" class="w-20 border rounded p-1 text-center" x-model.number="row.qty" @change="validateQty(idx)">
                            <input type="hidden" :name="`items[${idx}][id]`" :value="row.id">
                            <input type="hidden" :name="`items[${idx}][qty]`" :value="row.qty">
                        </td>
                        <td class="p-2 text-center" x-text="row.stock"></td>
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
</form>

<script>
    function loanForm(){
  return {
    query: '', items: [], suggestions: [],
    async init(){},
    async fetchSuggestions(q){
      if(!q) { this.suggestions=[]; return; }
      const res = await fetch(`{{ route('items.search') }}?q=${encodeURIComponent(q)}`);
      this.suggestions = await res.json();
    },
    addItem(it){
      const exist = this.items.find(x=>x.id===it.id);
      if(exist){ exist.qty = Math.min(exist.qty+1, it.stock); }
      else { this.items.push({...it, qty: 1}); }
      this.query=''; this.suggestions=[];
    },
    async addByQuery(){
      await this.fetchSuggestions(this.query);
      if(this.suggestions.length) this.addItem(this.suggestions[0]);
    },
    remove(i){ this.items.splice(i,1); },
    validateQty(i){
      const r=this.items[i];
      if(r.qty<1) r.qty=1;
      if(r.qty>r.stock){ 
        r.qty=r.stock;
        this.showModal(`Jumlah melebihi stok untuk <b>${r.name}</b>. Otomatis disesuaikan ke stok maksimum (${r.stock}).`);
      }
    },
    openDamagedInfo(){
      this.showModal(`Barang dengan status <b>rusak</b> tetap bisa dipinjam berdasarkan kebijakan gudang.
      Catat pada kolom catatan transaksi. Pada saat pengembalian, barang berstatus <b>baik</b> yang kembali akan menambah stok; 
      yang <b>rusak</b> tidak menambah stok.`);
    },
    showModal(html){
      document.getElementById('modal-body').innerHTML = html;
      document.querySelector('[x-data]').__x.$data.modal = true;
    }
  }
}
document.addEventListener('alpine:init', () => {
  Alpine.effect(() => {
    const el = document.querySelector('input[x-model="query"]');
    if(!el) return;
    el.addEventListener('input', e => {
      const comp = Alpine.closestRoot(el).__x.$data;
      comp.fetchSuggestions(e.target.value);
    });
  });
});
</script>
@endsection