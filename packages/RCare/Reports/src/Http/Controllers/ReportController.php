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
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon;
use Session;

class ReportController extends Controller
{


      //created by 27nov2020 drilldown for provider performance report( view file Total no. of patients in ccm)
     public function getPPPatientsDetailsInCCM(Request $request)
     {
      $practiceid = $request->route('practiceid');      
       $providerid = $request->route('providerid');

       $practicename=Practices::where('id',$practiceid)->get('name');
       if($providerid != 'null'){
        $providername=Providers::where('id',$providerid)->get('name');
       }else{
        $providername='';
       }

       return view('Reports::sub-steps-provider-performance-report.patient-details-in-ccm-list',compact('practiceid','providerid','practicename','providername'));
     }

    //created by 27nov2020 drilldown for provider performance report( view file Total no. of patients in rpm)
     public function getPPPatientsDetailsInRPM(Request $request)
     {
      $practiceid = $request->route('practiceid');      
       $providerid = $request->route('providerid');

       $practicename=Practices::where('id',$practiceid)->get('name');
       if($providerid != 'null'){
        $providername=Providers::where('id',$providerid)->get('name');
       }else{
        $providername='';
       }
       return view('Reports::sub-steps-provider-performance-report.patient-details-in-rpm-list',compact('practiceid','providerid','practicename','providername'));
     }
 
    //created by 27nov2020 drilldown for provider performance report(Total no. of patients details in ccm)
     public function getPPPatientDetailsInCCMData(Request $request)
     {
        $practiceid = $request->route('practiceid');      
       $providerid = $request->route('providerid');
       $query="select distinct pt.* from patients.patient_providers pp
       left join ren_core.providers p on pp.provider_id=p.id and p.is_active = 1 
       inner join ren_core.practices p2 on pp.practice_id = p2.id 
       inner join patients.patient pt on pt.id=pp.patient_id  
       inner join patients.patient_services ps  on ps.patient_id=pp.patient_id and ps.status =1
       where pt.status=1 and pp.provider_type_id = 1 and ps.module_id ='3' and pp.practice_id=$practiceid";
          if($providerid == "null"){
           $query.=" and pp.provider_id is null";
          }else{
           $query.=" and pp.provider_id =$providerid";
          }


            $data = DB::select($query);     
             return Datatables::of($data)
            ->addIndexColumn()   
            ->make(true);

     }

