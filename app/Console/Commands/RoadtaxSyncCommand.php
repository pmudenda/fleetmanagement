<?php

namespace App\Console\Commands;

use App\Models\VehicleManagement\RoadTax;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\progress;

class RoadtaxSyncCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roadtax:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command triggers the sync for vehicles to check their road tax status';

    /**
     * Execute the console command.
     */
    public function handle() {
        $vehicles = VehicleHeader::whereNotIn('status', ['08'])
            ->whereRelation('statusInfo', 'module', 'VEH')
//            ->whereIn('registration_number', ["BLA 4 ZM", "BBA 2 ZM"])
            ->get();
        progress(
            label: 'Syncing Road Tax',
            steps: $vehicles,
            callback: fn($vehicle) => $this->check($vehicle),
        );
    }

    private function check(VehicleHeader $vehicle) {
        $token = Cache::remember('rtsa-token', 30 * 60, function () {
            $response = Http::rtsa()->post('auth/authenticate', [
                'username' => config('services.rtsa.username'),
                'password' => config('services.rtsa.password'),
            ]);
            if ($response->successful()) {
                return $response->object()->token;
            }
            return null;
        });
       try{
           $response = Http::rtsa()
               ->withHeader('Authorization', $token)
               ->get('vehiclestatus', [
                   'registrationMark' => str_replace(' ', '', strtoupper($vehicle->registration_number))
//                'registrationMark' => "BLA4ZM"
               ]);

           if ($response->successful()) {
               $data = $response->object()->body;
               if ($response->object()->code == 200) {
                   RoadTax::updateOrCreate([
                       'reg_no' => $vehicle->registration_number,
                   ],
                       [
                           'licence_no' => $vehicle->registration_number,
                           'valid_from' => Carbon::parse($data->firstRegDate)->toDateString(),
                           'valid_to' => Carbon::parse($data->currentLicenseExpiryDate)->toDateString(),
                           'cost' => 0,
                           'payment_date' => Carbon::parse($data->firstRegDate)->toDateString(),
//                        'ORDER_NO' => $data->order_no,
                       ]
                   );
               }
               Log::info(sprintf("%s - %s", $vehicle->registration_number, $response->body()));
           }
       }catch(Exception $requestException){
           Log::error($requestException->getMessage());
       }
    }

}
