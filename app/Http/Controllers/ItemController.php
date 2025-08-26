<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // endpoint JSON untuk scan / pencarian
    public function search(Request $r)
    {
        $q = $r->get('q', '');
        $items = Item::withCount('assets as stock')
            ->when($q, function ($qq) use ($q) {
                $qq->where('code', $q)
                    ->orWhere('name', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['id', 'code', 'name']);
        return response()->json($items);
    }
}
