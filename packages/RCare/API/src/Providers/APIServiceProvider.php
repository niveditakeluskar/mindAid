<?php

namespace RCare\API\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
    
class APIServiceProvider extends ServiceProvider
{ 
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('RCare\API\Http\Controllers\APIController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'API'); 
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
    }
}
 
