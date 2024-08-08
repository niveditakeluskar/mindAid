<?php

namespace  RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers;

use Redirect;
use RCare\RCareAdmin\AdminPackages\License\src\Http\Requests\LicenseAddRequest;
// use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs;
use RCare\RCareAdmin\AdminPackages\Organization\src\Models\Rcare_Modules;
use RCare\RCareAdmin\AdminPackages\Organization\src\Models\OrgCategory;
use RCare\RCareAdmin\AdminPackages\License\src\Models\License;
//use RCare\RCareAdmin\AdminPackages\Organization\src\Models\License;
use DataTables;
use File; 
use DB;
use Carbon;
use Illuminate\Validation\Rule;
use Validator;
use Session;


/*use Illuminate\Http\Request;
use App\Http\Requests\LicenseAddRequest;
use App\Models\License;
use App\Models\RcareOrgs;
use Illuminate\Support\Str;
use Validator,Redirect,Response;
use DB;
use Carbon;

use DataTables;
*/

class LicenseController extends Controller
{
    //
    public function index()
    {
       return view('License::license-list');

    }

    public function addLicense($id)
    {   
  
      $users = RcareOrgs:: where('id',$id)->get();
       return view('License::add-license',['users'=>$users]);

    }

    public function reviewLicense($org_id)
    {   
        /* $license =$_GET['org_id'];*/
     // $license = RcareOrgs:: where('id',$user)->get();
       $license = License::with('licenses', 'modules')->where('org_id',$org_id)->get();

       return view('License::license-review',['license'=>$license]);

    }

     public function createLicense(LicenseAddRequest $request)
    {
        
      //  $uuid = License::withUuid($request)->first();
        $license = License::updateOrCreate(
          
          [

           
          
          'org_id' => $request->org_id, 
          'service_id'=>$request->service_id,
          'license_key' => Str::uuid()->toString(),
         /* 'license_key'=>$uuid, */
          'license_model' => $request->license_model, 
          'subscription_in_months' => $request->subscription_in_months, 
          'start_date' => $request->start_date, 
          'end_date' => $request->end_date,  
          'status' => $request->status
         
         ]);
       //return back()->with('success','License Create Successfully!');

       
       /* $arr = array('msg' => 'Something goes to wrong. Please try again lator', 'status' => false);
        if($license){ 
        $arr = array('msg' => 'Successfully submit license', 'status' => true);
        }*/
      // return Response()->json($arr);

        return redirect()->route('view_license',['org_id'=> $license->org_id])->with('success','License Add successfully!');

       


    } 

    public function updateLicense(LicenseAddRequest $request, $id)
    {

       $Updatelicense = ['license_model' => $request->license_model, 'subscription_in_months' => $request->subscription_in_months, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'status' => $request->status, 'service_id' => $request->service_id];
        license::where('id', $id)->update($Updatelicense);
       

         Session::put('success','License updated successfully!');
             // return redirect()->back();
             return redirect()->route('license');

      //return redirect()->route('license')->with('success','License updated successfully!');
      // return back()->with('success','license updated successfully!');
/*
        $user = RcareOrgs::find($id);
        $user = RcareOrgs::where('id',$id)->first();
        $user->id = $request->input('id');
        if($user->save())
        {
        $profile = License::find($id);
        $profile = License::where('org_id',$id)->first();
        $profile->license_model = $request->input('license_model');
        $profile->subscription_in_months = $request->input('subscription_in_months');
        $profile->start_date = $request->input('start_date');
        $profile->end_date = $request->input('end_date');
        $profile->status = $rsequest->input('status');
        //$profile->city = $request->input('city');

        

         $profile->save();
            return back()->with('success','Org updated successfully!');
        }
            return redirect()->back()->with('error','Something went wrong');*/
   }


