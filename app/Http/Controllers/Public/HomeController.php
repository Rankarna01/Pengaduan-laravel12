<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => Report::count(),
            'pending'   => Report::where('status', 'pending')->count(),
            'process'   => Report::where('status', 'process')->count(),
            'completed' => Report::where('status', 'completed')->count(),
            'rejected'  => Report::where('status', 'rejected')->count(),
        ];

        $stats['percent_completed'] = $stats['total'] > 0
            ? round(($stats['completed'] / $stats['total']) * 100)
            : 0;

        // Chart data: laporan per kategori
        $categoriesChart = Category::withCount('reports')->get()
            ->map(fn($c) => ['label' => $c->name, 'count' => $c->reports_count, 'icon' => $c->icon]);

        // Chart data: laporan per bulan (6 bulan terakhir)
        $monthlyChart = collect(range(5, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'month' => $month->format('M Y'),
                'count' => Report::whereYear('created_at', $month->year)
                                 ->whereMonth('created_at', $month->month)
                                 ->count(),
            ];
        });

        $latestNews = News::where('is_published', true)
                          ->with('admin')
                          ->latest()
                          ->take(6)
                          ->get();

        $categories = Category::all();

        return view('public.home', compact('stats', 'categoriesChart', 'monthlyChart', 'latestNews', 'categories'));
    }
}
