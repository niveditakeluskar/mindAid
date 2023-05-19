<?php

namespace RCare\System\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
	
	// protected function authenticate($request, array $guards)
	// {
	// 	//dd($this->auth->guard('renCore_user')->check());
	// 	if($this->auth->guard('rcare_user')->check()){
	// 		//return route('rcare-login');
	// 		//$request->path = 'dashboard';
	// 		//dd($this->auth->guard('renCore_user')->check());
	// 		 return route(Route::currentRouteName());
			
	// 	}
	// 	elseif($this->auth->guard('renCore_user')->check()){
	// 		//$request->path = 'dashboard';
	// 		//dd($this->auth->guard('renCore_user')->check());
	// 		 return route(Route::currentRouteName());
	// 	}
	// 	else{
	// 		//echo url('').'/';
	// 		//header("Location: ".url('').'/rcare-login'); 
	// 		//dd($this->auth->guard('renCore_user')->check());
	// 		return redirect()->guest('auth/login');
	// 		//die;
	// 		// return redirect()->guest("rcare-login");
	// 		//return response('Unauthorized.', 401);
	// 		//dd($this->auth->guard('renCore_user')->check());
	// 		//return route('rcare-login');
	// 	}
		
	// }

	protected function authenticate($request, array $guards)
	{
		
		$rcare_user = $this->auth->guard('rcare_user')->check();
		$renCore_user =$this->auth->guard('renCore_user')->check();
		// dd($renCore_user);
		if($rcare_user==true){
				
			//return route(Route::currentRouteName());
			//return redirect('/home');
		}else if ($renCore_user==true) {
			// return route(Route::currentRouteName());
			//return redirect('/home');
		}
		else{
			$url = url('').'/rcare-login';
			
			echo '<script>window.location.href="/rcare-login";</script>';
		}
		
	}



    protected function redirectTo($request)
    {
		// dd($request);
        return route(Route::currentRouteName());
    }
	
		
}
