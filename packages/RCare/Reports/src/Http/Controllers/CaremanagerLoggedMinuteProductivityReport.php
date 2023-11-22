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
use RCare\Org\OrgPackages\Responsibility\src\Models\Responsibility;
use RCare\Org\OrgPackages\Users\src\Models\Users;
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session;

class CaremanagerLoggedMinuteProductivityReport extends Controller
{

  //   public function CaremanagerLoggedMinuteProductivityReportSearch(Request $request)
  //   {
  //     $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
  //     $fromdate= sanitizeVariable($request->route('fromdate'));
  //     // dd($fromdate);
  //     $fromdate1 =$fromdate." "."00:00:00";
  //     $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
  //     $todate= sanitizeVariable($request->route('todate')); 
  //     $todate1 = $todate ." "."23:59:59";  
  //     $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
  //     $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 

  //     $query = "select pg.id,pg.practice_name ,concat(up.f_name || ' ' || up.l_name ) as caremanager,
    // p.name,
    // round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-25 00:00:00' and '2022-05-25 23:59:59')::numeric, 2) as five_days,
    // round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-26 00:00:00' and '2022-03-26 23:59:59')::numeric, 2) as four_days,
    // round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-27 00:00:00' and '2022-03-27 23:59:59')::numeric, 2) as three_days,
    // round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-28 00:00:00' and '2022-03-28 23:59:59')::numeric, 2) as two_days,
    // round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-29 00:00:00' and '2022-03-29 23:59:59')::numeric, 2) as yesterday,
    // round(avg(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    // FILTER(where ptr.record_date between '2022-03-29 00:00:00' and '2022-03-29 23:59:59')::numeric, 2) AS total_avg 
    // from patients.patient_time_records ptr
    // inner join ren_core.users up on ptr.created_by = up.id
    // inner join ren_core.practices p on p.id=up.practice__id 
    // inner join ren_core.practicegroup pg on pg.id=p.practice_group
    // --where pg.id =17  --ptr.record_date between '2022-01-25 00:00:00' and '2022-03-25 23:59:59'
    // group by ptr.created_by,up.f_name,up.l_name,pg.practice_name,pg.id,p.name";

  //   $data1 = DB::select(DB::raw($query));
  //   $data=array_filter($data1);

  //   foreach($data as $ac){
  //       // $date1=$ac->pfromdate;
  //       // $date2=$ac->ptodate;
  //       $patient_id=$ac->id;

  //       //$fromdate= sanitizeVariable($request->route('fromdate'));
  //       // $date11 =$date1." "."00:00:00";
  //       // $from_date = DatesTimezoneConversion::userToConfigTimeStamp( $date11); 
        
  //       // if(isset($date2)){
  //       //     $todate_new = explode(" ",$date2);
  //       //     $todate11 = $todate_new[0] ." "."23:59:59";  
  //       // }else{
  //       //   $todate11 ='';
  //       // } 
  //       // // dd($todate1);
  //       // $to_date = DatesTimezoneConversion::userToConfigTimeStamp( $todate11); 


  //     $configTZ = config('app.timezone');
  //     $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');   
  //     $childquery="select pg.id,pg.practice_name ,concat(up.f_name || ' ' || up.l_name ) as caremanager,
  //                 round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-25 00:00:00' and '2022-05-25 23:59:59')::numeric, 2) as five_days,
  //                 round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-26 00:00:00' and '2022-03-26 23:59:59')::numeric, 2) as four_days,
  //                 round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-27 00:00:00' and '2022-03-27 23:59:59')::numeric, 2) as three_days,
  //                 round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-28 00:00:00' and '2022-03-28 23:59:59')::numeric, 2) as two_days,
  //                 round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-29 00:00:00' and '2022-03-29 23:59:59')::numeric, 2) as yesterday,
  //                 round(avg(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
  //                 FILTER(where ptr.record_date between '2022-03-29 00:00:00' and '2022-03-29 23:59:59')::numeric, 2) AS total_avg 
  //                 from patients.patient_time_records ptr
  //                 inner join ren_core.users up on ptr.created_by = up.id
  //                 inner join ren_core.practices p on p.id=up.practice__id 
  //                 inner join ren_core.practicegroup pg on pg.id=p.practice_group
  //                 where pg.id = '".$patient_id."'
  //                 group by ptr.created_by,up.f_name,up.l_name,pg.practice_name,pg.id";
  //     // "select * from patients.sp_additional_activities_details($patient_id,'".$date1."','".$date2."','".$configTZ ."','".$userTZ."')";
  //     $childdata = DB::select(DB::raw($childquery));
     
