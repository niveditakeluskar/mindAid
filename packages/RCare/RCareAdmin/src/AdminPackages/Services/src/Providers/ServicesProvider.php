<?php

namespace RCare\RCareAdmin\AdminPackages\Services\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Services');
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
