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

class MonthlyAccountPerfomanceReport extends Controller
{
    
        public function listMonthlyAccountPerformanceReport(Request $request)//manually adjust timer report storeprocedure
    {   
        if ($request->ajax()) { 
          $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
          $practices    = sanitizeVariable($request->route('practice'));
          $module_id    = sanitizeVariable($request->route('modules'));
      
          $configTZ = config('app.timezone');
          $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
          $from_monthly = sanitizeVariable($request->route('from_month'));  
          // dd($from_monthly);
          $year = date('Y', strtotime($from_monthly));
          $month = date('m', strtotime($from_monthly));
          // $to_monthly   = sanitizeVariable($request->route('to_month'));  
             
          //   if($module_id == '' ||  $module_id == 'null'){
          //       $module_id=3;
          //   } 
          //   if($from_monthly=='' || $from_monthly=='null' || $from_monthly=='0'){
          //       $from_monthly1=date('Y-m');
          //   }else{
          //       $from_monthly1=$from_monthly;
          //   } 

          //   if($to_monthly=='' || $to_monthly=='null' || $to_monthly=='0'){
          //       $to_monthly1=date('Y-m');
          //   }else{ 
          //        $to_monthly1=$to_monthly;
          //   }
     
          //   $fromyear  = date('Y', strtotime($from_monthly1));
          //   $frommonth = date('m', strtotime($from_monthly1));

          //   $toyear = date('Y', strtotime($to_monthly1)); 
          //   $tomonth = date('m', strtotime($to_monthly1));  
              
          //   $fromdate=$toyear.'-'.$frommonth.'-01 00:00:00';            
          //   $to_date= $fromyear.'-'.$tomonth.'-01';
            // $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
            // $todate=date('Y-m-d', $convertdate);
                // --and mpra and pt1.patient_id = 172258696
            $query = "select pp.practice_id,mpra.name,count(distinct ps.patient_id) ,
            COUNT(distinct ps.patient_id) filter (where EXTRACT(Month from ps.created_at) = '$month' and EXTRACT(year from ps.created_at) = $year)
            as newenrolledcount,
            sum(ptr.totaltime) as time, count(distinct p.id) filter (where p.status=1) as patient_count,
                  COUNT(p.id) filter (where ptr.totaltime <= '00:05:00') as less_five_minutes,     
                  count(p.id) filter (where ptr.totaltime >'00:05:00' and ptr.totaltime <= '00:10:00') as five_ten_minutes,      
                  count(p.id) filter (where ptr.totaltime >'00:10:00' and ptr.totaltime <= '00:15:00') as ten_fiften_minutes,       
                  count(p.id) filter (where ptr.totaltime >'00:15:00' and ptr.totaltime <= '00:20:00') as fiften_twenty_minutes,      
                  COUNT(p.id) filter (where ptr.totaltime > '00:20:00') as grether_twenty_minutes,
                   
                   ( 
                   COUNT(p.id) filter (where ptr.totaltime <= '00:05:00') +        
                   count(p.id) filter (where ptr.totaltime >'00:05:00' and ptr.totaltime <= '00:10:00')+
                   count(p.id) filter (where ptr.totaltime >'00:10:00' and ptr.totaltime <= '00:15:00')+
                   count(p.id) filter (where ptr.totaltime >'00:15:00' and ptr.totaltime <= '00:20:00')+
                   COUNT(p.id) filter (where ptr.totaltime > '00:20:00')
                  ) as alltotalnum
                  
            
            from patients.patient p
            inner join patients.patient_providers pp on pp.patient_id =p.id
            inner join patients.patient_services ps on ps.patient_id=p.id 
             inner join ren_core.practices as mpra on mpra.id =pp.practice_id and mpra.is_active = 1            
             left join (select pt1.patient_id, pt1.created_by
             ,   COALESCE(sum(pt1.timeone) - sum(pt2.timetwo), sum(pt1.timeone)) as totaltime, 
             case when COALESCE(sum(pt1.timeone) - sum(pt2.timetwo), sum(pt1.timeone)) > '00:20:00'::time then 1 else 0 end as billable
             from ( SELECT t1.patient_id, t1.created_by,
                      sum(t1.net_time::interval) AS timeone            
                      FROM patients.patient_time_records t1           
                     WHERE t1.adjust_time = 1
                     and t1.module_id in ('".$module_id."', 8) and 
                     (EXTRACT(Month from t1.created_at) = '$month' and EXTRACT(year from t1.created_at) = $year)
                     GROUP BY t1.patient_id, t1.created_by) pt1          
                LEFT JOIN ( SELECT t2.patient_id, t2.created_by,           
                       sum(t2.net_time) AS timetwo          
                      FROM patients.patient_time_records t2 
                     WHERE t2.adjust_time = 0
                     and t2.module_id in ('".$module_id."', 8) and ( EXTRACT(Month from t2.created_at) = '$month' and EXTRACT(year from t2.created_at) = $year)
                     GROUP BY t2.patient_id, t2.created_by) pt2 
                     ON pt2.patient_id = pt1.patient_id and pt1.created_by = pt2.created_by
                   where  1=1
                   group  by pt1.patient_id, pt1.created_by) ptr on ptr.patient_id=pp.patient_id 
                   where 1=1";
                   
           if($practices=='' || $practices=='null')
            {
               
            }
            else
            {
                $query.=" AND pp.practice_id = '".$practices."' ";

            } 
              if($practicesgrp =='' || $practicesgrp=='null')
            {
               
            }
            else
            {
               $query.=" AND mpra.practice_group = '".$practicesgrp."' ";
            }
            if($module_id !='0' && $module_id != 'null')
             {
                $query.=" AND ps.module_id in ($module_id,8) ";
             }
             else if($module_id =='0')
             {             
                $query.=" and p.id not in (select patient_id from patients.patient_services where status=1)";
             }

             /* if($from_monthly!='null' && $to_monthly!='null')
              {
              $query.=" AND ps.created_at between '$fromdate' and '$todate 23:59:59' ";
              }*/

                  $query.=" group  by pp.practice_id,mpra.name" ; 
        //  dd($query);
            $data  = DB::select($query);
            // dd($data); 

            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){ 
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                // return $btn;
            // })
            // ->rawColumns(['action'])
            ->make(true);
        }
    }


    public function getnewEnrolledPatientData(Request $request)
   {
    $practices = sanitizeVariable($request->route('practice'));
    
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
    else{ 
      $p = $practices;   
    }
    //dd($p);
    $query = "select distinct
    p.id,
    p.fname, 
    p.lname, 
    p.mname, 
    p.profile_img,
    dob,
    concat(pra.name || ' ' || pra.location ) as practice, 
    pr.name as provider_name,
    p.created_at as registered_date,
    pp.practice_emr 
    from patients.patient p 
    inner join patients.patient_services ps on ps.patient_id=p.id and ps.status=1 and (ps.created_at between '2021-02-01 00:00:00' and '2021-02-27 23:59:59')
    left join patients.patient_providers pp on pp.patient_id =ps.patient_id and pp.provider_type_id = 1 and pp.is_active=1
     left join ren_core.practices pra on pp.practice_id=pra.id
     left join ren_core.providers pr on pp.provider_id=pr.id  
     where p.status =1 "; 
     if($p=='' || $p=='null')
    {
               
    }
    else if($p == '0'){
      $query.=" and pp.practice_id is null"; 
            }
     else{
      $query.=" and pp.practice_id = $p"; 
     }
   // dd($query);
    $data = DB::select($query);
          return Datatables::of($data)  
          ->addIndexColumn()            
          ->make(true);
   }

}

     