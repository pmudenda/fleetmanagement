<?php

namespace App\Console\Commands;

use App\Models\Security\User;
use App\Models\VehicleManagement\RoadTax;
use App\Models\VehicleManagement\VehicleHeader;
use App\Notifications\RtsaAlertNotification;
use Illuminate\Console\Command;

class RtsaComplianceAlertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rtsa:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roadtaxes = Roadtax::where('is_compliant', 0)->get();

        if($roadtaxes->isEmpty()){
            return Command::SUCCESS;
        }

        $users = User::permission('rtsa_receive_alerts')->get();

        $complianceReport = $this->generateComplianceReport();

        $users->each(function($user) use ($complianceReport) {
            new RtsaAlertNotification($complianceReport);
        });
        dd($complianceReport);
    }

    private function generateComplianceReport()
    {
        $totalVehicles = VehicleHeader::count();
        $nonCompliant = RoadTax::nonCompliant()->with('vehicle')->get();
        $compliantCount = RoadTax::compliant()->count();
        $nonCompliantCount = $nonCompliant->count();

        // Status breakdown
        $statusBreakdown = $nonCompliant->groupBy('status')->map->count()->sortDesc();

        // Critical vehicles (expired or suspended)
        $criticalVehicles = RoadTax::where('is_compliant', false)
            ->where(function($query) {
                $query->where('valid_to', '<', now())
                    ->orWhere('fitness_expiry', '<', now())
                    ->orWhere('status', 'like', '%Suspended%');
            })
            ->orderBy('valid_to')
            ->limit(10)
            ->get();

        $expiredFitnessCount = RoadTax::where('fitness_expiry', '<', now())->count();
        $expiredTaxCount = RoadTax::where('valid_to', '<', now())->count();
        $suspendedCount = RoadTax::where('status', 'like', '%Suspended%')->count();

        return [
            'totalVehicles' => $totalVehicles,
            'compliantCount' => $compliantCount,
            'nonCompliantCount' => $nonCompliantCount,
            'statusBreakdown' => $statusBreakdown,
            'criticalVehicles' => $criticalVehicles,
            'expiredFitnessCount' => $expiredFitnessCount,
            'expiredTaxCount' => $expiredTaxCount,
            'suspendedCount' => $suspendedCount,
        ];
    }
}
