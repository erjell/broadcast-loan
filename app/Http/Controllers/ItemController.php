<?php

namespace App\Http\Controllers;

use App\Models\{Item, Category};
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category','activeLoanItem.loan'])->get();
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
        $id = $request->query('id');
        $type = $request->query('type', 'code'); // 'code' or 'serial'

        if (!$id) {
            abort(404);
        }

        $item = Item::findOrFail($id);

        return view('items.printBarcode', [
            'item' => $item,
            'type' => in_array($type, ['code', 'serial']) ? $type : 'code',
        ]);
    }
}
