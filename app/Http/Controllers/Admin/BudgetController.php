<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'process'); // default process
        
        $query = Report::whereIn('status', ['process', 'completed'])->with(['budget.admin', 'category']);
        
        if ($tab === 'process') {
            $query->where('status', 'process');
        } else {
            $query->where('status', 'completed')->has('budget');
        }

        $reports = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.budgets.index', compact('reports', 'tab'));
    }

    public function store(Request $request, Report $report)
    {
        if (!in_array($report->status, ['process', 'completed'])) {
            return response()->json(['status' => 'error', 'message' => 'Laporan belum diproses atau ditolak.'], 403);
        }

        $validated = $request->validate([
            'amount'          => ['required', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string'],
            'items'           => ['nullable', 'array'],
            'items.*.name'    => ['required', 'string'],
            'items.*.price'   => ['required', 'numeric', 'min:0'],
        ]);

        $budget = Budget::updateOrCreate(
            ['report_id' => $report->id],
            [
                'admin_id' => auth()->id(),
                'amount'   => $validated['amount'],
                'items'    => $validated['items'] ?? null,
                'notes'    => $validated['notes'],
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'Anggaran berhasil disimpan.', 'budget' => $budget]);
    }
}