  //       $ac->results=$childdata;
    
  //   }
  //     return Datatables::of($data)
  //     ->addIndexColumn()           
  //     ->make(true);
  //   }

  public function CaremanagerLoggedMinuteProductivityReportSearch(Request $request){

    $practice_id = sanitizeVariable($request->route('practiceid'));
    $fromdate= sanitizeVariable($request->route('fromdate')); 

    $five_days_start = date('Y-m-d', strtotime('-5 day', strtotime($fromdate)))." "."00:00:00";
    $five_days_end = date('Y-m-d', strtotime('-5 day', strtotime($fromdate)))." "."23:59:59";
    
    $four_days_start = date('Y-m-d', strtotime('-4 day', strtotime($fromdate)))." "."00:00:00";
    $four_days_end = date('Y-m-d', strtotime('-4 day', strtotime($fromdate)))." "."23:59:59"; 

    $three_days_start = date('Y-m-d', strtotime('-3 day', strtotime($fromdate)))." "."00:00:00";
    $three_days_start = date('Y-m-d', strtotime('-3 day', strtotime($fromdate)))." "."23:59:59";

    $two_days_start = date('Y-m-d', strtotime('-2 day', strtotime($fromdate)))." "."00:00:00";
    $two_days_end = date('Y-m-d', strtotime('-2 day', strtotime($fromdate)))." "."23:59:59";

    $yesterday_start = date('Y-m-d', strtotime('-1 day', strtotime($fromdate)))." "."00:00:00"; 
    $yesterday_end = date('Y-m-d', strtotime('-1 day', strtotime($fromdate)))." "."23:59:59";

    $from_five_days=DatesTimezoneConversion::userToConfigTimeStamp($five_days_start);
    $to_five_days=DatesTimezoneConversion::userToConfigTimeStamp($five_days_end);

    $from_four_days=DatesTimezoneConversion::userToConfigTimeStamp($four_days_start);
    $to_four_days=DatesTimezoneConversion::userToConfigTimeStamp($four_days_end);

    $from_three_days=DatesTimezoneConversion::userToConfigTimeStamp($three_days_start);
    $to_three_days=DatesTimezoneConversion::userToConfigTimeStamp($five_days_end);

    $from_two_days=DatesTimezoneConversion::userToConfigTimeStamp($two_days_start);
    $to_two_days=DatesTimezoneConversion::userToConfigTimeStamp($two_days_end);

    $from_yesterday=DatesTimezoneConversion::userToConfigTimeStamp($yesterday_start);
    $to_yesterday=DatesTimezoneConversion::userToConfigTimeStamp($yesterday_end);

    $query ="select p.id, concat(up.f_name || ' ' || up.l_name ) as caremanager,
    p.name,
    coalesce(round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    FILTER(where ptr.record_date between '".$from_five_days."' and '".$to_five_days."')::numeric, 2),0) as five_days, 
    coalesce(round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60)
    FILTER(where ptr.record_date between '".$from_four_days."' and '".$to_four_days."')::numeric, 2),0) as four_days,
    coalesce(round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    FILTER(where ptr.record_date between '".$from_three_days."' and '".$to_three_days."')::numeric, 2),0) as three_days,
    coalesce(round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    FILTER(where ptr.record_date between '".$from_two_days."' and '".$to_two_days."')::numeric, 2),0) as two_days,
    coalesce(round(sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    FILTER(where ptr.record_date between '".$from_yesterday."' and '".$to_yesterday."')::numeric, 2),0) as yesterday,
    coalesce(round((sum(EXTRACT(hour from ptr.net_time)*60 + EXTRACT(minutes from ptr.net_time) + EXTRACT(seconds from ptr.net_time) / 60) 
    FILTER(where ptr.record_date between '".$from_five_days."' and '".$to_yesterday."')/5)::numeric, 2),0) as total_avg 
    from patients.patient_time_records ptr 
    inner join ren_core.users up on ptr.created_by = up.id 
    inner join ren_core.practices p on p.id=up.practice__id  
    --inner join ren_core.practicegroup pg on pg.id=p.practice_group 
    where 1=1"; 
    if($practice_id=='' || $practice_id=='null'){}else{
    $query.=" AND p.id ='".$practice_id."' ";
    }
    $query.="group by ptr.created_by,up.f_name,up.l_name,p.id,p.name";
    
        $data = DB::select(DB::raw($query));    
        return Datatables::of($data)
        ->addIndexColumn()                      
        ->make(true);
  }
}
?>