      //created by 27nov2020 drilldown for provider performance report(Total no. of patients details in rpm)
     public function getPPPatientDetailsInRPMData(Request $request)
     {
        $practiceid = $request->route('practiceid');      
       $providerid = $request->route('providerid');
       $query="select distinct pt.* from patients.patient_providers pp
       left join ren_core.providers p on pp.provider_id=p.id and p.is_active = 1 
       inner join ren_core.practices p2 on pp.practice_id = p2.id 
       inner join patients.patient pt on pt.id=pp.patient_id  
       inner join patients.patient_services ps  on ps.patient_id=pp.patient_id and ps.status =1
       where pt.status=1 and pp.provider_type_id = 1 and ps.module_id ='2' and pp.practice_id=$practiceid";
          if($providerid == "null"){
           $query.=" and pp.provider_id is null";
          }else{
           $query.=" and pp.provider_id =$providerid";
          }


            $data = DB::select($query);       
             return Datatables::of($data)
            ->addIndexColumn()   
            ->make(true);

     }
   //created by radha 20 nov 2020 for provider performance report
     public function getProviderPerformanceReport(Request $request)
    {
        $practiceid = $request->route('practice');      
        $providerid = $request->route('provider');

       $query = "select * from patients.SP_Provider_Performance_Report($practiceid,$providerid)";  
       $data = DB::select($query);
       //$this->assignedPatients();
             return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('ccmview', function($row){
              if($row->provider_id == ""){
                $row->provider_id = 'null';
              }
                $btn ='<a href="/reports/provider-performance-patient-details-in-ccm/'.$row->practice_id.'/'.$row->provider_id.'" data-toggle="tooltip"  data-original-title="View Details" class="ccm_details" target="_blank" title="View Details"><i class="text-20 i-Eye" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
             ->addColumn('rpmview', function($row){
              if($row->provider_id == ""){
                $row->provider_id = 'null';
              }
                $btn ='<a href="/reports/provider-performance-patient-details-in-rpm/'.$row->practice_id.'/'.$row->provider_id.'" data-toggle="tooltip" data-id="'.$row->practice_id.'" data-original-title="View Details" class="rpm_details" target="_blank" title="View Details"><i class="text-20 i-Eye" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['ccmview','rpmview'])
            ->make(true);


    }

  //created by ->radha(12oct20) .datewise enrollment report
    public function EnrollmentReport(Request $request)
    {
        return view('Reports::datewise-enrollment-report');
    }
    public function EnrollmentReportSearch(Request $request)
    {

           $caremanager = $request->route('care_manager_id');
             $practices = $request->route('practiceid');
            $fromdate=$request->route('fromdate');
            $todate=$request->route('todate');
              $module_id = $request->route('module');

          // $query="select  p.id, p.fname, p.lname, p.mname, p.profile_img,concat(pra.name || ' ' || pra.location ) as cmname, p.dob,
          //        m.module  as modules,
          //       pr.name,usr.f_name,usr.l_name,ps.created_at,tp.totalpatient,pra.location,pp.practice_emr
          //        from patients.patient p ";
          //        if($module_id =='0')
          //        {
          //             $query .= "left JOIN patients.patient_services ps on p.id = ps.patient_id and ps.status = 1 ";
          //        }
          //        else
          //        {
          //             $query .= "inner JOIN patients.patient_services ps on p.id = ps.patient_id and ps.status = 1 ";
          //        }
          //        $query .= "  left join ren_core.modules m on m.id=ps.module_id and m.patients_service='1' and m.status='1'
          //       left join ren_core.users usr on usr.id=ps.created_by 

          //         left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
          //      from patients.patient_providers pp1
          //      inner join (select patient_id, max(id) as max_pat_practice 
          //      from patients.patient_providers  where provider_type_id = 1 and is_active=1
          //      group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id          
          //      and pp1.id = pp2.max_pat_practice
          //       and pp1.provider_type_id = 1 and pp1.is_active=1) pp
          //       on p.id = pp.patient_id  ";

          //       //  LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
          //       // from patients.patient_providers pp1
          //       // inner join (select patient_id, max(created_at) as created_date 
          //       // from patients.patient_providers  where provider_type_id = 1 
          //       // group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.created_at = pp2.created_date
          //       // and pp1.provider_type_id = 1 ) pp                 
                
          //       $query.=" left join ren_core.practices pra on pp.practice_id=pra.id
          //        left join ren_core.providers pr on pp.provider_id=pr.id ";

          //       $query.="  left join (select count(pser.patient_id) as totalpatient,pser.created_by,pp.practice_id from patients.patient p 
          //         inner JOIN patients.patient_services pser on pser.patient_id =p.id and pser.status=1
          //       LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
          //       from patients.patient_providers pp1
          //       inner join (select patient_id, max(created_at) as created_date 
          //       from patients.patient_providers  where provider_type_id = 1 
          //       group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.created_at = pp2.created_date
          //       and pp1.provider_type_id = 1 ) pp                 
          //       on pser.patient_id = pp.patient_id 
          //       left join ren_core.users usr on usr.id= pser.created_by              
          //       group by pser.created_by,pp.practice_id) tp on tp.created_by=ps.created_by and tp.practice_id=pp.practice_id ";

          //       $query.= " where 1=1 ";//group by ps.created_at,pra.name, pr.name,usr.f_name,usr.l_name,ps.created_at,p.id ";

          $query="select  p.id, p.fname, p.lname, p.mname, p.profile_img,concat(pra.name || ' ' || pra.location ) as cmname, p.dob,
          m.module  as modules,
         pr.name,usr.f_name,usr.l_name,ps.created_at,tp.totalpatient,pra.location,pp.practice_emr
          from patients.patient p ";
          if($module_id =='0')
          {
               $query .= "left JOIN patients.patient_services ps on p.id = ps.patient_id and ps.status = 1 ";
          }
          else
          {
               $query .= "inner JOIN patients.patient_services ps on p.id = ps.patient_id and ps.status = 1 ";
          }
          $query .= "  left join ren_core.modules m on m.id=ps.module_id and m.patients_service='1' and m.status='1'
         left join ren_core.users usr on usr.id=ps.created_by 

           left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
        from patients.patient_providers pp1
        inner join (select patient_id, max(id) as max_pat_practice 
        from patients.patient_providers  where provider_type_id = 1 and is_active=1
        group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id          
        and pp1.id = pp2.max_pat_practice
         and pp1.provider_type_id = 1 and pp1.is_active=1) pp
         on p.id = pp.patient_id ";            
         
         $query.=" left join ren_core.practices pra on pp.practice_id=pra.id
          left join ren_core.providers pr on pp.provider_id=pr.id ";

         $query.="  left join (select count(pser.patient_id) as totalpatient,pser.created_by,ppp.practice_id from patients.patient p 
           inner JOIN patients.patient_services pser on pser.patient_id =p.id and pser.status=1
        left join (select pp11.patient_id , pp11.practice_id, pp11.provider_id, pp11.practice_emr 
        from patients.patient_providers pp11
        inner join (select patient_id, max(id) as max_pat_practice 
        from patients.patient_providers  where provider_type_id = 1 and is_active=1
        group by patient_id  ) as pp22 on pp11.patient_id = pp22.patient_id          
        and pp11.id = pp22.max_pat_practice
         and pp11.provider_type_id = 1 and pp11.is_active=1) ppp
         on p.id = ppp.patient_id 
         left join ren_core.users usr on usr.id= pser.created_by              
         group by pser.created_by,ppp.practice_id) tp on tp.created_by=ps.created_by and tp.practice_id=pp.practice_id ";

         $query.= " where 1=1 ";//group by ps.created_at,pra.name, pr.name,usr.f_name,usr.l_name,ps.created_at,p.id ";

              if($practices=='null' || $practices=='')
              {
                 
              }
              else
              {
                $query.=" AND pp.practice_id = '".$practices."' ";
              }

               if($caremanager != '0' && $caremanager != 'null')              
             {
                $query.=" AND ps.created_by = '".$caremanager."' ";
             }
            else if($caremanager == '0' )
             {
                  $query.=" and ps.created_by not IN (select usr1.id from ren_core.users usr1 where usr1.role=5)";
             }
             
             if($fromdate!='null')
             {
                  $query.=" AND ps.created_at between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'";
             }
              if($module_id !='0' && $module_id != 'null')
             {
                $query.=" AND ps.module_id='".$module_id."' ";
             }
             else if($module_id =='0')
             {
                    $query.=" and p.id not in (select patient_id from patients.patient_services where status=1)";
             }
            

             $data = DB::select($query);
          
            return Datatables::of($data)
            ->addIndexColumn()            
            ->make(true);
    }

    //created by ->radha(19oct20) non enrolled details in enrollment report
   public function getNonEnrolledPatientData(Request $request)
   {
    $practices = $request->route('practice');
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
    else if($practices=='0'){
      $p = 0;
    }
    else{
      $p = $practices;   
    }
    $query = "select * from patients.sp_nonenrolled_patients_details($p)"; 
    // dd($query);
    $data = DB::select($query);
          return Datatables::of($data) 
          ->addIndexColumn()            
          ->make(true);
   }
    
     //created by ->radha(19oct20) enrolled details in enrollment report
    //modifiedby ashvini(7dec2020) for procedure
    public function getEnrolledPatientData(Request $request)
   {
    $practices = $request->route('practice');
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
    else if($practices=='0'){
      $p = 0;
    }
    else{ 
      $p = $practices;   
    }
    $query = "select * from patients.sp_enrolled_patients_details($p)"; 
    // dd($query);
    $data = DB::select($query);
          return Datatables::of($data)  
          ->addIndexColumn()            
          ->make(true);
   }

   //created by ->radha(20oct20) enrolled details in enrollment report
   //modified by ->ashvini (07dec2020)procedure
   public function getEnrolledInCCMPatientData(Request $request)
   {
    $practices = $request->route('practice');
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
     else if($practices=='0'){
      $p = 0;
    }
    else{
      $p = $practices;   
    }
    $query = "select * from patients.sp_enrolled_in_ccm_details($p)"; 
    // dd($query);
    $data = DB::select($query);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true);
   }
    
    //created by ->radha(20oct20) enrolled details in enrollment report
     //modified by ashvini(7dec202) procedure
     public function getEnrolledInRPMPatientData(Request $request) 
     {
      $practices = $request->route('practice');
      $p;
      if($practices=='null' || $practices==''){    
        $p = "null";
      }
       else if($practices=='0'){
        $p = 0;
      }
      else{
        $p = $practices;   
      }
      $query = "select * from patients.sp_enrolled_in_rpm_details($p)"; 
      // dd($query);
      $data = DB::select($query);
              return Datatables::of($data) 
              ->addIndexColumn()            
              ->make(true);
     }

     
     //created by ->radha(19oct20) total patients details in enrollment report
     //modified by -ashvini(7dec2020) procedure
     public function getTotalPatientData(Request $request)
   {
      $practices = $request->route('practice');
      $p;
      if($practices=='null' || $practices==''){   
        $p = "null";
      }
       else if($practices=='0'){
        $p = 0;
      }
      else{
        $p = $practices;   
      }
      $query = "select * from patients.sp_enrollment_totalpatients($p)"; 
      // dd($query);
      $data = DB::select($query);
              return Datatables::of($data) 
              ->addIndexColumn()            
              ->make(true);
   }

//created by ->radha(20oct20) view total patients details 
    public function viewTotalPatients(Request $request)
    {
       return view('Reports::sub-steps-enrollment-report.total-patients-list');
    }
//created by ->radha(20oct20) view total patients details 
    public function viewEnrolledPatients(Request $request)
    {
       return view('Reports::sub-steps-enrollment-report.enrolled-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewNonEnrolledPatients(Request $request)
    {
       return view('Reports::sub-steps-enrollment-report.non-enrolled-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewEnrolledInCCMPatients(Request $request)
    {
       return view('Reports::sub-steps-enrollment-report.enrolled-in-ccm-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewEnrolledInRPMPatients(Request $request)
    {
       return view('Reports::sub-steps-enrollment-report.enrolled-in-rpm-list');
    }

    //created by ->radha(17oct20) for patient summary in enrollment report
  public function getPatientEnrolledData(Request $request)    
  {
      $query="select count(*) from patients.patient where status=1";
      $totalPatient=DB::select($query);

       $queryenrolledcount="select count( ps.patient_id) from patients.patient_services ps where ps.status=1 and ps.patient_id in (select id from patients.patient where status=1) and ps.patient_id is not null";
      $totalEnreolledPatient=DB::select($queryenrolledcount);

       $querynewenroll="select count( ps.patient_id) from patients.patient_services ps where ps.status=1 and ps.patient_id in (select id from patients.patient where status=1) and ps.patient_id is not null and (ps.created_at between '2021-02-01 00:00:00' and '2021-02-12 23:59:59')";
       $totalnewenroll=DB::select($querynewenroll);
       
       $query2="select count( ps.patient_id) from patients.patient_services ps where ps.status=1 and ps.module_id=3 and ps.patient_id in (select id from patients.patient where status=1) ";
      $totalCCMPatient=DB::select($query2);

      $query3="select count( ps.patient_id) from patients.patient_services ps where ps.status=1 and ps.module_id=2 and ps.patient_id in (select id from patients.patient where status=1) ";
      $totalRPMPatient=DB::select($query3);

      $query4="select count(p.id) from patients.patient p where p.id not in (select patient_id from patients.patient_services where status=1) and p.status=1";
      $totalUnEnrolledPatient=DB::select($query4);

      // $query5 = "select count(*) from task_management.user_patients where status=1";
      //Updated by -pranali on 22Oct2020
      //updated by radha 18nov20
       $query5 = " select count(distinct up.patient_id) from task_management.user_patients up
         inner join patients.patient p on  up.patient_id=p.id 
         inner join patients.patient_providers pp on pp.patient_id =p.id
         inner join ren_core.practices p2 on p2.id=pp.practice_id 
         where up.status=1  and pp.provider_type_id =1 and pp.is_active=1 and p2.is_active =1 and p.status=1";
        $totalAssignedPatient=DB::select($query5);
       
       //added by radha 18nov20
          $query6="select count(distinct p.id) from patients.patient p
           inner join patients.patient_providers pp on pp.patient_id =p.id
           inner join ren_core.practices p2 on p2.id=pp.practice_id 
          where pp.provider_type_id =1 and pp.is_active=1 and p2.is_active =1 and p.status=1";
        $totalPatientActive=DB::select($query6);

      // $query6 = "select count(*) from ren_core.users where role=5 ";
      //Updated by -pranali on 22Oct2020
      $query6 = "select count(*) from ren_core.users where role=5 and status = 1";
      $totalCareManeger=DB::select($query6);
       $data=array('Totalpatient'=>$totalPatient,'TotalEnreolledPatient'=>$totalEnreolledPatient,'TotalEnrolledInCCM'=>$totalCCMPatient,
         'TotalEnrolledInRPM'=>$totalRPMPatient,'TotalUnEnrolledPatient'=>$totalUnEnrolledPatient,'TotalCareManeger'=>$totalCareManeger,
         'ToltalAssignedPatient'=>$totalAssignedPatient,'totalPatientActive'=>$totalPatientActive, 'totalnewenroll'=>$totalnewenroll);

      return $data;
  }

  
    public function listMonthlyReportPatients(Request $request)
    {
        //    $module_id = getPageModuleName();
        //    // $currentYear  =date('Y');
        //     $data = DB::select( DB::raw("select distinct p.id, p.fname, p.lname, p.mname, p.profile_img, p.dob, p.mob, p.home_number, p.pid, sum(ptr.net_time) 
        //         FROM patients.patient p
        //     INNER JOIN patients.patient_time_records ptr ON p.id = ptr.patient_id 
        //     WHERE ptr.module_id = '3' AND EXTRACT(Month from ptr.record_date) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR from ptr.record_date) = EXTRACT(YEAR FROM CURRENT_DATE)
        //      GROUP BY p.id ") ); 

        // search
        // $data = DB::select( DB::raw("
            // SELECT 
            // distinct p.id, p.fname, p.lname, p.mname, p.profile_img, p.dob, p.mob, p.home_number, p.pid, sum(ptr.net_time) 
            // FROM patients.patient p
            // INNER JOIN patients.patient_providers pp 
            // on p.id = pp.patient_id 

            // INNER JOIN patients.patient_time_records ptr
            // on p.id = ptr.patient_id

            // WHERE pp.practice_id = '".$practices."' AND pp.provider_id = '".$provider."' AND ptr.module_id = '".$module_id."' 
            // AND EXTRACT(Month from ptr.record_date) = EXTRACT(MONTH FROM CURRENT_DATE) 
            // AND EXTRACT(YEAR from ptr.record_date) = EXTRACT(YEAR FROM CURRENT_DATE)
            // GROUP BY p.id
            // ") );

        return view('Reports::patient-monthly-report-list'); 
    }

    public function listMonthlyReportPatientsSearch(Request $request)
    {   
        if ($request->ajax()) { 
            $practices = sanitizeVariable($request->route('practice'));
            $provider  = sanitizeVariable($request->route('provider'));
            $module_id = sanitizeVariable($request->route('module'));
            $monthly   = sanitizeVariable($request->route('monthly'));
            
             // list($year, $month) = explode('-', $monthly);
            // dd($monthly);
            if($module_id=='' || $module_id==0)
            {
                $module_id=3;
            }  
            if($monthly=='' || $monthly=='null' || $monthly=='0')
            {
                $monthly=date('Y-m');
            }
            else
            {
                 $monthly=$monthly;
            }
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));

            //  // updated by -ashvini 23rd oct 2020
            // $query="select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pd.condition,pr.name,ccs.rec_date,ptr.totaltime
            //         from patients.patient p
            //         inner join patients.patient_services ps on p.id=ps.patient_id
            //         left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            //         from patients.patient_providers pp1
            //         inner join (select patient_id,  max(id) as max_pat_practice 
            //         from patients.patient_providers  where provider_type_id = 1 
            //         group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
            //         and pp1.provider_type_id = 1 ) pp                   
            //         on ps.patient_id = pp.patient_id
            //         left join ren_core.providers pr on pp.provider_id=pr.id
            //         left join (select  string_agg(pd.condition,',') as condition,pd.patient_id
            //         from patients.patient_diagnosis_codes pd 
            //         group by pd.patient_id) pd on ps.patient_id=pd.patient_id
            //         left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
            //         (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
            //         inner join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
            //         from  patients.patient_time_records pt
            // LEFT JOIN (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
            // adjust_time =1 and  EXTRACT(Month from record_date) = '$month' AND EXTRACT(YEAR from record_date) = '$year' GROUP BY patient_id) pt1 
            // ON  pt1.patient_id = pt.patient_id 
            // LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
            // adjust_time =0 and  EXTRACT(Month from record_date) = '$month' AND EXTRACT(YEAR from record_date) = '$year' GROUP BY patient_id) pt2 
            // ON  pt2.patient_id = pt.patient_id 
            // where  EXTRACT(Month from pt.record_date) = '".$month."' AND EXTRACT(YEAR from pt.record_date) = '".$year."' 
            // ) ptr on ptr.patient_id=ps.patient_id  where  ps.module_id = '".$module_id."' AND ps.status=1 ";

            //updated by pranali on 4Nov2020 added practice name in fetch columns
            $query = "select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pd.condition,pr.name,ccs.rec_date,ptr.totaltime, pra.name as pract_name
            from patients.patient p
            inner join patients.patient_services ps on p.id=ps.patient_id
            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1
            inner join (select patient_id,  max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1 
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
            and pp1.provider_type_id = 1 ) pp                   
            on ps.patient_id = pp.patient_id
            left join ren_core.providers pr on pp.provider_id=pr.id
            left join ren_core.practices pra on pp.practice_id=pra.id
            left join (select  string_agg(pd.condition,',') as condition,pd.patient_id
            from patients.patient_diagnosis_codes pd 
            group by pd.patient_id) pd on ps.patient_id=pd.patient_id
            left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
            (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
            inner join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
            from  patients.patient_time_records pt
            LEFT JOIN (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
            adjust_time =1 and  EXTRACT(Month from record_date) = '$month' AND EXTRACT(YEAR from record_date) = '$year' GROUP BY patient_id) pt1 
            ON  pt1.patient_id = pt.patient_id 
            LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
            adjust_time =0 and  EXTRACT(Month from record_date) = '$month' AND EXTRACT(YEAR from record_date) = '$year' GROUP BY patient_id) pt2 
            ON  pt2.patient_id = pt.patient_id 
            where  EXTRACT(Month from pt.record_date) = '".$month."' AND EXTRACT(YEAR from pt.record_date) = '".$year."' 
            ) ptr on ptr.patient_id=ps.patient_id  where  ps.module_id = '".$module_id."' AND ps.status=1 ";  
           
            // on ps.patient_id = ptr.patient_id 
            // AND
            // EXTRACT(Month from ptr.record_date) = '".$month."' AND EXTRACT(YEAR from ptr.record_date) = '".$year."' ";

            if($practices=='' || $practices=='null')
            {
               // $query.=" AND pp.practice_id = ".$practices." ";
            }
            else
            {
                $query.=" AND pp.practice_id = '".$practices."' ";

            }
            if($provider=='' || $provider=='null')
            {
                //$query.="AND pp.practice_id = ".$provider." ";
            }
            else
            {
                $query.="AND pp.provider_id = '".$provider."' ";

            }
            //$query .= " group by p.id";

            // dd($query);
            $data = DB::select($query);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        // return view('Reports::patient-monthly-report-list');
    }


   public function PatientDailyReport(Request $request)
    {
    
          return view('Reports::patient-daily-report-list');
    }

    public function DailyReportPatientsSearch1(Request $request)
    {     
       // dd($request);
        $practices = $request->route('practiceid');
        $provider = $request->route('providerid');
        $module_id = $request->route('module');
         $date  =$request->route('date');
         $time  =$request->route('time');
         $timeoption=$request->route('timeoption');
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
               $time='00:20:00';
               $timeoption='<';
         }
        
       
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
    
        //  $query="select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pd.condition,pr.name,ccs.rec_date,ptr.totaltime
        //           from patients.patient p
        //           inner join patients.patient_services ps on p.id=ps.patient_id
        //          left join patients.patient_providers pp on ps.patient_id = pp.patient_id
        //          left join ren_core.providers pr on pp.provider_id=pr.id
        //            left join (select  string_agg(pd.condition,',') as condition,pd.patient_id
        //           from patients.patient_diagnosis_codes pd 
        //                 group by pd.patient_id) pd on ps.patient_id=pd.patient_id
        //           left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
        //         (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
        //         left join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
        //         from  patients.patient_time_records pt
        //          left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
        //          adjust_time =1 and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id  
        //         LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
        //          adjust_time =0 and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id   
        //          where pt.record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59') ptr on ptr.patient_id=ps.patient_id where ps.status=1";   
          
        //updated by -ashvini 29thoct 2020
          $query="select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img,
           p.dob,pd.condition,pr.name,prac.name as practicename ,ccs.rec_date,ptr.totaltime
                  from patients.patient p
                  inner join patients.patient_services ps on p.id=ps.patient_id
                 left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                 from patients.patient_providers pp1
                 inner join (select patient_id, max(id) as max_pat_practice 
                 from patients.patient_providers  where provider_type_id = 1 
                 group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
                 and pp1.provider_type_id = 1 ) pp                 
                 on ps.patient_id = pp.patient_id
                 left join ren_core.providers pr on pp.provider_id=pr.id
                 left join ren_core.practices prac on pp.practice_id = prac.id
                   left join (select  string_agg(pd.condition,',') as condition,pd.patient_id
                  from patients.patient_diagnosis_codes pd  
                        group by pd.patient_id) pd on ps.patient_id=pd.patient_id
                  left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
                (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
                left join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
                from  patients.patient_time_records pt
                 left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
                 adjust_time =1 and module_id in ($module_id,8)
                  and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id  
                LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
                 adjust_time =0 and module_id in ($module_id,8)
                  and record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id   
                 where pt.record_date between '".$year."-".$month."-01 00:00:00' and '".$date." 23:59:59' and pt.module_id in ($module_id,8)) ptr on ptr.patient_id=ps.patient_id where ps.status=1";   
                 
           
         if( $practices!='null')
         {
            if( $practices==0)
            {
              $query.="AND pp.practice_id IS NULL ";  
            } 
            else
            {
              $query.=" AND pp.practice_id = '".$practices."' ";
            }
            
         }
         if($provider!='null')
         {
            if( $provider==0) 
            {
              $query.="AND pp.provider_id IS NULL ";  
            }
            else
            {
              $query.="AND pp.provider_id = '".$provider."' ";
            }
            
         }

         if($time!="null" && $time!="00:00:00")
         {
          $query.=" AND ps.module_id='".$module_id."' AND ptr.totaltime ".$timeoption." '".$time."'";
         }
         
         if($time=="00:00:00")
         {
             // dd($time);
            //  $time1 = "null";
            //  $query.=" AND ptr.totaltime IS NULL ";   
             $query.=" AND ps.module_id='".$module_id."' AND ptr.totaltime IS NULL ";
         
         } 
         
          $query.=" AND ps.module_id='".$module_id."' AND ptr.totaltime ".$timeoption." '".$time."'";
        
          // dd($query);

       
            $data = DB::select($query);
          //  dd($data);
            return Datatables::of($data)
            ->addIndexColumn()            
            ->make(true); 
       
    }

    public function DailyReportPatientsSearch(Request $request)
    {     
      $practices = $request->route('practiceid');
      $provider = $request->route('providerid');
      $module_id = $request->route('module');
      $date  =$request->route('date');
      $time  =$request->route('time'); 
      $timeoption=$request->route('timeoption');
      $p;
      $pr;
      $totime;
      $totimeoption;
     
      if($module_id=='null')
      {
         $module_id=3;
     }
       if($date=='null' || $date=='')
       {
             $date=date("Y-m-d");
             $year = date('Y', strtotime($date));
             $month = date('m', strtotime($date));
             $fromdate =  $date ." "."00:00:00";
             $todate = $date ." "."23:59:59";
           
       }
       else{
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $fromdate =  $date ." "."00:00:00";
        $todate = $date ." "."23:59:59"; 
       }
       if($time=='null' || $time=='')
       {
          $timeoption="1";
          $totime = '00:20:00'; 
       }
       else{
         $totime = $time;
        //  dd($totime);
       }

            
       if( $practices!='null')
       {
          if( $practices==0)
          {
            $p = 0;  
          }
          else{
            $p = $practices;
          } 
 
      }
      else
      {
        $p = 'null';
      }


    if($provider!='null')
    {
    if( $provider==0) 
    {
      $pr = 0;  
    }
    else
    {
      $pr = $provider;
    }
    
  }
  else{
    $pr = 'null';
  }

       
  if($time!="null" && $time!="00:00:00")
  {
    $totime = $time;
  }
  
  if($timeoption=="3" && $time=="00:00:00" ) //equal to 00:00:00
  {
    
    $timeoption="5";
    
  
  } 
  if($timeoption=="2" && $time=='00:00:00') //greater than 00:00:00
  {
    $timeoption = "6"; 
  }
     
      $query = "select * from patients.daily_billing_report($p,$pr,$module_id, timestamp '".$fromdate."', timestamp '".$todate."',$timeoption,'".$totime."')";      
      // dd($query);   
      $data = DB::select($query);  
     
          return Datatables::of($data) 
          ->addIndexColumn()            
          ->make(true);   
     
       
    }

    public function listCareManagerMonthlyReportPatients(Request $request ){
        return view('Reports::patients-monthly-reports-associated-with-caremanager');
    }

    public function listCareManagerMonthlyReportPatientsSearch(Request $request)
    {

            $practices =   $request->route('practice');
            $caremanager = $request->route('caremanager');
            $module_id = $request->route('modules');
            $monthly   = $request->route('monthly');
            $monthlyto   = $request->route('monthlyto');
             // list($year, $month) = explode('-', $monthly);
            // dd($monthly);
           
            if($monthly=='' || $monthly=='null' || $monthly=='0')
            {
                $monthly=date('Y-m');
            }
            else
            {
                 $monthly=$monthly;
            }

            if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
            {
                $monthlyto=date('Y-m');
            }
            else
            {
                 $monthlyto=$monthlyto;
            }
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));

              $toyear = date('Y', strtotime($monthlyto));
              $tomonth = date('m', strtotime($monthlyto)); 
              $query = "select * from patients.CAREMANAGER_MONTHLY_BILLING_REPORT($practices,$caremanager,$module_id,$month,$tomonth,$year,$toyear)"; 
                $data = DB::select($query);
              //  dd($data);
                 return Datatables::of($data)
                 ->addIndexColumn()           
                 ->make(true); 
    }

    public function listCareManagerMonthlyReportPatientsSearch1(Request $request)
    { 
      //updated by -ashvini 23rdoct2020
      if ($request->ajax()) { 
            $practice =   $request->route('practice');
            $caremanager = $request->route('caremanager');
            $module_id = $request->route('modules');
            $monthly   = $request->route('monthly');
            $monthlyto   = $request->route('monthlyto');
             // list($year, $month) = explode('-', $monthly);
            // dd($monthly);
           
            if($monthly=='' || $monthly=='null' || $monthly=='0')
            {
                $monthly=date('Y-m');
            }
            else
            {
                 $monthly=$monthly;
            }

            if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
            {
                $monthlyto=date('Y-m');
            }
            else
            {
                 $monthlyto=$monthlyto;
            }
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));

              $toyear = date('Y', strtotime($monthlyto));
              $tomonth = date('m', strtotime($monthlyto)); 
        
      $configTZ = config('app.timezone');
      $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
          
            $query="SELECT 
            distinct p.id, p.fname, p.lname, p.mname, p.profile_img, usr.f_name,usr.l_name, p.dob, p.mob, p.home_number, pp.practice_emr ,pr.name as provider_name, pra.name, p.pid, ptr.totaltime, to_char(ccs.last_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as last_date
            FROM patients.patient p

            INNER JOIN patients.patient_services ps 
            on p.id = ps.patient_id 

            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1
                inner join (select patient_id,  max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1 and is_active = 1
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
                and pp1.provider_type_id = 1 and is_active = 1) pp                 
                on ps.patient_id = pp.patient_id 
             

            left join ren_core.providers pr on pp.provider_id=pr.id
            left join ren_core.practices pra on pp.practice_id=pra.id
            
            left JOIN (SELECT patient_id, MAX(created_at) as last_date
                       FROM ccm.ccm_call_status
                      WHERE call_status = '1'
                      GROUP BY patient_id
                    ) ccs on ccs.patient_id =  ps.patient_id 

            INNER JOIN (select distinct pt.patient_id,pt1.created_by,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
            from  patients.patient_time_records pt
            LEFT JOIN (SELECT distinct patient_id,created_by,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
            adjust_time =1 and (EXTRACT(Month from record_date) between '$tomonth' and  '$month') AND (EXTRACT(YEAR from record_date) between '$toyear' and  '$year') GROUP BY patient_id,created_by) pt1 
            ON  pt1.patient_id = pt.patient_id 
            LEFT JOIN (SELECT distinct patient_id,created_by,sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
            adjust_time =0 and  (EXTRACT(Month from record_date) between '$tomonth' and  '$month') AND (EXTRACT(YEAR from record_date) between '$toyear' and  '$year') GROUP BY patient_id,created_by) pt2 
            ON  pt2.patient_id = pt.patient_id 
            where  (EXTRACT(Month from pt.record_date) between '$tomonth' and  '$month') AND (EXTRACT(YEAR from pt.record_date) between '$toyear' and  '$year') 
            ) ptr
            on ps.patient_id = ptr.patient_id
            left join ren_core.users usr on usr.id=ptr.created_by
            where  ps.status=1 ";
            // AND
            // EXTRACT(Month from ptr.record_date) = '".$month."' AND EXTRACT(YEAR from ptr.record_date) = '".$year."' ";
            
            if($module_id !='0' && $module_id != 'null')
             {
                $query.=" AND ps.module_id in ($module_id,8) ";
             }
             else if($module_id =='0')
             {
              //$query.=" AND ps.module_id = 8 ";
                    $query.=" and p.id not in (select patient_id from patients.patient_services where status=1)";
             }

             if($caremanager != '0' && $caremanager != 'null')              
             {
                $query.=" AND ptr.created_by = '".$caremanager."' ";
             }
            else if($caremanager == '0' )
             {
                  $query.=" and ptr.created_by not IN (select usr1.id from ren_core.users usr1 where usr1.role=5)";
             }
            
            if($practice=='' || $practice=='null')
            {
               // $query.=" AND pp.practice_id = ".$practices." ";
            }
            else if($practice=='0'){
              $query.=" AND pp.practice_id IS NULL";
            }
            else
            {
                $query.=" AND pp.practice_id = ".$practice." ";

            }
            
            //$query .= " group by p.id";

            // dd($query);
            $data = DB::select($query);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }


    


    public function PatientMonthlyBillingReport(Request $request)
    {
          $monthly = date('Y-m');
          $year = date('Y', strtotime($monthly));
          $month = date('m', strtotime($monthly));
          
          $diagnosis = "select max(count) from (select uid,count(*) as count from patients.patient_diagnosis_codes where EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year group by uid) x";
          $diagnosis = DB::select($diagnosis);
          //dd($diagnosis);
         
          return view('Reports::patients-monthly-billing-report');
    }

    // public function MonthlyBilllingReportPatientsSearch(MonthlyBilllingReportPatientsSearchRequest $request)
    public function MonthlyBilllingReportPatientsSearch1(Request $request)
    {     
       // dd($request);
       $practices = $request->route('practiceid');
       $provider = $request->route('providerid');
       $module_id = $request->route('module');
       $monthly   = $request->route('monthly');
       $monthlyto   = $request->route('monthlyto');
       // die;
       if($module_id=='null')
       {
          $module_id=3;
       }
      if($monthly=='' || $monthly=='null' || $monthly=='0')
      {
          $monthly=date('Y-m');
      }
      else
      {
           $monthly=$monthly;
      }
      if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
           {
               $monthlyto=date('Y-m');
           }
           else
           {
                $monthlyto=$monthlyto;
           }
             $year = date('Y', strtotime($monthly));
             $month = date('m', strtotime($monthly));

             $toyear = date('Y', strtotime($monthlyto));
             $tomonth = date('m', strtotime($monthlyto)); 
   
      
     //modified by -ashvini 29oct2020  
             $query="select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, 
             p.dob,pd.condition,pr.name as providername, prac.name as practicename,ccs.rec_date,ptr.totaltime
               from patients.patient p
               inner join patients.patient_services ps on p.id=ps.patient_id
               left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
               from patients.patient_providers pp1
               inner join (select patient_id, max(id) as max_pat_practice  
               from patients.patient_providers  where provider_type_id = 1 and is_active =1
               group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
               and pp1.provider_type_id = 1 ) pp                  
               on ps.patient_id = pp.patient_id  
               left join ren_core.providers pr   on pp.provider_id=pr.id and pr.is_active = 1
                left join ren_core.practices prac   on pp.practice_id = prac.id and prac.is_active = 1
               left join (select  string_agg(pd.condition,',') as condition,pd.patient_id 
               from patients.patient_diagnosis_codes pd 
                     group by pd.patient_id) pd on ps.patient_id=pd.patient_id
               left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
             (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id 

             inner join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime 
             from  patients.patient_time_records pt

             left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
             adjust_time =1 and module_id in ($module_id,8)  
              and (EXTRACT(Month from record_date) <= '$tomonth' and EXTRACT(Month from record_date) >= '$month') 
              AND (EXTRACT(YEAR from record_date) <= '$toyear' and EXTRACT(YEAR from record_date) >= '$year')  group by patient_id) pt1
               ON  pt1.patient_id = pt.patient_id  

             LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
             adjust_time =0 and module_id in ($module_id,8)    
             and (EXTRACT(Month from record_date) <= '$tomonth' and EXTRACT(Month from record_date) >= '$month')
              AND (EXTRACT(YEAR from record_date) <= '$toyear' and EXTRACT(YEAR from record_date) >= '$year') 
              group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id 

             where  (EXTRACT(Month from pt.record_date) <= '$tomonth' and 
             EXTRACT(Month from pt.record_date) >= '$month') 
             AND (EXTRACT(YEAR from pt.record_date) <= '$toyear' 
             and EXTRACT(YEAR from pt.record_date) >= '$year' and pt.module_id in ($module_id,8) ) )ptr on ptr.patient_id=ps.patient_id where ps.status=1";    
     
            

        if( $practices!='null')
        { 
          
          if( $practices==0)
             {
                $query.="AND pp.practice_id IS NULL ";  
             }  
             else{
               $query.=" AND pp.practice_id = '".$practices."' ";
             }

           

        }
       

        if($provider!='null')
        {
          if($provider==0)
          {
            
           $query.="AND pp.provider_id IS NULL ";
          }
          else
          {
           $query.="AND pp.provider_id = '".$provider."' ";
          }
           
        }
        
         $query.=" AND ps.module_id='".$module_id."'";
       
        //  dd($query);   

      
           $data = DB::select($query);
         //  dd($data);
           return Datatables::of($data)
           ->addIndexColumn()            
           ->addColumn('diagonish', function($row){
            $monthly = date('Y-m');
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));
             $diagnosis = "select code from patients.patient_diagnosis_codes where  EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year and uid=$row->id";
             $diagnosis = DB::select($diagnosis);
             $output='';
             
             foreach($diagnosis as $key => $val){
               //dd($key);
               $output.=$val->code;
               if($key < sizeof($diagnosis)-1){
                $output.='; ';
               }
             }
             return $output;
            })
          ->rawColumns(['diagonish'])
           ->make(true);
       
    }

