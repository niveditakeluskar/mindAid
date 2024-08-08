<?php

namespace RCare\RCareAdmin\AdminPackages\Protocol\src\Http\Controllers;

// use RCare\RCareAdmin\AdminPackages\Protocol\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Protocol\src\Models\RPMProtocol;
//use RCare\RCareAdmin\AdminPackages\Protocol\src\http\Requests\RPMVitalsProtocolRequest;
use RCare\RCareAdmin\AdminPackages\Protocol\src\Http\Requests\RPMVitalsProtocolRequest;
use RCare\Rpm\Models\Devices;
use Illuminate\Support\Str;
use Session;
use File;
use DataTables;
use Validator,Redirect,Response;


class ProtocolController extends Controller {
    public function index() {
        // return view('Protocol::rpm-vitals-protocol');
    }

    public function fileUploadPost(RPMVitalsProtocolRequest $request)
    {
        $deviceid=sanitizeVariable($request->device);
        if( $deviceid!='')
        {
            $devicename=Devices::where('id',$deviceid)->first();
              $devicenm=$devicename->device_name;
        }
          if(isset($_FILES) && !empty($_FILES)) {
            if($request->hasFile('file'))
            {                  
                $image = $request->file('file');               
                $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());    
                $file_extension=$image->getClientOriginalExtension(); 
                 $devicefolder=str_replace(' ', '-', $devicenm);       
                $new_name =$devicefolder.'_'.date("d-m-Y")."-".time(). '.' .$file_extension;                               
                $path="Vitals-Alert-Protocol-Documentation/".$devicefolder;
               
               
                if(is_dir($path)==false) {
                   mkdir($path);
                } 

                $image = $image->move(public_path($path), $new_name);  
                $existdata= RPMProtocol::where('device_id',$deviceid)->where('status',1)->exists();
                if($existdata==true)
                {
                    RPMProtocol::where('device_id',$deviceid)->where('status',1)->update(['status'=>0]);
                }

                 $data=array(
                'device_id'=>$deviceid,
                'file_name'=>$new_name,
                'status'=>1,
                'created_by'=>session()->get('userid'));
                RPMProtocol::create($data);                     
                
            }
          }
     }

    public function getVitalsDocumentList(Request $request)
    {
       if ($request->ajax()) {
        $data = RPMProtocol::with('devices')->with('users')->orderBy('created_at', 'desc')->get();    
        return Datatables::of($data)
        ->addIndexColumn()  
        ->addColumn('action', function($row){

        if($row->status == 1){           
            $btn ='<a href="javascript:void(0)" data-id="'.$row->id.'/'.$row->device_id.'" class="change_rpmprotocolstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
        } else {            
            $btn ='<a href="javascript:void(0)" data-id="'.$row->id.'/'.$row->device_id.'" class="change_rpmprotocolstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
        }      
         return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
    }

     public function changeVitalsProtocolStatus(Request $request) {
        $id = sanitizeVariable($request->id);
         $deviceid = sanitizeVariable($request->deviceid);
         $arrayid=array($id);       
         $dataexist=RPMProtocol::where('device_id',$deviceid)->where('status','1')->whereNotIn('id',$arrayid)->exists();
         if($dataexist==true)
         {
            RPMProtocol::where('device_id',$deviceid)->where('status','1')->whereNotIn('id',$arrayid)->update(['status'=>'0']);
         }
        $data = RPMProtocol::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=> '0', 'updated_by' =>session()->get('userid'));
          $update_query = RPMProtocol::where('id',$id)->update($status);
        }else{
          $status =array('status'=> '1', 'updated_by' =>session()->get('userid'));
          $update_query = RPMProtocol::where('id',$id)->update($status);
        }
    }

}
