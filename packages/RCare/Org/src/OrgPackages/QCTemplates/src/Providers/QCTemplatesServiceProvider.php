<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class QCTemplatesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       // $this->app->bind(
       //     'Illuminate\Contracts\Auth\Registrar',
          //  'App\Services\Registrar'
    //  //   );
    //     $this->app->register('RCare\Org\OrgPackages\Roles\src\Providers\UserRolesServiceProvider');
    //     $this->app->register('RCare\Org\OrgPackages\Users\src\Providers\UserServiceProvider');
    //     $this->app->register('RCare\Org\OrgPackages\Modules\src\Providers\ModuleServiceProvider');
    //     $this->app->register('RCare\Org\OrgPackages\Menus\src\Providers\MenuServiceProvider');


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // include __DIR__.'/../Http/routes.php';
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'QCTemplates');
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
    }
}
