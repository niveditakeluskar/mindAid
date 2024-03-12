<?php

namespace RCare\Ccm\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientBilling;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Carbon\Carbon;
class ReportController extends Controller {    

public function listMonthalyReportPatients(Request $request)
    {
        if ($request->ajax()) {
           $module_id = getPageModuleName();
           // $currentYear  =date('Y');
            $data = DB::select( DB::raw("select distinct p.id, p.fname, p.lname, p.mname, p.profile_img, p.dob, p.mob, p.home_number, p.pid, sum(ptr.net_time) 
           	   FROM patients.patient p
			   INNER JOIN patients.patient_time_records ptr ON p.id = ptr.patient_id 
			   WHERE ptr.module_id = '3' AND EXTRACT(Month from ptr.record_date) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR from ptr.record_date) = EXTRACT(YEAR FROM CURRENT_DATE)
 			GROUP BY p.id ") ); 
			// dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                // $btn ='<a href="/ccm/monthly-monitoring/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Ccm::Report.patient-monthly-report-list');
    }

    public function listMonthalyReportPatientsSearch(Request $request)
    {	
        if ($request->ajax()) { 
        $practices = $request->practices;
        $provider  = $request->provider;
        $module_id = '3';
        $monthly   = $request->monthly;
        list($year, $month) = explode('-', $monthly);

            $data = DB::select( DB::raw("
            SELECT 
            distinct p.id, p.fname, p.lname, p.mname, p.profile_img, p.dob, p.mob, p.home_number, p.pid, sum(ptr.net_time) 
           	FROM patients.patient p
            INNER JOIN patients.patient_providers pp 
            on p.id = pp.patient_id 

            INNER JOIN patients.patient_time_records ptr
            on p.id = ptr.patient_id

            WHERE pp.practice_id = '".$practices."' AND pp.provider_id = '".$provider."' AND ptr.module_id = '".$module_id."' 
            AND EXTRACT(Month from ptr.record_date) = EXTRACT(MONTH FROM CURRENT_DATE) 
            AND EXTRACT(YEAR from ptr.record_date) = EXTRACT(YEAR FROM CURRENT_DATE)
            GROUP BY p.id
            ") );
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                // $btn ='<a href="/ccm/monthly-monitoring/'.$row->pid.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Ccm::Report.patient-monthly-report-list');
    }



      public function PatientDailyReport(Request $request)
    {
		
          return view('Ccm::Report.patient-daily-report-list');
    }

    public function DailyReportPatientsSearch(Request $request)
    {     
       // dd($request);
        $practices = $request->route('practiceid');
        $provider = $request->route('providerid');
        $module_id = $request->route('module');
         $date  =$request->route('date');
         $time  =$request->route('time');
        // die;
        if($module_id=='null')
        {
           $module_id=3;
       }
         if($date=='null' || $date=='')
         {
               $date=date("Y-m-d");
         }
         if($time=='null' || $time=='')
         {
               $time='00:20';
         }
       
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));    
        

      $query="select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pd.condition,pr.provider_name,ccs.rec_date,ptr.totaltime
                  from patients.patient p
                  inner join patients.patient_services ps on p.id=ps.patient_id
                 left join patients.patient_providers pp on ps.patient_id = pp.patient_id
                 left join ren_core.providers pr on pp.provider_id=pr.id
                   left join (select  string_agg(pd.condition,',') as condition,pd.patient_id
                  from patients.patient_diagnosis_codes pd 
                        group by pd.patient_id) pd on ps.patient_id=pd.patient_id
                  left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
                (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
                left join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
                from  patients.patient_time_records pt
                 left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
                 adjust_time =1 and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id  
                LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
                 adjust_time =0 and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id   
                 where pt.record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59') ptr on ptr.patient_id=ps.patient_id where ps.status=1";   


         if( $practices!='null')
         {
            $query.=" AND pp.practice_id = '".$practices."' ";
         }
         if($provider!='null')
         {
            $query.="AND pp.provider_id = '".$provider."' ";
         }
         
          $query.=" AND ps.module_id='".$module_id."' AND ptr.totaltime >= '".$time."'";
        
            $data = DB::select( DB::raw($query) );
         
            return Datatables::of($data)
            ->addIndexColumn()            
            ->make(true);
       
    }

    public function CareManagerReport(Request $request)
    {
        
          return view('Ccm::Report.care-manager-report');
    }
    public function CareManagerReportSearch(Request $request)
    {
           $practices = $request->route('practiceid');
           $provider = $request->route('providerid');
            $module_id = $request->route('module');
            $time  =$request->route('time');
           if($module_id=='null')
           {
            $module_id=3;
           }
          
       
        
            $query=" select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pr.provider_name,usr.f_name,usr.l_name,ccs.rec_date,ptr.totaltime,pb.status
                  from patients.patient p
                 inner join patients.patient_services ps on p.id=ps.patient_id
                 left join ren_core.users usr on usr.id=p.updated_by
                 left join patients.patient_billing pb on pb.patient_id=ps.patient_id
                 left join patients.patient_providers pp on ps.patient_id = pp.patient_id
                 left join ren_core.providers pr on pp.provider_id=pr.id
                 left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
                (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
                left join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
                from  patients.patient_time_records pt
                 left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
                 adjust_time =1  group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id  
                LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
                 adjust_time =0  group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id   
                  ) ptr on ptr.patient_id=ps.patient_id 
                 where ps.module_id='".$module_id."' and ps.status=1 ";
           
                 if( $practices!='null')
                 {
                    $query.=" AND pp.practice_id = '".$practices."' ";
                 }
                 if($provider!='null')
                 {
                    $query.="AND pp.provider_id = '".$provider."' ";
                 }
                  if($time!='null')
                 {
                    $query.=" AND ptr.totaltime >= '".$time."'";
                 }

        //dd($query);
              $data = DB::select( DB::raw($query) );
              return Datatables::of($data)
             ->addIndexColumn() 
             
           ->rawColumns(['action'])
            
                       
            ->make(true);
    }

    public function CMBillUpdate(Request $request)
    {
      
       $patient_id=$request->patient_id;
       $module_id=$request->module_id;
       $count=count($patient_id);
       

      for($i=0;$i<$count;$i++)
      {
              $patient_bill = PatientBilling::where('patient_id',$patient_id[$i])->exists();
            if ($patient_bill == true)
            {

            }
            else
            {
              $data = array(
                    'patient_id'=>$patient_id[$i],
                    'module_id' => $module_id,                    
                    'created_by' => session()->get('userid'),
                    'billing_date'=>Carbon::now(),
                    'updated_by' => session()->get('userid')
                );
                PatientBilling::create($data);
            }
      }
       
    }

   
}

?>