<?php

namespace RCare\Org\OrgPackages\ReportsMaster\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
class ReportsMasterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ReportsMaster'); 
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');
    }
}
