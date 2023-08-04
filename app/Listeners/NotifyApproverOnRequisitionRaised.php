<?php

namespace App\Listeners;

use App\Events\RequisitionRaised;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
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
            Log::info('Sending Mail Notification To Requisition Reviewer');
            $sender = User::where('staff_no', '=', trim($requisitionHeader->requested_by));
            $task = WorkflowTaskHeader::where('reference','=', trim($requisitionHeader->req_no))->first();
            $recipient = User::find((int)trim($task->assigned_user));
            $action = $event->action ?? 'requisition';
            EmailNotificationService::sendNotification($recipient, $sender, $requisitionHeader, $action, $task);
        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
