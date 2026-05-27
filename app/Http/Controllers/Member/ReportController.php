<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewReportNotification;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id', auth()->id())
                         ->with(['category', 'responses.admin'])
                         ->latest()
                         ->paginate(10);
        $categories = Category::all();
        return view('member.reports.index', compact('reports', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location'    => ['required', 'string', 'max:500'],
            'photo_damage'=> ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'title.required'       => 'Judul laporan wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'location.required'    => 'Lokasi wajib diisi.',
            'photo_damage.required'=> 'Foto kerusakan wajib diunggah.',
            'photo_damage.image'   => 'File harus berupa gambar.',
            'photo_damage.max'     => 'Ukuran foto maksimal 5MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_damage')) {
            $photoPath = $request->file('photo_damage')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id'     => auth()->id(),
            'category_id' => $validated['category_id'],
            'code'        => Report::generateCode(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'location'    => $validated['location'],
            'photo_damage'=> $photoPath,
            'status'      => 'pending',
        ]);
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewReportNotification($report));
        
        return response()->json(['message' => 'Laporan berhasil dikirim!', 'status' => 'success']);
    }

    public function show(Report $report)
    {
        if ($report->user_id !== auth()->id()) abort(403);
        $report->load(['category', 'responses.admin']);
        return response()->json($report);
    }

    public function destroy(Report $report)
    {
        if ($report->user_id !== auth()->id()) abort(403);
        if ($report->status !== 'pending') {
            return response()->json(['message' => 'Laporan yang sudah diproses tidak dapat dihapus.', 'status' => 'error'], 422);
        }
        $report->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus.', 'status' => 'success']);
    }
}
