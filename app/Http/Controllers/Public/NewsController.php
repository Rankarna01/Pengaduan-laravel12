<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_published', true)
                    ->with('admin')
                    ->latest()
                    ->paginate(9);
        return view('public.news.index', compact('news'));
    }

    public function show(string $slug)
    {
        $item = News::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $related = News::where('is_published', true)
                       ->where('id', '!=', $item->id)
                       ->latest()->take(3)->get();
        return view('public.news.show', compact('item', 'related'));
    }
}
