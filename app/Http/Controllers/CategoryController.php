<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|unique:categories,name',
            'code' => 'required|unique:categories,code',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('ok', 'Kategori berhasil disimpan');
    }
}
