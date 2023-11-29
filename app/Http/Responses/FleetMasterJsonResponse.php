<?php

namespace App\Http\Responses;

class FleetMasterJsonResponse
{
    /**
     * @param $state
     * @param $success
     * @param $message
     * @param null $payload
     * @param null $redirectUrl
     * @return array
     */
    public static function response($state, $success, $message, $payload = null, $redirectUrl = null): array
    {
        return [
            'state' => $state,
            'success' => $success,
            'message' => $message ?? "",
            'payload' => $payload ?? [],
            'redirectUrl' => $redirectUrl ?? "",
        ];
    }


}
