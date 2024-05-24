<?php

namespace RCare\System\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
// use RCare\System\Core;
// use RCare\System\Facades\Core as CoreFacade;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'System');

        // Validator::extend('slug', 'Webkul\System\Contracts\Validations\Slug@passes');

        // Validator::extend('code', 'Webkul\System\Contracts\Validations\Code@passes');

        // Validator::extend('decimal', 'Webkul\System\Contracts\Validations\Decimal@passes');

        $this->publishes([
            dirname(__DIR__) . '/Config/concord.php' => config_path('concord.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
    }
    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->make('RCare\System\Http\Controllers\TablePrintingController');
        // $loader = AliasLoader::getInstance();
        // $loader->alias('core', CoreFacade::class);

        // $this->app->singleton('core', function () {
        //     return app()->make(Core::class);
        // });
    }
}