<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%$q%")
                      ->orWhere('code_category', 'like', "%$q%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('categories.index', compact('categories', 'q'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|unique:categories,name',
            'code_category' => 'required',
        ]);

        try {
            Category::create($data);
            if ($r->filled('redirect_to')) {
                return redirect()->to($r->input('redirect_to'))->with('ok', 'Kategori berhasil disimpan');
            }
            return redirect()->route('categories.index')->with('ok', 'Kategori berhasil disimpan');
        } catch (\Throwable $e) {
            return back()->with('error', 'Kategori gagal disimpan.')->withInput();
        }
    }

    public function update(Request $r, Category $category)
    {
        $data = $r->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'code_category' => 'required',
        ]);

        try {
            $category->update($data);
            if ($r->filled('redirect_to')) {
                return redirect()->to($r->input('redirect_to'))->with('ok', 'Kategori berhasil diperbarui');
            }
            return redirect()->route('categories.index')->with('ok', 'Kategori berhasil diperbarui');
        } catch (\Throwable $e) {
            return back()->with('error', 'Kategori gagal diperbarui.')->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            $r = request();
            if ($r->filled('redirect_to')) {
                return redirect()->to($r->input('redirect_to'))->with('ok', 'Kategori berhasil dihapus');
            }

            return redirect()->route('categories.index')->with('ok', 'Kategori berhasil dihapus');
        } catch (\Throwable $e) {
            return back()->with('error', 'Kategori gagal dihapus.');
        }
    }
}
