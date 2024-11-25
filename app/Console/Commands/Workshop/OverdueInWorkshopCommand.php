<?php

namespace App\Console\Commands\Workshop;

use App\Models\WorkShopManagement\Mechanic;
use App\Notifications\Vehicle\VehicleOverIssuedNotification;
use App\Notifications\Workshop\OverdueInWorkshopNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OverdueInWorkshopCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workshop:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {

        $overdue_vehicles = DB::table("VEHICLE_IN_WORKSHOP_OVER_90_DAYS")->get()
            ->groupBy('workshop_code');

        foreach ($overdue_vehicles as $code => $vehicles) {
            $vs = $vehicles->map(function ($vehicle) {
                $vehicle->date_in = Carbon::createFromFormat('Y-m-d H:m:s', $vehicle->date_in)->toDateString();
                $vehicle->expected_date_out = Carbon::createFromFormat('Y-m-d H:m:s', $vehicle->expected_date_out)->toDateString();
           return $vehicle;
            });
            $mechanics = Mechanic::whereHas('workshops', function (Builder $query) use ($code) {
                    $query->where('workshop_code', $code);
                    $query->where("mechanic_workshop.is_supervisor", 1);
                })
                ->whereNotNull('email')
                ->get();

//            $mechanics->each->notify(new OverdueInWorkshopNotification($vs));

            $emails = collect([
                'gilbertsibajene@zesco.co.zm',
                'MChitala@zesco.co.zm',
                'cnamposya@zesco.co.zm',
                'vsingogo@zesco.co.zm',
                'SEChanda@zesco.co.zm',
                'LKamuya@zesco.co.zm',
                'csikazwe@zesco.co.zm'
            ]);

                $emails->each(function ($email) use ($vs) {
                    Notification::route('mail', $email)
                        ->notify(new OverdueInWorkshopNotification($vs));

                });

        }
    }
}
