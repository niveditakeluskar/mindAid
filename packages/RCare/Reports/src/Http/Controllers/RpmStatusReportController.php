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
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 
use Inertia\Inertia;

class RpmStatusReportController extends Controller
{

    public function PatientRpmStatusReport(Request $request) 
    {
    
          return view('Reports::rpm-status-report.rpm-status-list');
    }


    public function PatientNoReadingsReport(Request $request)
    {
        $selectedpractices = sanitizeVariable($request->route('practiceid'));
        return Inertia::render('Report/RpmPatientDetails', [
            'practiceId' => $selectedpractices
        ]); 
    }

    public function PatientRpmStatusReportSearch(Request $request)
    {     
       // dd($request);
        $practices = sanitizeVariable($request->route('practice'));
        $fromdate  =sanitizeVariable($request->route('fromdate'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
       
         $p;
         $timeperiods;

        // if($module_id=='null')
        // {
        //    $module_id=2;
        // }

    
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
        
        if($fromdate=='null' || $fromdate==''){

          $date=date("Y-m-d");
          $year = date('Y', strtotime($date));
          $month = date('m', strtotime($date));
          //$fromdate = $year."-".$month."-01 00:00:00";
          $fromdate = $date." 00:00:00";
          $todate = $date." 23:59:59"; 

          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 
          
          $lastthreedays = date('Y-m-d',strtotime('-3 days', strtotime($fromdate)));
          $lastthreedaysfromdate = $lastthreedays." 00:00:00";
          $lastthreedaystodate = $lastthreedays." 23:59:59" ; 
          $lastdt1 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaysfromdate);
          $lastdt2 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaystodate); 

         }
         else{
    
          $fdate = $fromdate." 00:00:00";
          $todate = $fromdate." 23:59:59" ;
        //   dd($fdate,$todate);
           
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 
          
        
          $lastthreedays = date('Y-m-d',strtotime('-3 days', strtotime($fromdate)));
          $lastthreedaysfromdate = $lastthreedays." 00:00:00";
          $lastthreedaystodate = $lastthreedays." 23:59:59" ; 
          $lastdt1 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaysfromdate);
          $lastdt2 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaystodate);   

         }
        
        //  dd($dt1,$dt2,$lastdt1,$lastdt2);

        
        // $query = "select rpmenrolledcount,
        // rpmnoreadingforlasthtreedayscount,
        // rpmnewlyenrolled,
        // practices from patients.rpm_daily_status_report($p,timestamp '".$dt1."', timestamp '".$dt2."',timestamp '".$lastdt1."', timestamp '".$lastdt1."')";         
         

        

        $query = "select count(pss.patient_id)as enrolledcount,
        count(rw.id) as noreadingforlasthtreedayscount,
        count(a.patient_id) as newlyenrolled,
        count(pd.patient_id) as patientdeviceslink,
        newrp.name as practices,
        newrp.id as practiceid 


        from patients.patient ppp
        inner join patients.patient_services ser on ser.patient_id = ppp.id and ser.module_id = 2 and ser.status = 1
        inner join patients.patient_providers as newpp on newpp.patient_id = ppp.id  and newpp.is_active = 1 and newpp.provider_type_id = 1
        inner join ren_core.practices as newrp on newrp.id = newpp.practice_id and newrp.is_active = 1 
        
        
        left join (select distinct patient_id from patients.patient_services where module_id = 2 ) pss on pss.patient_id =ppp.id

        left join (select distinct patient_id from patients.patient_devices where status = 1) pd on pd.patient_id = ppp.id
        
        left join (select distinct p.id from patients.patient p 
                inner join patients.patient_services ps on ps.patient_id = p.id and ps.module_id = 2 and ps.status = 1
                inner join patients.patient_providers as pp on pp.patient_id = p.id  and pp.is_active = 1 and pp.provider_type_id = 1
                inner join ren_core.practices as rp on rp.id = pp.practice_id and rp.is_active = 1
                and p.id not in 

        ((select bp.patient_id
        from rpm.observations_bp bp
        where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."')) 

        union 
        (select oxy.patient_id
        from rpm.observations_oxymeter oxy where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."') )
                	
		union 
		
		(select hrt.patient_id
		from rpm.observations_heartrate hrt
		where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."') )
		
		union 
		
		(select glc.patient_id
		 from rpm.observations_glucose glc
		 where (effdatetime::timestamp between'".$lastdt1."' and '".$lastdt2."')
		)
		
		union 
		
		(select spi.patient_id
		from rpm.observations_spirometer spi
		where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."') )
		
		union 
		
		(select wght.patient_id
		from rpm.observations_weight wght
		where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."') )
		
		union 
		
		(select temp.patient_id
		from rpm.observations_temp temp
		where (effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."')
		)
        ) 
                where 1=1 
                and
                p.status = 1
            )rw on rw.id = ppp.id
            
        left join (select distinct patient_id from patients.patient_services 
                    where module_id = 2 and created_at
                    between '".$dt1."' and '".$dt2."'
                     ) a on a.patient_id =ppp.id
                     
                     where ppp.status = 1
                  
                     "; 

        if($p!='null')
        {
            $query.=" and newrp.id = '".$p."' group by newrp.name,newrp.id ";
        }else{
            $query.="group by newrp.name,newrp.id "; 
        }
        
        // dd($query);     
        $data = DB::select($query);
        // dd($data);
        return Datatables::of($data) 
        ->addIndexColumn()             
        ->make(true);      
       
    }

     //created by ->ash a for patient summary in rpmstatus report
        public function getPatientRPMData(Request $request)    
        {    
            // for current Month and YEAR 
            // $from_monthly = sanitizeVariable($request->route('from_month'));  
            // $to_monthly   = sanitizeVariable($request->route('to_month'));  

            $from_monthly = '';
            $to_monthly = '';

                    
            if($from_monthly=='' || $from_monthly=='null' || $from_monthly=='0'){
                    $from_monthly1=date('Y-m');
                }else{
                    $from_monthly1=$from_monthly;
                } 

                if($to_monthly=='' || $to_monthly=='null' || $to_monthly=='0'){
                    $to_monthly1=date('Y-m');
                }else{ 
                    $to_monthly1=$to_monthly;
                }

            $fromyear  = date('Y', strtotime($from_monthly1));
            $frommonth = date('m', strtotime($from_monthly1));

            $toyear = date('Y', strtotime($to_monthly1)); 
            $tomonth = date('m', strtotime($to_monthly1));  
                
            $fromdate=$toyear.'-'.$frommonth.'-01 00:00:00';            
            $to_date= $fromyear.'-'.$tomonth.'-01';
            $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
            $todate=date('Y-m-d', $convertdate);
            // end

            
            $query1 = " select count(distinct ppp.id) from patients.patient ppp
            left join patients.patient_providers  newpp on newpp.patient_id = ppp.id  
            inner join ren_core.practices newrp on newrp.id = newpp.practice_id 
            inner join patients.patient_services ps on ps.patient_id = ppp.id
            where ps.module_id = 2 and ppp.status = 1  and newpp.provider_type_id =1
             and newpp.is_active=1 and newrp.is_active = 1 and ps.status = 1 ";    
            $totalRpmPatient=DB::select($query1);  
            

            $query2="select count(distinct ppp.id) from patients.patient ppp
            inner join patients.patient_providers newpp on newpp.patient_id = ppp.id  
            inner join ren_core.practices newrp on newrp.id = newpp.practice_id 
            inner join patients.patient_services ps on ps.patient_id = ppp.id
            where ps.module_id = 2 and ppp.status = 1 and newpp.is_active = 1 and newpp.provider_type_id =1 
            and newpp.is_active=1 and newrp.is_active =1 
            and ps.created_at between  '".$fromdate."' and '".$todate." 23:59:59' ";
            $totalnewlyenrolled=DB::select($query2);

           

            $data=array('totalRpmPatient'=>$totalRpmPatient, 
                        'totalnewlyenrolled'=>$totalnewlyenrolled
                       );

            return $data;
        } 

        public function getEnrolledInRPMPatientData(Request $request)
        {

            $practices = sanitizeVariable($request->route('practice'));
            // $activedeactivestatus = 1;
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

            $query = "select * from patients.sp_enrolled_in_rpm_details($p,1)"; 
            // dd($query);
            $data = DB::select($query);
                    return Datatables::of($data) 
                    ->addIndexColumn()            
                    ->make(true);

        }

        public function getNewlyEnrolledInRPMPatientData(Request $request)
        {

            $from_monthly = '';
            $to_monthly = '';

                    
            if($from_monthly=='' || $from_monthly=='null' || $from_monthly=='0'){
                    $from_monthly1=date('Y-m');
                }else{
                    $from_monthly1=$from_monthly;
                } 

                if($to_monthly=='' || $to_monthly=='null' || $to_monthly=='0'){
                    $to_monthly1=date('Y-m');
                }else{ 
                    $to_monthly1=$to_monthly;
                }

            $fromyear  = date('Y', strtotime($from_monthly1));
            $frommonth = date('m', strtotime($from_monthly1));

            $toyear = date('Y', strtotime($to_monthly1)); 
            $tomonth = date('m', strtotime($to_monthly1));  
                
            $fromdate=$toyear.'-'.$frommonth.'-01 00:00:00';            
            $to_date= $fromyear.'-'.$tomonth.'-01';
            $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
            $todate=date('Y-m-d', $convertdate);

            $practices = sanitizeVariable($request->route('practice'));
            $activedeactivestatus = '1';
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
            $query = "select * from patients.sp_newlyenrolled_in_rpm_details($p,$activedeactivestatus,timestamp '".$fromdate."', timestamp '".$todate."')";   
            // dd($query);
            $data = DB::select($query);
                    return Datatables::of($data) 
                    ->addIndexColumn()            
                    ->make(true);  

        }

        public function noReadingslastthreedaysInRPMData(Request $request)
        {

            $date=date("Y-m-d");
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            //$fromdate = $year."-".$month."-01 00:00:00";
            $fromdate = $date." 00:00:00";
            $todate = $date." 23:59:59"; 
  
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 
            
            $lastthreedays = date('Y-m-d',strtotime('-3 days', strtotime($fromdate)));
            $lastthreedaysfromdate = $lastthreedays." 00:00:00";
            $lastthreedaystodate = $lastthreedays." 23:59:59" ; 
            $lastdt1 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaysfromdate);
            $lastdt2 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaystodate); 

           
            $practices = sanitizeVariable($request->route('practice'));
            $activedeactivestatus = '1';
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

            $query2 = "select * from patients.sp_noreadingsinrpm_details($p,$activedeactivestatus,timestamp '".$lastdt1."', timestamp '".$lastdt2."')";
         
            $newdata = DB::select($query2);
                    return Datatables::of($newdata) 
                    ->addIndexColumn()            
                    ->make(true);    

            return view('Reports::rpm-status-report.no-readings');
        }




}



