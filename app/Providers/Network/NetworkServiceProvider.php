<?php

namespace App\Providers\Network;

use Illuminate\Support\ServiceProvider;
use App\Services\Network\Contracts\RouterInterface;
use App\Services\Network\Contracts\NetworkScannerInterface;
use App\Services\Network\MikroTikApiService;
use App\Services\Network\NetworkDetectionService;

class NetworkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            RouterInterface::class,
            MikroTikApiService::class
        );

        $this->app->singleton(
            NetworkScannerInterface::class,
            NetworkDetectionService::class
        );
    }

    public function boot(): void
    {
        //
    }
}