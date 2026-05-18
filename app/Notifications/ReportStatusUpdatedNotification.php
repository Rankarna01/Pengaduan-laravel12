<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $report;
    protected $responseMessage;

    // Kita passing data report dan (opsional) pesan tanggapan dari admin
    public function __construct(Report $report, $responseMessage = null)
    {
        $this->report = $report;
        $this->responseMessage = $responseMessage;
    }

   public function via($notifiable)
    {
        // Tambahkan 'database' ke dalam array
        return ['mail', 'database']; 
    }

    public function toMail($notifiable)
    {
        $statusLabels = [
            'pending'   => 'Menunggu Verifikasi',
            'process'   => 'Sedang Diproses',
            'completed' => 'Selesai',
            'rejected'  => 'Ditolak',
        ];

        $statusName = $statusLabels[$this->report->status] ?? $this->report->status;

        $mail = (new MailMessage)
                    ->subject('Update Status Laporan: ' . $this->report->code)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Status laporan Anda dengan kode tiket **' . $this->report->code . '** telah diperbarui menjadi: **' . $statusName . '**.')
                    ->line('Judul Laporan: ' . $this->report->title);

        // Jika admin memberikan tanggapan/pesan, kita sisipkan di email
        if ($this->responseMessage) {
            $mail->line('Tanggapan dari Admin:')
                 ->line('"' . $this->responseMessage . '"');
        }

        // Tombol CTA (Call to Action) ke dashboard member
        $mail->action('Lihat Detail Laporan', url('/member/laporan'))
             ->line('Terima kasih telah berpartisipasi dalam menjaga dan membangun infrastruktur desa kita!');

        return $mail;
    }

    public function toDatabase($notifiable)
    {
        $statusLabels = [
            'pending'   => 'Menunggu Verifikasi',
            'process'   => 'Sedang Diproses',
            'completed' => 'Selesai',
            'rejected'  => 'Ditolak',
        ];

        $statusName = $statusLabels[$this->report->status] ?? $this->report->status;

        return [
            'report_code' => $this->report->code,
            'title'       => $this->report->title,
            'status'      => $statusName,
            'message'     => 'Status laporan Anda telah diperbarui menjadi ' . $statusName . '.',
            'response'    => $this->responseMessage,
        ];
    }
}