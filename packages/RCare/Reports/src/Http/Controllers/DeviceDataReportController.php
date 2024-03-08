<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController; 
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Temp;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Patients\Models\PatientDevices;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Rpm\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 
use PDF;
use RCare\Patients\Models\PatientThreshold;


class DeviceDataReportController extends Controller
{


  public function preview()
    {
        return view('Reports::device-data-reports.device-data-report');
    }

    public function htmltopdfview()
    {
        $pdf = PDF::loadView('Reports::device-data-reports.device-data-report-pdf');
        return $pdf->download('users.pdf');
    }
	
	public function getAssignDevice(Request $request)
    {
      // dd('getAssignDevice');
      try {
		  $patientid = sanitizeVariable($request->pid);
		  $patient_assign_device="";
		  $patient_assign_deviceid="";
			
		  $PatientDevices = PatientDevices::where('patient_id',$patientid)->where('status',1)->orderby('id','desc')->first();
		  if(!empty($PatientDevices)){
                $deviceid = $PatientDevices->device_id;
				$dev=  Devices::where('id',$deviceid)->where('status','1')->orderby('id','asc')->first();
                       if(!empty($dev)){
                        $show_device = $dev->device_name;
                        $show_device_id = $dev->id.","; 
					   }
					   return response()->json([ 'patient_assign_deviceid'=>$show_device_id]); 
            }      
            
      }
      catch(\Exception $ex) {
          //  DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

	/*
    public function getAssignDevice(Request $request)
    {
      // dd('getAssignDevice');
      try {
      $patientid = sanitizeVariable($request->pid);
		
      $PatientDevices = PatientDevices::where('patient_id',$patientid)->where('status',1)->orderby('id','desc')->first();
      
            if(!empty($PatientDevices)){
                $data = json_decode($PatientDevices->vital_devices);
                
                $show_device="";
                $show_device_id="";
                if(isset($data)){
                    for($i=0;$i<count($data);$i++){
                      if (array_key_exists("vid",$data[$i]))
                      {
                       $dev=  Devices::where('id',$data[$i]->vid)->where('status','1')->orderby('id','asc')->first();
                       if(!empty($dev)){
                        $show_device.= $dev->device_name.", ";
                        $show_device_id.= $dev->id.", ";
						dd($show_device_id);
                       }
                      }
                  
                    }
                }
                 $patient_assign_device= rtrim($show_device, ', ');
                 $patient_assign_deviceid= rtrim($show_device_id, ', ');
            }else{
                $patient_assign_device="";
                $patient_assign_deviceid="";
            }
			
            return response()->json([ 'patient_assign_deviceid'=>$patient_assign_deviceid]); 
            }
      catch(\Exception $ex) {
          //  DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }
	*/
    
    public function DDReportSerch(Request $request)
    {
     try{
      $configTZ = config('app.timezone');
      $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
      $patientid = sanitizeVariable($request->patientid);
      $fromdate = sanitizeVariable($request->month);
      
      

      if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
      $fromdate=date('Y-m');
      }else{
        $fromdate=$fromdate; 
      }

        $year = date('Y', strtotime($fromdate));
        $month = date('m', strtotime($fromdate));

        $firstDateOfMonth= $year.'-'.$month.'-01';
        //Last date of current month.
        $lastDateOfMonth = date("Y-m-t", strtotime($firstDateOfMonth));

        //$last_day_this_month  = date($fromdate.'-'.'t');

        $fdt = $firstDateOfMonth." "."00:00:00";   
        $tdt = $lastDateOfMonth ." "."23:59:59"; 
        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt);
    /*$practicesgrp = sanitizeVariable($request->practicesgrpid);
    $billable = sanitizeVariable($request->billable);
    $careplanstatuas = sanitizeVariable($request->careplanstatuas);
    $fromdate= sanitizeVariable($request->route('fromdate'));
    $configTZ     = config('app.timezone');
    $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
      $fromdate1 =$fromdate." "."00:00:00";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
      $todate= sanitizeVariable($request->route('todate')); 
      $todate1 = $todate ." "."23:59:59";  
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
     
      $query="select  ppatient_id, fname, lname, mname, profile_img, dob, practicename, cpdstautus, totaltimecpd, billabeltime, nonbillabeltime, totaltime, 
      to_char(finalizedate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as finalizedate 
       from patients.cpd_report($practices,$practicesgrp,$billable,$careplanstatuas,timestamp '".$dt1."',timestamp '".$dt2."',$activedeactivestatus)";*/

      /* $query = "select     
       to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as effdatetime,
       pid,
       systolic_qty,
       diastolic_qty, 
       resting_heartrate,
       weight, 
       oxy_qty,
       bodytemp, 
       value, 
       fev_value,
       pef_value
        from patients.devivedatareport($patientid, timestamp '".$dt1."',timestamp '".$dt2."')";  */

       $query="select distinct x.effdatetime,x.patient_id,bp.alert_status as bp_alert_status,bp.systolic_qty, bp.diastolic_qty,hr.alert_status as hr_alert_status, hr.resting_heartrate, wt.alert_status as wt_alert_status,wt.weight,ox.alert_status as ox_alert_status, ox.oxy_qty,temp.alert_status as temp_alert_status, temp.bodytemp,glc.alert_status as glc_alert_status, glc.value,spt.alert_status as spt_alert_status, spt.fev_value,spt.pef_value  from 
       ((select distinct effdatetime,patient_id from rpm.observations_heartrate where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid) 
         UNION 
        (select distinct effdatetime,patient_id from rpm.observations_oxymeter where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_bp where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_weight where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_temp where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_glucose where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_spirometer where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        ) x 
       left join rpm.observations_bp bp on bp.effdatetime =x.effdatetime 
       left join rpm.observations_heartrate hr on hr.effdatetime =x.effdatetime 
       left join rpm.observations_weight wt on wt.effdatetime =x.effdatetime 
       left join rpm.observations_oxymeter ox on ox.effdatetime =x.effdatetime
       left join rpm.observations_temp temp on temp.effdatetime =x.effdatetime
       left join rpm.observations_glucose glc on glc.effdatetime =x.effdatetime
       left join rpm.observations_spirometer spt on spt.effdatetime =x.effdatetime
        where x.effdatetime between '$fdt' and '$tdt' and x.patient_id=$patientid";
   
  $data  = DB::select( DB::raw($query) );

  return Datatables::of($data)
      ->addIndexColumn()
      
  ->make(true);

      }
      catch(\Exception $ex) {
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }

  }

//   public function graphBP($patient_id,$month){

//     $fromdate = sanitizeVariable($month);
//     if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
//     $fromdate=date('Y-m');
//     }else{
//       $fromdate=$fromdate; 
//     }

//       $year = date('Y', strtotime($fromdate));  
//       $month = date('m', strtotime($fromdate));

//       $datetime = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)->pluck('effdatetime');
//       $DateArray = $datetime->toArray();
      
//       $reading2 = PatientThreshold::where('patient_id',$patient_id)
//                   ->select('diastolichigh','diastoliclow','systolichigh','systoliclow')
//                   ->orderBy('created_at','desc')
//                   ->get(); 
//       $arrayreading2 = $reading2->toArray();

      



//                 $arrLength = count($DateArray);
//                 $uniArray =array();
//                 $min_threshold_array =array();
//                 $max_threshold_array =array();
                

//                 for($a=0;$a<$arrLength;$a++){
//                    $b = date("M j, g:i a",strtotime($DateArray[$a]));
//                    $c = array_push($uniArray,$b);
//                     /*if(isset($arrayreading2[$a])){
//                        $d = explode('-', $arrayreading2[$a]);
//                        $e = array_push($min_threshold_array,$d[0]);
//                        $f = array_push($max_threshold_array,$d[1]);
//                    }*/
//                 }

//                 $reading = Observation_BP::where('patient_id',$patient_id)
//                 ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
//                 ->pluck('systolic_qty'); 

//                 $reading1 = Observation_BP::where('patient_id',$patient_id)
//                 ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
//                 ->pluck('diastolic_qty');                 
//                 $arrayreading = $reading->toArray();
                
//                 $label = "Systolic";
//                 $arrayreading1=$reading1->toArray();
//                 $label1='Diastolic';

//                 $arrayreading_min =$min_threshold_array;
//                 $arrayreading_max =$max_threshold_array;
    
//                 $arrayreading_min1 ='';
//                 $arrayreading_max1 ='';
//                 $arrayreading1='';
//                 $label1='';

               

//                 return response()->json([
//                   'uniArray'=>$uniArray,
//                   'min_threshold_array'=>$min_threshold_array,
//                   'min_threshold_array'=>$min_threshold_array,
//                   'arrayreading'=>$arrayreading,
//                   'arrayreading1'=>$arrayreading1,
//                   'label'=>$label,
//                   'label1'=>$label1,
//                   'arrayreading_min' =>$arrayreading_min,
//                   'arrayreading_max' =>$arrayreading_max,
//                   'arrayreading_min1' =>$arrayreading_min1,
//                   'arrayreading_max1' =>$arrayreading_max1,
//                   'arrayreading2'=>$arrayreading2
//                 ]);


    
//  }

//created and modified by ashvini
public function graphBP($practice_id,$patient_id,$month){
try{
  $fromdate = sanitizeVariable($month);
  if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
  $fromdate=date('Y-m');
  }else{
    $fromdate=$fromdate; 
  }

    $year = date('Y', strtotime($fromdate));  
    $month = date('m', strtotime($fromdate));

    $datetime = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)
    ->whereYear('effdatetime',$year)
    ->orderBy('effdatetime','asc')
    ->pluck('effdatetime');

    $DateArray = isset($datetime)?$datetime->toArray():'';
    
    $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('diastolichigh','diastoliclow','systolichigh','systoliclow','created_at')
                ->orderBy('created_at','desc')
                ->first(); 
    $arrayreading2 = isset($reading2)?$reading2->toArray():'';
  //  dd($arrayreading2); 

    $arrLength = count($DateArray);
    $uniArray =array(); 
     

    // $min_threshold_array =isset($arrayreading2[0]['systoliclow'])?$arrayreading2[0]['systoliclow']:''; 
    // $max_threshold_array =isset($arrayreading2[0]['systolichigh'])?$arrayreading2[0]['systolichigh']:'';
    $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
    $org_id = $practice_group_id->practice_group;
    $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
    ->select('systolichigh','systoliclow','diastolichigh','diastoliclow')->orderBy('created_at','desc')->first();
    if(isset($org_id) || $org_id!=''){
      $org_threshold = OrgThreshold::where('org_id',$org_id)
      ->select('systolichigh','systoliclow','diastolichigh','diastoliclow')->orderBy('created_at','desc')->first(); 
    }            
          
    $admin_threshold = GroupThreshold::select('systolichigh','systoliclow','diastolichigh','diastoliclow')
    ->orderBy('created_at','desc')->first(); 
    //dd($admin_threshold);
                // $min_threshold_array =isset($arrayreading2[0]['bpmlow'])?$arrayreading2[0]['bpmlow']:''; 
                if($arrayreading2!='' && ($arrayreading2['systoliclow']!='')){
                  $min_threshold_array = $arrayreading2['systoliclow'];
                }elseif(isset($practice_threshold['systoliclow'])){
                  $min_threshold_array = $practice_threshold['systoliclow'];
                }elseif($org_threshold!='' && ($org_threshold['systoliclow']!='')){
                  $min_threshold_array = $org_threshold['systoliclow'];
                }elseif (isset($admin_threshold['systoliclow'])) {
                  $min_threshold_array = $admin_threshold['systoliclow'];
                }else{
                  $min_threshold_array ='';
                }
                //$max_threshold_array =isset($arrayreading2[0]['bpmhigh'])?$arrayreading2[0]['bpmhigh']:'';
                if($arrayreading2!='' && ($arrayreading2['systolichigh']!='')){
                  $max_threshold_array = $arrayreading2['systolichigh'];
                }elseif(isset($practice_threshold['systolichigh'])){
                  $max_threshold_array = $practice_threshold['systolichigh'];
                }elseif($org_threshold!='' && ($org_threshold['systolichigh']!='')){
                  $max_threshold_array = $org_threshold['systolichigh'];
                }elseif (isset($admin_threshold['systolichigh'])) {
                  $max_threshold_array = $admin_threshold['systolichigh'];
                }else{
                  $max_threshold_array ='';
                }
 
    // $min1_threshold_array =isset($arrayreading2[0]['diastoliclow'])?$arrayreading2[0]['diastoliclow']:'';
    // $max1_threshold_array =isset($arrayreading2[0]['diastolichigh'])?$arrayreading2[0]['diastolichigh']:''; 
               if($arrayreading2!=''&&($arrayreading2['diastoliclow']!='')){
                  $min1_threshold_array = $arrayreading2['diastoliclow'];
                }elseif(isset($practice_threshold['diastoliclow'])){
                  $min1_threshold_array = $practice_threshold['diastoliclow'];
                }elseif($org_threshold!='' && ($org_threshold['diastoliclow']!='')){
                  $min1_threshold_array = $org_threshold['diastoliclow'];
                }elseif (isset($admin_threshold['diastoliclow'])) {
                  $min1_threshold_array = $admin_threshold['diastoliclow'];
                }else{
                  $min1_threshold_array ='';
                }
                //$max_threshold_array =isset($arrayreading2[0]['bpmhigh'])?$arrayreading2[0]['bpmhigh']:'';
                if($arrayreading2!='' && ($arrayreading2['diastolichigh']!='')){
                  $max1_threshold_array = $arrayreading2['diastolichigh'];
                }elseif(isset($practice_threshold['diastolichigh'])){
                  $max1_threshold_array = $practice_threshold['diastolichigh'];
                }elseif($org_threshold!='' && ($org_threshold['diastolichigh']!='')){
                  $max1_threshold_array = $org_threshold['diastolichigh'];
                }elseif (isset($admin_threshold['diastolichigh'])) {
                  $max1_threshold_array = $admin_threshold['diastolichigh'];
                }else{
                  $max1_threshold_array ='';
                }
              for($a=0;$a<$arrLength;$a++){ 
                 $b = date("M j, g:i a",strtotime($DateArray[$a]));
                 $c = array_push($uniArray,$b);
                
              }

              $reading = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)->orderBy('effdatetime','asc')
                ->pluck('systolic_qty'); 

