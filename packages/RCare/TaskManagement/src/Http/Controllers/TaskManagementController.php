<?php

namespace RCare\TaskManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientProvider;
use Session, DB;
use DataTables;

class TaskManagementController extends Controller
{
    // public function getToDoListData($patient_id,$module_id)
    // {   
    //     $module_id = sanitizeVariable(getPageModuleName());
    //     $submodule_id = sanitizeVariable(getPageSubModuleName());
    //     $patient_id = sanitizeVariable(request()->segment(3));       
    //     $login_user =Session::get('userid');
    //     $configTZ = config('app.timezone');
    //     $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
    //     $query = "select fname, lname, id, task_time,  task_notes, notes, module_id, component_id, patient_id, module, components, created_at, enrolled_service_id, to_char(task_date at time zone '".$configTZ ."' at time zone '".$userTZ."','MM-DD-YYYY HH12:MI:SS')::timestamp as tt  from patients.SP_TO_DO_LIST($patient_id,$login_user,'".$configTZ ."','".$userTZ."')";  
    //     $to_do_list = DB::select( DB::raw($query) ); 
    //     $to_do_arr =[];
    //     $i = 0;
    //     foreach ($to_do_list as $value) {

    //         $to_do_arr[$i]['fname']               = $value->fname;
    //         $to_do_arr[$i]['lname']               = $value->lname;
    //         $to_do_arr[$i]['id']                  = $value->id;
    //         $to_do_arr[$i]['task_date']           = $value->tt;
    //         $to_do_arr[$i]['task_time']           = $value->task_time;
    //         $to_do_arr[$i]['task_notes']          = $value->task_notes;
    //         $to_do_arr[$i]['notes']               = $value->notes;
    //         $to_do_arr[$i]['module_id']           = $value->module_id; 
    //         $to_do_arr[$i]['component_id']        = $value->component_id; 
    //         $to_do_arr[$i]['patient_id']          = $value->patient_id;
    //         $to_do_arr[$i]['module']              = $value->module;
    //         $to_do_arr[$i]['components']          = $value->components;
    //         $to_do_arr[$i]['created_at']          = $value->created_at;
    //         $to_do_arr[$i]['enrolled_service_id'] = $value->enrolled_service_id;
    //         $i++;
    //     }

    //     return view('TaskManagement::components.to-do-list',['to_do_arr'=>$to_do_arr]);
    // }


    public function getToDoListData($patient_id, $module_id)
    { //dd("working");
        $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);
        // dd("anand".$module_id.",".$patient_id.",".$login_user.",".$configTZ.",".$userTZ);

        $data = "select
         fname, lname, id, task_time,  task_notes, notes, module_id, component_id, patient_id,
         module, components, created_at, enrolled_service_id,
         to_char(task_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "','MM-DD-YYYY HH12:MI:SS')::timestamp as tt,userfname,userlname 
         from patients.SP_TO_DO_LIST($patient_id,$login_user,'" . $configTZ . "','" . $userTZ . "')";
        // dd($data);
        $query = DB::select($data);
        // dd($query);
        return view('TaskManagement::components.to-do-list', compact('query', 'patient_id'));
    }

    public function Nonassignedpatients(Request $request)
    {
        if ($request->ajax()) {
            $practid = sanitizeVariable($request->practice);
            $query = "select * from patients.SP_NON_ASSIGNED_PATIENTS_DETAILS($practid)";
            $data = DB::select($query);

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function Assignedpatientstable(Request $request)
    {
        if ($request->ajax()) {
            $practid = sanitizeVariable($request->practice);

            $query = "select * from patients.SP_ASSIGNED_PATIENTS_DETAILS($practid)";

            $data = DB::select($query);

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }



    public function Cmlist(Request $request)
    {
        if ($request->ajax()) {
            $practid = sanitizeVariable($request->practice);
            if ($practid == "" || $practid == "null" || $practid == null || $practid == 0) {
                $data = \DB::table('ren_core.users as u')
                    //->join('ren_core.user_practices as rp','u.id','=','rp.user_id') 
                    ->where('u.role', 5)
                    ->where('u.status', 1) //Updated by -pranali on 22Oct2020
                    ->get();
            } else {
                $data = \DB::table('ren_core.users as u')
                    ->join('ren_core.user_practices as rp', 'u.id', '=', 'rp.user_id')
                    ->where('u.role', 5)
                    ->where('u.status', 1) //Updated by -pranali on 22Oct2020
                    ->where('rp.practice_id', $practid)
                    ->get();
            }


            foreach ($data as $d) {
                $count = \DB::table('task_management.user_patients')
                    ->where('user_id', $d->id)
                    ->distinct('patient_id')
                    ->count('patient_id');
                $d->count = $count;
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function Allpatientstable(Request $request)
    {
        if ($request->ajax()) {
            $practid = sanitizeVariable($request->practice);

            //   $query = "select * from patients.SP_TOTAL_PATIENT_DEATILS_OF_ASSIGN_PATIENT($practid)";  

            $query = "select * from patients.patient_details($practid)";

            $data = DB::select($query);

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
