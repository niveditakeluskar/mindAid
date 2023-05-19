<?php

namespace RCare\Org\OrgPackages\RPMAlert\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\RPMAlert\src\Http\Requests\DevicesAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\RPMAlert\src\Models\PatientAlert;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientProvider;
use DataTables,DB;
use Session; 
use RCare\System\Traits\DatesTimezoneConversion; 

class RPMAlertController extends Controller {
    public function index() {
       return view('RPMAlert::rpm-alert-list');
    }

    public function RPMAlertList(Request $request) {
        $date=sanitizeVariable($request->route('fromdate'));
        $tdate=sanitizeVariable($request->route('todate'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        if($date=='null' || $date=='')
         {
               $date=date("Y-m-d");
               $year = date('Y', strtotime($date));
               $month = date('m', strtotime($date));
               $fromdate = $year."-".$month."-01" ." "."00:00:00";
               $todate = $date ." "."23:59:59";
               $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
               $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
             
         }
         else{
          $year = date('Y', strtotime($date));
          $month = date('m', strtotime($date));
          $fromdate =  $date ." "."00:00:00";
          $todate = $tdate ." "."23:59:59";
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
         }
       
        if ($request->ajax()) {       
            $data = DB::table('patients.partner_patient_alerts as pa')
                ->select('pa.*','p.*',\DB::raw("to_char(pa.readingtimestamp at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as readingtimestamp"))
                ->leftjoin('patients.patient as p', 'pa.patient_id', '=', 'p.id')
                ->leftJoin('patients.patient_providers as pp',function ($join) {
                     $join->on('pp.patient_id', '=' , 'pa.patient_id') ;
                     $join->where('pp.provider_type_id',1) ;
                     $join->where('pp.provider_type_id',1) ;
                     $join->where('pp.is_active',1);
                 })             
                ->leftjoin('ren_core.practices as pr', 'pr.id', '=', 'pp.practice_id')      
                ->whereBetween('timestamp', [$dt1, $dt2])                
                ->get();
             
           
            return Datatables::of($data)
            ->addIndexColumn()  
             ->addColumn('action', function($row){
               $btn ='<a href="#"  title="Start" ><button type="button" class="btn btn-primary rpmalertnotes" name="rpmalertnotes" id="rpmalertnotes_'.$row->id.'" value="'.$row->notes.'">View Notes</button></a><input type="hidden" class="loadclass">';               
                return $btn;
            })    
             ->rawColumns(['action'])       
            ->make(true);
        }
      
    }
    public function deleteDevices(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = Devices::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Devices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Devices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        
        // Diagnosis::where('id', $id)->delete();
    }

} 