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
    public static function store($request, $action, $action_type, $comment): void
    {
        try {
            //BROWSER
            if (Browser::isMobile()) {
                $device_type = "Mobile";
            }
            if (Browser::isTablet()) {
                $device_type = "Tablet";
            }
            if (Browser::isDesktop()) {
                $device_type = "Desktop / Laptop";
            }
            if (Browser::isBot()) {
                $device_type = "Bot";
            }
            $device = Browser::deviceFamily();
            $os = Browser::platformName();
            $os_version = Browser::platformVersion();
            $browser = Browser::browserName();
            $browser_version = Browser::browserVersion();

            //get ip
            $ip_address = $request->getClientIp();
            //GEO DATA
            $location = GeoIP::getLocation($ip_address);

            //CREATE THE LOG
            ActivityLogsModel::create(
                [
                'user_id' => $request->user()->id,
                'staff_no' => $request->user()->staff_no,
                'staff_profile' => $request->user()->profile,
                'username' => $request->user()->name,
                'user_email' => $request->user()->email,

                'ip_address' => $ip_address,
                'request_method' => $request->method(),
                'request_params' => json_encode($request->all()),
                'route_url' => $request->url(),
                'previous_url' => $request->session()->previousUrl(),

                'action_name' => $action,
                'action_type' => $action_type,
                'comment' => $comment,
                'device' => $device,
                'device_type' => $device_type,
                'os' => $os,
                'os_version' => $os_version,
                'browser' => $browser,
                'browser_version' => $browser_version,

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
