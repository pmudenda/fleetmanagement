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

            $recipientMail = 'mchitala@zesco.co.zm';//$recipient->email
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
                               <br>To Take action immediately, click on the button below to login
                               .<br> Regards. "
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
