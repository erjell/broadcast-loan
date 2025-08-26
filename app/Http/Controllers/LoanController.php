<?php

// app/Http/Controllers/LoanController.php
namespace App\Http\Controllers;

use App\Models\{Loan,LoanItem,Item,Partner};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanController extends Controller {
    public function index(){
        $loans = Loan::with('partner','items.item')->latest()->paginate(15);
        return view('loans.index', compact('loans'));
    }

    public function create(){
        return view('loans.create', [
            'partners'=>Partner::orderBy('name')->get()
        ]);
    }

    public function store(Request $r){
        $data = $r->validate([
            'partner_id' => ['required','exists:partners,id'],
            'purpose'    => ['required','string','max:255'],
            'loan_date'  => ['required','date'],
            'items'      => ['required','array','min:1'],
            'items.*.id' => ['required','exists:items,id'],
            'items.*.qty'=> ['required','integer','min:1']
        ]);

        DB::transaction(function() use ($data){
            $loan = Loan::create([
                'partner_id'=>$data['partner_id'],
                'purpose'=>$data['purpose'],
                'loan_date'=>$data['loan_date'],
                'user_id'=>auth()->id()
            ]);
            foreach ($data['items'] as $row){
                $item = Item::lockForUpdate()->withCount('assets')->find($row['id']);
                $loaned = LoanItem::where('item_id', $item->id)
                    ->sum(DB::raw('qty - returned_qty'));
                $available = $item->assets_count - $loaned;
                if ($available < $row['qty']){
                    abort(422, "Stok {$item->name} tidak mencukupi.");
                }
                LoanItem::create([
                    'loan_id'=>$loan->id,
                    'item_id'=>$item->id,
                    'qty'=>$row['qty']
                ]);
            }
        });

        return redirect()->route('loans.index')->with('ok','Peminjaman tersimpan.');
    }

    public function show(Loan $loan){
        $loan->load('partner','items.item');
        return view('loans.show', compact('loan'));
    }

    public function returnForm(Loan $loan){
        $loan->load('items.item','partner');
        return view('loans.return', compact('loan'));
    }

    public function processReturn(Request $r, Loan $loan){
        $data = $r->validate([
            'returns'=>'required|array|min:1',
            'returns.*.loan_item_id'=>['required', Rule::exists('loan_items','id')->where('loan_id',$loan->id)],
            'returns.*.qty'=>['required','integer','min:1'],
            'returns.*.condition'=>['required','in:baik,rusak_ringan,rusak_berat'],
            'returns.*.notes'=>['nullable','string']
        ]);

        DB::transaction(function() use ($data, $loan){
            foreach ($data['returns'] as $row){
                /** @var \App\Models\LoanItem $li */
                $li = LoanItem::lockForUpdate()->find($row['loan_item_id']);
                $returnQty = min($row['qty'], $li->qty - $li->returned_qty);
                if ($returnQty <= 0) continue;

                $li->returned_qty += $returnQty;
                $li->return_condition = $row['condition']; // terakhir dipakai
                $li->return_notes = $row['notes'] ?? null;
                $li->save();

                // catat kondisi pengembalian tanpa memengaruhi stok
            }

            // update status pinjaman
            $total = $loan->items()->sum('qty');
            $returned = $loan->items()->sum('returned_qty');
            $loan->status = $returned === 0 ? 'dipinjam' : ($returned < $total ? 'sebagian_kembali' : 'selesai');
            $loan->save();
        });

        return redirect()->route('loans.show',$loan)->with('ok','Pengembalian diproses.');
    }
}

