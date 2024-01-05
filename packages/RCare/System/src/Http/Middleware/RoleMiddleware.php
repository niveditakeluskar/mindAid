<?php

namespace RCare\System\Http\Middleware;

use Closure;
use DB;
use Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		//print_r($request->session());
		if($request->session()->has('role')){
			$roleId = $request->session()->get('role');	
		//	echo $roleId;
			$userAccessQuery = DB::connection('ren_core')->select("select menu_url, mm.module_id, mm.component_id from menu_master mm, role_modules rm where mm.module_id = rm.module_id and rm.role_id ='".$roleId."' and mm.menu_url='".$request->path()."' and mm.component_id = rm.components_id and lower(rm.crud) like  '%' || mm.operation || '%'");
			
//echo "select menu_url, mm.module_id, mm.component_id from menu_master mm, role_modules rm where mm.module_id = rm.module_id and rm.role_id ='".$roleId."' and mm.menu_url='".$request->path()."' and mm.component_id = rm.components_id and lower(rm.crud) like  '%' || mm.operation || '%'";
           				
			$userAccess_arr =[];
			$i = 0;
			foreach ($userAccessQuery as $userAccess) {
				$userAccess_arr[$i]['menu_url']   = $userAccess->menu_url;
				session()->put(["module_id"=>$userAccess->module_id, "component_id"=>$userAccess->component_id]);
				$i++;
			}
			// var_dump($userAccess_arr);
			if(!empty($userAccess_arr)) {
				
				
			} else {
				// return '<script>alert("access");</script>';
				$url = url('').'/access-denied';
				// print '<script>window.location.href="/access-denied";</script>';
				return redirect($url);
				// return false;
				//return redirect()->route('access-denied');
			}
		
		}
        
        return $next($request);
    }
}
