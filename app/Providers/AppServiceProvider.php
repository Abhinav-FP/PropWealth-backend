<?php

namespace App\Providers;

use App\Services\TwilioService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TwilioService::class, function ($app) {
            return new TwilioService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register view namespaces for published vendor views
        View::addNamespace('authentication', resource_path('views/vendor/authentication'));
        View::addNamespace('adminpanel', resource_path('views/vendor/adminpanel'));
        

    }
}
