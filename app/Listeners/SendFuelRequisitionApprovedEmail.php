<?php

namespace App\Listeners;

use App\Events\FuelRequisitionApproved;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
use Illuminate\Support\Facades\Log;

class SendFuelRequisitionApprovedEmail
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
    public function handle(FuelRequisitionApproved $event): void
    {
        try {
            $reference = $event->reference;
            $user = $event->user;
            $action = $event->action; //$action = $event->action ?? 'requisition';
            // send notification
            Log::info('Sending Mail Notification To Request Originator');
            $sender = User::where('staff_no', '=', trim($user->staff_no))->first();
            $task = WorkflowTaskHeader::where('reference', '=', trim($reference))->first();

            $recipient = User::where('id', trim($task->created_by))->first();

            EmailNotificationService::sendNotification($recipient, $sender,
                ['req_no' => $reference,
                    'spms_ref' => $event->requisitionNumber],
                $action, $task);
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
