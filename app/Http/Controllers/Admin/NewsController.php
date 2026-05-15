<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('admin')->latest();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $news = $query->paginate(10)->withQueryString();
        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'category'     => ['nullable', 'string', 'max:100'],
            'is_published' => ['boolean'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $news = News::create([
            'admin_id'     => auth()->id(),
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'category'     => $validated['category'] ?? 'Umum',
            'is_published' => $request->boolean('is_published', true),
            'image'        => $imagePath,
        ]);

        return response()->json(['message' => 'Berita berhasil ditambahkan.', 'status' => 'success', 'news' => $news]);
    }

    public function show(News $news)
    {
        return response()->json($news);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'category'     => ['nullable', 'string', 'max:100'],
            'is_published' => ['boolean'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $data = [
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'category'     => $validated['category'] ?? 'Umum',
            'is_published' => $request->boolean('is_published', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);
        return response()->json(['message' => 'Berita berhasil diperbarui.', 'status' => 'success', 'news' => $news->fresh()]);
    }

    public function destroy(News $news)
    {
        $news->delete();
        return response()->json(['message' => 'Berita berhasil dihapus.', 'status' => 'success']);
    }
}
