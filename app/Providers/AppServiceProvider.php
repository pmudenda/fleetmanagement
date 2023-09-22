<?php

namespace App\Providers;


use App\Interfaces\VehicleManagement\VehicleDetailsService;
use App\Services\FileUploads\FileUploadService;
use App\Services\Logging\ActivityLogsService;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
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
        $this->app->bind(VehicleDetailsService::class, VehicleDetailsServiceImpl::class);
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
