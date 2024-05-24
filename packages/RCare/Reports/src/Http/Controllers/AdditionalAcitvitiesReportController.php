<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session; 
use RCare\System\Traits\DatesTimezoneConversion;   

class AdditionalAcitvitiesReportController extends Controller
{
   
    //created by radha(29dec2020)  
    public function AcitvitiesReportSearch(Request $request)
    {
      // dd('hello');
      $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
      $caremanager = sanitizeVariable($request->route('care_manager_id'));
      $practices = sanitizeVariable($request->route('practiceid'));
      $fromdate= sanitizeVariable($request->route('fromdate'));
      $fromdate1 =$fromdate." "."00:00:00";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
      $todate= sanitizeVariable($request->route('todate')); 
      $todate1 = $todate ." "."23:59:59";  
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
      $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
      $activityid = sanitizeVariable($request->route('activityid'));

   
    $query="select * from patients.sp_additional_activities_report($practices,$caremanager,'".$dt1."','".$dt2."',$practicesgrp,$activedeactivestatus,$activityid)";  

    // dd($query);   


    $data = DB::select($query);
      // dd($query); 
      // dd($data);
      return Datatables::of($data)
      ->addIndexColumn()   
       ->addColumn('action', function($row){
       $btn = '<a href="javascript:void(0)" class="detailsclick" id="'.$row->ppatient_id.'/'.$row->pfromdate.'/'.$row->ptodate.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a>';
               // $btn ='<img src="http://i.imgur.com/SD7Dz.png" id="'.$row->ppatient_id.'/'.$row->pfromdate.'/'.$row->ptodate.'" >';
                return $btn;
            }) 
      ->rawColumns(['action'])            
      ->make(true);
    }


    public function AcitvitiesReportDetails(Request $request)  
    {
        $patient_id = sanitizeVariable($request->route('patientid'));
        $fromdate= sanitizeVariable($request->route('fromdate'));
        $fromdate1 =$fromdate." "."00:00:00";
        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate); 
        $todate= sanitizeVariable($request->route('todate')); 
        //$todate1 = $todate ." "."23:59:59";  
        if(isset($todate)){
            $todate_new = explode(" ",$todate);
            $todate1 = $todate_new[0] ." "."23:59:59";  
        }else{
          $todate1 ='';
        }
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');   
        // $query="select * from patients.sp_additional_activities_details($patient_id,'".$fromdate."','".$todate."','".$configTZ ."','".$userTZ."')";
        $query="select * from patients.sp_additional_activities_details($patient_id,'".$dt1."','".$dt2."','".$configTZ ."','".$userTZ."')";
  
        $data = DB::select($query);    
           return Datatables::of($data) 
           ->addIndexColumn()                      
           ->make(true);

    } 

}



