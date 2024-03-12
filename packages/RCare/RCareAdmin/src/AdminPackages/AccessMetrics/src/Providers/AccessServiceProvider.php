<?php

namespace RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;


class AccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Http\Controllers\AccessMetricesController');
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // include __DIR__.'/../Http/routes.php';
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // $this->publishes([
        //     __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        // ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'AccessMetrics');
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
