<?php

namespace App\Providers;

use App\Facades\Metrics;
use App\Telemetry\MetricsService;
use Illuminate\Support\ServiceProvider;

class MetricsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Metrics::CONTAINER_KEY, function () {
            return new MetricsService(config('app.name'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->terminating(function () {
            try {
                app(Metrics::CONTAINER_KEY)->forceFlush();
            } catch (\Throwable $e) {
                //
            }
        });
    }
}
