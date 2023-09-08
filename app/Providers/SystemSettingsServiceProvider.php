<?php

namespace App\Providers;

use App\Models\Common\SystemError;
use App\Models\Settings\SystemConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SystemSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {

            $systemErrorMessages = SystemError::all();
            foreach ($systemErrorMessages as $setting) {
                config(['error_message.' . $setting->error_code => $setting->error_message]);
            }

            $settings = SystemConfig::where('status', 1);
            foreach ($settings as $setting) {
                if ($setting->data_type == 'bool') {
                    config([$setting->config_file_name . $setting->name => (bool)$setting->value]);
                } elseif ($setting->data_type == 'string') {
                    config([$setting->config_file_name . $setting->name => $setting->value]);
                }
            }

            config([
                'settings' => [
                    'fuel_requisition_validity' => 7
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Loading System Config and Error Messages');
        }
    }

}
