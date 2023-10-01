<?php

namespace App\Helpers;
class StatusHelper
{
    public static function pendingVerification(): string
    {
        return config('status.pendingVerification', '021');
    }

    public static function pendingGeneralDataEntry(): string
    {
        return config('status.pendingGeneralDataEntry', '100');
    }

    public static function pendingTechnicalDataEntry(): string
    {
        return config('status.pendingTechnicalDataEntry', '101');
    }

    public static function pendingAccessoriesCheckin(): string
    {
        return config('status.pendingAccessoriesCheckin', '102');
    }

    public static function pendingCostingDataEntry(): string
    {
        return config('status.pendingCostingDataEntry', '103');
    }

    public static function pendingAssignment(): string
    {
        return config('status.pendingAssignment', '104');
    }

    public static function active(): string
    {
        return config('status.active', '01');
    }

    public static function approved(): string
    {
        return config('status.approved', '02');
    }

    public static function new(): string
    {
        return config('status.new', '01');
    }

    public static function pendingApproval(): string
    {
        return config('status.pendingApproval', '100');
    }

    public static function authorised(): string
    {
        return config('status.authorised', '02');
    }

    public static function partiallyAuthorised(): string
    {
        return config('status.partially_authorised', '21');
    }

    public static function partiallyReleased(): string
    {
        return config('status.partially_released', '26');
    }

    public static function partiallyReleasedExpired(): string
    {
        return config('status.partially_released_expired', '42');
    }

    public static function onboardingComplete(): string
    {
        return config('status.onboarding_complete', '030');
    }

    public static function organizationStructureActive(): string
    {
        return config('status.organization_structure_active', '00');
    }

    public static function activeUser(): string
    {
        return config('status.active_user', '01');
    }

    public static function cancelled(): string
    {
        return config('status.cancelled', '45');
    }

    public static function closed(): string
    {
        return config('status.closed', '08');
    }

    public static function rejected(): string
    {
        return config('status.rejected', '03');
    }

    public static function released(): string
    {
        return config('status.released', '39');
    }

    public static function fullyReleased(): string
    {
        return config('status.fully_released', '32');
    }

    /* VEHICLE STATUS */

    public static function inactive(): string
    {
        return config('status.inactive', "02");
    }

    public static function issued(): string
    {
        return config('status.issued', "04");
    }

    public static function partiallyReleasedCancelled(): string
    {
        return config('status.partially_released_cancelled', '46');
    }

    public static function resubmitted(): string
    {
        return config('status.resubmitted', '99');
    }

    public static function activeArticle(): string
    {
        return config('status.active_article', '11');
    }


}
