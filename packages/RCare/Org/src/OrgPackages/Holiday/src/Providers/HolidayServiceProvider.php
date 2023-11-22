<?php

namespace RCare\Org\OrgPackages\Holiday\src\Providers;  

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
    
class HolidayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void  
     */
    public function register()
    {
        $this->app->make('RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController');  
        // $this->app->make('RCare\Org\OrgPackages\Calender\src\Http\Controllers\Uploadcdf');   
        
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

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Holiday'); 
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
