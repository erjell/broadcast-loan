<?php

// app/Http/Controllers/ItemController.php
namespace App\Http\Controllers;

use App\Models\{Category, Item};
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index', [
            'items' => Item::with('category')->get(),
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required',
            'details' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ]);

        Item::create($data);

        return back()->with('ok', 'Item ditambahkan');
    }

    // endpoint JSON untuk scan / pencarian master item
    public function search(Request $r)
    {
        $q = $r->get('q', '');
        $item = Item::query()
            ->when($q, fn($qq) => $qq->where('name', 'like', "%$q%"))
            ->withCount('assets as stock')
            ->limit(10)
            ->get(['id','name']);
        return response()->json($item);
    }
}
