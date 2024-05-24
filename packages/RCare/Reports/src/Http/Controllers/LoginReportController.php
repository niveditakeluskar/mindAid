<?php 
namespace RCare\Reports\Http\Controllers;
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
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;

class LoginReportController extends Controller  
{ 
    public function LoginLogsReport(Request $request)
    {
        
          return view('Reports::login-report');
    }
    public function LoginLogsReportSearch(Request $request)  
    {  
        $date =  sanitizeVariable($request->route('fromdate')); 
        $users =  sanitizeVariable($request->route('users'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
         
        $d = RenUserLoginHistory::with('newloginusers')->orderBy('login_time','desc'); 
        if($date!="null") 
        {  
            // $d->whereDate('login_time',$date);       
            $d->whereDate(DB::raw("to_char(login_time at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS')"),$date);    
        } 
        if($users!="null")
        {
            $d->where('userid',$users);  
        }
        $d = $d->get();
        $data = $d->toArray();
        foreach($data as $key =>$value)       
        {
         
            $data[$key]['f_name'] = $value['newloginusers']['f_name'];
            $data[$key]['l_name'] = $value['newloginusers']['l_name'];
            $data[$key]['profile_img'] = $value['newloginusers']['profile_img'];
        }
        
            return Datatables::of($data)   
            ->addIndexColumn()            
            ->make(true);   
       
    }

    

}