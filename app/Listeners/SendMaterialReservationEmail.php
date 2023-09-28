<?php

namespace App\Listeners;

use App\Events\MaterialReservationMade;
use Illuminate\Support\Facades\Log;

class SendMaterialReservationEmail
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
    public function handle(MaterialReservationMade $event): void
    {
        //send email
        Log::debug("Request Type " . $event->requestType);
    }
}
