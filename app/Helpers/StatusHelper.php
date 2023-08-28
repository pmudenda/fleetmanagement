<?php

namespace App\Helpers;
class StatusHelper
{
    public static function submitted(): string
    {
        return '99';
    }

    public static function pendingVerification(): string
    {
        return "021";
    }

    public static function pendingGeneralDataEntry(): string
    {
        return "100";
    }

    public static function pendingTechnicalDataEntry(): string
    {
        return "101";
    }

    public static function pendingAccessoriesCheckin(): string
    {
        return "102";
    }

    public static function pendingCostingDataEntry(): string
    {
        return "103";
    }

    public static function pendingAssignment(): string
    {
        return "104";
    }

    public static function active(): string
    {
        return "01";
    }

    public static function approved(): string
    {
        return "02";
    }

    public static function new(): string
    {
        return "01";
    }

    public static function pendingApproval(): string
    {
        return "100";
    }

    public static function authorised(): string
    {
        return "02";
    }

    public static function partiallyAuthorised(): string
    {
        return "21";
    }

    public static function partiallyReleased(): string
    {
        return "26";
    }

    public static function partiallyReleasedExpired(): string
    {
        return "42";
    }

    public static function onboardingComplete(): string
    {
        return "030";
    }

    public static function organizationStructureActive(): string
    {
        return "00";
    }

    public static function activeUser(): string
    {
        return '01';
    }

    public static function cancelled(): string
    {
        return "45";
    }

    public static function closed(): string
    {
        return "08";
    }

    public static function rejected(): string
    {
        return "03";
    }

    public static function released(): string
    {
        return "39";
    }

    /* VEHICLE STATUS */

    public static function vehicleActive(): string
    {
        return "01";
    }

    public static function vehicleInactive(): string
    {
        return "02";
    }

    public static function vehicleHandedOver(): string
    {
        return "03";
    }

    public static function vehicleGrounded(): string
    {
        return "04";
    }

    public static function vehicleInWorkshop(): string
    {
        return "05";
    }

    public static function vehicleScrap(): string
    {
        return "06";
    }

    public static function vehicleStolen(): string
    {
        return "07";
    }

    public static function vehicleDisposed(): string
    {
        return "08";
    }


    public static function vehiclePendingDisposal(): string
    {
        return "09";
    }

    public static function vehicleSalvage(): string
    {
        return "10";
    }

    public static function vehicleReRegistered(): string
    {
        return "11";
    }

    public static function inactive(): string
    {
        return "02";
    }

    public static function issued(): string
    {
        return "04";
    }


}
