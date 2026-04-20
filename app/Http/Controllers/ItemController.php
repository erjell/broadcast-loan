<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\{Item, Category};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function find()
    {
        // Render the responsive item search page (uses AJAX + html5-qrcode)
        return view('items.find');
    }

    public function index(Request $request)
    {
        $filters = $this->extractFilters($request);

        $itemsQuery = Item::with(['category', 'activeLoanItem.loan', 'lastReturn']);
        $this->applyFilters($itemsQuery, $filters);

        $items = $itemsQuery
            ->orderBy('code')
            ->paginate(25)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $exportParams = array_filter(
            Arr::only($filters, ['q', 'category', 'condition', 'status', 'year_start', 'year_end']),
            static fn ($value) => $value !== null && $value !== ''
        );

        if ($request->ajax()) {
            return view('items._list', compact('items', 'categories', 'filters'));
        }

        return view('items.index', compact('items', 'categories', 'filters', 'exportParams'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required',
            'merk' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'details' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'nullable',
            'procurement_year' => 'nullable|integer',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'is_missing' => 'sometimes|boolean',
            'lokasi' => 'nullable|string|max:255',
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
            'merk' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'details' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'nullable',
            'procurement_year' => 'nullable|integer',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'is_missing' => 'sometimes|boolean',
            'lokasi' => 'nullable|string|max:255',
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

        $items = Item::with(['category', 'activeLoanItem.loan', 'lastReturn'])
            ->when($q, function ($qq) use ($q) {
                $qq->where('code', 'like', "%$q%")
                    ->orWhere('serial_number', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%")
                    ->orWhere('merk', 'like', "%$q%")
                    ->orWhere('tipe', 'like', "%$q%")
                    ->orWhere('details', 'like', "%$q%")
                    ->orWhere('lokasi', 'like', "%$q%")
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', "%$q%");
                    });
            })
            ->orderBy('code')
            ->limit(10)
            ->get();

        $payload = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'merk' => $item->merk,
                'tipe' => $item->tipe,
                'details' => $item->details,
                'serial_number' => $item->serial_number,
                'procurement_year' => $item->procurement_year,
                'condition' => $item->condition,
                'category' => optional($item->category)->name,
                'lokasi' => $item->lokasi,
                'is_missing' => (bool) $item->is_missing,
                'on_loan' => (bool) $item->activeLoanItem,
                'loan_id' => optional(optional($item->activeLoanItem)->loan)->id,
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

        $item = Item::with(['category', 'activeLoanItem.loan', 'lastReturn'])
            ->where(function($qq) use ($q) {
                $qq->where('code', $q)
                   ->orWhere('serial_number', $q);
            })
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json([
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'merk' => $item->merk,
            'tipe' => $item->tipe,
            'details' => $item->details,
            'serial_number' => $item->serial_number,
            'procurement_year' => $item->procurement_year,
            'condition' => $item->condition,
            'category' => optional($item->category)->name,
            'lokasi' => $item->lokasi,
            'is_missing' => (bool) $item->is_missing,
            'on_loan' => (bool) $item->activeLoanItem,
            'loan_id' => optional(optional($item->activeLoanItem)->loan)->id,
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

    protected function extractFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->query('q', '')),
            'category' => $request->query('category', ''),
            'condition' => (string) $request->query('condition', ''),
            'status' => strtolower((string) $request->query('status', '')),
            'year_start' => $request->query('year_start', ''),
            'year_end' => $request->query('year_end', ''),
        ];
    }

    /**
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        $q = $filters['q'] ?? '';
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%$q%")
                    ->orWhere('serial_number', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%")
                    ->orWhere('merk', 'like', "%$q%")
                    ->orWhere('tipe', 'like', "%$q%")
                    ->orWhere('details', 'like', "%$q%")
                    ->orWhere('lokasi', 'like', "%$q%")
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', "%$q%");
                    });
            });
        }

        $categoryValue = $filters['category'] ?? '';
        if ($categoryValue !== '' && $categoryValue !== null) {
            if (ctype_digit((string) $categoryValue)) {
                $query->where('category_id', (int) $categoryValue);
            } else {
                $query->whereHas('category', function ($c) use ($categoryValue) {
                    $c->where('name', $categoryValue);
                });
            }
        }

        $condition = $filters['condition'] ?? '';
        if (in_array($condition, ['baik', 'rusak_ringan', 'rusak_berat'], true)) {
            $query->where('condition', $condition);
        }

        $status = $filters['status'] ?? '';
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

        $startYear = $this->normalizeYear($filters['year_start'] ?? null);
        $endYear = $this->normalizeYear($filters['year_end'] ?? null);
        if (!is_null($startYear)) {
            $query->where('procurement_year', '>=', $startYear);
        }
        if (!is_null($endYear)) {
            $query->where('procurement_year', '<=', $endYear);
        }
    }

    protected function normalizeYear($year): ?int
    {
        if ($year === '' || $year === null) {
            return null;
        }

        $year = filter_var($year, FILTER_VALIDATE_INT);

        return $year === false ? null : (int) $year;
    }

    public function export(Request $request)
    {
        $filters = $this->extractFilters($request);

        $query = Item::query()
            ->with(['category', 'lastReturn', 'activeLoanItem.loan']);

        $this->applyFilters($query, $filters);

        $items = $query->orderBy('code')->get();

        $fileName = 'daftar-barang-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ItemsExport($items), $fileName);
    }
}

