<?php
 
namespace RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers;

// use RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
// use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRole;
use RCare\RCareAdmin\AdminPackages\Scheduler\src\Models\Scheduler; 
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use Illuminate\Support\Str;
use Session;
use DB;
use File;
use DataTables;
use Validator,Redirect,Response;


class SchedulerController extends Controller {   

    public function index() {
        $modules = \DB::table('ren_core.modules')->where('patients_service',1)->get();  
        return view('Scheduler::scheduler',compact('modules'));  
    }

    

    public function saveScheduler(Request $request)
    {
        dd($request->all());    
      $activity      = sanitizeVariable($request->activity);
      $services      = sanitizeVariable($request->services);
      $executiondate = sanitizeVariable($request->day_of_execution);
      $executiontime = sanitizeVariable($request->time_of_execution);
      $operation     = sanitizeVariable($request->operation);
      $operation == '1' ? $operation='add': $operation='deduct';
      $comments      = sanitizeVariable($request->comments);  
      $created_by    = session()->get('userid');

      $data = array(
        'activity_id'          =>  $activity,
        'module_id'            =>  $services,
        'operation'            =>  $operation,
        'day_of_execution'     =>  $executiondate,
        'time_of_execution'    =>  $executiontime,
        'comments'             =>  $comments,
        'created_by'           =>  $created_by,
        'updated_by'           =>  $created_by,
        'status'               =>  1
     );
    //  dd($data);
     
     Scheduler::create($data);

    }

    public function SchedulerList(Request $request)  
    {
        if($request->ajax()){
            $configTZ = config('app.timezone');
            $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			//DB::enableQueryLog();
            $data     = Scheduler::with('activity')->get();   
           // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){  
                $btn ='<a href="javascript:void(0)"data-id="'.$row->id.'" id="edit-scheduler" data-original-title="Edit" class="editScheduler" title="Edit"><i class=" editform i-Pen-4"></i></a>';

               if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_scheduler_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
               } else {
                  $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_scheduler_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
               }
               return $btn; 
            })
            ->rawColumns(['action']) 
            ->make(true); 
        }
        return view('Scheduler::scheduler');

    }

    public function populateSchedulerData($schedulerId)
    {
        $schedulerId   = sanitizeVariable($schedulerId);
        // $data = Scheduler::schedulerActivity($schedulerId);  
        $data = Scheduler::self($schedulerId)->population(); 
        // $data = Scheduler::with('schedulerActivity')->where('id',$schedulerId)->population(); 
        // dd($data); 

        // $data = (Scheduler::self($schedulerId) ? Scheduler::self($schedulerId)->population() : "");
        // dd($data);
        // $paramdata    = Activity::where('activity_id',$data->activity_id)->where('status',1)->get()->toArray(); 

  
        // if($paramdata){ 
        //    $activityparamdata      = array('paramdata'=>$paramdata);
        //    $activitydata['static'] = array_merge($activitydata['static'], $activityparamdata);
        // }
        $result['edit_scheduler_form'] = $data;
        // dd($result);   
        return $result; 
    }

    public function updateScheduler(Request $request) {
        // dd($request->all()); 
        $schedulerId   = sanitizeVariable($request->scheduler_id);        
        $activity      = sanitizeVariable($request->activity);
        $services      = sanitizeMultiDimensionalArray($request->services);
        $executiondate = sanitizeVariable($request->executiondate);
        $executiontime = sanitizeVariable($request->executiontime);
        $operation     = sanitizeVariable($request->operation);
        $operation == '1' ? $operation='add': $operation='deduct';
        $comments      = sanitizeVariable($request->comments);  
        $created_by    = session()->get('userid');
  
        $updatedata = array(
          'activity_id'          =>  $activity,
          'module_id'            =>  $services,
          'operation'            =>  $operation,
          'day_of_execution'     =>  $executiondate,
          'time_of_execution'    =>  $executiontime,
          'comments'             =>  $comments,
          'updated_by'           =>  $created_by, 
          'status'               =>  1
       );
       
        Scheduler::where('id', $schedulerId)->update($updatedata); 
     } 



    
   

   

   

   
   
}
