<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Notifications\ReportStatusUpdatedNotification;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'category', 'responses'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $reports = $query->paginate(10)->withQueryString();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['user', 'category', 'responses.admin']);
        return response()->json($report);
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => ['required', 'in:pending,process,completed,rejected'],
        ]);
        $report->update(['status' => $request->status]);

        $report->user->notify(new ReportStatusUpdatedNotification($report));
        // ===============================
        return response()->json(['message' => 'Status laporan berhasil diperbarui.', 'status' => 'success', 'new_status' => $report->status]);
    }

    public function addResponse(Request $request, Report $report)
    {
        $request->validate([
            'message'     => ['required', 'string'],
            'photo_repair'=> ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status'      => ['required', 'in:pending,process,completed,rejected'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_repair')) {
            $photoPath = $request->file('photo_repair')->store('responses', 'public');
        }

        $response = Response::create([
            'report_id'   => $report->id,
            'admin_id'    => auth()->id(),
            'message'     => $request->message,
            'photo_repair'=> $photoPath,
        ]);

        $report->update(['status' => $request->status]);
        $report->user->notify(new ReportStatusUpdatedNotification($report, $request->message));

        $response->load('admin');
        return response()->json([
            'message' => 'Tanggapan berhasil ditambahkan.',
            'status'  => 'success',
            'response'=> $response,
            'new_status' => $report->fresh()->status,
        ]);
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus.', 'status' => 'success']);
    }
}
