<?php

namespace RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\RCareAdmin\AdminPackages\Services\src\Models\Services;
use RCare\RCareAdmin\AdminPackages\UserRoles\src\Models\UserRoles;
use RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DataTables;


class AccessMetricesController extends Controller
{
    //
    public function index()
    {
	$data = UserRoles::all();
	$sdata = Services::all();
	$adata = Access::all();
        return view('AccessMetrics::access-metrices', compact('data','sdata','adata'));
    }


	 public function usersRolesAssign()
        {
		//$data = Roles::all();
   		//return view('usersRolesAssign', compact(['data']));
        }
	
	 public function AssignRoles(Request $request)
	{
		$role = $request->role;
		$service = $request->service;
		$create = $request->create;
		$read = $request->read;
		$update = $request->update;
		$delete = $request->delete;

		foreach($role as $key => $value){
        	   $data = array(
            		'role_id' => $value,
            		'service_id' => $service[$key],
			'crud' => $create[$key].$read[$key].$update[$key].$delete[$key],
			'status' =>'1'
        		);

			if (Access::where('role_id', $value)->where('service_id',$service[$key])->exists()) {
				$user1 = Access::where('role_id', $value)->where('service_id',$service[$key])->update($data);
			}else{
				$user = Access::create($data);
			}
    		}
		//dd($data);
		//$user = AssignRoles::create($data));
        	return back()->with('success','Role Assign successfully!');


	}


}
