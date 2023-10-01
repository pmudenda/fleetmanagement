<?php

namespace App\Listeners;

use App\Events\PettyCashRaised;
use App\Services\WorkShopManagement\ImprestBuyService;

class PostPettyCash
{
    private ImprestBuyService $imprestBuyService;

    /**
     * Create the event listener.
     * @param ImprestBuyService $imprestBuyService
     */
    public function __construct(ImprestBuyService $imprestBuyService)
    {
        //
        $this->imprestBuyService = $imprestBuyService;
    }

    /**
     * Handle the event.
     */
    public function handle(PettyCashRaised $event): void
    {
        $this->imprestBuyService->postToPettyCashSystem($event->reference, $event->staff);
    }
}
