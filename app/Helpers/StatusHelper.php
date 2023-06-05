<?php

namespace App\Helpers;

class StatusHelper
{
    public static function Submitted(): string
    {
        return '99';
    }

    public static function PendingVerification(): string
    {
        return "021";
    }

    public static function PendingGeneralDataEntry(): string
    {
        return "100";
    }

    public static function PendingTechnicalDataEntry(): string
    {
        return "101";
    }

    public static function PendingAccessoriesCheckin(): string
    {
        return "102";
    }

    public static function PendingCostingDataEntry(): string
    {
        return "103";
    }

    public static function PendingAssignment(): string
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
        return "01";
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

}
