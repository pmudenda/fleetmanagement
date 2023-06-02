<?php

namespace App\Providers;

use App\Models\general\SystemError;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SystemSettingsServiceProvider extends ServiceProvider
{

    function boot(): void
    {
        //dynamic system constants
        try {
            /*$systemSettings = Permission::all();
            foreach ($systemSettings as $setting) {
                config(['settings.' . $setting->slug => $setting->slug]);
            }*/

            // load application error messages
            $systemErrorMessages = SystemError::all();
            foreach ($systemErrorMessages as $setting) {
                config(['error_message.' . $setting->error_code => $setting->error_message]);
            }

            // load application settings
            config([
                'settings' => [
                    'fuel_requisition_validity' => 7
                ]
            ]);
        } catch (\Exception $e) {
           Log::error('Loading System Config and Error Messages');
        }
    }

    public function register()
    {

    }

}