                $reading1 = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)->orderBy('effdatetime','asc')
                ->pluck('diastolic_qty'); 
               
                $arrayreading = isset($reading)?$reading->toArray():'';
                $arrayreading1 = isset($reading1)?$reading1->toArray():'';
                $label = 'Systolic mm[Hg]';
                $label1 = 'Diastolic mm[Hg]';
                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 =$min1_threshold_array;
                $arrayreading_max1 =$max1_threshold_array;

            $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 

            
              return response()->json([
              'uniArray'=>$uniArray,
              'min_threshold_array'=>$min_threshold_array,
              'max_threshold_array'=>$max_threshold_array,
              'arrayreading'=>$arrayreading,
              'arrayreading1'=>$arrayreading1,
              'arrayreading_min' =>$arrayreading_min, 
              'arrayreading_max' =>$arrayreading_max,
              'arrayreading_min1' =>$arrayreading_min1,
              'arrayreading_max1' =>$arrayreading_max1,
              'label'=>$label,
              'label1'=>$label1,
              'title_name'=>'Blood Pressure',
              'p_name'=> ucwords($fname." ".$lname),
              'p_dob'=> $dob,
              'mrn'=>$mrn

              ]);   

     }catch(\Exception $ex) {
        // DB::rollBack();
        // return $ex;
        return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
    }
  
}

  public function graphHart($practice_id,$patient_id,$month){
    try{ 
    $fromdate = sanitizeVariable($month);
    if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
    $fromdate=date('Y-m');
    }else{
      $fromdate=$fromdate; 
    }
      $year = date('Y', strtotime($fromdate));
      $month = date('m', strtotime($fromdate));
      $datetime = Observation_Heartrate::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)->orderBy('effdatetime','asc')->pluck('effdatetime');              
      $DateArray =isset($datetime)?$datetime->toArray():'';
      
      $reading2 = PatientThreshold::where('patient_id',$patient_id)
      ->select('bpmhigh','bpmlow')->orderBy('created_at','desc')->first(); 
      
      $arrayreading2 = isset($reading2)?$reading2->toArray():'';
      // dd($arrayreading2);   

      $arrLength = count($DateArray);
      $uniArray =array();
      $min_threshold_array =array();
      $max_threshold_array =array();

      for($a=0;$a<$arrLength;$a++){
          $b = date("M j, g:i a",strtotime($DateArray[$a]));
          $c = array_push($uniArray,$b);
          
      }        
      $reading = Observation_Heartrate::where('patient_id',$patient_id)
      ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
      ->orderBy('effdatetime','asc')
      ->pluck('resting_heartrate'); 
      $arrayreading =isset($reading)? $reading->toArray():'';
      
      $label = "Heart Rate";
      $arrayreading1='';
      $label1='';
      $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
      ->select('bpmhigh','bpmlow')->orderBy('created_at','desc')->first();
      $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
      $org_id = $practice_group_id->practice_group;
      if(isset($org_id) || $org_id!=''){
        $org_threshold = OrgThreshold::where('org_id',$org_id)
        ->select('bpmhigh','bpmlow')->orderBy('created_at','desc')->first();
      }             
      $admin_threshold = GroupThreshold::select('bpmhigh','bpmlow')->orderBy('created_at','desc')->first(); 
     
                // $min_threshold_array =isset($arrayreading2[0]['bpmlow'])?$arrayreading2[0]['bpmlow']:''; 
                if($arrayreading2!='' && ($arrayreading2['bpmlow']!='')){
                  $min_threshold_array = $arrayreading2['bpmlow'];
                }elseif(isset($practice_threshold['bpmlow'])){
                  $min_threshold_array = $practice_threshold['bpmlow'];
                }elseif($org_threshold!='' && $org_threshold['bpmlow']!=''){
                  $min_threshold_array = $org_threshold['bpmlow'];
                }elseif (isset($admin_threshold['bpmlow'])) {
                  $min_threshold_array = $admin_threshold['bpmlow'];
                }else{
                  $min_threshold_array ='';
                }
                //$max_threshold_array =isset($arrayreading2[0]['bpmhigh'])?$arrayreading2[0]['bpmhigh']:'';
                if($arrayreading2!='' && ($arrayreading2['bpmhigh']!='')){
                  $max_threshold_array = $arrayreading2['bpmhigh'];
                }elseif(isset($practice_threshold['bpmhigh'])){
                  $max_threshold_array = $practice_threshold['bpmhigh'];
                }elseif($org_threshold!='' && $org_threshold['bpmhigh']!=''){
                  $max_threshold_array = $org_threshold['bpmhigh'];
                }elseif (isset($admin_threshold['bpmhigh'])) {
                  $max_threshold_array = $admin_threshold['bpmhigh'];
                }else{
                  $max_threshold_array ='';
                }
 

                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
    
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';
                $arrayreading1='';
                $label1='';
                $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 

             
                return response()->json([
                  'uniArray'=>$uniArray,
                  'min_threshold_array'=>$min_threshold_array,
                  'max_threshold_array'=>$max_threshold_array,
                  'arrayreading'=>$arrayreading,
                  'arrayreading1'=>$arrayreading1,
                  'label'=>$label,
                  'label1'=>$label1,
                  'arrayreading_min' =>$arrayreading_min,
                  'arrayreading_max' =>$arrayreading_max,
                  'arrayreading_min1' =>$arrayreading_min1,
                  'arrayreading_max1' =>$arrayreading_max1,
                  'title_name'=>'Heart Rate',
                  'p_name'=> ucwords($fname." ".$lname),
                  'p_dob'=> $dob,
                  'mrn'=>$mrn
                
                ]);

      }
      catch(\Exception $ex) {
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
      }
  }

  public function grapWt($practice_id,$patient_id,$month){
   try{
    $fromdate = sanitizeVariable($month);
    if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
    $fromdate=date('Y-m');
    }else{
      $fromdate=$fromdate; 
    }

      $year = date('Y', strtotime($fromdate));
      $month = date('m', strtotime($fromdate));
      $datetime = Observation_Weight::where('patient_id',$patient_id)
      ->whereMonth('effdatetime',$month)
      ->whereYear('effdatetime',$year)
      ->orderBy('effdatetime','asc')
      ->pluck('effdatetime');                
      $DateArray = isset($datetime)?$datetime->toArray():'';
                             
      $reading2 = PatientThreshold::where('patient_id',$patient_id)->select('weighthigh','weightlow')->orderBy('created_at','desc')->first();                  
      $arrayreading2 =isset($reading2)? $reading2->toArray():'';


      $arrLength = count($DateArray);
      $uniArray =array();
      $min_threshold_array =array();
      $max_threshold_array =array();

      for($a=0;$a<$arrLength;$a++){
          $b = date("M j, g:i a",strtotime($DateArray[$a]));
          $c = array_push($uniArray,$b);
        
      }

        $reading = Observation_Weight::where('patient_id',$patient_id)
        ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        ->orderBy('effdatetime','asc')
        ->pluck('weight'); 
        $arrayreading =isset($reading)? $reading->toArray():'';
        
        $label = 'Weight';
        $arrayreading1='';
        $label1='';

        // $min_threshold_array =isset($arrayreading2[0]['weightlow'])?$arrayreading2[0]['weightlow']:''; 
        // $max_threshold_array =isset($arrayreading2[0]['weighthigh'])?$arrayreading2[0]['weighthigh']:'';
                $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
                  ->select('weighthigh','weightlow')->orderBy('created_at','desc')->first();              
                  $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
                  $org_id = $practice_group_id->practice_group;
                  if(isset($org_id) || $org_id!=''){
                    $org_threshold = OrgThreshold::where('org_id',$org_id)
                    ->select('weighthigh','weightlow')->orderBy('created_at','desc')->first();
                  }
                 $admin_threshold = GroupThreshold::select('weighthigh','weightlow')->orderBy('created_at','desc')->first(); 
                
                if($arrayreading2!='' && ($arrayreading2['weightlow']!='')){
                  $min_threshold_array = $arrayreading2['weightlow'];
                }elseif(isset($practice_threshold['weightlow'])){
                  $min_threshold_array = $practice_threshold['weightlow'];
                }elseif($org_threshold!='' && ($org_threshold['weightlow']!='')){
                  $min_threshold_array = $org_threshold['weightlow'];
                }elseif (isset($admin_threshold['weightlow'])) {
                  $min_threshold_array = $admin_threshold['weightlow'];
                }else{
                  $min_threshold_array ='';
                }
                
                if($arrayreading2!='' && ($arrayreading2['weighthigh']!='')){
                  $max_threshold_array = $arrayreading2['weighthigh'];
                }elseif(isset($practice_threshold['weighthigh'])){
                  $max_threshold_array = $practice_threshold['weighthigh'];
                }elseif($org_threshold!='' && ($org_threshold['weighthigh']!='')){
                  $max_threshold_array = $org_threshold['weighthigh'];
                }elseif (isset($admin_threshold['weighthigh'])) {
                  $max_threshold_array = $admin_threshold['weighthigh'];
                }else{
                  $max_threshold_array ='';
                }
 
        $arrayreading_min =$min_threshold_array;
        $arrayreading_max =$max_threshold_array;

        $arrayreading_min1 ='';
        $arrayreading_max1 ='';
        $arrayreading1='';
        $label1='';


        $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 

        
        return response()->json([
          'uniArray'=>$uniArray,
          'min_threshold_array'=>$min_threshold_array,
          'max_threshold_array'=>$max_threshold_array,
          'arrayreading'=>$arrayreading,
          'arrayreading1'=>$arrayreading1,
          'label'=>$label,
          'label1'=>$label1,
          'arrayreading_min' =>$arrayreading_min,
          'arrayreading_max' =>$arrayreading_max,
          'arrayreading_min1' =>$arrayreading_min1,
          'arrayreading_max1' =>$arrayreading_max1,
          'arrayreading2'=>$arrayreading2,
          'title_name'=>'Weight',
          'p_name'=> ucwords($fname." ".$lname),
          'p_dob'=> $dob,
          'mrn'=>$mrn
                
        ]);
      }catch(\Exception $ex) {
           // DB::rollBack();
          // return $ex;
        return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
      }
  }

  public function graphreadReadings($practice_id,$patient_id,$month){
   try{
    $fromdate = sanitizeVariable($month);
      if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
      $fromdate=date('Y-m');
      }else{
        $fromdate=$fromdate; 
      }

        $year = date('Y', strtotime($fromdate));
        $month = date('m', strtotime($fromdate));


    $datetime = Observation_Oxymeter::where('patient_id',$patient_id)
    ->whereMonth('effdatetime',$month)
    ->whereYear('effdatetime',$year)
    ->orderBy('effdatetime','asc')
    ->pluck('effdatetime');
              
                $DateArray =isset($datetime)? $datetime->toArray():'';
               

                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('oxsathigh','oxsatlow')->orderBy('created_at','desc')->first();

                $arrayreading2 =isset($reading2)?$reading2->toArray():'';  

                // dd($arrayreading2);

                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =array();
                $max_threshold_array =array();

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                   
                }

                $reading = Observation_Oxymeter::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->orderBy('effdatetime','asc')
                ->pluck('oxy_qty'); 
                $arrayreading = isset($reading)?$reading->toArray():'';   
                
                $label = "Oxygen";
                $arrayreading1='';
                $label1='';
 
                // $min_threshold_array =isset($arrayreading2[0]['oxsatlow'])?$arrayreading2[0]['oxsatlow']:''; 
                // $max_threshold_array =isset($arrayreading2[0]['oxsathigh'])?$arrayreading2[0]['oxsathigh']:'';
                $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
                  ->select('oxsathigh','oxsatlow')->orderBy('created_at','desc')->first();              
                
                $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
                $org_id = $practice_group_id->practice_group;
                  if(isset($org_id) || $org_id!=''){
                    $org_threshold = OrgThreshold::where('org_id',$org_id)
                    ->select('oxsathigh','oxsatlow')->orderBy('created_at','desc')->first();
                  }
                $admin_threshold = GroupThreshold::select('oxsathigh','oxsatlow')->orderBy('created_at','desc')->first(); 
                
                if($arrayreading2!='' || ($arrayreading2['oxsatlow']!='')){
                  $min_threshold_array = $arrayreading2['oxsatlow'];
                }elseif(isset($practice_threshold['oxsatlow'])){
                  $min_threshold_array = $practice_threshold['oxsatlow'];
                }elseif($org_threshold!='' && ($org_threshold['oxsatlow']!='')){
                  $min_threshold_array = $org_threshold['oxsatlow'];
                }elseif (isset($admin_threshold['oxsatlow'])) {
                  $min_threshold_array = $admin_threshold['oxsatlow'];
                }else{
                  $min_threshold_array ='';
                }
                
                if(arrayreading2!='' || ($arrayreading2['oxsathigh']!='')){
                  $max_threshold_array = $arrayreading2['oxsathigh'];
                }elseif(isset($practice_threshold['oxsathigh'])){
                  $max_threshold_array = $practice_threshold['oxsathigh'];
                }elseif($org_threshold!='' && ($org_threshold['oxsathigh']!='')){
                  $max_threshold_array = $org_threshold['oxsathigh'];
                }elseif (isset($admin_threshold['oxsathigh'])) {
                  $max_threshold_array = $admin_threshold['oxsathigh'];
                }else{
                  $max_threshold_array ='';
                }
 

                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;

                // dd( $arrayreading_min,$arrayreading_max);
    
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';
                $arrayreading1='';
                $label1='';


                
               

                
                $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }

                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 
                
                return response()->json([
                  'uniArray'=>$uniArray,
                  'min_threshold_array'=>$min_threshold_array,
                  'max_threshold_array'=>$max_threshold_array,
                  'arrayreading'=>$arrayreading,
                  'arrayreading1'=>$arrayreading1,
                  'label'=>$label,
                  'label1'=>$label1,
                  'arrayreading_min' =>$arrayreading_min,
                  'arrayreading_max' =>$arrayreading_max,
                  'arrayreading_min1' =>$arrayreading_min1,
                  'arrayreading_max1' =>$arrayreading_max1,
                  'arrayreading2'=>$arrayreading2,
                  'title_name'=>'Oximeter',
                  'p_name'=> ucwords($fname." ".$lname),
                  'p_dob'=> $dob,
                  'mrn'=>$mrn
                 
              ]);
                }
      catch(\Exception $ex) {
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
  }

  public function graphTemp($practice_id,$patient_id,$month){
   try{
    $fromdate = sanitizeVariable($month);
      if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
      $fromdate=date('Y-m');
      }else{
        $fromdate=$fromdate; 
      }

        $year = date('Y', strtotime($fromdate));
        $month = date('m', strtotime($fromdate));


    $datetime = Observation_Temp::where('patient_id',$patient_id)
    ->whereMonth('effdatetime',$month)
    ->whereYear('effdatetime',$year)
    ->orderBy('effdatetime','asc')
    ->pluck('effdatetime');
               
                $DateArray =isset($datetime)? $datetime->toArray():'';
               
                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =array();
                $max_threshold_array =array();

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                   
                }

                $reading = Observation_Temp::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->orderBy('effdatetime','asc')
                ->pluck('bodytemp'); 
                $arrayreading =isset($reading)? $reading->toArray():'';
                
                
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('temperaturehigh','temperaturelow')->orderBy('created_at','desc')->first();
                $arrayreading2 = isset($reading2)?$reading2->toArray():'';


                
                $label = "Temperature";
                $arrayreading1='';
                $label1='';

                // $min_threshold_array =isset($arrayreading2[0]['temperaturelow'])?$arrayreading2[0]['temperaturelow']:''; 
                // $max_threshold_array =isset($arrayreading2[0]['temperaturehigh'])?$arrayreading2[0]['temperaturehigh']:'';
                $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
                  ->select('temperaturehigh','temperaturelow')->orderBy('created_at','desc')->first();              
                
                  $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
                  $org_id = $practice_group_id->practice_group;
                    if(isset($org_id) || $org_id!=''){
                      $org_threshold = OrgThreshold::where('org_id',$org_id)
                      ->select('temperaturehigh','temperaturelow')->orderBy('created_at','desc')->first();
                    }

                $admin_threshold = GroupThreshold::select('temperaturehigh','temperaturelow')->orderBy('created_at','desc')->first(); 
                
                if($arrayreading2!='' || ($arrayreading2['temperaturelow'])!=''){
                  $min_threshold_array = $arrayreading2['temperaturelow'];
                }elseif(isset($practice_threshold['temperaturelow'])){
                  $min_threshold_array = $practice_threshold['temperaturelow'];
                }elseif($org_threshold!='' && ($org_threshold['temperaturelow']!='')){
                  $min_threshold_array = $org_threshold['temperaturelow'];
                }elseif (isset($admin_threshold['temperaturelow'])) {
                  $min_threshold_array = $admin_threshold['temperaturelow'];
                }else{
                  $min_threshold_array ='';
                }
                
                if($arrayreading2!='' || ($arrayreading2['temperaturehigh']!='')){
                  $max_threshold_array = $arrayreading2['temperaturehigh'];
                }elseif(isset($practice_threshold['temperaturehigh'])){
                  $max_threshold_array = $practice_threshold['temperaturehigh'];
                }elseif($org_threshold!='' && ($org_threshold['temperaturehigh']!='')){
                  $max_threshold_array = $org_threshold['temperaturehigh'];
                }elseif (isset($admin_threshold['temperaturehigh'])) {
                  $max_threshold_array = $admin_threshold['temperaturehigh'];
                }else{
                  $max_threshold_array ='';
                }
 
 

                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;


    
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';
                $arrayreading1='';
                $label1='';
                
                
                $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 

      
                return response()->json([
                'uniArray'=>$uniArray,
                'min_threshold_array'=>$min_threshold_array,
                'max_threshold_array'=>$max_threshold_array,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'label'=>$label,
                'label1'=>$label1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'arrayreading2'=>$arrayreading2,
                'reading2'=>$reading2,
                 'title_name'=>'Temperature',
                'p_name'=> ucwords($fname." ".$lname),
                  'p_dob'=> $dob,
                  'mrn'=>$mrn
                
              ]);
                }
      catch(\Exception $ex) {
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
  }

  public function graphGul($practice_id,$patient_id,$month){
   try{
    $fromdate = sanitizeVariable($month);
      if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
      $fromdate=date('Y-m');
      }else{
        $fromdate=$fromdate; 
      }

        $year = date('Y', strtotime($fromdate));
        $month = date('m', strtotime($fromdate));


    $datetime = Observation_Glucose::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)->orderBy('effdatetime','asc')->pluck('effdatetime');
               
                $DateArray =isset($datetime)?$datetime->toArray():'';
              
                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =array();
                $max_threshold_array =array();

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                   
                }

                $reading = Observation_Glucose::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->orderBy('effdatetime','asc')->pluck('value'); 
                $arrayreading =isset($reading)? $reading->toArray():'';

                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('glucosehigh','glucoselow')->orderBy('created_at','desc')->first(); 
                $arrayreading2 =isset($reading2)? $reading2->toArray():'';
                
                $label = "Glucose";
                $arrayreading1='';
                $label1='';

                //  $min_threshold_array =isset($arrayreading2[0]['glucoselow'])?$arrayreading2[0]['glucoselow']:''; 
                // $max_threshold_array =isset($arrayreading2[0]['glucosehigh'])?$arrayreading2[0]['glucosehigh']:'';
   
                $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
                  ->select('glucosehigh','glucoselow')->orderBy('created_at','desc')->first();              
              
                  $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
                  $org_id = $practice_group_id->practice_group;
                    if(isset($org_id) || $org_id!=''){
                      $org_threshold = OrgThreshold::where('org_id',$org_id)
                      ->select('glucosehigh','glucoselow')->orderBy('created_at','desc')->first();
                    }

                $admin_threshold = GroupThreshold::select('glucosehigh','glucoselow')->orderBy('created_at','desc')->first(); 
                
                if($arrayreading2!='' || ($arrayreading2['glucoselow']!='')){
                  $min_threshold_array = $arrayreading2['glucoselow'];
                }elseif(isset($practice_threshold['glucoselow'])){
                  $min_threshold_array = $practice_threshold['glucoselow'];
                }elseif($org_threshold!='' && ($org_threshold['glucoselow']!='')){
                  $min_threshold_array = $org_threshold['glucoselow'];
                }elseif (isset($admin_threshold['glucoselow'])) {
                  $min_threshold_array = $admin_threshold['glucoselow'];
                }else{
                  $min_threshold_array ='';
                }
                
                if($arrayreading2!='' || ($arrayreading2['glucosehigh']!='')){
                  $max_threshold_array = $arrayreading2['glucosehigh'];
                }elseif(isset($practice_threshold['glucosehigh'])){
                  $max_threshold_array = $practice_threshold['glucosehigh'];
                }elseif($org_threshold!='' && ($org_threshold['glucosehigh']!='')){
                  $max_threshold_array = $org_threshold['glucosehigh'];
                }elseif (isset($admin_threshold['glucosehigh'])) {
                  $max_threshold_array = $admin_threshold['glucosehigh'];
                }else{
                  $max_threshold_array ='';
                }
 
 
                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
    
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';
                $arrayreading1='';
                $label1='';
                
                
                $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 


                return response()->json([
                  'uniArray'=>$uniArray,
                  'min_threshold_array'=>$min_threshold_array,
                  'max_threshold_array'=>$max_threshold_array,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'label'=>$label,
                'label1'=>$label1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'arrayreading2'=>$arrayreading2,
                'title_name'=>'Glucose',
                'p_name'=> ucwords($fname." ".$lname),
                  'p_dob'=> $dob,
                  'mrn'=>$mrn
                
              ]);
                }
      catch(\Exception $ex) {
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
  }
  