    //created by -ashvini 31oct2020 --storedprocedure--modified by-ashvini 2ndnov2020
    public function MonthlyBilllingReportPatientsSearch(Request $request)
    {     
       // dd($request);
        $practices = $request->route('practiceid');
        $provider = $request->route('providerid');
        $module_id = $request->route('module');
        $monthly   = $request->route('monthly');
        $monthlyto   = $request->route('monthlyto');
        // die;
        if($module_id=='null')
        {
           $module_id=3;

        }
       if($monthly=='' || $monthly=='null' || $monthly=='0')
       {
           $monthly=date('Y-m');
       }
       else
       {
            $monthly=$monthly;
       }
       if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
            {
                $monthlyto=date('Y-m');
            }
            else
            {
                 $monthlyto=$monthlyto;
            }
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));

              $toyear = date('Y', strtotime($monthlyto));
              $tomonth = date('m', strtotime($monthlyto)); 
 
     
                 $query = "select * from patients.SP_MONTHLY_BILLING_REPORT($practices,$provider,$module_id,$month,$tomonth,$year,$toyear)"; 

                $data = DB::select($query);

              $diagnosis = "select max(count) from (select patient_id,count(*) as count from patients.patient_diagnosis_codes where (EXTRACT(Month from created_at) <= '$tomonth' and EXTRACT(Month from created_at) >= '$month') 
               AND (EXTRACT(YEAR from created_at) <= '$toyear' and EXTRACT(YEAR from created_at) >= '$year') group by patient_id) x ";
               if( $practices!='null')
              { 
              if( $practices==0)
              {
                 $query.="inner join patients.patient_providers as pp on x.patient_id =pp.patient_id where pp.practice_id IS NULL ";  
              }  
              else{
               $diagnosis.="inner join patients.patient_providers as pp on x.patient_id =pp.patient_id where pp.practice_id = '".$practices."'";
              }
            }
              $diagnosis = DB::select($diagnosis);    
              //  dd($diagnosis[0]->max);
                $arrydata=array();
                $ddata=array();
                  $columnheader=array();
                  $columnheader1=array();
                  $finalheader=array();
                  $maxcount=0;
                  $codedata;
                for($i=0;$i<count($data);$i++)
                {
                   $headername="header".$i;
                   
                    
                      $dcode=$data[$i]->pdcode;
                      $splitcode=explode(',', $dcode);

                      if(is_null($data[$i]->prprovidername)){
                        $data[$i]->prprovidername='';
                      }
                      if(is_null($data[$i]->pppracticeemr)){
                        $data[$i]->pppracticeemr='';
                      }
                      if(is_null($data[$i]->pfname)){
                        $data[$i]->pfname='';
                      }
                      if(is_null($data[$i]->plname)){
                        $data[$i]->plname='';
                      }if(is_null($data[$i]->pdob)){
                        $data[$i]->pdob='';
                      }else{
                        $data[$i]->pdob = gmdate("m/d/Y", strtotime($data[$i]->pdob));
                      }if(is_null($data[$i]->ccsrecdate)){
                        $data[$i]->ccsrecdate='';
                      }else{
                        $data[$i]->ccsrecdate = date("m/d/Y", strtotime($data[$i]->ccsrecdate));
                      }
                      if($data[$i]->billingcode == '000'){
                        $data[$i]->billingcode='';
                      }
                      $unit = '';
                      if(($data[$i]->ptrtotaltime >= '00:20:00') &&($data[$i]->ptrtotaltime < '00:40:00')){
                        $unit = '1';
                      }
                      if(($data[$i]->ptrtotaltime >= '00:40:00') &&($data[$i]->ptrtotaltime < '00:60:00')){
                        $unit = '1';
                      }
                      if(($data[$i]->ptrtotaltime >= '00:60:00') &&($data[$i]->ptrtotaltime < '01:30:00')){
                        $unit = '2';
                      }
                      if($data[$i]->ptrtotaltime >= '01:30:00'){ 
                        $unit = '1';
                      }if($data[$i]->billingcode == '99490'){
                        $unit = '1';
                      }
              

                     
                   $arrydata=array($data[$i]->prprovidername,$data[$i]->pppracticeemr,$data[$i]->pfname,$data[$i]->plname,$data[$i]->pdob,$data[$i]->ccsrecdate,$data[$i]->billingcode,$unit);


                    for($j=0;$j<$diagnosis[0]->max;$j++)
                    {

                      if (array_key_exists($j,$splitcode))                       
                      {
                        $maxcount=$maxcount;
                         $arrydata[]=$splitcode[$j];
                        
                      }
                      else
                      {
                         // $maxcount=$j;
                          $arrydata[]='';
                        
                      }

                    }
                    if(is_null($data[$i]->pracpracticename)){
                      $arrydata[]='';
                    }else{
                      $arrydata[]=$data[$i]->pracpracticename;
                    }
                    
                    $arrydata[]=$data[$i]->ptrtotaltime;


                  //   for($j=0;$j<count($splitcode);$j++)
                  //   {
                  //     if($j<$maxcount)
                  //     {
                  //       $maxcount=$maxcount;
                  //     }
                  //     else
                  //     {
                  //         $maxcount=$j;
                  //     }

                  //     $arrydata[]=$splitcode[$j];
                  //       //  array_push($arrydata,$splitcode[$j]);
                  // // $codedata[]=;
                  //  // print_r($splitcode);
                  //  // echo "<br/>";

                  //   }
                   //die;

                    $patientdetails='';
                  if($data[$i]->pprofileimg=='' || $data[$i]->pprofileimg==null)
                  {
                    $patientdetails="<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> ".$data[$i]->pfname;
                  }
                  else
                  {
                      $patientdetails="<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> ".$data[$i]->pfname;
                  }

                


                   
                     // array_push($arrydata, $data[$i]->plname);
                   //echo $data[$i]->pfname." ".$i." <br> ";
                //   $i++;
                  // $tdata=array($arrydata);
                 // $finalheader[]= array_push($columnheader, "title"=>$headername);
                   $ddata['DATA'][]=$arrydata;
                }
                 
                 $dynamicheader=array();
                  $columnheader=array("Provider","EMR","Patient First Name","Patient Last Name","DOB","DOS","CPT Code","Units");

                    for($m=0;$m<count($columnheader);$m++)
                 {
                  $dynamicheader[]=array("title"=>$columnheader[$m]);
                 }

                 for($k=1;$k<$diagnosis[0]->max+1;$k++)
                 {

                  $varheader="Diagnosis ".$k;
                    $dynamicheader[]=array("title"=>$varheader);
                 }
                 $columnheader1=array("Practice","Minutes Spent");
                 for($m1=0;$m1<count($columnheader1);$m1++)
                 {
                  $dynamicheader[]=array("title"=>$columnheader1[$m1]);
                 }
                $fdata['COLUMNS']=$dynamicheader;

                $finldata=array_merge($fdata,$ddata);
      
                 return json_encode($finldata);
                
                
      }

    public function ProductivityReport(Request $request)
    {
     
          return view('Reports::productivity-report');
    }

    public function ProductivityReportSearch(Request $request)
    {
      $practices = $request->route('practiceid'); 
      $care_manager_id  =$request->route('care_manager_id'); 
      $fromdate=$request->route('fromdate');
      $todate=$request->route('todate');
      $currentdate=date('Y-m-d HH:mm:ss');

      $firstDay = mktime (0, 0, 0, date("m"), 1, date("Y"));
      $lastDay = mktime (0, 0, 0, date("m"), date('t'), date("Y"));

      $fromyear = date('Y', strtotime($fromdate));
      $frommonth = date('m', strtotime($fromdate));

      $toyear = date('Y', strtotime($todate));
      $tomonth = date('m', strtotime($todate)); 
      //dd($frommonth);
      
      if($fromdate == 'null'){
        $fdate = date('m-Y',$firstDay);
      }else{
        $fdate = $fromdate;
      }
      
      if($todate == 'null'){
        $tdate = date('m-Y',$lastDay);
      }else{
        $tdate = $todate;
      }
       
        
      $query="select '".$tdate."' as tdate,'".$fdate."' as fdate, concat(u.f_name || ' ' || u.l_name ) as caremanager, p2.name as practice, 
      sum( (EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+
      EXTRACT(seconds FROM ptr.totaltime)/60) ) as totaltime, count(ptr.patient_id) as total_patients, sum(ptr.billable) as billable_patients
      from patients.caremanager_monthwise_total_time_spent ptr inner join ren_core.users u
      on  ptr.created_by = u.id
      left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                      from patients.patient_providers pp1
                      inner join (select patient_id, max(created_at) as created_date 
                      from patients.patient_providers  where provider_type_id = 1 and is_active = 1
                      group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.created_at = pp2.created_date
                      and pp1.provider_type_id = 1 and is_active =1
                       
                      ) pp
                      on ptr.patient_id = pp.patient_id
      inner join ren_core.practices p2 on pp.practice_id = p2.id where 1=1 " ;

           if($fromdate!='null' && $todate!='null')
           {
             $query.=" AND (ptr.month_created between '$frommonth' and  '$tomonth') and (ptr.year_created between '$fromyear' and  '$toyear')";
           }
        
           if( $practices!='null')
            {
              $query.=" AND pp.practice_id = '".$practices."' ";
            }

              
              if($care_manager_id!='null')
              {
               $query.=" AND u.id = '".$care_manager_id."'";
              }
           
              $query.="group by concat(u.f_name || ' ' || u.l_name ), p2.name";

        
              
            
              
           $data = DB::select($query);
           return Datatables::of($data)
          ->addIndexColumn() 
          
        ->rawColumns(['action'])
         
                    
         ->make(true);
        
    }

    public function CareManagerReport(Request $request)
    {
        
          return view('Reports::care-manager-report');
    }
    public function CareManagerReportSearch(Request $request)
    {
     // dd($request);
            $practices = $request->route('practiceid'); 
            $provider = $request->route('providerid'); 
             $module_id = $request->route('module'); 
             $time  =$request->route('time'); 
             $care_manager_id  =$request->route('care_manager_id'); 
            $fromdate=$request->route('fromdate');
            $todate=$request->route('todate');
              $timeoption=$request->route('timeoption');
   
           if($module_id=='null')
           {
            $module_id=3;
           }
          $currentdate=date('Y-m-d HH:mm:ss');
       
        
            $query=" select distinct p.id,pp.practice_emr, p.fname, p.lname, p.mname, p.profile_img, p.dob,pr.name,usr.f_name,usr.l_name,ccs.rec_date,ptr.totaltime,pb.status
                  from patients.patient p
                 inner join patients.patient_services ps on p.id=ps.patient_id
                 left join ren_core.users usr on usr.id=p.updated_by
                 left join patients.patient_billing pb on pb.patient_id=ps.patient_id
                 left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1
                inner join (select patient_id,  max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1 
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
                and pp1.provider_type_id = 1 ) pp                  
                on ps.patient_id = pp.patient_id
                 left join ren_core.providers pr on pp.provider_id=pr.id
                 left join (select rec_date,patient_id from ccm.ccm_call_status where call_status=1 and id in 
                (select max(id) from ccm.ccm_call_status group by patient_id)) ccs on ccs.patient_id=ps.patient_id     
                inner join (select DISTINCT pt.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
                from  patients.patient_time_records pt
                 left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
                 adjust_time =1"; 
              
              if($fromdate!='null' && $todate!='null')
              {
                $query.=" AND record_date between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'";
              }
             
             // elseif(($fromdate!='null' || $fromdate==' ') && ($todate=='null' || $todate==' '))
             //  {
             //    $query.=" AND record_date between '".$fromdate." 00:00:00' and '".$currentdate."'";
             //  }
             // elseif($todate!='null' && $fromdate=='null')
             //  {
             //    $query.=" AND record_date <= '".$todate." 23:59:59' ";
             //  }

               $query.=" group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id  
                LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
                 adjust_time =0 ";

                if($fromdate!='null' && $todate!='null')
              {
                $query.=" AND record_date between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'";
              }
             

             // elseif(($fromdate!='null' || $fromdate==' ') && ($todate=='null' || $todate==' '))
             //  {
             //    $query.=" AND record_date between '".$fromdate." 00:00:00' and '".$currentdate."'";
             //  }
             // elseif($todate!='null' && $fromdate=='null')
             //  {
             //    $query.=" AND record_date <= '".$todate." 23:59:59' ";
             //  }

                 $query.=" group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id ";

                if($fromdate!='null' && $todate!='null')
              {
                $query.=" where pt.record_date between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'";
              }
             

             // elseif(($fromdate!='null' || $fromdate==' ') && ($todate=='null' || $todate==' '))
             //  {
             //    $query.=" where record_date between '".$fromdate." 00:00:00' and '".$currentdate."'";
             //  }
             // elseif($todate!='null' && $fromdate=='null')
             //  {
             //    $query.=" where record_date <= '".$todate." 23:59:59' ";
             //  }

                 $query.=" ) ptr on ptr.patient_id=ps.patient_id 
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
                    $query.=" AND ptr.totaltime ".$timeoption." '".$time."'";
                 }
                 else
                 {
                    $query.=" AND ptr.totaltime < '00:20:00'";

                 }

                 if($care_manager_id!='null')
                 {
                  $query.=" AND usr.id = '".$care_manager_id."'";
                 }


       //dd($query);
              $data = DB::select($query);
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