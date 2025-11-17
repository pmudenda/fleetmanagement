<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return (new MailMessage)->markdown('notifications.email.rtsa_alert', $complainceReport);
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
