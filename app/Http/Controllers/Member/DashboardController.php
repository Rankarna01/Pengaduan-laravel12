<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $reports = Report::where('user_id', $user->id)->with('category')->latest()->get();

        $stats = [
            'total'     => $reports->count(),
            'pending'   => $reports->where('status', 'pending')->count(),
            'process'   => $reports->where('status', 'process')->count(),
            'completed' => $reports->where('status', 'completed')->count(),
            'rejected'  => $reports->where('status', 'rejected')->count(),
        ];

        $latestReports = $reports->take(5);

        return view('member.dashboard', compact('stats', 'latestReports'));
    }
}
