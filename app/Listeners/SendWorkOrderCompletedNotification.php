<?php

namespace App\Listeners;

use App\Events\WorkOrderCompleted;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWorkOrderCompletedNotification
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
    public function handle(WorkOrderCompleted $event): void
    {
        try {
            $workOrder = $event->workOrder;
            // send notification
            Log::info('Sending Notification To Work Order Approver');

            $task = WorkflowTaskHeader::where('reference', '=', trim($workOrder->job_card_no))->first();

            $sender = User::where('id', trim($task->created_by))->first();

            $recipient = User::where('staff_no', trim($task->assigned_user))->first();

            $action = $event->action ?? 'workOrderCompleted';

            EmailNotificationService::sendNotification($recipient, $sender, $workOrder, $action, $task);

        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
