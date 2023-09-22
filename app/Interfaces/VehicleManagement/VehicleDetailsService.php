<?php

namespace App\Interfaces\VehicleManagement;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface VehicleDetailsService
{
    function getAllVehicles(): LengthAwarePaginator;

    function getVehicleByReg(mixed $ref);

    function getAllVehiclesByStatus(array $array): Collection;

    function getVehicleDetails($ref): object|null;

    function getVehicleDocuments(mixed $reference);

    function getBasicVehicleDetails(mixed $vehicleRegistration): object|null;

    function getVehicleImages(mixed $reference);

    function getSubmittedAccessories($vehicleHeaderId): Collection;

    function getFilteredVehiclesInformation(Request $request): LengthAwarePaginator;
    function getCheckInsurance(mixed $registrationNumber): array;
}
