<?php

namespace App\Services\Logging;

use App\Models\ActivityLogsModel;
use App\Services\LoggingServices\SystemErrorModel;
use Exception;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Torann\GeoIP\Facades\GeoIP;

class ActivityLogsService
{
    public static function store($request, $action, $actionType, $comment): void
    {
        try {
            //BROWSER
            if (Browser::isMobile()) {
                $deviceType = "Mobile";
            }
            if (Browser::isTablet()) {
                $deviceType = "Tablet";
            }
            if (Browser::isDesktop()) {
                $deviceType = "Desktop / Laptop";
            }
            if (Browser::isBot()) {
                $deviceType = "Bot";
            }

            $device = Browser::deviceFamily();
            $os = Browser::platformName();
            $osVersion = Browser::platformVersion();
            $browser = Browser::browserName();
            $browserVersion = Browser::browserVersion();
            $ipAddress = $request->getClientIp();
            $location = GeoIP::getLocation($ipAddress);

            ActivityLogsModel::create([
                'user_id' => $request->user()->id,
                'staff_no' => $request->user()->staff_no,
                'staff_profile' => $request->user()->profile,
                'username' => $request->user()->name,
                'user_email' => $request->user()->email,
                'ip_address' => $ipAddress,
                'request_method' => $request->method(),
                'request_params' => json_encode($request->all()),
                'route_url' => $request->url(),
                'previous_url' => $request->session()->previousUrl(),
                'action_name' => $action,
                'action_type' => $actionType,
                'comment' => $comment,
                'device' => $device,
                'device_type' => $deviceType,
                'os' => $os,
                'os_version' => $osVersion,
                'browser' => $browser,
                'browser_version' => $browserVersion,
                'iso_code' => $location->iso_code,
                'country' => $location->country,
                'city' => $location->city,
                'state' => $location->state,
                'state_name' => $location->state_name,
                'postal_code' => $location->postal_code,
                'latitude' => $location->lat,
                'longitude' => $location->lon,
                'timezone' => $location->timezone,
                'continent' => $location->continent,
                'currency' => $location->currency,
                'value' => $location->iso_code,
            ]);

        } catch (Exception $e) {
            //save system errors
            Log::error($e);
        }

    }
}
