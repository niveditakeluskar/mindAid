<?php

namespace RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Diagnosis\src\Http\Requests\DiagnosisAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use DataTables;
use Redirect;
use Session; 
use DB;

class DiagnosisController extends Controller {
    public function index() {
       return view('Diagnosis::diagnosis-list');
    }

    public function addDiagnosis()  
    {
       return view('Diagnosis::diagnosis-add');
    }

    public function populateDiagnosisData($patientId){
      $patientId = sanitizeVariable($patientId);
      $diagnosis_data=DB::select("select d.id,d.condition,upper(dc.code) as code,dc.status,dc.qualified 
      from ren_core.diagnosis as d left join ren_core.diagnosis_codes as dc on dc.diagnosis_id=d.id  
      where d.id='$patientId' and dc.status=1 order by dc.created_at desc");    
      $result['main_diagnosis_form'] = $diagnosis_data;
      return $result;
    }

    // created by ashwini 26april2022
    public function  getDiagnoDataCount($patientid){
      $patientid = sanitizeVariable($patientid);
      $data=DB::select("select *  from patients.patient_diagnosis_codes where patient_id='$patientid' and status = 1  ");  
      $count = count($data);
      // dd( $count);
      return $count;  


    }

    public function getDistinctDiagnosisCodesCountForBubble($patientid){

        $patientId = sanitizeVariable($patientid);
        $date = date('Y-m-d');
        $currentmonth = $date." "."00:00:00";
        //changed by ashvini bharti on 27-feb-2023 bcz count is read by previous six months
        $previoussixmonths =  date('Y-m-d', strtotime('-6 month'));
        $previoussixmonths =  $previoussixmonths." "."23:59:59";
        $monthly = date('Y-m');
        $year = date('Y', strtotime($monthly));
        $month = date('m', strtotime($monthly)); 

      

      //  $data  = DB::select( DB::raw("
      //           select count(distinct pd.diagnosis) 
      //           from patients.patient_diagnosis_codes pd               
      //           where  pd.patient_id='$patientId' and pd.status = 1 and pd.updated_at < '".$previousthreemonths."' 
      //           "));             

              
      $data  = DB::select("
      SELECT                 
      ((select count(distinct pd.diagnosis) 
      from patients.patient_diagnosis_codes pd               
      where  pd.patient_id='$patientId' 
      and pd.status = 1 
      and  EXTRACT(Month from created_at) = '$month'    
      and EXTRACT(year from created_at) = $year
      and pd.diagnosis not in (select diagnosis_id from patients.patient_careplan_last_update_n_review where status = 1 and patient_id='$patientId' ))                                
      +                
     (select count(distinct pr.diagnosis_id) 
      from patients.patient_careplan_last_update_n_review pr               
      where  pr.patient_id='$patientId' and pr.status = 1 and pr.review_date < '".$previoussixmonths."'))
      AS count;            
                         
      ");      
      //this query adds count from patient_diagnosis_codes and count from patient_careplan_last_update_n_review

      return $data; 
    
    }


     // created by ashwini 3rdjune2022
     public function getDisableDataofDiagnosis($id){
      $id = sanitizeVariable($id);
      $patientid = sanitizeVariable($patientid);
      $data=DB::select("
              select id,upper(code) as code,condition,symptoms,goals,tasks,comments,created_at,updated_at,patient_id,
              uid,diagnosis,created_at,updated_at,review,status where  id='$id'
              from patients.patient_diagnosis_codes
              ");


      $diagnosisconditionid = $data[0]->diagnosis;
      $symptons = $data[0]->symptons;
      $goals = $data[0]->goals;
      $tasks = $data[0]->tasks;
      
      $patientdiagnosis = Diagnosis::where('id',$diagnosisconditionid)->get();
      $diagnosiscondition = $patientdiagnosis[0]->condition; 

      return $diagnosiscondition;        
    }



     // created by ashwini 26april2022
     public function getDiagnoData($id,$patientid,$condition_name,$code){ 
       //to  check  if  currentmonth of that particular patient carries same condition and code 
      $id = sanitizeVariable($id);
      $patientid = sanitizeVariable($patientid);
      $month     = date('m');
      $year      = date("Y");

      $data=DB::select(" select id,upper(code) as code,condition,symptoms,goals,tasks,comments,created_at,updated_at,patient_id,
              uid,diagnosis,created_at,updated_at,review,status 
              from patients.patient_diagnosis_codes where patient_id='".$patientid."' and
               EXTRACT(Month from created_at) = '".$month."' AND EXTRACT(YEAR from created_at) = '".$year."' 
               and condition = '".$condition_name."' and code = '".$code."'
              ");
              // dd($data);
     $count=         count($data);
      // $diagnosisconditionid = $data[0]->diagnosis;
      // dd($diagnosisconditionid)
      // $patientdiagnosis = Diagnosis::where('id',$diagnosisconditionid)->get();
      // $diagnosiscondition = $patientdiagnosis[0]->condition; 

      return $count;        
    }



    public function editDiagnosis($id)  	
    {   
		$id = sanitizeVariable($id);
		$patient = Diagnosis::where('id',$id)->get();
       return view('Diagnosis::diagnosis-edit',['patient'=>$patient]);
    }

     public function saveDiagnosis(DiagnosisAddRequest $request){ //dd($request);
      $id        = sanitizeVariable($request->id);
      $code      = sanitizeVariable($request->code);     
      $condition = sanitizeVariable($request->condition);
      $qualified = sanitizeVariable($request->qualified);
     // $u_code = array_change_key_case($code, CASE_UPPER);
      $u_condition = strtoupper($condition); 
      $data      = array(   
          'condition' => $condition,
          'created_by'  => session()->get('userid'),
          'updated_by'   =>session()->get('userid'),
          'qualified'   =>$qualified
      ); 
      if($id==''){
        //$existcondition=Diagnosis::where('condition',$condition)->exists(); 
        //$existcode=Diagnosis::where(UPPER(code),$u_code)->where(UPPER(condition),$u_condition)->exists(); 
       $existcondition = DB::select("select * from ren_core.diagnosis d  where  upper(d.condition) = '".$u_condition."'");
       //dd($existcondition); 
       if($existcondition  == '' || $existcondition == null ){ //dd("if"); for editing the code 
          //$data['created_by']= session()->get('userid');
          $insert_query = Diagnosis::create($data);
          $lastinsertedid=$insert_query->id;
          if(!empty($code))
          {
            for($i=0;$i<count($code);$i++)
            {
                $u_code = strtoupper($code[$i]);
                $existcode = DB::select("select * from ren_core.diagnosis_codes dc  
                                                  inner join ren_core.diagnosis d on d.id = dc.diagnosis_id  
                                                  where  upper(dc.code) = '".$u_code."' and  upper(d.condition) = '".$u_condition."' "); 
                if($existcode == '' || $existcode == null) {  
                  $datacode     = array(
                    'code'      => $u_code,
                    'diagnosis_id' => $lastinsertedid,
                    // 'status'    =>1,
                    'created_by'  => session()->get('userid'),
                    'updated_by'   =>session()->get('userid'),
                    'qualified'  => $qualified     
                  ); 
                 $insert_code = DiagnosisCode::create($datacode);
                 //$insert_code1 = Diagnosis::create($datacode);
                } else { 
                  $j = $i + 1;
                  $codeexist = "codeexist+'".$j."'";
                  return $codeexist;
                }
            } //dd($insert_code);
          }    
          return "add";     
        } else { //dd("else");
          return "exist";
        }
      } else {
       // $existcondition=Diagnosis::where('condition',$condition)->where('id','!=',$id)->exists();
       $existcondition = DB::select("select * from ren_core.diagnosis d  where  upper(d.condition) = '".$u_condition."' and id != '".$id."' ");
       if($existcondition  == '' || $existcondition == null ){ //dd("if");
          $update_query = Diagnosis::where('id',$id)->orderBy('id', 'desc')->update($data);
          $statusdeactive=array("status"=>0);
          $updateDiagnosisCode=DiagnosisCode::where('diagnosis_id',$id)->update($statusdeactive);
        
          if(!empty($code)) {
            for($i=0;$i<count($code);$i++){
              $u_code = strtoupper($code[$i]);
              $existcode = DB::select("select * from ren_core.diagnosis_codes dc  
                                                inner join ren_core.diagnosis d on d.id = dc.diagnosis_id  
                                                where  upper(dc.code) = '".$u_code."' and  upper(d.condition) = '".$u_condition."' and dc.status = 1");
              if($existcode == '' || $existcode == null) {  
                $datacode=[];
                $datacode     = array(
                  'code'      => $u_code,
                  'diagnosis_id' => $id,
                  'status'    =>1,
                  'created_by'  => session()->get('userid'),
                  'updated_by'   =>session()->get('userid'),
                  'qualified'  => $qualified
                );
                $update_query = DiagnosisCode::where('diagnosis_id',$id)->create($datacode);
              } else{ //dd("else");
                $j = $i + 1 ;
                $codeexist = "codeexist+'".$j."'";
                return $codeexist;
              }
            }   
          }        
          return "edit";
        } else {
          return "exist";
        }
      } 
    }

    public function CheckConditionExist(Request $request)
    {
      $condition=sanitizeVariable($request->condition);
      $id=sanitizeVariable($request->id);
      
        if($id=='0')
      {
         $existcondition=Diagnosis::where('condition',$condition)->exists(); 
      if($existcondition==true)
      {
     
         return "exist";      
      }
     }
      else
      {
         $existcondition=Diagnosis::where('condition',$condition)->where('id','!=',$id)->exists();
      
        if($existcondition==true)
      {
        return "exist";
      }
      }
      
    }

     public function DiagnosisList(Request $request) {
        if ($request->ajax()) {
          // $data = Diagnosis::with('DiagnosisCode')->with('users')->get();
           $configTZ = config('app.timezone'); 
           $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
          // dd($userTZ);
           $data=DB::select("select d.id,d.condition,
           rtrim(array_to_string(array_agg(upper(concat(dc.code , '#' , dc.valid_invalid))), ','),'#') as code, d.status, to_char(d.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at,
               u.f_name, u.l_name,d.qualified from ren_core.diagnosis as d 
              FULL OUTER JOIN  ren_core.diagnosis_codes as dc on dc.diagnosis_id=d.id and dc.status=1 and dc.code is not null 
              left join ren_core.users as u on d.created_by=u.id  
              where d.condition is not null
              group by d.id,u.f_name,u.l_name,d.qualified order by d.created_at desc");
          
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function($row){
              $code_vi =$row->code;  
              // dd($code_vi);          
              $codevi = preg_split('/[,]/' , $code_vi);
              // print_r($codevi);
              $countcode = count($codevi);
              //dd($countcode);
              $invalidcodearray = array();
              $validcodearray  = array();
             
              for ($i = 0; $i < $countcode; $i++) {
                $dccode = $codevi[$i];
                // print_r($dccode); 
                $color_vi = preg_split('/[#]/' , $dccode);
                // print_r($color_vi);
                if(count($color_vi)>=2){
                  $codedata = $color_vi[0];
                  // print_r($color_vi);echo "<pre>";
                  $validinvalid = $color_vi[1];
                  if($validinvalid == '0'){
                      //$invalidcolor = '<div class="color-red">'.$codedata.'</div>';
                      array_push($invalidcodearray,"<span style='color: red;'>".$codedata."</span>");
                  }else{
                    array_push($validcodearray,$codedata);
                  }
                } 
                else{
                  $codedata = $color_vi[0];
                  $validinvalid = '1'; //whend valid invalid is null
                  if($validinvalid == '0'){
                      array_push($invalidcodearray,"<span style='color: red;'>".$codedata."</span>");
                  }else{
                    array_push($validcodearray,$codedata);
                  }
                }
                $invalidcolor = implode(",",$invalidcodearray); 
                $validcolor = implode(",",$validcodearray);
               
                $color1 = $validcolor.','.$invalidcolor;
                //$color1 = $invalidcolor;
                $color = trim($color1, ','); 
              }  //end foreach
              // die; 
              //dd($color);  
              return $color;
            })
            ->addColumn('action', function($row){
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editDiagnosis" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_diagnosis_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else 
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_diagnosis_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['code','action'])
            ->make(true);
        }
        return view('Diagnosis::diagnosis-list');
    }

    public function deleteDiagnosis(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = Diagnosis::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Diagnosis::where('id',$id)->orderBy('id', 'desc')->update($status);
          return view('Diagnosis::diagnosis-list');
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Diagnosis::where('id',$id)->orderBy('id', 'desc')->update($status);
          return view('Diagnosis::diagnosis-list');
        }
        
        // Diagnosis::where('id', $id)->delete();
    }
  
  
} 