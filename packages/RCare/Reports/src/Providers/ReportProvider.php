<?php

namespace RCare\Reports\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ReportProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('RCare\Reports\Http\Controllers\ReportController'); 
    }

    /**
     * Bootstrap services.
     * 
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Reports'); 
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
