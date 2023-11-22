<?php

namespace RCare\Org\OrgPackages\Holiday\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Holiday\src\Http\Requests\holidayAddRequest;
use RCare\Org\OrgPackages\Holiday\src\Models\Holiday;
use DataTables;
use Redirect;
use Session; 
use DB;

class holidayController extends Controller {
    public function index() {
       return view('Holiday::holiday');
    }

    public function saveHoliday(holidayAddRequest $request){ //dd($request);
        $id 		   = sanitizeVariable($request->id);
        $event         = sanitizeVariable($request->event);     
        $date          = sanitizeVariable($request->date);
       //dd($date);
       $adddata 	   = array(    
        'status'    => 1,
        'event'      => $event,
        'date'       => $date,
        'created_by'  => session()->get('userid'),
        'updated_by'  =>session()->get('userid')
      ); 
       $editdata 	   = array(    
           'event'      => $event,
           'date'       => $date,
           'created_by'  => session()->get('userid'),
           'updated_by'  =>session()->get('userid')
         ); 
        // dd($data);
         if($id==''){
            $insert = Holiday::create($adddata);
            return "add";
         }else{
            $update=Holiday::where('id',$id)->update($editdata);
            return "edit";
         }
        // dd($insert_code);
    } 

    public function HolidayList(Request $request){ //dd($request);
        if ($request->ajax()) {
            // $data = Diagnosis::with('DiagnosisCode')->with('users')->get();
             $configTZ = config('app.timezone'); 
             $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
             //dd($configTZ , $userTZ);
             $data=DB::select( DB::raw("select h.id,h.status,u.f_name, u.l_name,h.event,h.date,to_char(h.date, 'mm-dd-yyyy') as date1,INITCAP(to_char(h.date,'day')) as day,h.created_at,h.created_by,h.updated_at,h.updated_by from ren_core.holiday h  
             left join ren_core.users as u on h.created_by=u.id"));
             //dd( $data);
              return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){
                  // $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                  $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editholiday" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                  if($row->status == 1){
                    $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_holiday_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                    }
                    else 
                    {
                      $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_holiday_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                  }
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
          }
          return view('Holiday::holiday');
    }

    public function populateHolidayData($patientId){ //dd("working"); FORMAT (getdate(), 'yyyy-MM-dd hh:mm:ss tt') as date
        $patientId = sanitizeVariable($patientId); 
        $data=DB::select( DB::raw("select h.id,h.event,to_char(h.date, 'YYYY-MM-DD') as date1,h.date,h.created_at,h.updated_at,h.created_by,h.updated_by from ren_core.holiday h where h.id = '".$patientId."'"));  
        $result['holiday_form'] = $data;
        return $result;
    }
    public function deleteHoliday(Request $request)
    {   //dd("working");
        $id = sanitizeVariable($request->id);
        $data = Holiday::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Holiday::where('id',$id)->orderBy('id', 'desc')->update($status);
          return view('Holiday::holiday');
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Holiday::where('id',$id)->orderBy('id', 'desc')->update($status);
          return view('Holiday::holiday');
        }
        // Diagnosis::where('id', $id)->delete();
    }
}