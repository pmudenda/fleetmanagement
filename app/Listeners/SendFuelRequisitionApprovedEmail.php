<?php

namespace App\Listeners;

use App\Events\FuelRequisitionWorkflowUpdate;
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
    public function handle(FuelRequisitionWorkflowUpdate $event): void
    {
        try {
            $reference = $event->reference;
            $user = $event->user;
            $action = $event->action;

            $sender = null;
            $task = null;

            $sender = User::where('staff_no', '=', trim($user->staff_no))->first();
            $task = WorkflowTaskHeader::where('reference', '=', trim($reference))->first();
            if ($action == 'resubmitted') {
                $recipient = User::where('staff_no', trim($task->assigned_user))->first();
            }else{
                $recipient = User::where('id', trim($task->created_by))->first();
            }

            Log::debug('Sending Mail Notification');

            EmailNotificationService::sendNotification($recipient, $sender,
                [
                    'req_no' => $reference,
                    'spms_ref' => $event->requisitionNumber
                ],
                $action
            );
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
