<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Mengambil semua notifikasi admin yang sedang login
        $notifications = auth()->user()->notifications()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // Arahkan admin ke halaman kelola laporan dan otomatis search kode laporannya
        return redirect()->route('admin.reports.index', ['search' => $notification->data['report_code']]);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}