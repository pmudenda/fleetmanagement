<?php

namespace App\Listeners;

use App\Events\JobCardCreated;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
use Illuminate\Support\Facades\Log;

class SendJobCardCreatedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobCardCreated $event): void
    {
        try {
            $jobCard = $event->jobCard;
            $user = $event->user;

            $supervisor = $event->supervisor;
            $action = 'job_card_created';

            // send notification
            Log::info('Sending Mail Notification To Request Workshop Supervisor');
            $sender = $user;

            $recipient = User::where('staff_no', trim($supervisor->staff_no))->first();

            EmailNotificationService::sendNotification($recipient, $sender, $jobCard, $action);
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
