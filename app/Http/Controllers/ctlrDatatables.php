<?php

namespace App\Http\Controllers;


use App\Models\{Item, Category};
use Illuminate\Http\Request;

class ctlrDatatables extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        // $items = Item::with('category')->latest()->paginate(10);
        
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }
}
