<?php

namespace RCare\RCareAdmin\AdminPackages\Organization\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;


class OrgServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
       {
        $this->app->make('RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController');
       }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //

         // include __DIR__.'/../Http/routes.php';
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // $this->publishes([
        //     __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        // ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Organization');
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');


    }
}
