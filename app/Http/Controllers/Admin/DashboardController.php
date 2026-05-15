<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Report;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reports'    => Report::count(),
            'pending'          => Report::where('status', 'pending')->count(),
            'process'          => Report::where('status', 'process')->count(),
            'completed'        => Report::where('status', 'completed')->count(),
            'rejected'         => Report::where('status', 'rejected')->count(),
            'total_users'      => User::where('role', 'masyarakat')->count(),
            'total_categories' => Category::count(),
            'total_news'       => News::count(),
        ];

        $stats['percent_completed'] = $stats['total_reports'] > 0
            ? round(($stats['completed'] / $stats['total_reports']) * 100)
            : 0;

        // Laporan terbaru
        $latestReports = Report::with(['user', 'category'])->latest()->take(8)->get();

        // Chart: laporan per bulan (6 bulan)
        $monthlyChart = collect(range(5, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'month'     => $month->format('M'),
                'total'     => Report::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
                'completed' => Report::where('status', 'completed')->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            ];
        });

        // Chart: per kategori
        $categoryChart = Category::withCount('reports')->get();

        return view('admin.dashboard', compact('stats', 'latestReports', 'monthlyChart', 'categoryChart'));
    }
}
