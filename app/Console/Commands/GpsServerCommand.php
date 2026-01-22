<?php

namespace App\Console\Commands;

use App\Enums\GpsStatus;
use App\Events\Tracking\CurrentLocationEvent;
use App\Events\Tracking\GpsConnected;
use App\Events\Tracking\GpsDisconnected;
use App\Models\VehicleManagement\Tracking\Gps;
use App\Models\VehicleManagement\Tracking\GpsLocation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use KMLaravel\GeographicalCalculator\Facade\GeographicalCalculatorFacade;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use Uro\TeltonikaFmParser\FmParser;
use Uro\TeltonikaFmParser\Protocol\Tcp\Reply;

class GpsServerCommand extends Command
{
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
    protected $description = 'GPS TCP listener for Teltonika devices';

    /**
     * IMEI-PREFIXED LOGGING HELPER.
     */
    private function gpsLog(string $level, ?string $imei, string $message, array $context = []): void
    {
        $prefix = $imei ? "[IMEI:{$imei}] " : '[IMEI:N/A] ';
        $context = array_merge(['imei' => $imei], $context);

        // NORMALIZE LEVEL TO A VALID LOGGER METHOD.
        $level = strtolower($level);
        if (!method_exists(Log::class, $level)) {
            $level = 'info';
        }

        Log::$level($prefix . $message, $context);
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // CAPTURE FATAL SHUTDOWNS THAT WOULD OTHERWISE "JUST TERMINATE".
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error) {
                Log::critical('[IMEI:N/A] FATAL SHUTDOWN', $error);
            }
        });

        $this->gpsLog('info', null, 'SERVER INIT: SETTING ALL DEVICES TO OFFLINE');

