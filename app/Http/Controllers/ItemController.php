<?php

namespace App\Http\Controllers;

use App\Models\{Item, Category};
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->paginate(10);
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required',
            'details' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'nullable',
            'procurement_year' => 'nullable|integer',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        Item::create($data);

        return redirect()->route('items.index')->with('ok', 'Barang berhasil disimpan');
    }

    public function search(Request $r)
    {
        $q = $r->get('q', '');
        $items = Item::when($q, function ($qq) use ($q) {
                $qq->where('code', $q)
                    ->orWhere('name', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['id', 'code', 'name']);
        return response()->json($items);
    }
}
