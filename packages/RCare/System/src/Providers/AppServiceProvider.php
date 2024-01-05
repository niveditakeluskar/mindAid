<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive("css", function($expression) {
            return "<link rel='stylesheet' href='/css/<?php echo $expression ?>.css'/>";
        });
        Blade::directive("js", function($expression) {
            return "<script src='/js/<?php echo $expression ?>.js'></script>";
        });
        Blade::directive("divider", function($expression) {
            return "<div class='row'><div class='col-12'><hr></div></div>";
        });
        Blade::directive("header", function($expression) {
            $expression = substr($expression, 1, strlen($expression) - 2);
            return "<div class='row'><div class='col-12 text-center'><h3>$expression</h3></div></div>";
        });

        Blade::include("component.table", "table");

        Blade::if("admin", function() {
            return Auth::check() && Auth::user()->is_admin;
        });
		
		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
