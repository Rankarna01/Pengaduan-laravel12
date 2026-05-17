<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        // Untuk admin, kita gunakan database (in-app notification)
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        return [
            'report_id'   => $this->report->id,
            'report_code' => $this->report->code,
            'title'       => $this->report->title,
            'user_name'   => auth()->user()->name,
            'message'     => 'mengirim laporan kerusakan baru:',
        ];
    }
}