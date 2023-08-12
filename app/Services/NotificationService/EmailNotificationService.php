<?php

namespace App\Services\NotificationService;


use App\Mail\SendMail;
use App\Models\Security\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailNotificationService
{
    public static function sendNotification(User $recipient, User $sender, $record, string $action, $task): bool
    {
        try {
            $recipientMail = config("mail.default_mail") ?? $recipient->email;
            $to[] = ['email' => $recipientMail, 'name' => $recipient->name];
            $names = $recipient->name;

            switch ($action) {
                case 'requisition':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workshop.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => "New Task Needs Your Attention",
                        'title' => "New Task Needs Your Attention",
                        'body' => "Please be informed that {$sender->name} has raised a request for spares, with reference
                               <strong>{$record->req_no}</strong>
                               in Fleet Master.
                               <br>Kindly click on the button below to login to ZFMS
                               and take action.<br> Regards. "
                    ];
                    break;
                case 'job_card_material_requisition':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workshop.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => "New Task Needs Your Attention",
                        'title' => "New Task Needs Your Attention",
                        'body' => "Please be informed that {$sender->name} has raised a request for spares with reference
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
                        'subject' => "New Task Needs Your Attention",
                        'title' => "New Task Needs Your Attention",
                        'body' => "Please be informed that {$sender->name} has raised a fuel request, with reference
                               <strong>{$record->req_no}</strong>
                               <br>To Take action immediately, click on the button below
                               .<br>Regards. "
                    ];
                    break;
                case 'workOrderCompleted':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.workorder.closure', ['ref' => $record->job_card_no]),
                        'identity' => $record->job_card_no,
                        'subject' => "New Task Needs Your Attention",
                        'title' => "New Task Needs Your Attention",
                        'body' => "Work-Order No. <strong>{$record->job_card_no}</strong> assigned to {$sender->name} has been completed and submitted
                                   for your approval.
                               <br>To Take action immediately, click on the button below
                               .<br>Regards. "
                    ];
                    break;
                case 'partiallyAuthorised':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => "Requisition Update",
                        'title' => "Requisition Update",
                        'body' => "Please be informed that fuel requisition, Ref. No.
                        <strong>{$record->req_no} has raised a partially authorised and submitted for further approval.
                        <br>To Take action immediately, click on the button below
                        .<br>Regards. "
                    ];
                    break;
                case 'fullyAuthorised':
                    $details = [
                        'name' => $names,
                        'systemLink' => URL::signedRoute('show.fuel.requisition', ['ref' => $record->req_no]),
                        'identity' => $record->req_no,
                        'subject' => "Requisition Update",
                        'title' => "Requisition Update",
                        'body' => "Please be informed that the fuel requisition
                                Ref. No. <strong>{$record->req_no}</strong> has been fully Authorised. Your SPMS requisition no. is {$record->spms_ref}.
                               <br>
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
