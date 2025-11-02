{{-- resources/views/loans/edit.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Peminjaman') }} {{ $loan->code }}
    </h2>
  </x-slot>
  <x-slot name="breadcrumb">
    <x-breadcrumb :items="[
            ['label' => 'Peminjaman', 'url' => route('loans.index')],
            ['label' => 'Edit']
        ]" />
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <form action="{{ route('loans.update', $loan) }}" method="post" class="grid gap-4 bg-white p-4 rounded-2xl shadow">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm">Nama Peminjam</label>
            <input name="partner_name" class="w-full border rounded p-2" placeholder="Nama Peminjam" value="{{ old('partner_name', optional($loan->partner)->name) }}" required>
          </div>
          <div>
            <label class="block text-sm">Nomor Telepon</label>
            <input type="tel" name="partner_phone" class="w-full border rounded p-2" placeholder="Nomor Telepon" value="{{ old('partner_phone', optional($loan->partner)->phone) }}">
          </div>
          <div>
            <label class="block text-sm">Unit (Satuan Kerja)</label>
            <input name="partner_unit" class="w-full border rounded p-2" placeholder="Misal: Program, Pemberitaan, Produksi" value="{{ old('partner_unit', optional($loan->partner)->unit) }}">
          </div>
          <div>
            <label class="block text-sm">Lokasi</label>
            <input name="lokasi" class="w-full border rounded p-2" placeholder="Lokasi barang dipinjam" value="{{ old('lokasi', $loan->lokasi) }}" required>
          </div>
          <div>
            <label class="block text-sm">Tanggal Pinjam</label>
            @php
              $loanDateVal = $loan->loan_date instanceof \Carbon\Carbon ? $loan->loan_date->format('Y-m-d\TH:i') : (new \Carbon\Carbon($loan->loan_date))->format('Y-m-d\TH:i');
            @endphp
            <input type="datetime-local" name="loan_date" class="w-full border rounded p-2" value="{{ old('loan_date', $loanDateVal) }}" required>
          </div>
          <div>
            <label class="block text-sm">Petugas</label>
            <input value="{{ optional($loan->user)->name }}" class="w-full border rounded p-2" disabled>
          </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-2">
          <a href="{{ route('loans.show', $loan) }}" class="inline-flex justify-center px-4 py-2 rounded border border-slate-300 text-slate-700 bg-white hover:bg-slate-50">Batal</a>
          <button type="submit" class="inline-flex justify-center px-4 py-2 rounded bg-slate-800 text-white">Simpan Perubahan</button>
        </div>
      </form>

      <div class="mt-4 p-3 text-xs text-slate-500 bg-slate-50 rounded-lg border border-slate-200">
        Catatan: Saat Anda mengubah lokasi, lokasi pada barang yang masih berstatus dipinjam akan ikut diperbarui.
      </div>
    </div>
  </div>
</x-app-layout>

