<?php

namespace App\Notifications\Vehicle;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleOverIssuedNotification extends Notification {
    use Queueable;

    private mixed $overIssue;

    /**
     * Create a new notification instance.
     */
    public function __construct($overIssue) {
        $this->overIssue = $overIssue;
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
        $overIssue = $this->overIssue;
        return (new MailMessage)
            ->subject("Fuel issued more than tank capacity alert")
            ->markdown('mail.over-issue-alert', compact('overIssue'));
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
