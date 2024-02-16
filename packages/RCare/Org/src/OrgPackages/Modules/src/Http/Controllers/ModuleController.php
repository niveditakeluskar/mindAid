<?php
namespace RCare\Org\OrgPackages\Modules\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Modules\src\Http\Requests\ComponentAddRequest;
use RCare\Org\OrgPackages\Modules\src\Http\Requests\ModuleAddRequest;
use RCare\Org\OrgPackages\Modules\src\Http\Requests\ModuleUpdateRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\Modules\src\Models\RolesModule;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use DataTables;

class ModuleController extends Controller {
    public function index() {    
        return view('Modules::module-list');
    }

    public function getModuleId($name) { 
        $module_name       = sanitizeVariable($name);
        $check_module_name = \DB::connection('ren_core')->select("SELECT * FROM ren_core.modules WHERE LOWER(module) ='".strtolower($module_name)."'");
        return (count($check_module_name) > 0 ? $check_module_name[0]->id : 0 );
    }

    public function componentindex() {
        $module = Module::where('status', 1)->get();
        return view('Modules::component-list',compact('module'));
    }

    public function moduleList(Request $request) {
        if ($request->ajax()) {
            $data = Module::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1) {
                    $btn = $btn. '<a href="javascript:void(0)" class="changeModuleStatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                } else {
                    $btn = $btn.'<a href="javascript:void(0)" class="changeModuleStatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Roles::module-list');
    }

    public function componentList(Request $request) {
        if ($request->ajax()) {
            $data = ModuleComponents::with('module','users')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit_comp" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->status == 1){
                               $btn = $btn. '<a href="javascript:void(0)" class="changeComponentStatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="javascript:void(0)" class="changeComponentStatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Roles::component-list');
    }

    public function AddModule(ModuleAddRequest $request) {
        $data = array(
            'module'     => sanitizeVariable($request->module),
            'status'     => '1',
            'updated_by' => session()->get('userid'),
            'created_by' => session()->get('userid'),
        );
        Module::create($data);
    }

    public function AddComponent(ComponentAddRequest $request) {
        $data =array(
                'module_id'  => sanitizeVariable($request->module_id),
                'components' => sanitizeVariable($request->components),
                'status'     => '1', 
                'updated_by' => session()->get('userid'),
                'created_by' => session()->get('userid'),
            );
        ModuleComponents::create($data);
    }

    public function AccessMatrix(){
        $data       =  Roles::all();
	    $sdata      = ModuleComponents::all();
        $adata      = RolesModule::all();
        $moduledata = Module::all();
        return view('Modules::org-access-matrix', compact('data','sdata','adata','moduledata'));
    }

    public function AssignModule(Request $request) {
        $role             = sanitizeVariable($request->role_id);
		$module           = sanitizeVariable($request->module_id);
		$components       = sanitizeVariable($request->components_id);
		$create           = sanitizeVariable($request->create);
		$read             = sanitizeVariable($request->read);
		$update           = sanitizeVariable($request->update);
        $delete           = sanitizeVariable($request->delete);
        $role_array       = json_decode($role, true);
        $module_array     = json_decode($module, true);
        $components_array = json_decode($components, true);
        $create_array     = json_decode($create, true);
		$read_array       = json_decode($read, true);
		$update_array     = json_decode($update, true);
        $delete_array     = json_decode($delete, true);
        
		foreach($role_array as $key => $value){
			    if (RolesModule::where('role_id', $value['value'])->where('components_id',$components_array[$key]['value'])->exists()) {
                    $data = array(
                        'role_id' => $value['value'],
                        'module_id' => $module_array[$key]['value'],
                        'components_id' => $components_array[$key]['value'],
                        'crud' => $create_array[$key]['value'].$read_array[$key]['value'].$update_array[$key]['value'].$delete_array[$key]['value'],
                        'status' =>'1',
                        'updated_by'=> session()->get('userid')
                    );
				    $user1 = RolesModule::where('role_id', $value['value'])->where('components_id',$components_array[$key]['value'])->update($data);
			    } else {
                    $data = array(
                        'role_id' => $value['value'],
                        'module_id' => $module_array[$key]['value'],
                        'components_id' => $components_array[$key]['value'],
                        'crud' => $create_array[$key]['value'].$read_array[$key]['value'].$update_array[$key]['value'].$delete_array[$key]['value'],
                        'status' =>'1',
                        'created_by'=> session()->get('userid')
                    );
				    $user = RolesModule::create($data);
                }
            }
        	return back()->with('success','Role Assign successfully!');
    }

    public function editModule($id) {
        $id   = sanitizeVariable($id);
        $user = Module::find($id);
        return response()->json($user);
    }

    public function updateModule(ModuleUpdateRequest $request) {
        $id     = sanitizeVariable($request->id);
        $update = [
                    'module' => sanitizeVariable($request->module),
                    'updated_by'=> session()->get('userid')
                ];
        Module::where('id',$id)->update($update);
        return back()->with('success','Module updated successfully!');
    }

    public function editComponent($id) {
        $id   = sanitizeVariable($id);
        $user = ModuleComponents::find($id);
        return response()->json($user);
    }

    public function updateComponents(ComponentAddRequest $request) {
        $id     = sanitizeVariable($request->id);
        $update = [
                    'module_id'  => sanitizeVariable($request->module_id), 
                    'components' => sanitizeVariable($request->components), 
                    'updated_by' => session()->get('userid')
                ];
        ModuleComponents::where('id',$id)->update($update);
        return back()->with('success','Components updated successfully!');
    }

    public function changeModuleStatus(Request $request) {
        $id     = sanitizeVariable($request->id);
        $data   = Module::where('id',$id)->get();
        $status = $data[0]->status;
        if($status==1) {
            $status       = array('status'=>0, 'updated_by' =>session()->get('userid'));
            $update_query = Module::where('id',$id)->orderBy('id', 'desc')->update($status);
        } else {
            $status       = array('status'=>1, 'updated_by' =>session()->get('userid'));
            $update_query = Module::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }

    public function changeComponentStatus(Request $request) {
        $id     = sanitizeVariable($request->id);
        $data   = ModuleComponents::where('id',$id)->get();
        $status = $data[0]->status;
        if($status==1){
            $status       = array('status'=>0, 'updated_by' =>session()->get('userid'));
            $update_query = ModuleComponents::where('id',$id)->orderBy('id', 'desc')->update($status);
        } else {
            $status       = array('status'=>1, 'updated_by' =>session()->get('userid'));
            $update_query = ModuleComponents::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }

    public function moduleSubModules($id) { 
        $id         = sanitizeVariable($id);
        $subModules = [];
        $subModules = ModuleComponents::all()->where("module_id", $id)->where("status", 1)->where("type", '<>', 's');
         //$subModules = ModuleComponents::where("module_id", $id)->where("status", 1)->whereNull('type')->orderBy('components','asc')->get();
        return response()->json($subModules);
	}

    public function under() { 
        return view('Modules::component-list');
    }
}   