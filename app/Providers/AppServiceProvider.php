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
use App\Services\VehicleManagement\VehicleDetailsServiceImpl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OnBoardingService::class, OnBoardingService::class);
        $this->app->bind(FileUploadService::class, FileUploadService::class);
        $this->app->bind(ActivityLogsService::class, ActivityLogsService::class);
        //$this->app->bind(VehicleDetailsService::class, VehicleDetailsServiceImpl::class);
        $this->app->bind(InsuranceService::class, InsuranceServiceImpl::class);
        $this->app->bind(FitnessService::class, FitnessServiceImpl::class);
        $this->app->bind(RoadTaxService::class, RoadTaxServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        Schema::defaultStringLength(191);
    }
}
