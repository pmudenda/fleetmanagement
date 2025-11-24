<?php

namespace App\Notifications;

use App\Exports\NonCompliantVehicleExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class RtsaAlertNotification extends Notification {
    use Queueable;

    private mixed $complainceReport;

    /**
     * Create a new notification instance.
     */
    public function __construct($complianceReport) {
        $this->complainceReport = $complianceReport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        $complainceReport = $this->complainceReport;
        $vehicles = $complainceReport['nonCompliant'];
        $date = date('Y-m-d');
        return (new MailMessage)
            ->attachData(Excel::raw(new NonCompliantVehicleExport($vehicles), \Maatwebsite\Excel\Excel::XLSX),"non-compliant-vehicles-{{$date}}.xlsx")
            ->markdown('notifications.email.rtsa_alert', $complainceReport);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}
