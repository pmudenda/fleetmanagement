<?php

namespace App\Listeners;

use App\Events\RequisitionRaised;
use App\Models\Security\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyApproverOnRequisitionRaised
{
    use InteractsWithQueue;

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
    public function handle(RequisitionRaised $event): void
    {
        try {
            $requisitionHeader = $event->requestHeader;
            // send notification
            Log::info('Sending Mail Notification For Requisition');
            $sender = User::where('staff_no', '=', trim($requisitionHeader->requested_by));
            //$recipient = User::find((int)trim($nonConformance->originatorId));
            //$action = $event->action;
            //EmailNotificationService::sendNotification($recipient, $sender, $nonConformance, $action);
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
