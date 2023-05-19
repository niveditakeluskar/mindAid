<?php
namespace RCare\Patients\Http\Controllers;  
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\MonthlyService;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientMedication;
use  RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Ccm\src\Http\Requests\PatientsMedicationAddRequest;
use Hash;
use DB;
use Validator,Redirect,Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PatientMedicationController extends Controller
{

 //==========For patient medication details===================
    public function getPatientsMedication($id)
    {
        $id        = sanitizeVariable($id);
        $lastmonth = date('m', strtotime(date('m')." -1 month")); 
        $dataexist = PatientMedication::with('medication')->where("patient_id", $id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if($dataexist==true) {
            $data = PatientMedication::with('medication')->with('users')->where("patient_id", $id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->get();
        } else {
            $data = PatientMedication::with('medication')->with('users')->where("patient_id", $id)->whereMonth('created_at', $lastmonth)->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->get();
        } 
      
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row) {
            $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editMedicationsRpmPatient" title="Edit"><i class="editform i-Pen-4"></i></a>';
            $btn =$btn .'<a href="javascript:void(0)" class="deleteMedicationsRpmPatient" data-id ="'.$row->id.'" title="Delete" ><i class="i-Close-Window" style="color:red;"></i></a>';
            return $btn; 
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    
//  Save Medication Details

    public function savePatientmedicationData(PatientsMedicationAddRequest $request) {  
        $id                   = sanitizeVariable($request->id);
        $patient_id           = sanitizeVariable($request->patient_id);
        $uid                  = sanitizeVariable($request->patient_id);
        $med_id               = sanitizeVariable($request->med_id);
        $med_description      = sanitizeVariable($request->med_description);
        $purpose              = sanitizeVariable($request->purpose);
        $description          = isset($request->description) ? sanitizeVariable($request->description) : '';
        $strength             = sanitizeVariable($request->strength);
        $dosage               = sanitizeVariable($request->dosage);
        $frequency            = sanitizeVariable($request->frequency);
        $route                = sanitizeVariable($request->route);
        $duration             = sanitizeVariable($request->duration);
        $drug_reaction        = isset($request->drug_reaction) ? sanitizeVariable($request->drug_reaction) : '';
        $pharmacogenetic_test = isset($request->pharmacogenetic_test) ? sanitizeVariable($request->pharmacogenetic_test) : '';
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $billable             = 1;
        // DB::beginTransaction();
        // try {
            if(isset($med_description)){
                $insert_med_id  = array(
                                            'description' => $med_description,
                                            'created_by'  => session()->get('userid')
                                        ); 
                $new_med_id     = Medication::create($insert_med_id);
            }
            if($med_id == 'other'){
                $med_id           = $new_med_id->id;
            }
            $insert_medicationData = array(
                'patient_id'           => $patient_id,
                'uid'                  => $uid,
                'med_id'               => $med_id,
                'purpose'              => $purpose,
                'description'          => $description,
                'strength'             => $strength,
                'dosage'               => $dosage,
                'frequency'            => $frequency,
                'route'                => $route,
                'duration'             => $duration,
                'drug_reaction'        => $drug_reaction,
                'pharmacogenetic_test' => $pharmacogenetic_test,
            );
            // dd($insert_medicationData);
            //record time 
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id);
            $check_med_id = PatientMedication::where('patient_id', $patient_id)->where('med_id',$med_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
            if($check_med_id == true){
                $insert_medicationData['updated_by'] = session()->get('userid');
                $update_query = PatientMedication::where('patient_id',$patient_id)->where('med_id',$med_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->orderBy('id', 'desc')->update($insert_medicationData);
            } else {
                $insert_medicationData['updated_by'] = session()->get('userid');
                $insert_medicationData['created_by'] = session()->get('userid');
                $insert_query                        = PatientMedication::create($insert_medicationData);
            }
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }
    }
// medication populate
    public function getSelectedMedicationsPatientById(Request $request) {
        $med_id    = sanitizeVariable($request->med_id);
        $patientId = sanitizeVariable($request->patientId);
        $check_id  = PatientMedication::where('med_id',$med_id)->where('patient_id',$patientId)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        if($check_id == true){
            $get_id = PatientMedication::where('med_id',$med_id)->where('patient_id',$patientId)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
            $id = $get_id[0]->id;  
            $allMedicationsPatients            = (PatientMedication::self($id) ? PatientMedication::self($id)->population() : ""); // 
            $result['rpm_medications_form']        = $allMedicationsPatients;
            return $result;
        }
    }
//
    public function getPatientMedicationsById(Request $request) {
        $id                                = sanitizeVariable($request->id);
        $allMedicationsPatients            = (PatientMedication::self($id) ? PatientMedication::self($id)->population() : ""); // 
        $result['rpm_medications_form']        = $allMedicationsPatients;
        return $result; 
    }
//Delete
    public function deletePatientMedicationsById(Request $request) {
        $id           = sanitizeVariable($request->id);
        $patient_id   = PatientMedication::where('id',$id)->get(); 
        $module_id    = getPageModuleName();
        // $comp         = ModuleComponents::where('module_id',$module_id)->where('components','Care Plan Development')->get('id');
        $component_id = getPageSubModuleName();
        $start_time   = sanitizeVariable($request->start_time);  
        $end_time     = sanitizeVariable($request->end_time);
        $patient_id   = $patient_id[0]->patient_id;
        $stage_id     = isset($request->stage_id) ? sanitizeVariable($request->stage_id) : 0;
        $step_id      = sanitizeVariable($request->step_id); 
        $billable     = 1;
        // DB::beginTransaction();
        // try { 
            if(!empty($patient_id) || $patient_id != ''){
                PatientMedication::where('id',$id)->delete();
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id);
            } 
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }
    }

}