   public function fetchLicense(Request $request)
   {

    if ($request->ajax()) {
      //     $data = User::latest()->get()->where('status',1);
           // $data1 = License::latest()->get();
        // $data =DB::table('rcare_admin.rcare_orgs')
             //   ->join('rcare_admin.rcare_licences', 'rcare_admin.rcare_orgs.id', '=', 'rcare_admin.rcare_licences.org_id')
                //->join('users', 'users.id', '=', 'articles.user_id')
            //    ->select('rcare_admin.rcare_orgs.name', 'rcare_admin.rcare_licences.status','rcare_admin.rcare_licences.org_id', 'rcare_admin.rcare_orgs.id', 'rcare_admin.rcare_licences.license_model', 'rcare_admin.rcare_licences.subscription_in_months', 'rcare_admin.rcare_licences.start_date', 'rcare_admin.rcare_licences.end_date')
              //  ->where(DB::raw("(DATE_FORMAT(rcare_admin.rcare_licences.start_date,'%Y-%m'))"),"2016-07")
               // ->get();
               $data = License::with('licensfetch', 'modules')->get()->sortByDesc('updated_at');
       ///  $data = License::with('rcareorg')->get();
            return Datatables::of($data)
                       
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                     
           
                       $btn =    '<a href="edit-license/'.$row->id.'" title="Edit"><i class=" editform i-Pen-4"  style="color: #2cb8ea;"></i></a>';

                        $btn = $btn.   '<a href="view-license/'.$row->org_id.'" title="View"><i class="text-15 i-Eye" style="color: green;"></i></a>';


                         if($row->status == 1){
                         $btn = $btn. '<a href="changeLicenseStatus/'.$row->id.'" title="Active"><i class="i-Yess i-Yes" style="color: green;"></i></a>';

                       } else {  

                          $btn = $btn. '<a href="changeLicenseStatus/'.$row->id.'" title="Inactive"><i class="i-Closee i-Close" style="color: red;"></i></a>';
                         
                       }
                       

                            return $btn;
                    })
                       /*->filterColumn('start_date', function ($query, $keyword) {
                             $query->whereRaw("DATE_FORMAT(start_date,'%m/%d/%Y') like ?", ["%$keyword%"]);
                      })
                         ->filterColumn('end_date', function ($query, $keyword) {
                          $query->whereRaw("DATE_FORMAT(end_date,'%Y/%m/%d') like ?", ["%$keyword%"]);
                          })
*/                              ->rawColumns(['action'])

                    ->make(true);
        }
      
        return view('license');
    }


   public function LicenseEdit($id)
    {
       //$row = RcareOrgs::find($id);
       //return response()->json($row);
        $where = array('id' => $id);
        $data['row'] = license::where($where)->first();

        return view('License::edit-license', $data);
   
    }

    public function changeLicenseStatus($id)
    {
        //User active or notactive
      $row = License::find($id);
      $row->status=!$row->status;
    
     if($row->save())
     {
       
           return redirect()->route('license');
     }
    else

     {
           return redirect()->route('statuschange');
          
     }

     }

     /*public function dynamicLicense(Request $request)

     {     


         $request->validate([
            'addmore.*.org_id'                  => 'required',
            'addmore.*.service_id'              => 'required',
            'addmore.*.license_model'           => 'required',
            'addmore.*.subscription_in_months'  => 'required',
            'addmore.*.start_date'              => 'required',
            'addmore.*.end_date'                => 'required',
            'addmore.*.status'                  => 'required',

        ]);
    
        foreach ($request->addmore as $key => $value) {
            License::create($value);
        }
    
        return back()->with('success', 'License Created Successfully.');
  

     }*/

     /* $rules = [];


        foreach([$request->input('org_id'),
                 $request->input('service_id'),
                 $request->input('license_model'),
                 $request->input('subscription_in_months'),
                 $request->input('start_date'),
                 $request->input('end_date'),
                 $request->input('status')] as $key => $value) {
            

                //$rules["org_id.{$key}"] = 'required';
        }


        $validator = Validator::make($request->all());


        if ($validator->passes()) {


            foreach([$request->input('org_id'),
                     $request->input('service_id'),
                     $request->input('license_model'),
                     $request->input('subscription_in_months'),
                     $request->input('start_date'),
                     $request->input('end_date'),
                     $request->input('status')] as $key => $value) {
                License::create(['org_id'=>$value]);
            }


            return response()->json(['success'=>'done']);
        }


        return response()->json(['error'=>$validator->errors()->all()]);

    }*/

    public function populate(Request $request)
    {
        // return printHello('pranali');
        $role = License::find($request->input('license_id'));
        return $role->population();
    }

}
