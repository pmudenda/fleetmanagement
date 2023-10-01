<?php

namespace App\Listeners;

use App\Events\RequisitionResubmitted;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
use Illuminate\Support\Facades\Log;

class SendRequisitionResubmittedNotification
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
    public function handle(RequisitionResubmitted $event): void
    {
        try {
            $reference = $event->reference;
            $user = $event->user;
            $action = $event->action;
            $remarks = $event->remarks;

            // reduce query with join
            $sender = $user; //User::where('staff_no', '=', trim(->staff_no))->first();
            $task = WorkflowTaskHeader::where('reference', '=', trim($reference))->first();
            $recipient = User::where('staff_no', trim($task->assigned_user))
                ->first();

            Log::debug('Sending Mail Notification');

            EmailNotificationService::sendNotification(
                $recipient,
                $sender,
                [
                    'ref_no' => $reference,
                    'remarks' => $remarks
                ],
                $action
            );
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e->getMessage());
        }
    }
}
