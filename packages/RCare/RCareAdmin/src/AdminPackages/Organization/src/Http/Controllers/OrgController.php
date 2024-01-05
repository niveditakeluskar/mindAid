<?php

namespace RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers;
use Redirect;
use RCare\RCareAdmin\AdminPackages\Organization\src\Http\Requests\RcareOrgsAddRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs;
//use RCare\RCareAdmin\AdminPackages\Organization\src\Models\License;
use DataTables;
use File;
use Session;



class OrgController extends Controller
{
    //

    public function index()
    {
      return view('Organization::rcare-org-list');
    }

     public function addOrg()
    {
      return view('Organization::add-rcare-org');
    }
   

    

   /* public function createOrgs(RcareOrgsAddRequest $request)
    {
       
       $orgs = RcareOrgs::create(request(
       	[ 'name', 
       	  'uid', 
       	  'add1', 
       	  'add2', 
       	  'city', 
       	  'state',
          'category', 
       	  'zip', 
       	  'phone', 
       	  'email', 
       	  'contact_person',
       	  'contact_person_phone',
       	  'contact_person_email'
        
        ]));
        
        //auth()->login($user);
        //return back()->with('success','Org updated successfully!');
        return redirect()->route('Addlicense')->with('success','Org updated successfully!');
       
    }*/

// public function action(RcareOrgsAddRequest $request)
//     {
//      $validation = Validator::make($request->all(), [
//       'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
//      ]);
//      if($validation->passes())
//      {
//       $image = $request->file('select_file');
//       $new_name = time() . '.' . $image->getClientOriginalExtension();
//       $image->move(public_path('images/orgLogoRcare'), $new_name);
//       return response()->json([
//        'message'   => 'Image Upload Successfully',
//        // 'uploaded_image' => '<img src="/images/orgLogoRcare/'.$new_name.'" class="img-thumbnail" width="300" />',
//        'img_path' => '/images/orgLogoRcare/'.$new_name,
//        'class_name'  => 'alert-success'
//       ]);
//      }
//      else
//      {
//       return response()->json([
//        'message'   => $validation->errors()->all(),
//        // 'uploaded_image' => '',
//        'img_path' => '',
//        'class_name'  => 'alert-danger'
//       ]);
//      }
//     }

    public function createOrgs(RcareOrgsAddRequest $request){  
      $validation = Validator::make($request->all(), [
        'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if($validation->passes()){
           $image = $request->file('select_file');
            $new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/orgLogoRcare'), $new_name);
            $img_path = '/images/orgLogoRcare/'.$new_name;
           
        $orgs = RcareOrgs::updateOrCreate(
          [
          'name'                   => $request->name, 
          'uid'                    => $request->uid, 
          'logo_img'               => $img_path,
          'add1'                   => $request->add1, 
          'add2'                   => $request->add2,  
          'city'                   => $request->city,
          'state'                  => $request->state,
          'category'               => $request->category,
          'zip'                    => $request->zip,      
          'phone'                  => $request->phone,
          'email'                  => $request->email,
          'contact_person'         => $request->contact_person,
          'contact_person_phone'   => $request->contact_person_phone,
          'contact_person_email'   => $request->contact_person_email
         ]); 
     }  
        $id = $orgs->id;
        Session::put('success','Org update successfully! Please add license!');
             // return redirect()->back();
              return redirect()->route('add_license',$id);
       //return redirect()->route('add_license',$id)->with('success','Org create successfully!');
    }   


    public function fetchRcareOrgs(Request $request)
    {
    	 if ($request->ajax()) {
         /*  $data = User::latest()->get()->where('status',1);*/
           // $data1 = License::latest()->get();
            $data = RcareOrgs::latest()->get()->sortByDesc('updated_at');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){


/* */
                       $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';

                        $btn = $btn.   '<a href="view-license/'.$row->id.'" title="View"><i class="text-15 i-Eye" style="color: green;"></i></a>';

                      if($row->status == 1){
                         $btn = $btn. '<a href="changeOrgStatus/'.$row->id.'" title="Active"><i class="i-Yess i-Yes"  style="color: green;"></i></a>

                         ';
                       }

                   
                       else
                         {  

                          $btn = $btn. '<a href="changeOrgStatus/'.$row->id.'" title="Inactive"><i class="i-Closee i-Close"  style="color: red;"></i></a>

                         ';



                         
                       }
                       

                      



                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('rcareorgs');



    }

    public function rcareOrgsedit($id)
    {
       //$row = RcareOrgs::find($id);
       //return response()->json($row);
        $where = array('id' => $id);
        $data['row'] = RcareOrgs::where($where)->first();
 
        return view('Organization::edit-rcare-org', $data);
   
    }

    public function updateRcareOrg(Request $request, $id)
    {

        $update = ['name' => $request->name, 'uid' => $request->uid, 'email' => $request->email, 'add1' => $request->add1, 'add2' => $request->add2, 'city' => $request->city, 'state' => $request->state, 'zip' => $request->zip, 'phone' => $request->phone, 'contact_person_phone' => $request->contact_person_phone, 'contact_person_email' => $request->contact_person_email, 'contact_person' => $request->contact_person];
        RcareOrgs::where('id',$id)->update($update);

        //return redirect()->route('rcareorgs')->with('success','Org update successfully!');
           Session::put('success','Org update successfully!');
             // return redirect()->back();
             return redirect()->route('rcareorgs');
   
      // return back()->with('success','Org updated successfully!');
	 
    }

  
    public function GetCount(Request $request){
      $MessageResult =  RcareOrgs::count();
      echo $MessageResult;
     }
     public function GetLic(Request $request){
      $MessageResult =  License::count();
      echo $MessageResult;
     }
  
  
    public function changeOrgStatus($id)
    {
        //User active or notactive
      $row = RcareOrgs::find($id);
      $row->status=!$row->status;
    
     if($row->save())
     {
       
           return redirect()->route('rcareorgs');
     }
    else

     {
           return redirect()->route('statuschange');
          
     }

     }


}
