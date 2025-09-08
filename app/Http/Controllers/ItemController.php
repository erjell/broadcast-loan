<?php

namespace App\Http\Controllers;

use App\Models\{Item, Category};
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Eager-load latest return info (with notes) for tooltip rendering
        $items = Item::with(['category','activeLoanItem.loan','lastReturn'])
            ->get();
        // $items = Item::with('category')->latest()->paginate(10);
        
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

        try {
            Item::create($data);
            return redirect()->route('items.index')->with('ok', 'Barang berhasil disimpan');
        } catch (\Throwable $e) {
            return back()->with('error', 'Barang gagal disimpan.')->withInput();
        }
    }

    public function update(Request $r, Item $item)
    {
        $data = $r->validate([
            'name' => 'required',
            'details' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'nullable',
            'procurement_year' => 'nullable|integer',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        try {
            $categoryChanged = $item->category_id != $data['category_id'];
            if ($categoryChanged) {
                $category = Category::find($data['category_id']);
                $prefix = strtoupper($category->code_category);
                $count = Item::where('category_id', $category->id)
                    ->where('id', '!=', $item->id)
                    ->count() + 1;
                $item->code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
            $item->update($data);
            return redirect()->route('items.index')->with('ok', 'Barang berhasil diperbarui');
        } catch (\Throwable $e) {
            return back()->with('error', 'Barang gagal diperbarui.')->withInput();
        }
    }

    public function destroy(Item $item)
    {
        try {
            $item->delete();
            return redirect()->route('items.index')->with('ok', 'Barang berhasil dihapus');
        } catch (\Throwable $e) {
            return back()->with('error', 'Barang gagal dihapus.');
        }
    }

    public function search(Request $r)
    {
        $q = $r->get('q', '');
        $items = Item::when($q, function ($qq) use ($q) {
                $qq->where('code', 'like', "%$q%")
                    ->orWhere('serial_number', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['id', 'code', 'name', 'serial_number', 'condition']);

        return response()->json($items);
    }

    public function lookup(Request $r)
    {
        // Exact, fast lookup for barcode scanners (code or serial_number)
        $q = trim((string) $r->get('q', ''));
        if ($q === '') {
            return response()->json(['message' => 'Query required'], 422);
        }

        $item = Item::where('code', $q)
            ->orWhere('serial_number', $q)
            ->first(['id', 'code', 'name', 'serial_number', 'condition']);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json($item);
    }

    public function code(Request $r)
    {
        $data = $r->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::find($data['category_id']);
        $prefix = strtoupper($category->code_category);
        $count = Item::where('category_id', $category->id)->count() + 1;
        $code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

        return response()->json(['code' => $code]);
    }
    public function print(Request $request)
    {
        $input = trim((string) $request->query('id', ''));
        if ($input === '') {
            abort(404);
        }

        // Resolve item by id (if numeric), or by code/serial_number
        $item = null;
        if (ctype_digit($input)) {
            $item = Item::find($input);
        }
        if (!$item) {
            $item = Item::where('code', $input)
                ->orWhere('serial_number', $input)
                ->first();
        }
        if (!$item) {
            abort(404);
        }

        // Determine which value to print; allow explicit override via ?type=
        $explicitType = $request->query('type'); // 'code' | 'serial_number'
        if ($explicitType === 'serial_number') {
            $type = 'serial_number';
        } elseif ($explicitType === 'code') {
            $type = 'code';
        } else {
            // Infer from input match, fallback to 'code'
            $type = ($item->serial_number && $input === $item->serial_number) ? 'serial_number' : 'code';
        }

        return view('items.printBarcode', [
            'item' => $item,
            'type' => $type,
        ]);
    }
}
