<?php

namespace App\Providers;

use App\Models\Security\Permission;
use App\Models\Security\Role;
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

            // load application settings
            config([
                'settings' => [
                    'fuel_requisition_validity' => 7
                ]
            ]);
        } catch (\Exception $e) {
            //ignored
        }
    }

    public function register()
    {

    }

}
