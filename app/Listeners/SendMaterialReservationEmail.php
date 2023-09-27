<?php

namespace App\Listeners;

use App\Events\MaterialReservationMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    }
}
