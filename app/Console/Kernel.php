<?php

namespace App\Console;

use App\Console\Commands\JobCardLinkCommand;
use App\Console\Commands\RoadtaxSyncCommand;
use App\Console\Commands\Vehicle\VehicleOverIssuedCommand;
use App\Console\Commands\Workshop\OverdueInWorkshopCommand;
use App\Notifications\Workshop\OverdueInWorkshopNotification;
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

        $schedule->command(JobCardLinkCommand::class)->everyMinute();
        $schedule->command(OverdueInWorkshopCommand::class)->dailyAt('07:00');
        $schedule->command(VehicleOverIssuedCommand::class)->hourly();
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
