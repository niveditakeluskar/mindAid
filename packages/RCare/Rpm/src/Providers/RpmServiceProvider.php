<?php

namespace RCare\Rpm\Providers;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;


class RpmServiceProvider extends ServiceProvider
{
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    //    $this->app->bind(
    //        'Illuminate\Contracts\Auth\Registrar',
    //        'App\Services\Registrar'
    //    );
        // $this->app->make('RCare\Rpm\src\Http\Controllers\MailTemplateController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // $this->publishes([
        //     __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        // ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Rpm');
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }
}
