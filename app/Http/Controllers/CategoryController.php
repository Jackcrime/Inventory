<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Halaman utama (render Blade saja)
        return view('category');
    }

    public function json(Request $request)
    {
        $query = Category::withTrashed()->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10)->appends($request->query());

        return response()->json([
            'data' => $categories->items(),
            'links' => (string) $categories->links('pagination::tailwind')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        Category::create($validated);

        return response()->json(['message' => 'Kategori berhasil ditambahkan.']);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($validated);

        return response()->json(['message' => 'Kategori berhasil diperbarui.']);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return response()->json(['message' => 'Kategori berhasil dipulihkan']);
    }
    
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();
        return response()->json(['message' => 'Kategori dihapus permanen']);
    }    
}
