<?php

namespace RCare\RCareAdmin\AdminPackages\License\src\Providers;

use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
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
          $this->app->make('RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController');
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

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'License');
        $this->loadViewsFrom(__DIR__ . '/../../../../../Theme/src/Resources/views', 'Theme');


    }
}
