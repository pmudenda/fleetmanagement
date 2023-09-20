<?php

namespace App\Services\NotificationService;


use App\Mail\SendMail;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailNotificationService
{
    const NEW_TASK_NEEDS_YOUR_ATTENTION = "New Task Needs Your Attention";

    public static function sendNotification($recipient, $sender, $record, $action): bool
    {
        try {
            $recipientMail = config("mail.default_mail") ?? $recipient->email;
            $to[] = ['email' => $recipientMail, 'name' => $recipient->name ?? ''];
            $names = $recipient->name;

            switch ($action) {
                case 'requisition':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workshop.requisition', [
                            'ref' => $record->req_no
                        ]),
                        'identity' => $record->req_no,
                        'subject' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'title' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'body' => "Please be informed that {$sender->name}
                        has raised a request for spares, with reference
                               <strong>{$record->req_no}</strong>
                               in Fleet Master.
                               <br>Kindly click on the button below to login to ZFMS
                               and take action.<br> Regards. "
                    ];
                    break;
                case 'job_card_created':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.job.card', ['ref' => $record->job_card_no]),
                        'identity' => $record->job_card_no,
                        'subject' => "Job Card Task Needs Your Attention",
                        'title' => "Job Card Task Needs Your Attention",
                        'body' => "Please be informed that {$sender->name} has raised a new Job Card, reference
                               <strong>{$record->job_card_no}</strong>
                               in Zesco Fleet Master.
                               <br>To Take action immediately, click on the button below.
                               .<br> Regards. "
                    ];
                    break;
                case 'job_card_material_requisition':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workshop.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'title' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'body' => "{$sender->name} has raised a request for workshop materials/services with reference
                               <strong>{$record->req_no}</strong>
                               in Fleet Master.
                               <br>To Take action immediately, click on the button below to login
                               .<br> Regards. "
                    ];
                    break;
                case 'fuel_requisition':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'title' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'body' => "Please be informed that {$sender->name} has raised a fuel request, with reference
                               <strong>{$record->req_no}</strong>
                               <br>To Take action immediately, click on the button below
                               .<br>Regards. "
                    ];
                    break;
                case 'workOrderCompleted':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workorder.closure', [
                            'ref' => $record->job_card_no
                        ]),
                        'identity' => $record->job_card_no,
                        'subject' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'title' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'body' => "Work-Order No. <strong>{$record->job_card_no}</strong> assigned to {$sender->name}
                        has been completed and submitted for your approval.
                               <br>To Take action immediately, click on the button below
                               .<br>Regards. "
                    ];
                    break;
                case 'job_card_closed':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('view.job.card', ['ref' => $record->job_card_no]),
                        'identity' => $record->job_card_no,
                        'subject' => "Attention::Job Card",
                        'title' => "Attention::Job Card",
                        'body' => "Job-Card No. <strong>{$record->job_card_no}</strong> has been closed succefully
                               <br>No Action Required
                               .<br>Regards. "
                    ];
                    break;
                case 'partiallyAuthorised':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record['req_no']]),
                        'identity' => $record['req_no'],
                        'subject' => "Requisition Approval Update",
                        'title' => "Requisition Approval Update",
                        'body' => "Please be informed that fuel requisition, Ref. No.
                        <strong>{$record['req_no']} you raised has been partially authorised
                        and submitted for further approval.
                        <br>To Take action immediately, click on the button below
                        .<br>Regards. "
                    ];
                    break;
                case 'fullyAuthorised':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record['req_no']]),
                        'identity' => $record['req_no'],
                        'subject' => "Requisition Update",
                        'title' => "Requisition Update",
                        'body' => "Please be informed that the fuel requisition
                                Ref. No. <strong>{$record['req_no']}</strong> has been fully Authorised.
                                Your SPMS requisition no. is {$record['spms_ref']}.
                               <br>
                               .<br>Regards. "
                    ];
                    break;
                case 'sendBack':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('edit.fuel.requisition', ['ref' => $record['req_no']]),
                        'identity' => $record['req_no'],
                        'subject' => "Requisition Approval Update",
                        'title' => "Requisition Approval Update",
                        'body' => "Please be informed that fuel requisition, Ref. No.
                        <strong>{$record['req_no']} you raised has been sent back.
                        The details can be viewed on the task.
                        <br>To Take action immediately, click on the button below
                        .<br>Regards. "
                    ];
                    break;
                case 'resubmitted':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'title' => self::NEW_TASK_NEEDS_YOUR_ATTENTION,
                        'body' => "Fuel request, with reference
                               <strong>{$record->req_no}</strong> has been resubmitted for your approval.
                               <br>To Take action immediately, click on the button below
                               .<br>Regards. "
                    ];
                    break;
                default:
                    return false;
            }

            Mail::to($to)
                ->bcc(config("mail.blindCarbonCopy"))
                ->send(new SendMail($details));
            Log::info('Email Sent ');
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
}