public function graphSpiro($practice_id,$patient_id,$month){
  try{
    $fromdate = sanitizeVariable($month);
      if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
        $fromdate=date('Y-m');
      }else{
        $fromdate=$fromdate; 
      }

      $year = date('Y', strtotime($fromdate));
      $month = date('m', strtotime($fromdate));
      $datetime = Observation_Spirometer::where('patient_id',$patient_id)
                                          ->whereMonth('effdatetime',$month)
                                          ->whereYear('effdatetime',$year)
                                          ->orderBy('effdatetime','asc')
                                          ->pluck('effdatetime');
      $DateArray =isset( $datetime)? $datetime->toArray():'';
                
      $reading2 = PatientThreshold::where('patient_id',$patient_id)
                                    ->select('spirometerfevhigh','spirometerfevlow','spirometerpefhigh','spirometerpeflow')
                                    ->orderBy('created_at','desc')
                                    ->first(); 
      $arrayreading2 = isset($reading2)?$reading2->toArray():'';
      $uniArray =array();
      // $min_threshold_array =isset($arrayreading2[0]['spirometerpeflow'])?$arrayreading2[0]['spirometerpeflow']:''; 
      // $max_threshold_array =isset($arrayreading2[0]['spirometerpefhigh'])?$arrayreading2[0]['spirometerpefhigh']:'';
      $practice_threshold = PracticeThreshold::where('practice_id',$practice_id)
      ->select('spirometerpefhigh','spirometerpeflow','spirometerfevlow','spirometerfevhigh')->orderBy('created_at','desc')->first();              
      $practice_group_id = Practices::where('id',$practice_id)->orderBy('created_at','desc')->first();
      $org_id = $practice_group_id->practice_group;
        if(isset($org_id) || $org_id!=''){
          $org_threshold = OrgThreshold::where('org_id',$org_id)
          ->select('spirometerpefhigh','spirometerpeflow','spirometerfevlow','spirometerfevhigh')->orderBy('created_at','desc')->first();
        }
      $admin_threshold = GroupThreshold::select('spirometerpefhigh','spirometerpeflow')->orderBy('created_at','desc')->first(); 
                
        if($arrayreading2!='' || ($arrayreading2['spirometerpeflow'])!=''){
          $min_threshold_array = $arrayreading2['spirometerpeflow'];
        }elseif(isset($practice_threshold['spirometerpeflow'])){
          $min_threshold_array = $practice_threshold['spirometerpeflow'];
        }elseif($org_threshold!='' && ($org_threshold['spirometerpeflow']!='')){
          $min_threshold_array = $org_threshold['spirometerpeflow'];
        }elseif (isset($admin_threshold['spirometerpeflow'])) {
          $min_threshold_array = $admin_threshold['spirometerpeflow'];
        }else{
          $min_threshold_array ='';
        }
                
        if($arrayreading2!='' || ($arrayreading2['spirometerpefhigh']!='')){
          $max_threshold_array = $arrayreading2['spirometerpefhigh'];
        }elseif(isset($practice_threshold['spirometerpefhigh'])){
          $max_threshold_array = $practice_threshold['spirometerpefhigh'];
        }elseif($org_threshold!='' && ($org_threshold['spirometerpefhigh']!='')){
          $max_threshold_array = $org_threshold['spirometerpefhigh'];
        }elseif (isset($admin_threshold['spirometerpefhigh'])) {
          $max_threshold_array = $admin_threshold['spirometerpefhigh'];
        }else{
          $max_threshold_array ='';
        }
 
        // $min1_threshold_array =isset($arrayreading2[0]['spirometerfevlow'])?$arrayreading2[0]['spirometerfevlow']:'';
        // $max1_threshold_array =isset($arrayreading2[0]['spirometerfevhigh'])?$arrayreading2[0]['spirometerfevhigh']:''; 
        if($arrayreading2!='' || ($arrayreading2['spirometerfevlow']!='')){
          $min1_threshold_array = $arrayreading2['spirometerfevlow'];
        }elseif(isset($practice_threshold['spirometerfevlow'])){
          $min1_threshold_array = $practice_threshold['spirometerfevlow'];
        }elseif($org_threshold!='' && ($org_threshold['spirometerfevlow']!='')){
          $min1_threshold_array = $org_threshold['spirometerfevlow'];
        }elseif(isset($admin_threshold['spirometerfevlow'])) {
          $min1_threshold_array = $admin_threshold['spirometerfevlow'];
        }else{
          $min1_threshold_array ='';
        }
                
        if($arrayreading2!='' || ($arrayreading2['spirometerfevhigh']!='')){
          $max1_threshold_array = $arrayreading2['spirometerfevhigh'];
        }elseif(isset($practice_threshold['spirometerfevhigh'])){
          $max1_threshold_array = $practice_threshold['spirometerfevhigh'];
        }elseif($org_threshold!='' && ($org_threshold['spirometerfevhigh']!='')){
          $max1_threshold_array = $org_threshold['spirometerfevhigh'];
        }elseif (isset($admin_threshold['spirometerfevhigh'])) {
          $max1_threshold_array = $admin_threshold['spirometerfevhigh'];
        }else{
          $max1_threshold_array ='';
        }

        for($a=0;$a<count($DateArray);$a++){
           $b = date("M j, g:i a",strtotime($DateArray[$a]));
           $c = array_push($uniArray,$b);    
        }

               $reading =  Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->orderBy('effdatetime','asc')
                ->pluck('pef_value');  
                $reading1 = Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->orderBy('effdatetime','asc')
                ->pluck('fev_value'); 
                $arrayreading =isset($reading)? $reading->toArray():'';
               
                $arrayreading1= isset($reading1)?$reading1->toArray():'';
                
                $label = "PEF [L/min]";
                $label1 = "FEV [L]";
 
                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 =$min1_threshold_array;
                $arrayreading_max1 =$max1_threshold_array;
                
                
                
                $p_name=Patients::select('fname','lname',DB::raw("TO_CHAR(dob, 'MM-DD-YYYY') as dob") )->where('id',$patient_id)->first();
               // dd($patient_id);
                if(!empty($p_name)){
                  
                  if($p_name->fname !="" && $p_name->fname!=null){ 
                    $fname=$p_name->fname;
                  }else{
                    $fname="";
                  }
                  if($p_name->lname !="" && $p_name->lname!=null){ 
                    $lname=$p_name->lname;
                  }else{
                    $lname="";
                  }
                  if($p_name->dob !="" && $p_name->dob!=null){ 
                    $dob=$p_name->dob;
                  }else{
                    $dob="";
                  }
                 
                }else{
                  $fname="";
                  $lname="";
                  $dob="";
                }
                
                $mrn=PatientProvider::select('practice_emr')->where('patient_id', $patient_id)->where('is_active',1)->where('provider_type_id',1)->orderby('id','desc')->first(); 


                return response()->json([
                  'uniArray'=>$uniArray,
                  'arrayreading'=>$arrayreading,
                  'arrayreading1'=>$arrayreading1,
                  'label'=>$label,
                  'label1'=>$label1,
                  'arrayreading_min'=>$arrayreading_min,
                  'arrayreading_max'=>$arrayreading_max,
                  'arrayreading_min1' =>$arrayreading_min1,
                  'arrayreading_max1' =>$arrayreading_max1,
                 // 'arrayreading2'=>$arrayreading2,
                  'title_name'=>'Spirometer',
                   'min_threshold_array'=>$min_threshold_array,
                  'max_threshold_array'=>$max_threshold_array,
                  'p_name'=> ucwords($fname." ".$lname),
                  'p_dob'=> $dob,
                  'mrn'=>$mrn
              
              ]);
        }
      catch(\Exception $ex) { 
           // DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
  }
} 