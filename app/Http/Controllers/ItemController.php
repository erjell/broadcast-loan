<?php

// app/Http/Controllers/ItemController.php
namespace App\Http\Controllers;

use App\Models\{Category, Item};
use Illuminate\Http\Request;

class ItemController extends Controller {
    public function index(){
        return view('items.index', [
            'items'=>Item::with('category')->paginate(20),
            'categories'=>Category::all(),
        ]);
    }
    public function store(Request $r){
        $data = $r->validate([
            'barcode'=>'required|unique:items,barcode',
            'name'=>'required',
            'serial_number'=>'nullable',
            'procurement_year'=>'nullable|integer|min:1900|max:'.date('Y'),
            'details'=>'nullable',
            'category_id'=>'required|exists:categories,id',
            'stock'=>'required|integer|min:0',
            'condition'=>'required|in:baik,rusak_ringan,rusak_berat',
        ]);
        Item::create($data);
        return back()->with('ok','Barang ditambahkan');
    }
    // endpoint JSON untuk scan / pencarian
    public function search(Request $r){
        $q = $r->get('q','');
        $item = Item::query()
            ->when($q, function($qq) use ($q){
                $qq->where('barcode',$q)->orWhere('name','like',"%$q%");
            })
            ->limit(10)->get(['id','barcode','name','stock','condition']);
        return response()->json($item);
    }
}

