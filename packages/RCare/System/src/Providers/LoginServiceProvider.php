<?php

namespace RCare\System\Providers;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LoginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
       // $this->app->make('RCare\System\src\Http\Controllers\Auth');

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
        
        // config file
        //  $this->publishes([
        //     dirname(__DIR__) . '/Config/auth.php' => config_path('auth.php'),
        // ]);

        // $this->publishes([
        //     __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        // ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views/auth', 'System');
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
    }
}
