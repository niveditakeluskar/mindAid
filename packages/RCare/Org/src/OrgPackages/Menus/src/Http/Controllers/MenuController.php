<?php

namespace RCare\Org\OrgPackages\Menus\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Menus\src\Http\Requests\MenuAddRequest;
use RCare\Org\OrgPackages\Menus\src\Http\Requests\MenuUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RCare\Org\OrgPackages\Menus\src\Models\OrgMenus;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use DataTables;
use Validator;
use Session;

class MenuController extends Controller {
    public function index() {
		// $service = Module::all();
		// $component = ModuleComponents::all();

        $service = Module::orderBy('module','asc')->get(); 
        $component = ModuleComponents::orderBy('components','asc')->get();
        $menus = OrgMenus::where('menu','!=','')->whereNotNull('menu')->orderBy('menu','asc')->get(); // where('parent','=',0)->
        return view('Menus::org-menu-list', compact('service','menus','component'));
        
	}
	
	public function createMenu(MenuAddRequest $request)
    {
        $data = array(
            'menu' => sanitizeVariable($request->menu),
            'menu_url' => sanitizeVariable($request->menu_url),
			'module_id' => sanitizeVariable($request->service_id),
			'component_id' => sanitizeVariable($request->component_id),
            'icon' => sanitizeVariable($request->icon),
            'parent' => sanitizeVariable($request->parent),
            'status' => sanitizeVariable($request->status),
            'sequence' => sanitizeVariable($request->sequence),
            'operation' => sanitizeVariable($request->operation),
            'updated_by'=> session()->get('userid'),
            'created_by'=> session()->get('userid'),
        );
		
        
        $menu = OrgMenus::create($data);
		
     // return "success";
      // return back()->with('success','Menu created successfully!');
        //Session::put('success','Menu created successfully!'); 
        //return redirect()->back();
    }


    public function fetchMenu(Request $request)
    {
		if ($request->ajax()) {
			//$data = Menus::latest()->get()->where('status',1);
			//$data = Menus::latest()->get();
			$data = OrgMenus::with('module','components','mnu','users')->get()->sortByDesc('updated_at');
			return Datatables::of($data)
				->addIndexColumn() 
				->addColumn('action', function($row){

				$btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-original-title="Edit"  class="editMenu" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
				// $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteMenu" title="Delete"><i class="i-Close error"></i></a>';
                if($row->status == 1){
            // $btn = $btn. '<a href="changePracticeStatus/'.$row->id.'"><i class="i-Yess i-Yes medicationstatus" title="Active"></i></a>';
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_status_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
        } else {
            // $btn = $btn.'<a href="changePracticeStatus/'.$row->id.'" class="medstatus"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_status_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
        }
				return $btn;
				})
				->rawColumns(['action'])
				->make(true);
		}  
        return view('org_menu_list');
	}
	 
	public function GetComponent(Request $request){
		$value = ModuleComponents::where('module_id',sanitizeVariable($request->module))->orderBy('components','asc')->get();
            foreach($value as $key){
                echo "<option value='".$key->id."'>".$key->components."</option>";
            }

	}

     public function getActiveMenuList() {
        $MenuList = [];
        $MenuList = OrgMenus::activeMenu();
        return response()->json($MenuList);
    }

      public function changeMenuStatus(Request $request) {
        // $row = Medication::find($id);
        // // dd($row);
        // $row->status=!$row->status; 
        // $row->save(); 
        $id = sanitizeVariable($request->id);
        $data = OrgMenus::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = OrgMenus::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = OrgMenus::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }
	
	public function editMenu($id)
    {
        $id= sanitizeVariable($id);
        $menu = OrgMenus::find($id);
        return response()->json($menu);
    }

    public function updateMenu(MenuUpdateRequest $request)
    {
        $id= sanitizeVariable($request->menu_id);
        $update = ['menu' => sanitizeVariable($request->menu), 'menu_url' => sanitizeVariable($request->menu_url), 'module_id' => sanitizeVariable($request->service_id), 'component_id' => sanitizeVariable($request->component_id), 'icon' => sanitizeVariable($request->icon), 'parent' => sanitizeVariable($request->parent), 'status' => sanitizeVariable($request->status), 'sequence' => sanitizeVariable($request->sequence), 'operation' => sanitizeVariable($request->operation),
                'updated_by'=> session()->get('userid')];
        OrgMenus::where('id',$id)->update($update);
  
      // return back()->with('success','Menu updated successfully!');
  // return Redirect::to('index')
       //->with('success','Great! Product updated successfully');
        // Session::put('success','Menu updated successfully!'); 
        // return redirect()->back();
    }

    public function deleteMenu(Request $request)
    {
        //Log::info($request);
         $id= sanitizeVariable($request->menu_id);
         //$update = ['status' => '0'];
        $data =['updated_by'=> session()->get('userid')];
         OrgMenus::where('id',$id)->delete($data);
     
        //return response()->json(['success'=>'Menu deleted successfully.']);
    }

    public function populate(Request $request)
    {
        // return printHello('pranali');
        $orgmenus = OrgMenus::find(sanitizeVariable($request->input('org_menu_id')));
        return $orgmenus->population();
    }
} 