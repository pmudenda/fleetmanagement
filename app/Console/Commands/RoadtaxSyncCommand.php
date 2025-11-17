<?php

namespace App\Console\Commands;

use App\Models\VehicleManagement\RoadTax;
use App\Models\VehicleManagement\VehicleHeader;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadtaxSyncCommand extends Command {
    protected $signature = 'roadtax:sync {reg?} {--batch-size=100} {--delay=100}';
    protected $description = 'This command triggers the sync for vehicles to check their road tax status';

    public function handle() {
        $reg_no = $this->argument('reg');
        $batchSize = $this->option('batch-size');
        $delay = $this->option('delay');

        $vehicles = VehicleHeader::whereNotIn('status', ['08', '10', '07'])
            ->whereRelation('statusInfo', 'module', 'VEH')
            ->whereNotIn('body_type_code', ['37',
                '27',
                '24',
                '30',
                '11',
                '32',
                '26',
                '25',
                '23',
                '22',
                '21',
                '10',
                '12',
                '42'])
            ->when($reg_no, function ($query, $reg_no) {
                $query->where('registration_number', $reg_no);
            })
            ->whereDoesntHave('roadTax',function ($query){
                $query->whereDate('updated_at',now()->toDateString());
            })
            ->get();

        Log::channel('rtsa')->info('Starting road tax sync', [
            'total_vehicles' => $vehicles->count(),
            'specific_vehicle' => $reg_no ?? 'all',
            'batch_size' => $batchSize,
            'delay_ms' => $delay
        ]);

        $token = $this->getTokenWithRefresh();
        if (!$token) {
            Log::channel('rtsa')->error('Failed to obtain initial RTSA token - sync aborted');
            $this->error('Failed to obtain RTSA token');
            return 1;
        }

        $successCount = 0;
        $failCount = 0;
        $notFoundCount = 0;
        $timeoutCount = 0;

        // Create progress bar for real-time visibility
        $progressBar = $this->output->createProgressBar($vehicles->count());
        $progressBar->start();

        $processed = 0;
        foreach ($vehicles as $vehicle) {
            $result = $this->check($vehicle, $token, $delay);

            if ($result === 'success') $successCount++;
            elseif ($result === 'not_found') $notFoundCount++;
            elseif ($result === 'timeout') $timeoutCount++;
            else $failCount++;

            $processed++;
            $progressBar->advance();

            // Refresh token every batch to prevent timeout issues
            if ($processed % $batchSize === 0) {
                $progressBar->display(); // Ensure progress is shown before token refresh
                $this->info(" Refreshing token...");

                $token = $this->getTokenWithRefresh();
                if (!$token) {
                    Log::channel('rtsa')->error('Failed to refresh token during sync');
                    break;
                }
            }

            // Small delay between requests to avoid overwhelming the API
            if ($delay > 0) {
                usleep($delay * 1000);
            }
        }

        $progressBar->finish();
        $this->newLine(2); // Add some space after progress bar

        Log::channel('rtsa')->info('Road tax sync completed', [
            'successful' => $successCount,
            'not_found' => $notFoundCount,
            'timeouts' => $timeoutCount,
            'other_failures' => $failCount,
            'total' => $vehicles->count()
        ]);

        $this->info("Sync completed: {$successCount} successful, {$notFoundCount} not found, {$timeoutCount} timeouts, {$failCount} other failures");
        return 0;
    }

    private function getTokenWithRefresh(): ?string {
        return Cache::remember('rtsa-token', 25 * 60, function () {
            Log::channel('rtsa')->info('Requesting new RTSA token');

            $response = Http::rtsa()
                ->timeout(15) // Shorter timeout for auth
                ->retry(2, 100) // Retry twice with 100ms delay
                ->post('auth/authenticate', [
                    'username' => config('services.rtsa.username'),
                    'password' => config('services.rtsa.password'),
                ]);

            if ($response->successful()) {
                Log::channel('rtsa')->info('Successfully obtained new RTSA token');
                return $response->object()->token;
            }

            Log::channel('rtsa')->error('Failed to obtain RTSA token', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;
        });
    }

    private function check(VehicleHeader $vehicle, string &$token, int $delay): string {
        $cleanReg = str_replace(' ', '', strtoupper($vehicle->registration_number));

        try {
            // First attempt with original registration
            $response = $this->makeVehicleRequest($cleanReg, $token);
            $responseData = $response->successful() ? $response->object() : null;

            // If vehicle not found and doesn't have ZM, try with ZM suffix
            if ($this->isVehicleNotFound($responseData) && !str_ends_with($cleanReg, 'ZM')) {
                $zmReg = $cleanReg . 'ZM';
                Log::channel('rtsa')->info('Vehicle not found, retrying with ZM suffix', [
                    'original_reg' => $cleanReg,
                    'retry_reg' => $zmReg
                ]);

                // Small delay before retry
                if ($delay > 0) {
                    usleep($delay * 1000);
                }

                $response = $this->makeVehicleRequest($zmReg, $token);
                $responseData = $response->successful() ? $response->object() : null;
            }

            if ($response->successful() && !$this->isVehicleNotFound($responseData)) {
                $this->processVehicleData($vehicle, $response);
                Log::channel('rtsa')->debug('Vehicle data synced successfully', [
                    'registration' => $cleanReg,
                    'response_code' => $responseData->code ?? 'unknown',
                    'response_message' => $responseData->registrationStatus ?? 'unknown'
                ]);
                return 'success';
            }

            // Log not found vehicles
            if ($this->isVehicleNotFound($responseData)) {
                Log::channel('rtsa')->warning('Vehicle not found in RTSA system', [
                    'registration' => $cleanReg,
                    'attempted_variations' => !str_ends_with($cleanReg, 'ZM') ? [$cleanReg, $cleanReg . 'ZM'] : [$cleanReg],
                    'error_message' => $responseData->errorMessage ?? 'Unknown error'
                ]);
                return 'not_found';
            }

            // Handle timeouts specifically
            if ($response->failed() && $this->isTimeoutError($response)) {
                Log::channel('rtsa')->error('Vehicle request timeout', [
                    'registration' => $cleanReg,
                    'status' => $response->status()
                ]);
                return 'timeout';
            }

            Log::channel('rtsa')->warning('Vehicle sync failed', [
                'registration' => $cleanReg,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return 'failure';

        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'timeout') ||
                str_contains($e->getMessage(), 'Connection timed out')) {
                Log::channel('rtsa')->error('Vehicle request timeout exception', [
                    'registration' => $cleanReg,
                    'error' => $e->getMessage()
                ]);
                return 'timeout';
            }

            Log::channel('rtsa')->error('Vehicle sync exception', [
                'registration' => $cleanReg,
                'error' => $e->getMessage()
            ]);
            return 'failure';
        }
    }

    private function makeVehicleRequest(string $registration, string &$token) {
        $response = Http::rtsa()
            ->timeout(25) // Increased but reasonable timeout
            ->retry(1, 2000) // Retry once after 2 seconds for transient issues
            ->withHeader('Authorization', $token)
            ->get('vehiclestatus', [
                'registrationMark' => $registration
            ]);

        // Handle token expiry
        if ($response->status() === 401) {
            Log::channel('rtsa')->warning('Token expired, refreshing...');
            $token = $this->getTokenWithRefresh();

            if (!$token) {
                throw new Exception('Failed to refresh expired token');
            }

            // Retry with new token
            $response = Http::rtsa()
                ->timeout(25)
                ->retry(1, 2000)
                ->withHeader('Authorization', $token)
                ->get('vehiclestatus', [
                    'registrationMark' => $registration
                ]);
        }

        return $response;
    }

    private function    isVehicleNotFound($responseData): bool {
        // Check if the response indicates vehicle not found
        return $responseData &&
            isset($responseData->errorType) &&
            $responseData->errorType === 'E' &&
            isset($responseData->errorMessage) &&
            str_contains($responseData->errorMessage, 'Vehicle record was not found');
    }

    private function isTimeoutError($response): bool {
        return $response->status() === 408 || // Request Timeout
            $response->status() === 504 || // Gateway Timeout
            $response->status() === 499;   // Client Closed Request (often used for timeouts)
    }

    private function processVehicleData(VehicleHeader $vehicle, $response) {
        $data = $response->object()->body;

        $compliantStatuses = [
            'Registered Permanent - Customs paid',
            'Registered Permanent',
            'Valid',
            'Active'
            // Add other statuses that indicate compliance
        ];

        $isCompliant = in_array($data->registrationStatus, $compliantStatuses);

        RoadTax::updateOrCreate(
            ['reg_no' => $vehicle->registration_number],
            [
                'licence_no' => $vehicle->registration_number,
                'valid_from' => Carbon::parse($data->firstRegDate)->toDateString(),
                'valid_to' => Carbon::parse($data->currentLicenseExpiryDate)->toDateString(),
                'fitness_expiry' => Carbon::parse($data->roadWorthinessExpiryDate)->toDateString(),
                'cost' => 0,
                'payment_date' => Carbon::parse($data->firstRegDate)->toDateString(),
                'status' => $data->registrationStatus ?? 'Status not available',
                'is_compliant' => $isCompliant
            ]
        );
    }
}