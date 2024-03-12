<?php

namespace RCare\Reports\Http\Controllers; 
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Users\src\Models\Users;

use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
//use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 
    
class Verifyicd10CodeReportController extends Controller {

  public function verifycodeReport(Request $request){ 
      return view('Reports::verify_icd10_codes_reports.verifyicd10_codes_reports');
  }
   //valid 
  public function verifysearchReport(Request $request) { 
      $diagnosis  = sanitizeVariable($request->diagnosis);
      $practices = sanitizeVariable($request->practices);
      $login_user = Session::get('userid');
      $configTZ   = config('app.timezone');
      $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
       
      $cid = session()->get('userid');
      $userid = $cid;
      $usersdetails = Users::where('id',$cid)->get();
      $roleid = $usersdetails[0]->role;
      if( $diagnosis!='null') {
        if( $diagnosis==0){
          $c = $diagnosis;  
        }else{
          $c = $diagnosis; 
        } 
      } else{
        $c = 'null';
      }

      if( $practices!='null') {
        if( $practices==0){
          $p = $practices;  
        }else{
          $p = $practices; 
        } 
      } else{
        $p = 'null';
      }
                   
     
      $query = "select dc.diagnosis_id, upper(dc.code) as code1 , REPLACE(dc.code, ' ', '__') as newcode, dc.code, pdc.condition, u.f_name , u.l_name ,
      TO_CHAR(count(distinct pdc.patient_id) , '0000') as patientcount , dc.verify_status, dc.valid_invalid ,
      to_char(dc.verify_on  at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as verify_on 
      from ren_core.diagnosis_codes dc 
      inner join patients.patient_diagnosis_codes pdc on dc.code = pdc.code and  dc.diagnosis_id = pdc.diagnosis and pdc.status = 1 
      left join ren_core.users as u on dc.verified_by = u.id 
      inner join patients.patient_providers pp on pp.patient_id = pdc.patient_id  and pp.provider_type_id = 1 and pp.is_active = 1
      left join ren_core.practices prac on pp.practice_id = prac.id and prac.is_active = 1 
      left join patients.patient p on p.id = pdc.patient_id
      where dc.verify_status = '1' and dc.code is not null and dc.diagnosis_id is not null and pdc.condition is not null and dc.status = 1  ";   

      if($practices!='null' && ($diagnosis=='null' || $diagnosis == '')){
          $query .= "  and pp.practice_id = '".$p."'";
      } elseif (($practices=='null' || $practices == '') && $diagnosis!='null') {
          $query .= "  and dc.diagnosis_id = '".$c."'";
      } elseif ($practices!='null' && $diagnosis!='null') {
          $query .= "  and pp.practice_id = '".$p."' and dc.diagnosis_id = '".$c."'";
      }else{}

      $query .="group by dc.diagnosis_id, dc.code , pdc.condition , dc.valid_invalid, dc.verify_status, dc.verified_by , dc.verify_on  , u.f_name ,u.l_name";
        
      //dd($query);
         
      $data = DB::select($query);
      //dd($data);
      return Datatables::of($data) 
        ->addIndexColumn()
        ->addColumn('valid_chk', function($row){
          if($row->valid_invalid=="1") {
              $chk="checked";
          } else{
              $chk="";
          }
          $valid_btn = '<input type="checkbox" class="chk_valid valid" id="'.$row->code.'/'.$row->diagnosis_id.'" value="'.$row->valid_invalid.'" '.$chk.'>';
          return  $valid_btn;
        })
        ->addColumn('invalid_chk', function($row){
          if($row->valid_invalid=="0") {
              $chk="checked";
          } else{
              $chk="";
          }
          //$invalid_btn = '<input type="checkbox" class="chk_validinvalid valid" id="'.$row->code.'/'.$row->condition.'" value="'.$row->valid_invalid.'" '.$chk.'>';
          $invalid_btn = '<div class="checkdiv grey400">
          <label for="invalid" class="css-label">
          <input type="checkbox" class="chk_invalid invalid" id="'.$row->code.'/'.$row->diagnosis_id.'" value="'.$row->valid_invalid.'" '.$chk.'>
          </label>
          </div>';
          return  $invalid_btn;
        })
        ->addColumn('verify_yes_no', function($row){
          if($row->verify_status=="1"){
              $verify_1="yes";
          }else{
              $verify_1="No";
          }
          $btn ="";

          return $verify_1;
        })
        ->rawColumns(['valid_chk','invalid_chk','verify_yes_no'])      
        ->make(true);       
  }
  //invalid
  public function verifysearchReport1(Request $request) { 
    $diagnosis  = sanitizeVariable($request->diagnosis);
    $practices = sanitizeVariable($request->practices);
   // dd($diagnosis);
    $login_user = Session::get('userid');
    $configTZ   = config('app.timezone');
    $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
    $cid = session()->get('userid');
    $userid = $cid;
    $usersdetails = Users::where('id',$cid)->get();
    $roleid = $usersdetails[0]->role;
    if( $diagnosis!='null') {
      if( $diagnosis==0){
        $c = $diagnosis;  
      }else{
        $c = $diagnosis; 
      } 
    } else{
      $c = 'null';
    }

    if( $practices!='null') {
      if( $practices==0){
        $p = $practices;  
      }else{
        $p = $practices; 
      } 
    } else{
      $p = 'null';
    }
                 
   
    $query1 = "select dc.diagnosis_id, upper(dc.code) as code1 , REPLACE(dc.code, ' ', '__') as newcode, dc.code, pdc.condition, u.f_name , u.l_name ,
    TO_CHAR(count(distinct pdc.patient_id) , '0000') as patientcount , dc.verify_status, dc.valid_invalid ,
    to_char(dc.verify_on  at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as verify_on 
    from ren_core.diagnosis_codes dc 
    inner join patients.patient_diagnosis_codes pdc on dc.code = pdc.code and  dc.diagnosis_id = pdc.diagnosis and pdc.status = 1 
    left join ren_core.users as u on dc.verified_by = u.id 
    inner join patients.patient_providers pp on pp.patient_id = pdc.patient_id  and pp.provider_type_id = 1 and pp.is_active = 1
    left join ren_core.practices prac on pp.practice_id = prac.id and prac.is_active = 1 
    left join patients.patient p on p.id = pdc.patient_id
    where dc.verify_status is null and dc.code is not null and dc.diagnosis_id is not null and  pdc.condition is not null and dc.status = 1";  

      if($practices!='null' && ($diagnosis=='null' || $diagnosis == '')){
          $query1 .= "  and pp.practice_id = '".$p."'";
      } elseif (($practices=='null' || $practices == '') && $diagnosis!='null') {
          $query1 .= "  and dc.diagnosis_id = '".$c."'";
      } elseif ($practices!='null' && $diagnosis!='null') {
          $query1 .= "  and pp.practice_id = '".$p."' and dc.diagnosis_id = '".$c."'";
      }else{}

      $query1 .="group by dc.diagnosis_id, dc.code , pdc.condition , dc.valid_invalid, dc.verify_status , dc.verified_by , dc.verify_on  , u.f_name ,u.l_name";
      
   //dd($query1);
       
    $data = DB::select($query1);
    //dd($data);
    return Datatables::of($data) 
      ->addIndexColumn()
      ->addColumn('valid_chk', function($row){
        if($row->valid_invalid=="1") {
            $chk="checked";
        } else{
            $chk="";
        }
        $valid_btn = '<input type="checkbox" class="chk_valid valid" id="'.$row->code.'/'.$row->diagnosis_id.'" value="'.$row->valid_invalid.'" '.$chk.'>';
        return  $valid_btn;
      })
      ->addColumn('invalid_chk', function($row){
        if($row->valid_invalid=="0") {
            $chk="checked";
        } else{
            $chk="";
        }
        //$invalid_btn = '<input type="checkbox" class="chk_validinvalid valid" id="'.$row->code.'/'.$row->condition.'" value="'.$row->valid_invalid.'" '.$chk.'>';
        $invalid_btn = '<div class="checkdiv grey400">
        <label for="invalid" class="css-label">
        <input type="checkbox" class="chk_invalid invalid" id="'.$row->code.'/'.$row->diagnosis_id.'" value="'.$row->valid_invalid.'" '.$chk.'>
        </label>
        </div>';
        return  $invalid_btn;
      })
      ->addColumn('verify_yes_no', function($row){
        if($row->verify_status=="1"){
            $verify_1="yes";
        }else{
            $verify_1="No";
        }
        $btn ="";

        return $verify_1;
      })
      ->rawColumns(['valid_chk','invalid_chk', 'verify_yes_no'])      
      ->make(true);       
  }
  //button
  public function updateverifycode(Request $request){  
      $valid_invalid = sanitizeVariable($request->valid_invalid);
      $code = sanitizeVariable($request->code);
      $condition = sanitizeVariable($request->condition);
     // dd($condition);
      //$updated_atdata = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));
      $update_data = array(
        'verify_status' => '1',
        'valid_invalid' => $valid_invalid,
        'verified_by' => session()->get('userid'),
        'verify_on' => Carbon::now()
      ); 
      
      DiagnosisCode::where('code',$code)->where('diagnosis_id',$condition)->update($update_data);
  }

  //valid
  public function verifyChildSearchReport(Request $request){
      $diagnosis = sanitizeVariable($request->route('diagnosis'));
      $code = sanitizeVariable($request->route('code'));
      $practices = sanitizeVariable($request->route('practicid'));
     // dd($practices);
     
      $query = "select distinct p.id, p.fname , p.lname, p.mname ,p.profile_img,p.dob, 
      prac.name as practicename,dc.diagnosis_id, upper(dc.code) as code1 , dc.code , pdc.condition, 
      dc.verify_status, dc.valid_invalid  from ren_core.diagnosis_codes dc 
      inner join patients.patient_diagnosis_codes pdc on dc.code = pdc.code and dc.diagnosis_id = pdc.diagnosis  and pdc.status = 1 
      inner join patients.patient_providers pp on pp.patient_id = pdc.patient_id  and pp.provider_type_id = 1 and pp.is_active = 1
      left join ren_core.practices prac on pp.practice_id = prac.id and prac.is_active = 1 
      left join patients.patient p on p.id = pdc.patient_id 
      where dc.verify_status is not null and dc.code is not null and dc.diagnosis_id is not null and pdc.condition is not null"; 

      // if($diagnosis!='null'){   
      //   $query .= "  and pdc.diagnosis = '".$diagnosis."' and pdc.code = '".$code."'      ";
      // }else{
      // }   

      if($practices!='null' && $diagnosis!=='null'){
        $query .= "  and pp.practice_id = '".$practices."' and dc.code = '".$code."'";
      } elseif (($practices=='null' || $practices == '') && $diagnosis!='null') {
          $query .= "  and dc.diagnosis_id = '".$diagnosis."' and dc.code = '".$code."'";
      } elseif ($practices!='null' && $diagnosis!='null') {
          $query .= "  and pp.practice_id = '".$practices."' and dc.diagnosis_id = '".$diagnosis."' and dc.code = '".$code."'";
      }else{}

      //dd($query);
      $data = DB::select($query);
      // dd($data);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true); 

  }


//invalid

  public function verifyChildSearchReport1(Request $request){
      //dd($request);
      $diagnosis = sanitizeVariable($request->route('diagnosis'));
      $code = sanitizeVariable($request->route('code'));
      $practices = sanitizeVariable($request->route('practicid'));
     
      $query1 = "select distinct p.id, p.fname , p.lname, p.mname ,p.profile_img,p.dob, 
      prac.name as practicename,dc.diagnosis_id, upper(dc.code) as code1 , dc.code , pdc.condition, 
      dc.verify_status, dc.valid_invalid  from ren_core.diagnosis_codes dc 
      inner join patients.patient_diagnosis_codes pdc on dc.code = pdc.code and dc.diagnosis_id = pdc.diagnosis  and pdc.status = 1 
      inner join patients.patient_providers pp on pp.patient_id = pdc.patient_id  and pp.provider_type_id = 1 and pp.is_active = 1
      left join ren_core.practices prac on pp.practice_id = prac.id and prac.is_active = 1 
      left join patients.patient p on p.id = pdc.patient_id 
      where dc.verify_status is null and dc.code is not null and dc.diagnosis_id is not null and pdc.condition is not null"; 

      // if($diagnosis!='null'){   
      //   $query .= "  and pdc.diagnosis = '".$diagnosis."' and pdc.code = '".$code."'      ";
      // }else{
      // }   

      if($practices!='null' && $diagnosis!=='null'){
        $query1 .= "  and pp.practice_id = '".$practices."' and dc.code = '".$code."'";
      } elseif (($practices=='null' || $practices == '') && $diagnosis!='null') {
          $query1 .= "  and dc.diagnosis_id = '".$diagnosis."' and dc.code = '".$code."'";
      } elseif ($practices!='null' && $diagnosis!='null') {
          $query1 .= "  and pp.practice_id = '".$practices."' and dc.diagnosis_id = '".$diagnosis_id."' and dc.code = '".$code."'";
      }else{}

    // dd($query1);
      $data = DB::select($query1);
      // dd($data);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true); 

  }

}