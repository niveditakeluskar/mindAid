<?php
namespace RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Partner\src\Http\Requests\PartnerApiDetailsRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\PartnerApiDetails\src\Models\PartnerApiDetails;  
use RCare\Org\OrgPackages\Partner\src\Models\PartnerDevices;
use RCare\Org\OrgPackages\Partner\src\Models\PartnerApiConfiguration;
//use RCare\Org\OrgPackages\Medication\src\Models\Providers;
use RCare\Org\OrgPackages\Partner\src\Models\Partner;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File,DB;
use Illuminate\Validation\Rule;
use Validator;

class PartnerApiDetailsController extends Controller {
   
    //created by radha 20july2021
    public function addPartnerApi(Request $request) {
        
        // dd($request->all()); 

        $tag_name = sanitizeVariable($request->tag_name);
        $path = sanitizeVariable($request->path);
        $api_request = sanitizeVariable($request->api_request);
        $api_response = sanitizeVariable($request->api_response);
        $method = sanitizeVariable($request->method);
        $description = sanitizeVariable($request->description);       
        $created_by  = session()->get('userid');
        $updated_by  = session()->get('userid');        
        $status = '1';

        $partner_id = 1;
        $partnerapidetails_array = array(
            'tag_name'=>$tag_name,
            'path'=>$path,
            'api_request'=>($api_request),
            'api_response'=>($api_response),     
            'method'=>$method,
            'description'=>$description,         
            'created_by'=>$created_by,    
            'updated_by'=>$created_by,         
            'status'=>$status,
            'partner_id'=>$partner_id
            
        );
        $insert_partnerapidetails = PartnerApiDetails::create($partnerapidetails_array);
        
        
    }

    //showing list
    public function PartnerApiList(Request $request) {
        if ($request->ajax()) {

        $data = PartnerApiDetails::with('users')->latest()->get();
      
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
        if($row->status == 1){           
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_partnerapistatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
        } else {         
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_partnerapistatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
        }
        return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
     
    }

    public function populatePartnerApi(Request $request) {   

        $id = sanitizeVariable($request->id);
        $org_partner = (PartnerApiDetails::self($id) ? PartnerApiDetails::self($id)->population() : "");
        // $paramdata    = PartnerDevices::where('partner_id',$id)->where('status',1)->get()->toArray(); 
        // $partnerapiparamdata = PartnerApiConfiguration::where('partner_id',$id)->where('status',1)->get()->toArray(); 

        // if($paramdata){ 
        //     $partnerdeviceparamdata  = array('paramdata'=>$paramdata);
        //     $org_partner['static'] = array_merge($org_partner['static'], $partnerdeviceparamdata);
        // }
        // if($partnerapiparamdata){ 
        //     $newpartnerapiparamdata      = array('partnerapiparamdata'=>$partnerapiparamdata);
        //     $org_partner['static'] = array_merge($org_partner['static'], $newpartnerapiparamdata);
        // }


        $result['EditPartnerApiDetailsForm'] = $org_partner;  
        // dd($result);
        
        
        return $result;
    }

   
    public function updatePartnerApi(Request $request) {
        // dd($request->all());  
        $id = sanitizeVariable($request->id);
        // dd($id);
        $tag_name = sanitizeVariable($request->tag_name);
        $path = sanitizeVariable($request->path);
        $api_request = sanitizeVariable($request->api_request);
        // dd($request->api_request);
        $api_response = sanitizeVariable($request->api_response);
        $method = sanitizeVariable($request->method);
        $description = sanitizeVariable($request->description);       
        $created_by  = session()->get('userid');
        $updated_by  = session()->get('userid');        
        $status = '1';

        $partner_id = 1;
        $partnerapidetails_array = array(
            'tag_name'=>$tag_name,
            'path'=>$path,
            'api_request'=>$api_request,
            'api_response'=>$api_response,     
            'method'=>$method,
            'description'=>$description,         
            'created_by'=>$created_by,    
            'updated_by'=>$created_by,         
            'status'=>$status
            // 'partner_id'=>$partner_id
            
        );

        // dd($partnerapidetails_array);

        $insert_partnerapidetails = PartnerApiDetails::where('id',$id)->update($partnerapidetails_array);
      
    }   

    public function changePartnerStatus(Request $request) {
        $id = sanitizeVariable($request->id);
        
        $data = Partner::where('id',$id)->get();
        //dd($data);
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Partner::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Partner::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    } 
       
} 