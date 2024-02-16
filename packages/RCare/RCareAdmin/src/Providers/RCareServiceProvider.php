<?php

namespace RCare\RCareAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
    
class RCareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
        
        // $this->app->register('RCare\RCareAdmin\AdminPackages\Login\src\Providers\LoginProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\Users\src\Providers\UsersServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\UserRoles\src\Providers\UserRolesServiceProvider'); 
        $this->app->register('RCare\RCareAdmin\AdminPackages\Services\src\Providers\ServicesProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\Test\src\Providers\TestServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\Organization\src\Providers\OrgServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\License\src\Providers\LicenseServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\Menu\src\Providers\MenuServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Providers\AccessServiceProvider');
        $this->app->register('RCare\RCareAdmin\AdminPackages\Configuration\src\Providers\ConfigurationServiceProvider');
        //$this->app->register('RCare\RCareAdmin\AdminPackages\Protocol\src\Providers\ProtocolServiceProvider');
        // $this->app->register('RCare\RCareAdmin\AdminPackages\Scheduler\src\Providers\SchedulerServiceProvider');    
        // $this->app->register('RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Providers\AccessMetricsServiceProvider');
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

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'RCareAdmin');
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
    }
}
