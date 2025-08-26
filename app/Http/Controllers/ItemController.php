<?php

// app/Http/Controllers/ItemController.php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
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
