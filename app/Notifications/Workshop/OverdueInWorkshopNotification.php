<?php

namespace App\Notifications\Workshop;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueInWorkshopNotification extends Notification
{
    use Queueable;

    private  $vehicles;

    /**
     * Create a new notification instance.
     */
    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicles = $this->vehicles;
        return (new MailMessage)
            ->subject('Vehicles Overdue In Workshop for over 90 days')
            ->markdown('email.workshop.overdue-in-workshop', compact('vehicles'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
