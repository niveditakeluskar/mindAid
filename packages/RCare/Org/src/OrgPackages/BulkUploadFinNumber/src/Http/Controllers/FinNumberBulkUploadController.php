<?php
namespace App\Http\Requests;
namespace RCare\Org\OrgPackages\BulkUploadFinNumber\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; 
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientFinNumber;
use Rap2hpoutre\FastExcel\FastExcel;
use RCare\Patients\Requests\FinNumberUpload;
use RCare\Org\OrgPackages\BulkUploadFinNumber\src\Http\Requests\FinBulkURequest;
use Validator,Redirect,Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use File,DB;
use Session;
use Hash;
use DateTime;
use DateTimeZone;

class FinNumberBulkUploadController extends Controller {
    public function index() {
        return view('BulkUploadFinNumber::bulk_upload_finnumber');
    } 

    public function fileUpload(FinBulkURequest $request){ 
        
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $excelData =(new FastExcel)->import($request->file('file')); 

        // $excelData = (new FastExcel)->configureEmptyRowAndColumnFilter()->import($request->file('file')); for lateest version
    
        $excelcount = count($excelData);
        $practices = sanitizeVariable($request->practices);
        $p; 

        if( $practices!='null') {
            if( $practices==0){
              $p = $practices;  
            }else{
              $p = $practices; 
            } 
        } else{
            $p = 'null';
        }
        
        for($i=0; $i<$excelcount; $i++){ 
            $fname = strtolower(trim($excelData[$i]['fname']));
            $lname =  strtolower(trim($excelData[$i]['lname']));
            // $practice_name =  strtolower(trim($excelData[$i]['practice_name']));
            $fin_number =  strtolower(trim($excelData[$i]['fin_number']));

            //DateTime @-355190400 {#2010
            //   date: 1958-09-30 00:00:00.0 UTC (+00:00)
            // }

            $dateString = $excelData[$i]['dob'];
            $expectedFormat = 'y-m-d';

            if (!is_string($dateString)) {
              
               $date = $dateString->format("Y-m-d");

               // $date = $dateString->format($expectedFormat);
                $dob = $date;
                //return 'Invalid date format for'  .' '.  $fname .' '. $lname;
            } else {
                $date = DateTime::createFromFormat($expectedFormat, $dateString);
                if ($date === false) {
                    // return 'Invalid date for'  .' '.  $fname .' '. $lname;
                    $dob = $date->format('Y-m-d');
                    
                } else {
                    // echo 'Date format is valid';
                    $dob = $excelData[$i]['dob'];
                }
            }
                // echo $p .' '. $fname . ' '. $lname .' '. $dob ;  echo"===============";
            $data = "select distinct(p.id) from patients.patient p 
                    inner join ren_core.practices p2 on p2.id = '".$p."'
                    inner join patients.patient_providers pp  on  pp.practice_id = '".$p."' and pp.is_active = 1
                    where LOWER(p.fname) like '%".$fname."%' and LOWER(p.lname) like '%".$lname."%' 
                    and p.dob = TO_DATE('".$dob."', 'YYYY-MM-DD') ";

             // echo($data); echo "<br>"; 
             
            $dbData = DB::select( DB::raw($data) );
            // print_r($dbData); echo "<br>";
        

            if($dbData != null || $dbData == ''){ 
                    
                $ids = array_column($dbData, 'id');
                foreach ($ids as $id) {
                    $patient_id = $id;
                }
                
                $currentMonth       = date('m');
                $currentYear        = date('Y');
               
                $data = array(
                    'fin_number'  => $fin_number
                );

                //insert in patient fin table
                $patientfin = array(
                    'patient_id'    => $patient_id, 
                    'status'        => '1',
                    'fin_number'    => $fin_number

                );
                // dd($patientfin);

                //check patient exit or not
                $check_fn = Patients::where('id',$patient_id)->exists();
                if($check_fn == true){
                    $data['updated_by'] = session()->get('userid');
                    $data['updated_at']= Carbon::now();
                    $update = Patients::where('id',$patient_id)->update($data);
                }

                // check patient fin number exits in same month
                $check_exist_for_month = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();
                           
                if ($check_exist_for_month == true) {
                    $patientfin['updated_at']= Carbon::now();
                    $patientfin['updated_by']= session()->get('userid');
                    $update_query = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->update($patientfin);
                } else {
                    $patientfin['created_at']= Carbon::now();
                    $patientfin['created_by']= session()->get('userid');
                    $insert_query = PatientFinNumber::create($patientfin);
                }
            }  else{
                // return 'Something Wrong in '  .' '.  $fname .' '. $lname;
                return 'Incorrect  practice or dob for'  .' '.  $fname .' '. $lname .' '. 'patient, Please try again..!';
            }
        } //die;
        return 1;//'File Uploaded Successfully.!';
    }  
}  