<?php

namespace RCare\Org\OrgPackages\PartnerApiDetails\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
class PartnerApiDetailsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'PartnerApiDetails'); 
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
