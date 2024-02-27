<?php 
namespace RCare\Reports\Http\Controllers; 
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientDevices;
use Illuminate\Http\Request;
use RCare\Patients\Http\Requests\RpmShippingRequest;
use RCare\Patients\Http\Requests\RpmDeviceShippingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 

class RpmEnrolledReportController extends Controller
{

    public function RpmEnrolledReport(Request $request) 
    {
    
          return view('Reports::rpm-enrolled-report.rpm-enrolled-report');
    }

    public function RpmEnrolledReportSearch(Request $request)
    {     
        $monthly = date('Y-m');
        //date('2022-05'); 
        // $year = date('Y', strtotime($monthly));
        // $month = date('m', strtotime($monthly));
        $patient = sanitizeVariable($request->route('patient'));
        $practices = sanitizeVariable($request->route('practices'));
        $shipping_status = sanitizeVariable($request->route('shipping_status'));
        $fromdate=sanitizeVariable($request->route('fromdate'));
        $todate=sanitizeVariable($request->route('todate'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
         $pracgrp; 
         $p;
         $pr;

        
    
        if( $practices!='null')
        {
            if( $practices==0)
            {
                $pr = 'null';  
            }
            else{
                $pr = $practices;
            } 
        }
        else
        {
        $pr = 'null';
        }
        
        if( $patient!='null')
        {
            if( $patient==0)
            {
                $p = 'null';  
            }
            else{
                $p = $patient;
            } 
        }
        else
        {
        $p = 'null';
        }

        if( $shipping_status!='null')
        {
            if( $shipping_status==0)
            {
                $ss = 'null';  
            }
            else{
                $ss = $shipping_status;
            } 
        }
        else
        {
        $ss = 'null';
        }
        

        if($fromdate=='null' || $fromdate=='')
        {
            //   $date=date("Y-m-d"); 
            //   $year = date('Y', strtotime($date));
            //   $month = date('m', strtotime($date));
            //   $fromdate = $year."-".$month."-01 00:00:00";
            //   $todate = $date." 23:59:59"; 
    
            //   $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
            //   $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);        

            $dt1 ='null';
            $dt2 ='null';  
            $query="select * from patients.rpm_enrolled_patient_report($pr,$p,$ss,$dt1,$dt2)";  
        } 
        else
        {      
           $fromdate = $fromdate." 00:00:00"; 
           $todate = $todate." 23:59:59" ; 
           $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
           $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
           
           $query="select * from patients.rpm_enrolled_patient_report($pr,$p,$ss,'".$dt1."','".$dt2."')";  
        }      
        // dd($query);
        $data = DB::select( ($query) ); 
        return Datatables::of($data) 
        ->addIndexColumn()
            ->addColumn('device', function($row){ 
                $btn1='';
                $cm1=''; 
                $cm1 = "<button type='button' data-id='".$row->pid."' id='devicedetails' class='btn btn-primary mt-4 devicedetails' onclick ='devicedetails(".$row->pid.")'>Add Device";
                $cm1 .="</button>"; 
                $btn1 .= $cm1;  
                return  $btn1;

            }) 
            ->addColumn('action', function($row) use ($monthly){ 
                $btn='';
                $cm='';
                $cm .= "<button type='button' data-id='".$row->pid."'   id='shippingdetail' class='btn btn-primary mt-4 shippingdetail'onclick ='shippingdetail(".$row->pid.")'>Shipping Details</button> <a href='/rpm/timeline-daily-activity/".$row->pid."/timelinedailyactivity' target='_blank' data-id='".$row->pid."' id='timeline' class='btn btn-primary mt-4' type='button'>RPM Timeline</a>";
                $btn .= $cm;   
                return  $btn;
            })
            ->rawColumns(['device','action'])    
            ->make(true);       
       
    }

    public function shippingdetailssave(RpmShippingRequest $request){ 
        // dd($request->all());
        $patient_id = sanitizeVariable($request->patient_id);
        $device_id = sanitizeVariable($request->device_id);
        $courier_service_provider = sanitizeVariable($request->courier_service_provider);
        $shipping_date = sanitizeVariable($request->shipping_date);
        $shipping_status = sanitizeVariable($request->shipping_status);
        $welcome_call = sanitizeVariable($request->status);
        
        $data = array(
            'courier_service_provider' => sanitizeVariable($request->courier_service_provider),
            'shipping_date' => sanitizeVariable($request->shipping_date),
			'shipping_status' => sanitizeVariable($request->shipping_status),
            // 'device_code' => sanitizeVariable($request->device_code),
            'created_by'=> session()->get('userid'),
            // 'partner_id' => '3',
            'status' => '1',
            'updated_by' => session()->get('userid') 
        );

        $pateintservicedata = array(
			'welcome_call' => sanitizeVariable($request->status),
            'updated_by' => session()->get('userid')
        );
       
		
        $check_patient = PatientDevices::where('patient_id',$patient_id)->exists(); 
        $check_patient_d = PatientDevices::where('patient_id',$patient_id)->where('id',$device_id)->exists(); 
        // $check_patient_d = PatientDevices::where('patient_id',$patient_id)
        //                                     ->where('courier_service_provider',$courier_service_provider)
        //                                     ->where('shipping_date',$shipping_date)
        //                                     ->where('shipping_status',$shipping_status)
        //                                     ->where('welcome_call',$welcome_call)
        //                                     ->where('device_code',$device_code)
        //                                     ->exists();
                                            
        
        if($check_patient == true){     
            if($check_patient_d ==true){
                $updatetodo = PatientDevices::where('patient_id',$patient_id)->where('id',$device_id)->update($data);
                $updateservice = PatientServices::where('patient_id',$patient_id)->update($pateintservicedata);
            }
        } else{ 
            $data['patient_id'] = sanitizeVariable($request->patient_id);
            PatientDevices::create($data);

            $updateservice = PatientServices::where('patient_id',$patient_id)->update($pateintservicedata);
        }
       
       
        if(sanitizeVariable($request->shipping_status) == "2"){
            $ccmSubModule = ModuleComponents::where('components',"Monthly Monitoring")->where('module_id',2)->where('status',1)->get('id');
            $SID          = getFormStageId(2, $ccmSubModule[0]->id, 'Shipping Status');
            $enroll_msg = CommonFunctionController::sentSchedulMessage(2,$patient_id,$SID);
        } 
    }

    public function devicedetailssave(RpmDeviceShippingRequest $request){ //dd($request->all());
        $patient_id = sanitizeVariable($request->patient_id);
        $device_code = sanitizeVariable($request->device_code); 
        $last12Digits = substr($device_code, -12); 
        $data = array(
            'device_code' => $last12Digits,
            'created_by'=> session()->get('userid'),
            'partner_id' => sanitizeVariable($request->partner_id),
            'partner_device_id'  => sanitizeVariable($request->partner_devices_id),
            'status' => '1',
            'patient_id'=> sanitizeVariable($request->patient_id),
            'updated_by' => session()->get('userid') 
        ); 
        
        // dd($data);
        if($device_code!='' && $device_code!= 'null'){
            $check_patient_d = PatientDevices::where('patient_id',$patient_id)->where('device_code',$last12Digits)->exists();
            // dd($check_patient_d);
            if($check_patient_d==true){
                // return response()->json(['error' => 'This Device Code already added'], 400);
                return 'This Device Code already added';
            }else{
                $add = PatientDevices::create($data);
                // dd($add);
                return 'Device Add Successfully';
            }
        }else{
            return 'Please Enter Device Code';
        }
        
    }


    public function changeStatusShipping(Request $request){  
        $id = sanitizeVariable($request->id);
        $shipping_status = sanitizeVariable($request->shipping_status);
        // dd($request->all());
        switch ($shipping_status) {
            case '0':
                $status = "None";
                break;
            case '1':
                $status = "Dispatched";
                break;
            case '2':
                $status = "Delivered";
                break;
            case '3':
                $status = "Pending";
                break;
            break;
        }
        $data = array( 
        //   'shipping_status' => $status,
          'shipping_status' => $shipping_status,
          'created_by' => session()->get('userid'),
          'updated_by' => session()->get('userid')
        );
        if(PatientDevices::where('patient_id',$id)->exists()){
          if($shipping_status == "1"){
            $update_data = array(
                'shipping_status' => $shipping_status,
                // 'shipping_status' => $status,
            );
          } else {
            $update_data = array(
                'shipping_status' => $shipping_status,
                // 'shipping_status' => $status
            );
          }
          if($shipping_status == '' ||  $shipping_status == 'select status'){
                $updatetodo = PatientDevices::where('patient_id',$id)->update($update_data);
          }else{
                $updatetodo = PatientDevices::where('patient_id',$id)->update($update_data);
          }        
        }
        else
        {
           PatientDevices::create($data);
        }  
        if($shipping_status == "2"){
            $ccmSubModule = ModuleComponents::where('components',"Monthly Monitoring")->where('module_id',2)->where('status',1)->get('id');
            $SID          = getFormStageId(2, $ccmSubModule[0]->id, 'Shipping Status');
            $enroll_msg = CommonFunctionController::sentSchedulMessage(2,$id,$SID);
        }
    }

    // public function populateshipping(Request $request){ 
    //         $id = sanitizeVariable($request->id);
    //         // $PatientDevices = (PatientDevices::self($id) ? PatientDevices::self($id)->population() : "");
    //         $PatientDevices =  DB::select( ("select pd.updated_at,pd.status,p.id, pd.patient_id, pd.courier_service_provider , pd.shipping_date, pd.shipping_status,pd.welcome_call,pd.device_code
    //         from patients.patient_devices pd
    //         left join patients.patient p on pd.patient_id = p.id
    //         where p.id  = '".$id."' and pd.status = 1 " ));

            
    //         //dd($PatientDevices); ORDER BY pd.updated_at DESC limit 1 and pd.status = 1 and  courier_service_provider is not null and shipping_date  is not null and welcome_call is not null
    //         $result['shipping_form'] = $PatientDevices;    
    //         return $result;
    // }

    public function populateshippingdevicewise(Request $request){
        $patinet_id = sanitizeVariable($request->patinet_id);
        $device_id = sanitizeVariable($request->device_code);
        // dd($device_code);

        $PatientDevices =  DB::select( ("select pd.status,p.id, pd.patient_id, pd.courier_service_provider , pd.shipping_date, pd.shipping_status,
        ps.welcome_call,pd.device_code
        from patients.patient_services ps
        left join patients.patient p on ps.patient_id = p.id
        left join patients.patient_devices pd on pd.patient_id = ps.patient_id and pd.status = 1
        where ps.patient_id  = '".$patinet_id."' and ps.status = 1 and pd.id = '".$device_id."'" ));
        $result['shipping_form'] = $PatientDevices;    
        return $result;
    }

    public function actiondevice($id){
        $id  = sanitizeVariable($id);
        $data = PatientDevices::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = PatientDevices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = PatientDevices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        // $updatetodo = PatientDevices::where('patient_id',$id)->update($update_data);
    }

    public function getdeviceslist($rowid){ //dd("working");
        $id           = sanitizeVariable($rowid);
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        // $data = PatientDevices::where("patient_id", $id)->orderBy('created_at', 'desc')->get(['vital_devices->vid as vid','vital_devices->pid as pid','vital_devices->pdid as pdid', 'id as id','status as status', 'device_code as device_code','updated_by as updated_by','updated_at as updated_at']);
        $query = "select u.f_name,u.l_name,pd.id,pd.patient_id,pd.device_code ,pd.updated_by ,pdd.device_name,p.name , pd.status,
            to_char(pd.updated_at  at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at 
            from patients.patient_devices pd 
            left join ren_core.users as u on pd.updated_by=u.id
            inner join ren_core.partners as p on p.id = pd.partner_id   
            left join ren_core.partner_devices_listing as pdd on pdd.id = pd.partner_device_id 
            where pd.patient_id  = '".$id."'";  
        // dd($query); and pd.partner_id = 3
        $data = DB::select( ($query) );
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
                $btn ='';
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" data-additional-id="'.$row->patient_id.'" class="change_device_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else 
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" data-additional-id="'.$row->patient_id.'" class="change_device_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                  }
                  return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getshippinglist($id,$shipping_status){
        $id           = sanitizeVariable($id);
        $shipping_status = sanitizeVariable($shipping_status);
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $query = "select p.id,pd.courier_service_provider,pd.device_code,pd.shipping_status,pd.shipping_date,ps.welcome_call, u.f_name,u.l_name,
        to_char(pd.updated_at  at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at 
        from patients.patient_services ps
        left join patients.patient  p on ps.patient_id = p.id  and p.status = 1
        left join patients.patient_devices pd on pd.patient_id = ps.patient_id and pd.status = 1 
        left join ren_core.users as u on pd.updated_by=u.id
        left join patients.patient_providers pp on pp.patient_id = p.id  and pp.provider_type_id = 1 and pp.is_active = 1
        left join ren_core.practices prac on pp.practice_id = prac.id and prac.is_active = 1 
        where ps.module_id = 2 and ps.status = 1 and ps.patient_id = '".$id."' and pd.device_code is not null";


        if($shipping_status!=='null' && $shipping_status != '0'){
            $query .= "  and pd.shipping_status = '".$shipping_status."'";
          } else{}

        // dd($query);
        $data = DB::select( ($query) ); 
        return Datatables::of($data) 
        ->addIndexColumn()
        ->make(true);

    }
    
    public function patientdevicelist($patientid){
        $patientid = sanitizeVariable($patientid);
        $device = PatientDevices::where('patient_id', $patientid)
            ->where("status", 1)
            // ->where("partner_id", 3)
            ->orderBy('created_at', 'desc')
            ->get();
    
        foreach ($device as $p) {
            $id = $p->id;
            $pro = DB::select(("select pd.device_code, pd.patient_id, pd.id, pd.status
                from patients.patient_devices pd 
                left join ren_core.users as u on pd.updated_by = u.id
                left join ren_core.partners as p on p.id = pd.partner_id 
                left join ren_core.partner_devices_listing as pdd on pdd.id = pd.partner_device_id 
                where pd.patient_id  = '".$id."' "));
                // and pd.partner_id = 3  and p.id = 3
    
            if (!empty($pro)) {
                $practicecount = $pro[0]->count; 
                $p->count = $practicecount;
            } else {
                $p->count = 0; 
            }
        }
    
        return response()->json($device); 
    }
    
}



