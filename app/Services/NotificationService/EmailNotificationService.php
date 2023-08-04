<?php

namespace App\Services\NotificationService;


use App\Mail\SendMail;
use App\Models\Security\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public static function sendNotification(User $recipient, User $sender, $record, string $action, $task): bool
    {
        try {
            $recipientMail = 'lovemoredaka@zesco.co.zm'; //$recipient->email
            $to[] = ['email' => $recipientMail, 'name' => $recipient->name];
            $names = $recipient->name;

            switch ($action) {
                case 'requisition':
                case 'job_card_material_requisition':
                case 'fuel_requisition':
                    $dueDate = Carbon::parse($record->dueDate)->format('d/m/Y');
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => "New Task Needs Your Attention",
                        'title' => "New Task Needs Your Attention",
                        'body' => "Please be informed that {$sender->name} has assigned you a task with reference
                               <strong>{$record->req_no}</strong>
                               in ZFMS. The task is due on {$dueDate}.
                               <br>Kindly click on the button below to login to ZFMS
                               and take action.<br> Regards. "
                    ];
                    break;
                case 'approved':
                    //prepare details
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->taskNumber]),
                        'identity' => $record->taskNumber,
                        'subject' => "Task Approved",
                        'title' => "Task Approved",
                        'body' => "Task with reference number <strong>{$record->taskNumber}</strong>,
                               has been approved and consequently closed<br> Regards. "
                    ];
                    break;
                case 'rejected':
                    //prepare details
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->taskNumber]),
                        'identity' => $record->taskNumber,
                        'subject' => "Task Rejected",
                        'title' => "Task Rejected",
                        'body' => "Task with reference number <strong>{$record->taskNumber}</strong>,
                               has been rejected.
                               <br>Kindly login to ZQMS
                               to see the reason.<br> Regards. "
                    ];
                    break;
                case 'approve':
                    //prepare details
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->taskNumber]),
                        'identity' => $record->taskNumber,
                        'subject' => "Task Approval",
                        'title' => "Task Approval",
                        'body' => "Please be informed that the Task with reference number <strong>{$record->taskNumber}</strong>,
                               assigned to {$sender->name}  has been completed and
                               needs your attention.
                               <br>Kindly click on the button below to login to ZQMS
                               and take action.<br> Regards. "
                    ];
                    break;
                case 'reassign':
                    //prepare details
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->taskNumber]),
                        'identity' => $record->taskNumber,
                        'subject' => "Task Reassignment",
                        'title' => "Task Reassignment",
                        'body' => "Please be informed that the Task with reference number <strong>{$record->taskNumber}</strong>,
                               has been ressigned to you and requires your attention.
                               <br>Kindly click on the button below to login to ZQMS
                               and take action.<br> Regards. "
                    ];
                    break;
                case 'completed':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'tasks/show?reference=' . $record->taskNumber]),
                        'identity' => $record->taskNumber,
                        'subject' => "Task Needs Your Approval",
                        'title' => "Task Needs Your Approval",
                        'body' => "Please be informed that the Task with reference number <strong>{$record->taskNumber}</strong>,
                               assigned to {$sender->name}  has been completed and
                               needs your attention.
                               <br>Kindly click on the button below to login to ZQMS
                               and take action.<br> Regards. "
                    ];
                    break;
                // To view this record, click the following link:
                case 'nc_creation':
                    $dueDate = Carbon::parse($record->dueDate)->format('d/m/Y');
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity For Your Attention",
                        'title' => "",
                        'body' => "Nonconformity reference no. <strong>{$record->nonConformanceNumber}</strong>
                                   has been raised by {$sender->name}
                                   for your attention. To ensure compliance,
                                   promptly attend to the nonconformity before {$dueDate} by
                                   clicking on the link below to login to ZQMS."
                    ];
                    break;
                case 'nc_rejection':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity For Your Attention",
                        'title' => "",
                        'body' => "Please note that {$sender->name}
                                   has denied responsibility to a nonconformity with reference no.
                                   <strong>{$record->nonConformanceNumber}</strong>.
                               <br>To view this record, click the button below:"
                    ];
                    break;
                case 'nc_acceptance':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity For Your Attention",
                        'title' => "",
                        'body' => "Please note that {$sender->name} has accepted responsibility to a nonconformity with
                                   reference no. <strong>{$record->nonConformanceNumber}</strong>.
                                   <br>To view this record, click the button below:"
                    ];
                    break;
                case 'nc_containment_submitted':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity Containment",
                        'title' => "",
                        'body' => "Containment Action(s) for nonconformity reference no.
                                   <strong>{$record->nonConformanceNumber}</strong>
                                   submitted for your approval. Proceed to login for further action."
                    ];
                    break;
                case 'nc_containment_approved':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity Containment",
                        'title' => "",
                        'body' => "Containment Action(s) for nonconformity reference no.
                                 <strong>{$record->nonConformanceNumber}</strong> approved."
                    ];
                    break;
                case 'nc_containment_rejected':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => route('nonconformity/show',
                            ['reference' => $record->nonConformanceNumber])]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity Containment",
                        'title' => "",
                        'body' => "Containment Action(s) for nonconformity reference no.
                                   <strong>{$record->nonConformanceNumber}</strong>rejected.
                                    You are required to resolve and resubmit for approval"
                    ];
                    break;
                case 'nc_corrective_submitted':
                    $details = [
                        'name' => $names,
                        'systemLink' => route('home', ['external_link' => 'nonconformity/show?reference=' . $record->nonConformanceNumber]),
                        'identity' => $record->nonConformanceNumber,
                        'subject' => "Nonconformity's Corrective Action",
                        'title' => "",
                        'body' => "Corrective Action(s) for nonconformity
                                   reference no. <strong>{$record->nonConformanceNumber}</strong>
                                   submitted for your approval.
                                   You may proceed to login for further action."
                    ];
                    break;
                case 'nc_corrective_approved':
                    $details = [
                        'name' => $names,
                        'systemLink' => null,
                        'identity' => null,
                        'subject' => "Nonconformity closure",
                        'title' => "",
                        'body' => "Nonconformity reference no. <strong>{$record->nonConformanceNumber}</strong>
                                   assigned to you has been closed."
                    ];
                    break;
                default:
                    return false;
            }

            Mail::to($to)->send(new SendMail($details));
            Log::info('Email Sent ');
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
}
