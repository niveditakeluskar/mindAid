<?php

namespace RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\RCareAdmin\AdminPackages\Menu\src\Http\Requests\MenuAddRequest;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Menu\src\Models\Menus;
use RCare\RCareAdmin\AdminPackages\Services\src\Models\Services;
use DataTables;
use Session;
class MenuController extends Controller
{

    public function index()
    {
        $service = Services::all();
        $menus = Menus::where('parent','=',0)->orderBy('sequence','asc')->get();
        return view('Menu::menu-list', compact('service','menus'));
    }

    public function createMenu(MenuAddRequest $request)
    {
        $data = array(
            'menu' => $request->menu,
            'menu_url' => $request->menu_url,
            'service_id' => $request->service_id,
            'icon' => $request->icon,
            'parent' => $request->parent,
            'status' => '1',
            'sequence' => $request->sequence,
            'operation' => $request->operation
        );
        
        $menu = Menus::create($data);
        Session::put('success','Menu created successfully!'); 
        return redirect()->back();
       // return back()->with('success','Menu created successfully!');
    }

    public function fetchMenu(Request $request)
    {
		if ($request->ajax()) {
			//$data = Menus::latest()->get()->where('status',1);
			//$data = Menus::latest()->get();
			$data = Menus::with('services','mnu')->get()->sortByDesc('updated_at');
			return Datatables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){

						   $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-original-title="Edit"  class="editMenu" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                           
						   $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteMenu" title="Delete"><i class="i-Closee text-muted i-Close"></i></a>';
							return $btn;
					})
					->rawColumns(['action'])
					->make(true);
		}  
        return view('fetchMenus');
    }
	
	

    public function editMenu($id)
    {
        $menu = Menus::find($id);
        return response()->json($menu);
    }

    public function updateMenu(Request $request)
    {
        $id= $request->menu_id;
        $update = ['menu' => $request->menu, 'menu_url' => $request->menu_url, 'service_id' => $request->service_id, 'icon' => $request->icon, 'parent' => $request->parent, 'sequence' => $request->sequence, 'operation' => $request->operation];
        Menus::where('id',$id)->update($update);
  
       //return back()->with('success','Menu updated successfully!');
  // return Redirect::to('index')
       //->with('success','Great! Product updated successfully');


       Session::put('success','Menu updated successfully!'); 
        return redirect()->back();

    }

    public function deleteMenu(Request $request)
    {
        
         $id= $request->menu_id;
         //$update = ['status' => '0'];
         Menus::where('id',$id)->delete();
     
        return response()->json(['success'=>'Menu deleted successfully.']);
    }
}