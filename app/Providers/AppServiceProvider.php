<?php

namespace App\Providers;


use App\Interfaces\VehicleManagement\FitnessService;
use App\Interfaces\VehicleManagement\InsuranceService;
use App\Interfaces\VehicleManagement\RoadTaxService;
use App\Interfaces\VehicleManagement\VehicleDetailsService;
use App\Services\FileUploads\FileUploadService;
use App\Services\Logging\ActivityLogsService;
use App\Services\VehicleManagement\FitnessServiceImpl;
use App\Services\VehicleManagement\InsuranceServiceImpl;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use App\Services\VehicleManagement\RoadTaxServiceImpl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(OnBoardingService::class, OnBoardingService::class);
        $this->app->bind(FileUploadService::class, FileUploadService::class);
        $this->app->bind(ActivityLogsService::class, ActivityLogsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
//        dd('I am here');
            URL::forceScheme('https');


        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        Schema::defaultStringLength(191);
    }
}
