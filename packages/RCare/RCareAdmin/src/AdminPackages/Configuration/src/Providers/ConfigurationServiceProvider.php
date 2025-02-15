<?php

namespace RCare\RCareAdmin\AdminPackages\Configuration\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
    
class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController');
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

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Configuration');
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
