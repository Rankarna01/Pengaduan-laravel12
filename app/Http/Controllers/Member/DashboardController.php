<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allReports = Report::where('user_id', $user->id)->with('category')->latest()->get();

        $stats = [
            'total'     => $allReports->count(),
            'pending'   => $allReports->where('status', 'pending')->count(),
            'process'   => $allReports->where('status', 'process')->count(),
            'completed' => $allReports->where('status', 'completed')->count(),
            'rejected'  => $allReports->where('status', 'rejected')->count(),
        ];

        $activeStatus = $request->get('status', 'all');

        $latestReports = $activeStatus !== 'all'
            ? $allReports->where('status', $activeStatus)->values()
            : $allReports->take(5);

        return view('member.dashboard', compact('stats', 'latestReports', 'activeStatus'));
    }
}
