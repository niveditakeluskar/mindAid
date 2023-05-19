<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
// use RCare\Rpm\Models\MailTemplate;
// use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
// use RCare\Rpm\Models\Template;
// use RCare\Rpm\Models\RcareServices;
// use RCare\Rpm\Models\RcareSubServices;
// use RCare\Rpm\Models\Questionnaire;
// use RCare\Rpm\Models\PatientEnrollment;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\PatientTimeRecord;
use RCare\Rpm\Models\ContentTemplateUsageHistory;
use RCare\API\Models\WebhookObservation;
// use RCare\Rpm\Models\DeviceTraining;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientDevices;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Patients\Models\PatientDemographics;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;
//use RCare\Rpm\Http\Requests\RPMTextAddRequest;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Rpm\Models\Partner_Devices;
use RCare\API\Models\ApiException; 
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;  
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 

class ApiExceptionController extends Controller
{
   public function viewApiException(Request $request){

    $date=sanitizeVariable($request->route('fromdate'));
    $tdate=sanitizeVariable($request->route('todate'));
    $exception_type=sanitizeVariable($request->route('exception_type'));
    $exceptionemr=sanitizeVariable($request->route('exceptionemr'));
    $patientemr=sanitizeVariable($request->route('patientemr'));
    $patientid=sanitizeVariable($request->route('patientid'));
   
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
     
    $data = ApiException::whereBetween('updated_at', [$dt1, $dt2]);
            if($exception_type=='null' || $exception_type=="")
            {

            }else
            {
              $data=$data->where('exception_type',$exception_type);
            }
            if($patientemr == "null" || $patientemr=="")
            {
              
            }
            else
            {
              $data=$data->where('mrn',$patientemr);
            }
            if($exceptionemr == "null" || $exceptionemr=="")
            {
              
            }else
            {
              $data=$data->where('mrn',$exceptionemr);
            }
            if($patientid == "null" || $patientid=="")
            {
             
            }else
            {
               $data=$data->where('patient_id',$patientid);
            }
          
           $data=$data->get(); 
        
    
    return Datatables::of($data) 
    ->addIndexColumn()
    ->addColumn('action', function($row){
        $btn = '<a href="javascript:void(0)" class="btn btn-primary webhookdetail" onclick="Webhookdetail('.$row->webhook_id.')"  id="'.$row->webhook_id.'">Webhook Detail
       </a><br><div style="margin-left: 10px;"></div>'; 
        return $btn;
    })
    ->addColumn('reprocess', function($row){
    	if($row->reprocess=="1")
    	{
    		$chk="checked";
    	}
    	else
    	{
    		$chk="";
    	}
      if($row->exception_type=="info")
      {
          $btn ="";
      }
      else
      {
         $btn = ' <label class="switch ">
                          <input type="checkbox" class="chk_reproceess" id="'.$row->id.'/'.$row->parameter.'/'.$row->device_code.'/'.$row->reprocess.'" value="'.$row->webhook_id.'" '.$chk.'>
                          <span class="slider round"></span>
                          </label>';
      }
        
        return $btn;
    })
    
    ->rawColumns(['action','reprocess'])  
    ->make(true); 
   }

   public function populateApiExceptionData($id){
    $id = sanitizeVariable($id); 
    $data = (WebhookObservation::self($id) ? WebhookObservation::self($id)->population() : "");
    $result['rpm_cm_form'] = $data;
    return $result;
   }

   public function updateReprocessStatus(Request $request)
   {
      $webhookid=sanitizeVariable($request->webhookid);
      $reprocess=sanitizeVariable($request->reprocess);
      $id=sanitizeVariable($request->id);
      $parameter=sanitizeVariable($request->parameter);   
       $device_code=sanitizeVariable($request->device_code);  
        $exceptiondata=["reprocess"=>$reprocess];  
         if($reprocess=="1")
             {
                 $data=["fetch_status"=>'0'];       
             }
             else
             {
                $data=["fetch_status"=>'2'];        
             }
      if($parameter=="No practiceid for this patientid in PatientProvider table" || $parameter=="Empty-patientid-record")
      {
           if($device_code=="")
           {
            WebhookObservation::where('id',$webhookid)->update($data);
             $exceptionupdate= ApiException::where('id',$id)->update($exceptiondata);               
           }
           else
           {
              $updateexp=ApiException::where('device_code',$device_code)->update($exceptiondata);
              $query="update api.webhook_observation  set fetch_status=0 where content->>'xmit_id' ='".$device_code."'";
              $updateddata =DB::statement($query);
           }   
      }
      else
      {
         WebhookObservation::where('id',$webhookid)->update($data);
        $exceptionupdate= ApiException::where('id',$id)->update($exceptiondata);
      }
    return $reprocess;
     
   }
    
   
}