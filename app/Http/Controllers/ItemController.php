<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\{Item, Category};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index()
    {
        // Eager-load latest return info (with notes) for tooltip rendering
        $items = Item::with(['category','activeLoanItem.loan','lastReturn'])
            ->latest('id')
            ->paginate(20);
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
            'is_missing' => 'sometimes|boolean',
        ]);

        try {
            $data['is_missing'] = (bool)($data['is_missing'] ?? false);
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
            'is_missing' => 'sometimes|boolean',
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
            $data['is_missing'] = (bool)($data['is_missing'] ?? false);
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

        $items = Item::with('lastReturn')
            ->where('is_missing', false)
            ->when($q, function ($qq) use ($q) {
                $qq->where('code', 'like', "%$q%")
                    ->orWhere('serial_number', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['id', 'code', 'name', 'serial_number', 'condition']);

        $payload = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
                'condition' => $item->condition,
                'last_return_notes' => optional($item->lastReturn)->return_notes,
            ];
        });

        return response()->json($payload);
    }

    public function lookup(Request $r)
    {
        // Exact, fast lookup for barcode scanners (code or serial_number)
        $q = trim((string) $r->get('q', ''));
        if ($q === '') {
            return response()->json(['message' => 'Query required'], 422);
        }

        $item = Item::with('lastReturn')
            ->where(function($qq) use ($q) {
                $qq->where('code', $q)
                   ->orWhere('serial_number', $q);
            })
            ->where('is_missing', false)
            ->first(['id', 'code', 'name', 'serial_number', 'condition']);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json([
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'serial_number' => $item->serial_number,
            'condition' => $item->condition,
            'last_return_notes' => optional($item->lastReturn)->return_notes,
        ]);
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

    public function export(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoryName = trim((string) $request->query('category', ''));
        $condition = (string) $request->query('condition', '');
        $status = strtolower((string) $request->query('status', ''));
        $yearStart = $request->query('year_start');
        $yearEnd = $request->query('year_end');

        $query = Item::query()
            ->with(['category', 'lastReturn', 'activeLoanItem.loan']);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%$q%")
                  ->orWhere('serial_number', 'like', "%$q%")
                  ->orWhere('name', 'like', "%$q%")
                  ->orWhere('details', 'like', "%$q%")
                  ->orWhereHas('category', function ($c) use ($q) {
                      $c->where('name', 'like', "%$q%");
                  });
            });
        }

        if ($categoryName !== '') {
            $query->whereHas('category', function ($c) use ($categoryName) {
                $c->where('name', $categoryName);
            });
        }

        if (in_array($condition, ['baik', 'rusak_ringan', 'rusak_berat'], true)) {
            $query->where('condition', $condition);
        }

        if (in_array($status, ['tersedia', 'dipinjam', 'hilang'], true)) {
            if ($status === 'hilang') {
                $query->where('is_missing', true);
            } elseif ($status === 'dipinjam') {
                $query->whereHas('activeLoanItem');
            } elseif ($status === 'tersedia') {
                $query->where('is_missing', false)
                      ->whereDoesntHave('activeLoanItem');
            }
        }

        $startYear = is_null($yearStart) || $yearStart === '' ? null : (int) $yearStart;
        $endYear = is_null($yearEnd) || $yearEnd === '' ? null : (int) $yearEnd;
        if ($startYear !== null) {
            $query->where('procurement_year', '>=', $startYear);
        }
        if ($endYear !== null) {
            $query->where('procurement_year', '<=', $endYear);
        }

        $items = $query->orderBy('code')->get();

        $fileName = 'daftar-barang-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ItemsExport($items), $fileName);
    }
}
