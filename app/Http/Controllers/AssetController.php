<?php

namespace App\Http\Controllers;

use App\Models\{Asset, Item};
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return view('assets.index', [
            'assets' => Asset::with('item.category')->paginate(20),
            'items' => Item::with('category')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'item_id' => 'required|exists:items,id',
            'serial_number' => 'nullable',
            'procurement_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $item = Item::with('category')->findOrFail($data['item_id']);
        $prefix = $item->category->prefix;
        $count = Asset::whereHas('item', fn($q) => $q->where('category_id', $item->category_id))->count();
        $data['code'] = $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        Asset::create($data);

        return back()->with('ok', 'Aset ditambahkan');
    }
}