//        Gps::query()->update(['connected_at' => null]);

        $port = config('gps.port');
        $this->gpsLog('info', null, 'SERVER STARTING', ['port' => $port]);

        $server = new SocketServer(sprintf('0.0.0.0:%s', $port));
        $this->gpsLog('info', null, 'SERVER STARTED', ['port' => $port]);

        $self = $this;

        $server->on('connection', function (ConnectionInterface $connection) use ($self) {
            $gps = null;
            $imei = null;

            // CONNECTION IDENTIFIER HELPS WHEN IMEI NOT YET KNOWN.
            $connId = function_exists('spl_object_id') ? spl_object_id($connection) : null;
            $remote = (string) $connection->getRemoteAddress();

            $self->gpsLog('info', null, 'CONNECTION ATTEMPT', [
                'connection_id' => $connId,
                'remote' => $remote,
            ]);

            $connection->on('data', function ($data) use (&$gps, &$imei, $connection, $connId, $remote, $self) {
                // NOTE: DO NOT LIMIT TO Exception; MANY "CRASHES" ARE Throwable (TypeError, Error).
                try {
                    $parser = new FmParser('tcp');

                    // 1) DEVICE REGISTRATION (IMEI HANDSHAKE)
                    if (is_string($data) && strlen($data) === 17) {
                        $payload = $data;
                        $imei = $parser->decodeImei($payload)->getImei();

                        $self->gpsLog('info', $imei, 'REGISTRATION: IMEI RECEIVED', [
                            'connection_id' => $connId,
                            'remote' => $remote,
                        ]);

                        $self->gpsLog('info', $imei, 'REGISTRATION: LOOKUP DEVICE IN DB');

                        $gps = Gps::where('imei', $imei)
                            ->with('vehicle.engine')
                            ->where('status', GpsStatus::Active)
                            ->first();

                        if ($gps) {

                            Gps::find($imei)->update([
                                'connected_at' => now(),
                                'last_seen_at' => now(),
                            ]);

                            GpsConnected::dispatch($gps);

                            $self->gpsLog('info', $imei, 'REGISTRATION: ACCEPTED', [
                                'gps_id' => $gps->imei ?? null,
                                'reg_number' => $gps->vehicle->reg_number ?? null,
                            ]);

                            $connection->write(Reply::accept());
                        } else {
                            $self->gpsLog('warning', $imei, 'REGISTRATION: REJECTED (NOT ONBOARDED OR NOT ACTIVE)', [
                                'connection_id' => $connId,
                                'remote' => $remote,
                            ]);

                            $connection->write(Reply::reject());
                        }

                        return;
                    }

                    // 2) AVL PAYLOAD PROCESSING (ONLY AFTER GPS IS RESOLVED)
                    if (!$gps) {
                        $self->gpsLog('warning', $imei, 'DATA RECEIVED BEFORE REGISTRATION - IGNORING', [
                            'connection_id' => $connId,
                            'remote' => $remote,
                            'data_len' => is_string($data) ? strlen($data) : null,
                        ]);
                        return;
                    }

                    $payload = $data;
                    $packet = $parser->decodeData($payload);
//                    dd($packet);

                    $objects = $packet->getAvlDataCollection()->getAvlData();
                    $self->gpsLog('debug', $gps->imei, 'AVL PACKET RECEIVED', [
                        'objects_count' => count($objects),
                        'connection_id' => $connId,
                    ]);

                    $lastProcessedTime = null;

                    foreach ($objects as $idx => $avl) {
                        $timestamp = Carbon::createFromTimestamp($avl->getTimestamp() / 1000);
                        $gpsElement = $avl->getGpsElement();

                        $props = $avl->getIoElement()->getProperties();

                        // Convert to plain [id => number|string]
                        $io = [];
                        foreach ($props as $id => $prop) {
                            $id = $prop->getId();

                            // Most values are numeric; start with unsigned
                            $io[$id] = $prop->getValue()->toUnsigned();
//                            dd(get_class_methods($prop->getValue()));
//                            $bytes = $prop->getValue()->getBinaryValue(); // raw bytes string
//                            $io[(int)$id] = teltonika_decode_io($bytes);
                        }

                        // Example: odometer is in your dump as ID 16 (bytes "\x00\x04T\x1A")
                        $odometerMeters = $io[16] ?? null;   // 283674 in your sample (meters)

                        // Fuel: depends on your configuration/sensors; choose IDs you use
                        $fuel = $io[270] ?? $io[327] ?? null;

//                        dd($io);


                        $location = [
                            'latitude' => $gpsElement->getLatitude(),
                            'longitude' => $gpsElement->getLongitude(),
                            'altitude' => $gpsElement->getAltitude(),
                            'speed' => $gpsElement->getSpeed(),
                            'angle' => $gpsElement->getAngle(),
                            'tracked_at' => $timestamp,
                            'imei' => $gps->imei,
                            'reg_number' => $gps->reg_number ?? '--',
                        ];

                        $self->gpsLog('info', $gps->imei, 'GPS LOCATION RECEIVED', [
                            'index' => $idx,
                            'lat' => $location['latitude'],
                            'lon' => $location['longitude'],
                            'speed' => $location['speed'],
                            'angle' => $location['angle'],
                            'tracked_at' => $timestamp->toISOString(),
                        ]);

                        // --- REDIS LAST LOCATION
                        $redisKey = "last-location-{$gps->imei}";

                        $self->gpsLog('debug', $gps->imei, 'REDIS GET LAST LOCATION START', [
                            'key' => $redisKey,
                        ]);

                        $lastLocationRaw = Redis::client()->get($redisKey);

                        $self->gpsLog('debug', $gps->imei, 'REDIS LAST LOCATION RAW', [
                            'exists' => !empty($lastLocationRaw),
                            'raw_len' => is_string($lastLocationRaw) ? strlen($lastLocationRaw) : null,
                        ]);

                        $distanceKm = 0.0;
                        $lastLocation = null;

                        if (!empty($lastLocationRaw)) {
                            $lastLocation = json_decode($lastLocationRaw, true);

                            if (!is_array($lastLocation)) {
                                $self->gpsLog('warning', $gps->imei, 'REDIS LAST LOCATION INVALID JSON - IGNORING', [
                                    'raw' => $lastLocationRaw,
                                ]);
                                $lastLocation = null;
                            }
                        }

                        if ($lastLocation && isset($lastLocation['latitude'], $lastLocation['longitude'])) {
                            $self->gpsLog('debug', $gps->imei, 'DISTANCE CALC INPUT', [
                                'from' => [
                                    'lat' => $lastLocation['latitude'],
                                    'lon' => $lastLocation['longitude'],
                                    'odometer' => $lastLocation['odometer'] ?? null,
                                ],
                                'to' => [
                                    'lat' => $location['latitude'],
                                    'lon' => $location['longitude'],
                                ],
                            ]);

                            // IMPORTANT: THIS LIB EXPECTS LAT1, LAT2, LON1, LON2 (NOT LAT,LON,LAT,LON).
                            $distance = GeographicalCalculatorFacade::initCoordinates(
                                $lastLocation['latitude'],
                                $location['latitude'],
                                $lastLocation['longitude'],
                                $location['longitude'],
                                ['units' => ['km']]
                            )->getDistance();

                            $distanceKm = (float) ($distance['km'] ?? 0);

                            // ANOMALY GUARD: PREVENT POLLUTING ODOMETER/FUEL WITH JUMPS.
                            if ($distanceKm < 0 || $distanceKm > 2) {
                                $self->gpsLog('warning', $gps->imei, 'ANOMALY: UNREALISTIC DISTANCE - SKIPPING ODOMETER UPDATE', [
                                    'distance_km' => $distanceKm,
                                    'tracked_at' => $timestamp->toISOString(),
                                    'distance_raw' => $distance,
                                ]);
                                $distanceKm = 0.0;
                            }

                            $prevOdo = (float) ($lastLocation['odometer'] ?? 0);
                            $location['distance'] = $distanceKm;
                            $location['odometer'] = $prevOdo + $distanceKm;

                            $fuelConsumption = (float) ($gps->vehicle->engine->fuel_consumption ?? 0);
                            $location['fuel'] = $fuelConsumption > 0 ? ($distanceKm / $fuelConsumption) : 0;

                            $self->gpsLog('info', $gps->imei, 'DISTANCE CALC RESULT', [
                                'distance_km' => $distanceKm,
                                'prev_odometer_km' => $prevOdo,
                                'new_odometer_km' => $location['odometer'],
                                'fuel_used' => $location['fuel'],
                                'fuel_consumption_km_per_unit' => $fuelConsumption,
                            ]);
                        } else {
                            $location['distance'] = 0;
                            $location['odometer'] = (float) ($lastLocation['odometer'] ?? 0);
                            $location['fuel'] = 0;

                            $self->gpsLog('debug', $gps->imei, 'NO VALID LAST LOCATION - INITIALIZING METRICS', [
                                'odometer_km' => $location['odometer'],
                            ]);
                        }

                        // --- DB INSERT
                        $dbStart = microtime(true);
                        GpsLocation::create($location);

                        $self->gpsLog('info', $gps->imei, 'DB INSERT GPS LOCATION SUCCESS', [
                            'ms' => (int) ((microtime(true) - $dbStart) * 1000),
                        ]);

                        // --- REDIS UPDATE
                        $self->gpsLog('debug', $gps->imei, 'REDIS SET LAST LOCATION', [
                            'key' => $redisKey,
                        ]);

                        Redis::client()->set($redisKey, json_encode($location));

                        $self->gpsLog('debug', $gps->imei, 'REDIS SET LAST LOCATION COMPLETE', [
                            'key' => $redisKey,
                        ]);

                        CurrentLocationEvent::dispatch($location);
                        $self->gpsLog('info', $gps->imei, 'BROADCAST DISPATCHED CURRENT LOCATION');
                    }

                    Gps::find($imei)->update([
                        'last_seen_at' => $timestamp->toISOString(),
                    ]);
                    // TELTONIKA ACK
                    $connection->write(pack('N', count($objects)));

                    $self->gpsLog('debug', $gps->imei, 'AVL ACK SENT', [
                        'objects_count' => count($objects),
                    ]);

                } catch (\Throwable $e) {
                    $self->gpsLog('critical', $gps->imei ?? $imei, 'GPS SERVER CRASH WHILE PROCESSING DATA', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'connection_id' => $connId,
                        'remote' => $remote,
                    ]);
                }
            });

            $connection->on('error', function (\Throwable $e) use (&$gps, &$imei, $connId, $remote, $self) {
                $self->gpsLog('error', $gps->imei ?? $imei, 'CONNECTION ERROR', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'connection_id' => $connId,
                    'remote' => $remote,
                ]);

                if ($gps) {
                    $gps->connected_at = null;
                    $gps->save();
                    GpsDisconnected::dispatch($gps);
                    $self->gpsLog('warning', $gps->imei, 'DEVICE MARKED OFFLINE (ERROR)');
                }
            });

            $connection->on('close', function () use (&$gps, &$imei, $connId, $remote, $self) {
                $self->gpsLog('warning', $gps->imei ?? $imei, 'CONNECTION CLOSED', [
                    'connection_id' => $connId,
                    'remote' => $remote,
                ]);

                if ($gps) {
                    $gps->connected_at = null;
                    $gps->save();
                    // OPTIONAL: DISPATCH IF YOU WANT IT ON NORMAL CLOSES TOO.
                    // GpsDisconnected::dispatch($gps);

                    $self->gpsLog('warning', $gps->imei, 'DEVICE MARKED OFFLINE (CLOSE)');
                }
            });
        });

        $server->on('error', function (\Throwable $e) use ($self) {
            $self->gpsLog('critical', null, 'SERVER ERROR', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        });
    }
}
