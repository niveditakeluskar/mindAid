<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Session;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //       
    }

    /**
     * Bootstrap services.
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

		Blade::include("System::component.table", "table");
		
        Blade::if('feature', function ($module, $component, $feature) {			
			$roleId = Session::get('role') ;
            $userRights = DB::connection('ren_core')->select("select rm.*, m.module, mc.components from role_modules rm, modules m, module_components mc where rm.module_id = m.id 
			and m.id = mc.module_id
			and mc.id = rm.components_id and rm.role_id ='".$roleId."' and crud !='' ");
			$userRightsArr = array();
			foreach($userRights as $rights){
				//echo "CRUD: ".str_replace(' ','_', strtolower($rights->crud))."<br/>";
				//echo "Module: ".str_replace(' ','_', strtolower($rights->module_id))."<br/>";
				//echo "Component: ".str_replace(' ','_', strtolower($rights->components_id))."<br/>";
				$userRightsArr[$rights->module_id][$rights->components_id] = $rights->crud;
				
			}
			//print_r($userRights);
			/*if($userRightsArr[$module][$component] contains $feature){*/
			/*echo "<pre>";
			print_r($userRightsArr);
			echo array_key_exists(7,$userRightsArr);
			echo "compo".array_key_exists(7, $userRightsArr[9]);
			*/
			if(array_key_exists($module,$userRightsArr) && array_key_exists($component, $userRightsArr[$module]) && strpos($userRightsArr[$module][$component], $feature)!== false){
				//echo "allowed";
				return true;
			}
			else{
				//echo "not allowed";
				return false;
			}
			//$newArr = array_shift(9, $userRightsArr);
			/*if (strpos($userRightsArr[$module][$component], $feature) !== false) {
				return true;
			}
			else{
				return false;
			}
			*/
			
			
			
        });
    }
}
