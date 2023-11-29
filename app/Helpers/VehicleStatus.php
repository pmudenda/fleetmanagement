<?php

namespace App\Helpers;

class VehicleStatus
{
    public static function vehicleScrap(): string
    {
        return config('status.vehicleScrap', '06');
    }

    public static function vehicleStolen(): string
    {
        return config('status.vehicleStolen', '07');
    }

    public static function vehicleDisposed(): string
    {
        return config('status.vehicleDisposed', '08');
    }


    public static function vehiclePendingDisposal(): string
    {
        return config('status.vehicle_pending_disposal', '09');
    }

    public static function vehicleSalvage(): string
    {
        return config('status.vehicle_salvage', '10');
    }

    public static function vehicleReRegistered(): string
    {
        return config('status.vehicle_re_registered', '11');
    }

    public static function vehicleHandedOver(): string
    {
        return config('status.pending_general_data_entry', '03');
    }

    public static function vehicleGrounded(): string
    {
        return config('status.vehicle_grounded', '04');
    }

    public static function vehicleActive(): string
    {
        return config('status.vehicle_active', '01');
    }

    public static function vehicleInactive(): string
    {
        return config('status.vehicle_inactive', '02');
    }

    public static function vehicleInWorkshop(): string
    {
        return config('status.vehicle_in_workshop', '05');
    }
}
