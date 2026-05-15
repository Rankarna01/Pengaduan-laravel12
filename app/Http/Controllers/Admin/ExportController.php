<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function reportsPdf()
    {
        $reports = Report::with(['user', 'category', 'responses'])->latest()->get();

        $pdf = Pdf::loadView('admin.exports.reports-pdf', compact('reports'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kerusakan-' . now()->format('Y-m-d') . '.pdf');
    }
}
