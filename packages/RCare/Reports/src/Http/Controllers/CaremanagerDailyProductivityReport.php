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
 
class CaremanagerDailyProductivityReport extends Controller
{
    
    public function listCaremanagerDailyProductivityReport(Request $request)//manually adjust timer report storeprocedure
    {   
        if ($request->ajax()) { 
            $caremanager = sanitizeVariable($request->route('caremanager'));
            $configTZ = config('app.timezone'); 
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $date = sanitizeVariable($request->route('date'));  
            if($date=='null' || $date==''){
                $date=date("Y-m-d");   
                  $fromdate =$date." "."00:00:00";
                  $todate = $date ." "."23:59:59"; 
                  $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate); 
                  $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); 
            }
            else{
                  $fromdate =$date." "."00:00:00";   
                  $todate = $date ." "."23:59:59"; 
                  $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
                  $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
            }
            // echo $dt1; echo "string"; echo $dt2;
            $query =
            // "select distinct u.id,
            //         concat(u.f_name||' '||u.l_name) as user_name,
            //         u.extension,
            //         o.location,     
            //         u.id as user_id,
            //         urs.responsibility,
            //         count(ps.patient_id)  filter(where ps.date_enrolled between '".$dt1."' and '".$dt2."') as totalenrolled,
            //         coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (8,3)) ::numeric, 2 ),0) as ccmtotaltimeinminutes, 
            //         coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (8,3)) ::numeric, 2),0) as rpmtotaltimeinminutes, 
            //         count(ps.patient_id) filter(where ptr.totaltime >= '00:20:00' and ptr.module_id='3') as ccm_count,
            //         count(ps.patient_id) filter(where ptr.totaltime >= '00:20:00' and ptr.module_id='2') as rpm_count,
            //         coalesce(0+coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id='3') ::numeric, 2 ),0) + coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id='2') ::numeric, 2 ),0)+0,0) as productivity  
            //         from patients.patient p  
            //         left join patients.patient_services ps on ps.patient_id =p.id 
            //         left join ren_core.users u on ps.created_by = u.id
            //         left join (select DISTINCT pt.patient_id,pt.created_by,pt.module_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
            //         from  patients.patient_time_records pt
            //         left JOIN (SELECT patient_id,sum(net_time) as timeone,module_id FROM patients.patient_time_records WHERE 
            //         adjust_time =1 and module_id in (2,3) and billable=1
            //         and (created_at between '".$dt1."' and '".$dt2."')  group by patient_id,module_id) pt1 ON  pt1.patient_id = pt.patient_id  
            //         LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo,module_id FROM patients.patient_time_records WHERE 
            //         adjust_time =0 and module_id in (2,3) and billable=1
            //         and (created_at between '".$dt1."' and '".$dt2."') group by patient_id,module_id) pt2 ON  pt2.patient_id = pt.patient_id  
            //         where (pt.created_at between '".$dt1."' and '".$dt2."' ";
"select distinct u.id,
concat(u.f_name||' '||u.l_name) as user_name,
u.extension,
o.location,     
u.id as user_id,
urs.responsibility,
count(ps.patient_id) filter(where ps.date_enrolled between '".$dt1."' and '".$dt2."' ) as totalenrolled,
coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (3,8)) ::numeric, 2 ),0) as ccmtotaltimeinminutes, 
coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (2,8)) ::numeric, 2),0) as rpmtotaltimeinminutes,
count(ps.patient_id) filter(where ptr.totaltime >= '00:20:00' and ptr.module_id='3') as ccm_count,
count(ps.patient_id) filter(where ptr.totaltime >= '00:20:00' and ptr.module_id='2') as rpm_count,
coalesce(0+coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (3,8)) ::numeric, 2 ),0) + coalesce(round(sum((EXTRACT(hour FROM ptr.totaltime)*60+EXTRACT(minutes FROM ptr.totaltime)+EXTRACT(seconds FROM ptr.totaltime)/60) ) filter(where ptr.module_id in (2,8)) ::numeric, 2 ),0)+0,0) as productivity  
from patients.patient p  
left join patients.patient_services ps on ps.patient_id =p.id 
left join ren_core.users u on ps.created_by = u.id
left join (select DISTINCT pt.patient_id,pt1.timeone,pt.module_id,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
from  patients.patient_time_records pt 
left JOIN (SELECT patient_id,sum(net_time) as timeone,module_id FROM patients.patient_time_records WHERE 
adjust_time =1  and billable=1 and module_id in (2,3,8)
and (created_at between '".$dt1."' and '".$dt2."' ) group by patient_id,module_id ) pt1 ON  pt1.patient_id = pt.patient_id 
LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo,module_id FROM patients.patient_time_records WHERE 
adjust_time =0 and billable=1 and module_id in (2,3,8)
and (created_at between '".$dt1."' and '".$dt2."' ) group by patient_id,module_id ) pt2 ON  pt2.patient_id = pt.patient_id  
where (pt.created_at between '".$dt1."' and '".$dt2."' ";
                 if($caremanager!="null" &&  $caremanager!=""){
                        $cm = $caremanager;
                        $query.=" and pt.created_by= '".$cm."' ";
                    }
                        $query.= ") and pt.module_id in  (2,3,8)) ptr on ptr.patient_id=ps.patient_id
                        left join ren_core.office o on o.id = u.office_id and o.status = 1
                        left join (select u.id ,string_agg(r.responsibility, ',') as responsibility from ren_core.users u 
                        left join ren_core.user_reponsibilities ur on ur.user_id =u.id 
                        left join ren_core.responsibilities r on r.id=ur.responsibility_id
                        where u.role =5 group  by u.id ) urs on urs.id=u.id
                        where p.status=1 and u.role = 5";

                    if($caremanager!="null" &&  $caremanager!=""){
                        $cm = $caremanager;
                        $query.=" and u.id= '".$cm."'  ";
                    }
            $query.="group by u.f_name,u.l_name,u.extension,u.id,o.location,responsibility"; 
            // dd( $query);
            $data  = DB::select( DB::raw($query) );
            return Datatables::of($data) 
                ->addIndexColumn()
            ->make(true);
        }
    }

}
     