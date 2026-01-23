<?php

namespace App\Console\Commands;

use App\Enums\GpsStatus;
use App\Events\Tracking\CurrentLocationEvent;
use App\Events\Tracking\GpsConnected;
use App\Events\Tracking\GpsDisconnected;
use App\Helpers\StatusHelper;
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
     * Build secondary icon indicators (Font Awesome class names + titles).
     * Keep these compact; the UI can choose to render them as icons with tooltips.
     */

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

                        // --- SIGNALS (single source of truth)
                        $self->computeSignals($location, $gps);

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

    private function buildSignalIcons(
        array $flags,
        float $speed,
        float $speedLimit
    ): array {
        $icons = [];

        if (!empty($flags['rtsa_infraction'])) {
            $icons[] = [
                'key'   => 'rtsa_infraction',
                'icon'  => 'fas fa-file-alt',
                'title' => 'RTSA non-compliant',
                'class' => 'text-danger',
            ];
        }

        if (!empty($flags['overspeed'])) {
            $icons[] = [
                'key'   => 'overspeed',
                'icon'  => 'fas fa-tachometer-alt',
                'title' => sprintf('Over-speed (%.0f / %.0f km/h)', $speed, $speedLimit),
                'class' => 'text-danger',
            ];
        }

        if (!empty($flags['maintenance_moving'])) {
            $icons[] = [
                'key'   => 'maintenance_moving',
                'icon'  => 'fas fa-tools',
                'title' => 'Moving in maintenance',
                'class' => 'text-warning',
            ];
        }

        // --- Reserved / future flags ---

        if (!empty($flags['stale'])) {
            $icons[] = [
                'key'   => 'stale',
                'icon'  => 'fas fa-clock',
                'title' => 'Stale location',
                'class' => 'text-muted',
            ];
        }

        if (!empty($flags['offline'])) {
            $icons[] = [
                'key'   => 'offline',
                'icon'  => 'fas fa-plug',
                'title' => 'Device offline',
                'class' => 'text-danger', // change to 'text-warning' if you want it louder
            ];
        }

        return $icons;
    }


    /**
     * Compute signal state for the current location payload.
     *
     * DESIGN: "worst-state-wins" for primary severity + primary text, while also attaching
     * secondary flags/icons so the UI can show multiple active conditions without needing
     * to recompute anything.
     */
    private function computeSignals(array &$location, ?Gps $gps): void
    {
        // Default (unknown)
        $signals = [
            'severity' => 'green',
            'primary' => 'okay',
            'flags' => [
                'rtsa_infraction' => false,
                'overspeed' => false,
                'maintenance_moving' => false,
                'moving' => false,
                // Reserved for later:
                'offline' => false,
                'stale' => false,
            ],
            'icons' => [],
        ];

        if (!$gps) {
            $location['signals'] = $signals;
            return;
        }

        $speed = (float) data_get($location, 'speed', 0);
        $isMoving = $speed > config('gps.speed_limit.min', 5);

        $signals['flags']['moving'] = $isMoving;

        // Overspeed threshold (allow per-vehicle override; fallback to 100 km/h)
        $speedLimit = (float)config('gps.speed_limit.max', 100);
        $overspeed = $speed > $speedLimit;
        $signals['flags']['overspeed'] = $overspeed;

        // Maintenance/workshop status (tolerant to different status encodings)
        $vehicleStatus = data_get($gps, 'vehicle.status');
        $inMaintenance = $this->isInMaintenanceStatus($vehicleStatus);
        $maintenanceMoving = $inMaintenance && $isMoving;
        $signals['flags']['maintenance_moving'] = $maintenanceMoving;

        // Compliance (RTSA / road tax)
        $isCompliant = $this->resolveRoadTaxCompliance($gps);
        $rtsaInfraction = ($isCompliant === false);
        $signals['flags']['rtsa_infraction'] = $rtsaInfraction;

        // --- Primary signal selection (worst-state-wins)
        if ($rtsaInfraction) {
            $signals['severity'] = 'red';
            $signals['primary'] = 'RTSA non-compliant';
        } elseif ($overspeed) {
            $signals['severity'] = 'red';
            $signals['primary'] = sprintf('Speeding %.0f km/h', $speed);
        } elseif ($maintenanceMoving) {
            $signals['severity'] = 'amber';
            $signals['primary'] = 'Moving in maintenance';
        } elseif (!$isMoving) {
            $signals['severity'] = 'gray';
            $signals['primary'] = 'Idle';
        } else {
            $signals['severity'] = 'green';
            $signals['primary'] = 'Moving';
        }

        // --- Secondary indicators (icons)
        $signals['icons'] = $this->buildSignalIcons($signals['flags'], $speed, $speedLimit);

        $location['signals'] = $signals;
    }

    /**
     * Resolve road tax / RTSA compliance.
     *
     * This is intentionally tolerant: it supports either a "roadTax" relation on GPS
     * or a "roadTax" relation on vehicle. If nothing is available, returns null.
     */
    private function resolveRoadTaxCompliance(Gps $gps): ?bool
    {
        try {
            if (method_exists($gps, 'roadTax')) {
                $roadTax = $gps->vehicle->roadTax; // may lazy-load
                if ($roadTax) {
                    return (bool) (data_get($roadTax, 'is_compliant') ?? false);
                }
            }
        } catch (\Throwable $e) {
            // Ignore relation resolution issues; treat as unknown.
        }

        try {
            $vehicle = $gps->vehicle;
            if ($vehicle && method_exists($vehicle, 'roadTax')) {
                $roadTax = $vehicle->roadTax; // may lazy-load
                if ($roadTax) {
                    return (bool) (data_get($roadTax, 'is_compliant') ?? false);
                }
            }
        } catch (\Throwable $e) {
            // Ignore relation resolution issues; treat as unknown.
        }

        return null;
    }

    /**
     * Normalize "maintenance/workshop" status checks without coupling to a specific enum implementation.
     */
    private function isInMaintenanceStatus($status): bool
    {
        if ($status === null) {
            return false;
        }
        return $status == '05';
    }


}
