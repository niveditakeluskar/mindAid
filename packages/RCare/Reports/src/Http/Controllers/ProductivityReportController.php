<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 

class ProductivityReportController extends Controller
{

  public function ProductivityReport(Request $request)
  {
    return view('Reports::productivity-report.productivity-report');
  }

  public function ProductivityDailyReport(Request $request)
  {
    return view('Reports::productivity-report.productivity-daily-report');
  }

//   public function ProductivityReportSearch(Request $request)
//   {
//     $practices = $request->route('practice'); 
//     $care_manager_id  =$request->route('care_manager_id'); 
//     $module_id =$request->route('modules');
//     $Reqfromdate=$request->route('fromdate');
//     $Reqtodate=$request->route('todate');

//     $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

//     $req_fromyear = date('Y', strtotime($Reqfromdate));
//     $req_frommonth = date('m', strtotime($Reqfromdate));

//     $req_toyear = date('Y', strtotime($Reqtodate));
//     $req_tomonth = date('m', strtotime($Reqtodate)); 
//       //dd($frommonth);

//     if($Reqfromdate == 'null'){
//       $frommonth = date('m', strtotime($take_current_month_year));
//       $fromyear = date('Y', strtotime($take_current_month_year));

//     }else{
//       $frommonth = $req_frommonth;
//       $fromyear =$req_fromyear;
//     }


//     if($Reqtodate == 'null'){
//       $tomonth = date('m', strtotime($take_current_month_year));
//       $toyear = date('Y', strtotime($take_current_month_year));

//     }else{
//       $tomonth = $req_tomonth;
//       $toyear =$req_toyear;
//     } 
//         //   left join patients.patient_time_records ptr2 on ptr.patient_id = ptr2.patient_id 
//     $query="select concat('".$frommonth."' ||'-' ||'".$fromyear."')as fdate,concat('".$tomonth."' ||'-' ||'".$toyear."') as tdate, concat(u.f_name || ' ' || u.l_name ) as caremanager, u.id as care_manager_id, p2.id as practice_id, p2.name as practice, 
//     sum( (EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+
//       EXTRACT(seconds FROM ptr.totaltime)/60) ) as totaltime, count(ptr.patient_id) as total_patients, sum(ptr.billable) as billable_patients
// from patients.caremanager_monthwise_total_time_spent ptr 
// inner join ren_core.users u on  ptr.created_by = u.id

// left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
//   from patients.patient_providers pp1
//   inner join (select patient_id, max(id) as maxid 
//     from patients.patient_providers  where provider_type_id = 1 and is_active = 1
//     group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.maxid
// and pp1.provider_type_id = 1 and is_active =1
// ) pp
// on ptr.patient_id = pp.patient_id
// left join patients.patient_time_records ptr2 on ptr.patient_id =ptr2.patient_id
// inner join ren_core.practices p2 on pp.practice_id = p2.id
// inner join ren_core.providers p3 on pp.provider_id = p3.id
// where 1=1";

// if($Reqfromdate!='null' && $Reqtodate!='null'){
//   $query.=" AND ptr.month_created between '".$frommonth."' and  '".$tomonth."' 
//   and ptr.year_created between '".$fromyear."' and  '".$toyear."' ";
// }
// if($practices=='null' || $practices==''){

// }else{
//   $query.=" AND pp.practice_id = '".$practices."' ";
// }    
// if($care_manager_id!='null')
// { 
//  $query.=" AND u.id = '".$care_manager_id."'";
// }

// if($module_id=='null' || $module_id==''){
//     // $query.="";
// }else if($module_id==0){
//     $query.="AND ptr2.module_id <> 3 AND ptr2.module_id <> 2";
// }else{
//   $query.="AND ptr2.module_id ='".$module_id."' ";
// }

// $query.="group by concat(u.f_name || ' ' || u.l_name ), u.id,p2.id, p2.name";

// $data = DB::select( DB::raw($query) );
// return Datatables::of($data)
// ->addIndexColumn() 
// ->addColumn('action', function($row){
//   //dd($row); href="javascript:void(0)" data-toggle="tooltip" id="patient-view" onClick=abc("'.$row->fdate.'","'.$row->tdate.'","'.$row->care_manager_id.'","'.$row->practice_id.'")
//   $btn ='<a href="/reports/patient-in-productivity-reports/'.$row->fdate.'/'.$row->tdate.'/'.$row->care_manager_id.'/'.$row->practice_id.'"  title="Start" target="_blank">View Patients<i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
//   return $btn; 
// })
// ->rawColumns(['action'])

// ->make(true);

// }

public function ProductivityDailyReportSearch(Request $request){

  $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
  $practices = empty($request->route('practice'))?'null':sanitizeVariable($request->route('practice')); 
  $care_manager_id  =empty($request->route('care_manager_id'))?'null':sanitizeVariable($request->route('care_manager_id')); 
  // $module_id =empty($request->route('modules'))?'null':sanitizeVariable($request->route('modules'));
  $Reqfromdate=sanitizeVariable($request->route('fromdate'));
  $Reqtodate=sanitizeVariable($request->route('todate'));
  $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
  // dd($activedeactivestatus);
  $configTZ = config('app.timezone');
  $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
  //dd($Reqtodate." to date");
  $today = date('Y-m-d');

  // Current day of the month.
  $first_date = date('Y-m-d', strtotime($today));

  //current day of the month.
  $last_date =date('Y-m-d', strtotime($today));

  $dt1 = $first_date;
  $dt2 = $last_date;

  if($Reqfromdate == 'null'){ 
    $first_date = date('Y-m-d', strtotime($today));
    $fromdate =$first_date." "."00:00:00";   
    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
  }else{
    $first_date = $request->route('fromdate');
    $fromdate =$first_date." "."00:00:00";
    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
  } 

  if($Reqtodate == 'null'){
    //$last_date =date('Y-m-t', strtotime($today));
  
    $last_date =date('Y-m-d', strtotime($today));
    $todate = $last_date ." "."23:59:59"; 
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);

  }else{
    $last_date =$request->route('todate'); 
    $todate = $last_date ." "."23:59:59"; 
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
  } 
 
  
//  dd($dt1."   ".$dt2 );


// $query=DB::select("select * from patients.caremanager_daywise_productivity(timestamp  '".$first_date." 00:00:00',timestamp  '".$last_date." 23:59:59',$care_manager_id,$practices,$module_id,$practicesgrp,$activedeactivestatus)");
// $query=DB::select("select * from patients.caremanager_daywise_productivity(timestamp  '".$first_date."',timestamp  '".$last_date."',$care_manager_id,$practices,$module_id,$practicesgrp,$activedeactivestatus)");

$query1 = ("select to_char(fdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as fdate,
to_char(tdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as tdate,
caremanager, care_manager_id, practice, practice_id, totaltime, totalpatients, billable_patients, pstatus,psmodule from patients.caremanager_daywise_productivity(timestamp  '".$dt1."',timestamp  '".$dt2."',$care_manager_id,$practices,$practicesgrp,$activedeactivestatus)");

// dd($query1);     

if($activedeactivestatus == 0123){
$query=DB::select("select to_char(fdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as fdate,
to_char(tdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as tdate,
caremanager, care_manager_id, practice, practice_id, totaltime, totalpatients, billable_patients,pstatus,psmodule 
from patients.caremanager_daywise_productivity(timestamp  '".$dt1."',timestamp  '".$dt2."',$care_manager_id,$practices,$practicesgrp,$activedeactivestatus)");
}else{
$query=DB::select("select to_char(fdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as fdate,
to_char(tdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as tdate,
caremanager, care_manager_id, practice, practice_id, totaltime, totalpatients, billable_patients,pstatus,psmodule 
from patients.caremanager_daywise_productivity(timestamp  '".$dt1."',timestamp  '".$dt2."',$care_manager_id,$practices,$practicesgrp,$activedeactivestatus)");
}
$data = $query;   
$module_id = null;
return Datatables::of($data)    
->addIndexColumn()
->addColumn('action', function($row) use ($module_id, $Reqfromdate, $Reqtodate){
  $practice_id = (isset($row->practice_id) && $row->practice_id != null ) ? $row->practice_id : 0;
  $care_manager_id = (isset($row->care_manager_id) && $row->care_manager_id != null ) ? $row->care_manager_id : 0;
  // dd($row); //href="javascript:void(0)" data-toggle="tooltip" id="patient-view" onClick=abc("'.$row->fdate.'","'.$row->tdate.'","'.$row->care_manager_id.'","'.$row->practice_id.'")
  // $btn ='<a href="/reports/patient-in-daily-productivity-reports/'.$Reqfromdate.'/'.$Reqtodate.'/'.$care_manager_id.'/'.$practice_id.'/'.$row->psmodule.'/'.$row->pstatus. '"  title="Start" target="_blank">View Patients<i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
  $btn ='<a href="/reports/patient-in-daily-productivity-reports/'.$Reqfromdate.'/'.$Reqtodate.'/'.$care_manager_id.'/'.$practice_id.'/'.$row->pstatus. '"  title="Start" target="_blank">View Patients<i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';   
  
  return $btn;
}) 
->rawColumns(['action'])

->make(true);
}
public function PatientInProductivityReport(Request $request){

  $practices = sanitizeVariable($request->route('practice_id')); 
  $caremanager =sanitizeVariable($request->route('care_manager_id')); 
  $fromdate=sanitizeVariable($request->route('fdate'));
  $todate=sanitizeVariable($request->route('tdate'));

  return view('Reports::productivity-report.patient-in-productivity',compact('practices','caremanager','fromdate','todate'));
}

/*
public function PatientInProductivityReportSearch(Request $request)
{  
  $practices = sanitizeVariable($request->route('practice_id')); 
  $care_manager_id  = sanitizeVariable($request->route('care_manager_id')); 
  $fromdate= sanitizeVariable($request->route('fdate')); 
  $todate= sanitizeVariable($request->route('tdate'));
  $module_id = sanitizeVariable($request->route('module_id'));

  $fdate = explode("-", $fromdate);
  $tdate = explode("-", $todate);

  $fromyear = $fdate[1]; 
  $frommonth = $fdate[0];

  $toyear = $tdate[1];
  $tomonth =$tdate[0]; 

  $query="select concat(u.f_name || ' ' || u.l_name ) as caremanager, 
  p.fname, p.mname, p.lname, ptr.patient_id, u.id as care_manager_id,
  p2.id as practice_id, p2.name as practice,p3.name as provider,
  sum( (EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+
    EXTRACT(seconds FROM ptr.totaltime)/60) )  as totaltime, count(ptr.patient_id) as total_patients, sum(ptr.billable) as billable_patients
from patients.caremanager_monthwise_total_time_spent ptr inner join ren_core.users u
on  ptr.created_by = u.id
left join patients.patient p on p.id = ptr.patient_id 

left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
  from patients.patient_providers pp1
  inner join (select patient_id, max(id) as maxid 
    from patients.patient_providers  where provider_type_id = 1 and is_active = 1
    group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.maxid
and pp1.provider_type_id = 1 and is_active =1
) pp
on ptr.patient_id = pp.patient_id

inner join ren_core.practices p2 on pp.practice_id = p2.id
inner join ren_core.providers p3 on pp.provider_id = p3.id where p.id is not null";

           // if($fromdate!='null' && $todate!='null')
           // {
           //   $query.=" AND (ptr.month_created between '".$frommonth."' and  '".$tomonth."') 
           //   and (ptr.year_created between '".$fromyear."' and  '".$toyear."')";
           // }

if( $practices!='null')
{
  $query.=" AND pp.practice_id = '".$practices."' ";
}


if($care_manager_id!='null')
{
 $query.=" AND u.id = '".$care_manager_id."'";
}

$query.="group by concat(u.f_name || ' ' || u.l_name ), u.id,
p2.id, p2.name, p3.name, p.fname, p.mname, p.lname, 
ptr.patient_id";

            // echo $query;
$data = DB::select( DB::raw($query) );
            // print_r($data); 
return Datatables::of($data)
->addIndexColumn()
->rawColumns(['action'])
->make(true);
}
*/
public function PatientInDailyProductivityReport(Request $request){
// dd($request->all());
  $practices = sanitizeVariable($request->route('practice_id')); 
  $caremanager =sanitizeVariable($request->route('care_manager_id')); 
  $fromdate=sanitizeVariable($request->route('fdate'));
  $todate=sanitizeVariable($request->route('tdate'));
  // $module_id =sanitizeVariable($request->route('module_id'));
  $module_id = null;
  $pstatus =sanitizeVariable($request->route('pstatus'));
  return view('Reports::productivity-report.patient-in-daily-productivity',compact('practices','caremanager','fromdate','todate','module_id','pstatus'));
}


public function PatientInDailyProductivityReportSearch(Request $request)
{  
  $practices = sanitizeVariable($request->route('practice_id')); 
  $care_manager_id  =sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate=sanitizeVariable($request->route('fdate'));
  $Reqtodate=sanitizeVariable($request->route('tdate')); 
  // $module_id =sanitizeVariable($request->route('module_id'));
  $module_id = 'null';
  $status =sanitizeVariable($request->route('pstatus'));
  $today = date('Y-m-d');
   
  // dd($module_id);
  if($Reqfromdate == 'null'){ 
    $first_date = date('Y-m-d', strtotime($today));
    $fromdate =$first_date." "."00:00:00";   
    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
  }else{
    $first_date = $request->route('fdate');
    $fromdate =$first_date." "."00:00:00";
    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
  } 

  if($Reqtodate == 'null'){
    //$last_date =date('Y-m-t', strtotime($today));
    $last_date =date('Y-m-d', strtotime($today));
    $todate = $last_date ." "."23:59:59"; 
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);

  }else{
    $last_date =$request->route('tdate'); 
    $todate = $last_date ." "."23:59:59"; 
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
  } 


  
$query="select * from patients.caremanager_daywise_productivity_patients
(timestamp  '".$dt1."',timestamp  '".$dt2."',$care_manager_id,$practices,$status,$module_id)";
//  echo $query;
// dd($query);      
$data = DB::select( DB::raw($query) );
// dd($data);   
return Datatables::of($data) 
->addIndexColumn()
->rawColumns(['action'])
->make(true);
}

public function ProductivityDailySummary(Request $request){
  $today = date('Y-m-d');

  // current day of the month.
  $first_date = date('Y-m-d', strtotime($today));
  //current day of the month.
  $last_date =date('Y-m-d', strtotime($today));

  $care_manager_id=session()->get('userid');

  //select count(*) from patients.patient;
  // $query="select count(*) from patients.patient where status=1"; 
  $query ="select * from patients.active_patient_count()"; 
  $totalPatient=DB::select( DB::raw($query) );

  //select count(*) from ren_core.users where role=5 AND status=1
  $query1="select count(*) from ren_core.users where role=5 AND status=1";
  //"select * from ren_core.caremanagers_count()";  
  $totalCaremanagers=DB::select(DB::raw($query1) ); 

   //$query2=DB::select("select * from patients.productivity_worked_on_patients('".$first_date."','".$last_date."','".$care_manager_id."')");  
  //$query2="select * from patients.productivity_worked_on_patients_count('".$first_date."','".$last_date."')";
 //$query2="select * from patients.productivity_worked_on_patients_count('".$first_date." 00:00:00','".$last_date."')";
      $query2= "select count(distinct ptr.patient_id) 
       from 
       patients.patient_time_records ptr
       left join patients.view_caremanager_daywise_total_time_spent ptr2
       on ptr.patient_id = ptr2.patient_id 
       where date(ptr.created_at) between '".$first_date." 00:00:00' and '".$last_date." 23:59:59'
       ";
  $dailytotalPatientWorkedon=DB::select( DB::raw($query2) ); 

  // $query3=DB::select("select * from patients.productivity_billable_patients( '".$first_date."','".$last_date."','".$care_manager_id."')");
//   $query3="select count(distinct ptr.patient_id) from 
// patients.patient p inner join 
// patients.patientwise_caremanager_productivity('".$first_date."','".$last_date."',null, null, null) ptr
// on p.id = ptr.patient_id
// where billable = 1";
  $query3 ="select count(distinct ptr.patient_id) 
       from patients.view_caremanager_daywise_total_time_spent ptr
       where date(ptr.date_created) between '".$first_date." 00:00:00' and '".$last_date." 23:59:59' 
      AND ptr.billable = 1";
  $dailytotalBillablePatients = DB::select( DB::raw($query3) );

  //select count(*) from ren_core.practices where is_active=1
  $query4="select count(*) from ren_core.practices where is_active=1";
  //"select * from ren_core.practice_count()";  
  $totalpractices=DB::select( DB::raw($query4) );  

  // $query5 ="select * from patients.productivity_billable_practice('".$first_date."','".$last_date."',$care_manager_id)";
  // $dailytotalpracticebillable =DB::select(DB::raw($query5)); 

$data=array('Totalpatient'=>$totalPatient,
  'TotalCareManager'=>$totalCaremanagers,
  'TotalPatientWorkedon'=> $dailytotalPatientWorkedon,
  'TotalBillablepatient'=>$dailytotalBillablePatients,
  'Totalpractices'=>$totalpractices,
  // 'TotalPracticesBillablePatient'=>$dailytotalpracticebillable
  );
return $data; 
}
public function ProductivitySummary(Request $request)
{ 
  $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));
        // dd($take_current_month_year);
  $current_year = date('Y', strtotime($take_current_month_year));
  $current_month = date('m', strtotime($take_current_month_year));

  $query="select count(*) from patients.patient where status=1";  
  $totalPatient=DB::select( DB::raw($query) );

  $query1="select count(*) from ren_core.users where role=5 AND status=1";  
  $totalCaremanagers=DB::select( DB::raw($query1) );

  $query2="select count(distinct ptr.patient_id) 
  from patients.patient_time_records ptr
  inner join patients.patient p on ptr.patient_id=p.id
  left join patients.caremanager_monthwise_total_time_spent pcm on ptr.patient_id = pcm.patient_id
  where pcm.month_created=' ".$current_month."' and pcm.year_created=' ".$current_year." ' ";  
  $totalPatientWorkedon=DB::select( DB::raw($query2) );

  $query3="select count(distinct pcm.patient_id) 
  from patients.caremanager_monthwise_total_time_spent pcm,patients.patient p 
  where billable=1 and pcm.patient_id=p.id and pcm.month_created=' ".$current_month."' and pcm.year_created=' ".$current_year." ' ";
  $totalBillablePatients = DB::select( DB::raw($query3) );

  $query4="select count(*) from ren_core.practices where is_active=1";  
  $totalpractices=DB::select( DB::raw($query4) );

  $query5 ="select count(distinct pp.practice_id)
  from patients.caremanager_monthwise_total_time_spent  pcm inner join patients.patient p 
  on pcm.patient_id = p.id 
  inner join 
  (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
    from patients.patient_providers pp1 inner join (select patient_id, max(id) as maxid 
      from patients.patient_providers where provider_type_id = 1 and is_active = 1 group by patient_id ) as pp2 
on pp1.patient_id = pp2.patient_id and pp1.id = pp2.maxid and pp1.provider_type_id = 1 and is_active =1 
)
pp  on pcm.patient_id=pp.patient_id
and pcm.billable=1 and pcm.month_created=' ".$current_month."' and pcm.year_created=' ".$current_year." ' ";
$totalpracticebillable =DB::select(DB::raw($query5)); 

$data=array('Totalpatient'=>$totalPatient,
  'TotalCareManager'=>$totalCaremanagers,
  'TotalPatientWorkedon'=> $totalPatientWorkedon,
  'TotalBillablepatient'=>$totalBillablePatients,
  'Totalpractices'=>$totalpractices,
  'TotalPracticesBillablePatient'=>$totalpracticebillable);
return $data;

}


public function viewTotalPatients(Request $request)
{ 
 return view('Reports::productivity-report.sub-steps-productivity-report.patients-list');
}
public function patientWorkedon(Request $request)
{
 return view('Reports::productivity-report.sub-steps-productivity-report.patient-worked-on-list');
}
public function patientDailyWorkedon(Request $request)
{
 return view('Reports::productivity-report.sub-steps-daily-productivity-report.patient-worked-on-list');
}

public function ProductivityPatients(Request $request)
{   
  $practices =sanitizeVariable($request->route('practice')); 
  $care_manager_id  =$request->route('caremanager');
  $query="select * from patients.patient_details($practices)";
    $data = DB::select( DB::raw($query) );
    return Datatables::of($data)
    ->addIndexColumn()             
    ->make(true);
}

public function ProductivityPractice(Request $request)
{   
  $practices = sanitizeVariable($request->route('practice'));
  if($request->ajax())
  {   
    $query ="select * from ren_core.practices_details($practices)";
    $data = DB::select( DB::raw($query) );
    return Datatables::of($data)
    ->addIndexColumn()            
    ->make(true);
  }
}

public function ProductivityCaremanager(Request $request)
{   
  $caremanager = sanitizeVariable($request->route('caremanager'));
  if($request->ajax())
  {
    $query ="select * from ren_core.caremanagers_details($caremanager)";
    $data = DB::select( DB::raw($query) ); 
    return Datatables::of($data)  
    ->addIndexColumn()       
    ->make(true);
  }
}

public function ProductivityBillablePatients(Request $request)
{   

  $practices = sanitizeVariable($request->route('practice')); 
  $care_manager_id  =sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate=sanitizeVariable($request->route('fromdate'));
  $Reqtodate=sanitizeVariable($request->route('todate'));

  $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

  $req_fromyear = date('Y', strtotime($Reqfromdate));
  $req_frommonth = date('m', strtotime($Reqfromdate));

  $req_toyear = date('Y', strtotime($Reqtodate));
  $req_tomonth = date('m', strtotime($Reqtodate)); 
      //dd($frommonth);

  if($Reqfromdate == 'null'){
    $frommonth = date('m', strtotime($take_current_month_year));
    $fromyear = date('Y', strtotime($take_current_month_year));

  }else{
    $frommonth = $req_frommonth;
    $fromyear =$req_fromyear;
  }


  if($Reqtodate == 'null'){
    $tomonth = date('m', strtotime($take_current_month_year));
    $toyear = date('Y', strtotime($take_current_month_year));

  }else{
    $tomonth = $req_tomonth;
    $toyear =$req_toyear;
  }

      // echo $frommonth.''.$fromyear;
      // echo $tomonth.''.$toyear;

      // if($request->ajax())
      // { 
  $query = "select distinct pcm.patient_id,p.fname,p.lname,p.mname,p.dob,
  concat(pra.name || ' ' || pra.location ) as practice,
  pr.name as provider_name,p.created_at as registered_date,pp.practice_emr
  from patients.caremanager_monthwise_total_time_spent pcm
  inner join patients.patient p on pcm.patient_id =p.id
  LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
    from patients.patient_providers pp1
    inner join (select patient_id, max(id) as created_date 
      from patients.patient_providers  where provider_type_id = 1 
      group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.created_date and pp1.provider_type_id = 1 and is_active =1) pp on p.id = pp.patient_id
left join ren_core.practices pra on pp.practice_id=pra.id
left join ren_core.providers pr on pp.provider_id=pr.id
where pcm.billable=1 and pcm.patient_id =p.id and
pcm.month_created between '".$frommonth."' and  '".$tomonth."' 
and pcm.year_created between '".$fromyear."' and  '".$toyear."' 
";

if($practices=='null' || $practices=='') 
{

}else{
  $query.=" AND pp.practice_id = '".$practices."' ";
}
if($Reqfromdate!='null' && $Reqtodate!='null')
{
 $query.=" AND (pcm.month_created between '".$frommonth."' and  '".$tomonth."') and (pcm.year_created between '".$fromyear."' and  '".$toyear."')";
}
          // echo $query;
$data = DB::select( DB::raw($query) );
          // dd($query);
return Datatables::of($data)     
->addIndexColumn()         
->make(true);
      // }
}

public function ProductivityDailyBillablePatients(Request $request)
{   

  $practice_id = sanitizeVariable($request->route('practice')); 
  $care_manager_id  =sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate=sanitizeVariable($request->route('fromdate'));
  $Reqtodate=sanitizeVariable($request->route('todate'));
  $module_id=sanitizeVariable($request->route('modules'));

  $today = date('Y-m-d');

  // First day of the month.
  $start_date = date('Y-m-d', strtotime($today));

  //Last day of the month. 
  $end_date =date('Y-m-d', strtotime($today));



  if($Reqfromdate == 'null'){
    $first_date = $start_date;
  }else{
    $first_date = sanitizeVariable($request->route('fromdate'));
  }

  if($Reqtodate == 'null'){
    $last_date = $end_date;
  }else{
    $last_date =sanitizeVariable($request->route('todate'));
  } 
 
$query = "select * from patients.productivity_billable_patients_details('".$first_date."','".$last_date."',$care_manager_id,$practice_id,$module_id)";
$data = DB::select( DB::raw($query) );
return Datatables::of($data)     
->addIndexColumn()         
->make(true);
      // }
}


public function ProductivityPatientsWorkedOn(Request $request)
{   
  $practices = sanitizeVariable($request->route('practice')); 
  $care_manager_id  =sanitizeVariable($request->route('care_manager_id'));  
  $Reqfromdate=sanitizeVariable($request->route('fromdate'));
  $Reqtodate=sanitizeVariable($request->route('todate'));

  $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

  $req_fromyear = date('Y', strtotime($Reqfromdate));
  $req_frommonth = date('m', strtotime($Reqfromdate));

  $req_toyear = date('Y', strtotime($Reqtodate));
  $req_tomonth = date('m', strtotime($Reqtodate)); 
      //dd($frommonth);

  if($Reqfromdate == 'null'){
    $frommonth = date('m', strtotime($take_current_month_year));
    $fromyear = date('Y', strtotime($take_current_month_year));

  }else{
    $frommonth = $req_frommonth;
    $fromyear =$req_fromyear;
  }


  if($Reqtodate == 'null'){
    $tomonth = date('m', strtotime($take_current_month_year));
    $toyear = date('Y', strtotime($take_current_month_year));

  }else{
    $tomonth = $req_tomonth;
    $toyear =$req_toyear;
  }

  if($request->ajax()){    
    $query = "select distinct ptr.patient_id,p.id,p.fname,p.lname,p.mname,p.dob,
    concat(pra.name || ' ' || pra.location ) as practice,
    pr.name as provider_name,p.created_at as registered_date,pp.practice_emr
    from patients.patient_time_records ptr   
    inner join patients.patient p on ptr.patient_id =p.id
    left join patients.caremanager_monthwise_total_time_spent pcm on ptr.patient_id=pcm.patient_id
    LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
      from patients.patient_providers pp1
      inner join (select patient_id, max(id) as maxid 
        from patients.patient_providers  where provider_type_id = 1 
        group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.maxid
and pp1.provider_type_id = 1 ) pp                 
on p.id = pp.patient_id  
left join ren_core.practices pra on pp.practice_id=pra.id
left join ren_core.providers pr on pp.provider_id=pr.id
where pcm.month_created between '".$frommonth."' and  '".$tomonth."' 
and pcm.year_created between '".$fromyear."' and  '".$toyear."' ";       

if($practices=='null' || $practices=='')
{

} 
else
{
  $query.=" AND pp.practice_id = '".$practices."' ";
}
if($Reqfromdate!='null' && $Reqtodate!='null')
{
  $query.=" AND (pcm.month_created between '".$frommonth."' and  '".$tomonth."') and (pcm.year_created between '".$fromyear."' and  '".$toyear."')";
}
$data = DB::select( DB::raw($query) );
return Datatables::of($data) 
->addIndexColumn()            
->make(true);
}
}

public function ProductivityDailyPatientsWorkedOn(Request $request)
{   
  $practices = sanitizeVariable($request->route('practice')); 
  $care_manager_id  = sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate= sanitizeVariable($request->route('fromdate'));
  $Reqtodate= sanitizeVariable($request->route('todate'));
  $module_id= sanitizeVariable($request->route('modules')); 
  
  $today = date('Y-m-d');

  // First day of the month.
  $start_date = date('Y-m-d', strtotime($today));
  //Last day of the month. 
  $end_date =date('Y-m-d', strtotime($today));

  if($Reqfromdate == 'null'){
    $fromdate = $start_date;
  }else{
    $fromdate = $request->route('fromdate');
  }
  
  if($Reqtodate == 'null'){
    $todate = $end_date;
  }else{
    $todate =$request->route('todate');
  } 
  $query ="select * from patients.productivity_worked_on_patients_details('".$fromdate."','".$todate."',$care_manager_id,$practices,$module_id)";       
  // $query ="select * from patients.productivity_worked_on_patients_details('2020-12-07','2020-12-07',null,24,null)";       
    // dd($query);
    $data = DB::select( DB::raw($query) );
    return Datatables::of($data) 
    ->addIndexColumn()            
    ->make(true);

}


public function ProductivityPracticeBillablePatients(Request $request)
{

  $practices = sanitizeVariable($request->route('practice')); 
  $care_manager_id  = sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate= sanitizeVariable($request->route('fromdate'));
  $Reqtodate= sanitizeVariable($request->route('todate'));

  $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

  $req_fromyear = date('Y', strtotime($Reqfromdate));
  $req_frommonth = date('m', strtotime($Reqfromdate));

  $req_toyear = date('Y', strtotime($Reqtodate));
  $req_tomonth = date('m', strtotime($Reqtodate)); 
      //dd($frommonth);

  if($Reqfromdate == 'null'){
    $frommonth = date('m', strtotime($take_current_month_year));
    $fromyear = date('Y', strtotime($take_current_month_year));

  }else{
    $frommonth = $req_frommonth;
    $fromyear =$req_fromyear;
  }


  if($Reqtodate == 'null'){
    $tomonth = date('m', strtotime($take_current_month_year));
    $toyear = date('Y', strtotime($take_current_month_year));

  }else{
    $tomonth = $req_tomonth;
    $toyear =$req_toyear;
  }

  if($request->ajax())
  {    
    $query = "select distinct pp.practice_id,pra.name,pra.location,pra.phone,pra.address,pra.number,
    sum ((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+ EXTRACT(seconds FROM ptr.totaltime)/60) )
    as totaltime,
    count(ptr.patient_id) as total_patients, sum(ptr.billable) as billable_patients
    from patients.caremanager_monthwise_total_time_spent ptr 
    inner join ren_core.users u on ptr.created_by = u.id 
    left join patients.patient p on p.id = ptr.patient_id 
    inner join 
    (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
      from patients.patient_providers pp1 inner join (select patient_id, max(id) as created_date 
        from patients.patient_providers where provider_type_id = 1 and is_active = 1 group by patient_id ) as pp2 
on pp1.patient_id = pp2.patient_id and pp1.id = pp2.created_date and pp1.provider_type_id = 1 and is_active =1 
)
pp on ptr.patient_id=pp.patient_id
left join ren_core.practices pra on pp.practice_id=pra.id 
where pra.is_active=1 and ptr.billable=1 ";
if($practices=='null' || $practices==''){
}else{
  $query.=" AND pp.practice_id = '".$practices."' ";
}
if($Reqfromdate!='null' && $Reqtodate!='null'){
  $query.=" AND (pcm.month_created between '".$frommonth."' and  '".$tomonth."') and (pcm.year_created between '".$fromyear."' and  '".$toyear."')";
}

$query.="group by pp.practice_id,pra.name,pra.location,pra.phone,pra.address,pra.number";
$data = DB::select( DB::raw($query) );
return Datatables::of($data) 
->addIndexColumn()            
->make(true);

}
}

public function ProductivityDailyPracticeBillablePatients(Request $request)
{

  $practices = sanitizeVariable($request->route('practice')); 
  $care_manager_id  = sanitizeVariable($request->route('care_manager_id')); 
  $Reqfromdate= sanitizeVariable($request->route('fromdate'));
  $Reqtodate= sanitizeVariable($request->route('todate'));

  $take_current_month_year = date('F Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

  $req_fromyear = date('Y', strtotime($Reqfromdate));
  $req_frommonth = date('m', strtotime($Reqfromdate));

  $req_toyear = date('Y', strtotime($Reqtodate));
  $req_tomonth = date('m', strtotime($Reqtodate)); 
      //dd($frommonth);

  if($Reqfromdate == 'null'){
    $frommonth = date('m', strtotime($take_current_month_year));
    $fromyear = date('Y', strtotime($take_current_month_year));

  }else{
    $frommonth = $req_frommonth;
    $fromyear =$req_fromyear;
  }


  if($Reqtodate == 'null'){
    $tomonth = date('m', strtotime($take_current_month_year));
    $toyear = date('Y', strtotime($take_current_month_year));

  }else{
    $tomonth = $req_tomonth;
    $toyear =$req_toyear;
  }

  if($request->ajax())
  {    
    $query = "select distinct pp.practice_id,pra.name,pra.location,pra.phone,pra.address,pra.number,
    sum ((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+ EXTRACT(seconds FROM ptr.totaltime)/60) )
    as totaltime,
    count(ptr.patient_id) as total_patients, sum(ptr.billable) as billable_patients
    from patients.caremanager_monthwise_total_time_spent ptr 
    inner join ren_core.users u on ptr.created_by = u.id 
    left join patients.patient p on p.id = ptr.patient_id 
    inner join 
    (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
      from patients.patient_providers pp1 inner join (select patient_id, max(id) as created_date 
        from patients.patient_providers where provider_type_id = 1 and is_active = 1 group by patient_id ) as pp2 
on pp1.patient_id = pp2.patient_id and pp1.id = pp2.created_date and pp1.provider_type_id = 1 and is_active =1 
)
pp on ptr.patient_id=pp.patient_id
left join ren_core.practices pra on pp.practice_id=pra.id 
where pra.is_active=1 and ptr.billable=1 ";
if($practices=='null' || $practices==''){
}else{
  $query.=" AND pp.practice_id = '".$practices."' ";
}
if($Reqfromdate!='null' && $Reqtodate!='null'){
  $query.=" AND (pcm.month_created between '".$frommonth."' and  '".$tomonth."') and (pcm.year_created between '".$fromyear."' and  '".$toyear."')";
}

$query.="group by pp.practice_id,pra.name,pra.location,pra.phone,pra.address,pra.number";
$data = DB::select( DB::raw($query) );
return Datatables::of($data) 
->addIndexColumn()            
->make(true); 

}
}


}