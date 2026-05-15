<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);
        $category = Category::create($validated);
        return response()->json(['message' => 'Kategori berhasil ditambahkan.', 'status' => 'success', 'category' => $category]);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);
        $category->update($validated);
        return response()->json(['message' => 'Kategori berhasil diperbarui.', 'status' => 'success', 'category' => $category->fresh()]);
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->count() > 0) {
            return response()->json(['message' => 'Kategori tidak dapat dihapus karena masih memiliki laporan.', 'status' => 'error'], 422);
        }
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.', 'status' => 'success']);
    }
}
