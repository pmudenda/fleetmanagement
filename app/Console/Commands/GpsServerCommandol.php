<?php

namespace App\Console\Commands;

use App\Enums\GpsStatus;
use App\Events\Tracking\CurrentLocationEvent;
use App\Events\Tracking\GpsConnected;
use App\Events\Tracking\GpsDisconnected;
use App\Models\VehicleManagement\Tracking\Gps;
use App\Models\VehicleManagement\Tracking\GpsLocation;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use KMLaravel\GeographicalCalculator\Facade\GeographicalCalculatorFacade;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use Uro\TeltonikaFmParser\FmParser;
use Uro\TeltonikaFmParser\Protocol\Tcp\Reply;

class GpsServerCommandol extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gps:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void {
        Log::info('Setting all devices last connected status to offline');

        Gps::query()->update([
            'connected_at' => null
        ]);
        Log::info('Starting GPS server');

        $server = new SocketServer(sprintf("0.0.0.0:%s", config('gps.port')));
        Log::info('GPS Service has started on port {port}', ['port' => config('gps.port')]);

        $server->on('connection', function (ConnectionInterface $connection) {
            $gps = null;
            Log::info(sprintf("%s is attempting to connected", $connection->getRemoteAddress()));

            $connection->on('data', function ($data) use (&$gps, $connection) {

                try {
                    $parser = new FmParser('tcp');
                    if ($gps) {
                        $payload = $data;
                        $packet = $parser->decodeData($payload);

                        $objects = $packet->getAvlDataCollection()->getAvlData();
                        $lastProcessedTime = null;

                        foreach ($objects as $object) {
                            $timestamp = Carbon::createFromTimestamp($object->getTimestamp() / 1000);
                            $object = $object->getGpsElement();

                            $location = [
                                'latitude' => $object->getLatitude(),
                                'longitude' => $object->getLongitude(),
                                'altitude' => $object->getAltitude(),
                                'speed' => $object->getSpeed(),
                                'angle' => $object->getAngle(),
                                'tracked_at' => $timestamp,
                                'imei' => $gps->imei
                            ];
                            Log::info("GPS Location Received", compact('location'));
                            Log::info("Attempting to retrieve last location for {$gps->imei}");
                            $lastLocation = Redis::client()->get("last-location-{$gps->imei}");
                            Log::info('Last location: ' . $lastLocation ?? 'No last location found');

                            if ($lastLocation) {
                                $lastLocation = json_decode($lastLocation, true);
                                $distance = GeographicalCalculatorFacade::initCoordinates(
                                    $lastLocation['latitude'],
                                    $location['latitude'],
                                    $lastLocation['longitude'],
                                    $location['longitude'],
                                    ['units' => ['km']])->getDistance();

                                $location['distance'] = $distance['km'];
                                $location['odometer'] = ($lastLocation['odometer'] ?? 0) + $distance['km'];
                                $location['fuel'] =$distance['km'] / $gps->vehicle->engine->fuel_consumption;
                            } else {
                                $location['odometer'] = 0;
                                $location['fuel'] = 0;
                                $location['distance'] = 0;
                            }

                            Log::info('GPS DISTANCE CALCULATION', [
                                'imei' => $gps->imei,
                                'from' => [
                                    'latitude'  => $lastLocation['latitude'] ?? null,
                                    'longitude' => $lastLocation['longitude'] ?? null,
                                    'odometer'  => $lastLocation['odometer'] ?? null,
                                ],
                                'to' => [
                                    'latitude'  => $location['latitude'],
                                    'longitude' => $location['longitude'],
                                ],
                                'calculation' => [
                                    'distance_km' => $location['distance'],
                                    'new_odometer_km' => $location['odometer'],
                                    'fuel_used' => $location['fuel'],
                                    'fuel_consumption_km_per_unit' => $gps->vehicle->engine->fuel_consumption,
                                ],
                                'tracked_at' => $location['tracked_at'],
                            ]);



                            GpsLocation::create($location);
                            Log::info("GPS Location Created");

                            Log::info('Saving Last GPS Location');
                            Redis::client()->set("last-location-{$gps->imei}", json_encode($location));
                            Log::info('Last GPS Connection Created');

                            if (is_null($lastProcessedTime)) {
                                Log::debug('FIRST LOCATION IN SESSION - DISPATCHING');
                                CurrentLocationEvent::dispatch($location);
                                $lastProcessedTime = $timestamp;
                            } elseif ($lastProcessedTime->diffInSeconds($timestamp) >= config('services.pusher.gps.interval')) {
                                Log::debug('DISPATCHING LOCATION', [
                                    'diff_seconds' => $lastProcessedTime->diffInSeconds($timestamp),
                                    'interval' => config('services.pusher.gps.interval'),
                                ]);
                                CurrentLocationEvent::dispatch($location);
                                $lastProcessedTime = $timestamp;
                            }

                        }

                        $connection->write(pack('N', count($packet->getAvlDataCollection()->getAvlData())));

                    }

                    if (strlen($data) == 17) {
                        $payload = $data;
                        $imei = $parser->decodeImei($payload)->getImei();
                        Log::info("{$imei} has connected successfully, attempting to retrieve device information from database", compact('imei'));
                        $gps = Gps::where('imei', $imei)
                            ->with('vehicle.engine')
                            ->where('status', GpsStatus::Active)->first();
//                    dd($gps);

                        if ($gps) {

                            Log::info("{$imei} registration complete, device attached to vehicle reg {$gps->reg_number}");
                            $gps->connected_at = now();
                            $gps->save();
                            GpsConnected::dispatch($gps);

                            Log::info("{$imei} has successfully registered", compact('imei'));
                            $connection->write(Reply::accept());

                        } else {
                            Log::info("{$imei} has has been rejected, please check if onboarded or active on the system", compact('imei'));

                            $connection->write(Reply::reject());
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error('GPS SERVER CRASH', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }

            });

            $connection->on('error', function (Exception $e) use (&$gps) {
                Log::error("There was an error: ".$e->getMessage());
                if ($gps) {
                    $gps->connected_at = null;
                    $gps->save();
                    GpsDisconnected::dispatch($gps);
                }

            });

            $connection->on('close', function () use (&$gps) {
                if ($gps) {
                    Log::error("{imei} has disconnected", ['imei' => $gps->imei ?? '']);
                    $gps->connected_at = null;
                    $gps->save();
//                    GpsDisconnected::dispatch($gps);
                }
            });
        });

        $server->on('error', function (Exception $e) {
            Log::error($e->getMessage());
        });

//        while(true){}
    }

}
