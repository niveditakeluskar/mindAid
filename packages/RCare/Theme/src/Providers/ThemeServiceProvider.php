<?php

namespace RCare\Theme\Providers;

use Illuminate\Support\ServiceProvider;
    
class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('RCare\Theme\Http\Controllers\UsersController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // include __DIR__.'/../Http/routes.php';
    }
}
