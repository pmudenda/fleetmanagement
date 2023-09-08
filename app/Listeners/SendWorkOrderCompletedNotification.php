<?php

namespace App\Listeners;

use App\Events\WorkOrderCompleted;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\NotificationService\EmailNotificationService;
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

            Log::info('Sending Notification To Workshop Supervisor');

            $task = WorkflowTaskHeader::where('reference', '=', trim($workOrder->job_card_no))->first();

            if (empty($task)) {
                return;
            }

            $sender = User::where('id', trim($task->created_by))->first();

            $recipient = User::where('staff_no', trim($task->assigned_user))->first();

            $action = $event->action ?? 'job_card_closed';

            EmailNotificationService::sendNotification($recipient, $sender, $workOrder, $action);

        } catch (\Exception $e) {
            Log::info('Error When Sending Mail');
            Log::error($e);
        }
    }
}
