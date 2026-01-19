<?php

namespace App\Console\Commands;

use App\Events\Tracking\GpsConnected;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Console\Command;
use KMLaravel\GeographicalCalculator\Facade\GeographicalCalculatorFacade;

class GpsTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gps-test';

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
//        $gps = Gps::where('imei', 353691845951614)->first();
//        GpsConnected::dispatch($gps);

       $gg =  GeographicalCalculatorFacade::initCoordinates(22, 33, 37, 40, ['units' => ['km']]);
       dd($gg->getDistance());

    }
}
