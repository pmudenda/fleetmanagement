<?php

namespace App\Console;

use App\Console\Commands\RoadtaxSyncCommand;
use App\Services\Integration\ProcurementSystemIntegrationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            ProcurementSystemIntegrationService::updateRequisitions();
        })->everyTenMinutes();

        $schedule->command('auth:clear-resets')
            ->everyFifteenMinutes();

        $schedule->command(RoadtaxSyncCommand::class)->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require_once base_path('routes/console.php');
    }
}
