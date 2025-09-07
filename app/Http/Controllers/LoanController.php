<?php

// app/Http/Controllers/LoanController.php
namespace App\Http\Controllers;

use App\Models\{Loan,LoanItem,Partner};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LoanController extends Controller {
    public function index(){
        $loans = Loan::with('partner','items.item','user')->latest()->paginate(15);
        return view('loans.index', compact('loans'));
    }

    public function create(){
        return view('loans.create');
    }

    public function store(Request $r){
        $data = $r->validate([
            'partner_name' => ['required','string','max:255'],
            'purpose'    => ['required','string','max:255'],
            'loan_date'  => ['required','date'],
            'items'      => ['required','array','min:1'],
            'items.*.id' => ['required','exists:items,id']
        ]);

        try {
            $partner = Partner::firstOrCreate(['name'=>$data['partner_name']]);

            DB::transaction(function() use ($data, $partner){
                $loan = Loan::create([
                    'partner_id'=>$partner->id,
                    'purpose'=>$data['purpose'],
                    'loan_date'=>$data['loan_date'],
                    'user_id'=>auth()->id()
                ]);
                foreach ($data['items'] as $row){
                    LoanItem::create([
                        'loan_id'=>$loan->id,
                        'item_id'=>$row['id']
                    ]);
                }
            });

            return redirect()->route('loans.index')->with('ok','Peminjaman tersimpan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan peminjaman', [
                'error' => $e->getMessage(),
                'trace' => collect($e->getTrace())->take(3),
            ]);
            return back()->with('error','Peminjaman gagal disimpan: '.$e->getMessage())->withInput();
        }
    }

    public function show(Loan $loan){
        $loan->load('partner','items.item','user');
        return view('loans.show', compact('loan'));
    }

    public function returnForm(Loan $loan){
        $loan->load('items.item','partner','user');
        return view('loans.return', compact('loan'));
    }

    public function processReturn(Request $r, Loan $loan){
        // Ambil hanya baris yang dicentang (selected)
        $filtered = collect($r->input('returns', []))
            ->filter(fn($row) => !empty($row['selected']))
            ->values()
            ->all();

        // Gantikan input 'returns' dengan yang sudah difilter
        $r->merge(['returns' => $filtered]);

        $data = $r->validate([
            'returns' => 'required|array|min:1',
            'returns.*.loan_item_id' => ['required', Rule::exists('loan_items','id')->where('loan_id',$loan->id)],
            'returns.*.condition' => ['required','in:baik,rusak_ringan,rusak_berat'],
            'returns.*.notes' => ['nullable','string']
        ]);

        try {
            DB::transaction(function() use ($data, $loan){
                foreach ($data['returns'] as $row){
                    /** @var \App\Models\LoanItem $li */
                    $li = LoanItem::lockForUpdate()->find($row['loan_item_id']);

                    // Tandai item sebagai sudah kembali dengan menyimpan kondisi & catatan
                    $li->return_condition = $row['condition'];
                    $li->return_notes = $row['notes'] ?? null;
                    $li->save();

                    // Sinkronkan kondisi barang aktual dengan kondisi saat dikembalikan
                    if ($li->relationLoaded('item')) {
                        $item = $li->item;
                    } else {
                        $item = $li->item()->lockForUpdate()->first();
                    }
                    if ($item && $item->condition !== $row['condition']) {
                        $item->condition = $row['condition'];
                        $item->save();
                    }
                }

                // Perbarui status pinjaman berdasarkan jumlah item yang sudah kembali
                $total = $loan->items()->count();
                $returned = $loan->items()->whereNotNull('return_condition')->count();
                $loan->status = $returned === 0
                    ? 'dipinjam'
                    : ($returned < $total ? 'sebagian_kembali' : 'selesai');
                $loan->save();
            });

            return redirect()->route('loans.show', $loan)->with('ok','Pengembalian diproses.');
        } catch (\Throwable $e) {
            Log::error('Pengembalian gagal', [
                'loan_id' => $loan->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error','Pengembalian gagal diproses: '.$e->getMessage())->withInput();
        }
    }
}
