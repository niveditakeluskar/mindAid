<?php

namespace RCare\Org\OrgPackages\Threshold\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RCare\Org\OrgPackages\Threshold\src\Http\Requests\thresholdAddRequest;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use DataTables;
use Validator;
use Session;
use Carbon\Carbon;
use Config;
use RCare\API\Http\Controllers\ECGAPIController;
class ThresholdController extends Controller {
    public function populatethreshold() {
	
            //$id = sanitizeVariable($request->id);
            $threshold_group = (GroupThreshold::self() ? GroupThreshold::self()->population() : "");
            $result['group_threshold_form'] = $threshold_group;
            return $result;

	} 

    public function addgroupthreshold(thresholdAddRequest $request) { //dd($request->all());
       
        $bpmHigh = sanitizeVariable($request->bpmhigh);
        $bpmLow = sanitizeVariable($request->bpmlow);
        $diastolicHigh = sanitizeVariable($request->diastolichigh);
        $diastolicLow = sanitizeVariable($request->diastoliclow);
        $glucoseHigh = sanitizeVariable($request->glucosehigh);
        $glucoseLow = sanitizeVariable($request->glucoselow);
        $oxSatHigh = sanitizeVariable($request->oxsathigh);
        $oxSatLow = sanitizeVariable($request->oxsatlow);
        $systolicHigh = sanitizeVariable($request->systolichigh);
        $systolicLow = sanitizeVariable($request->systoliclow);
        $temperatureHigh= sanitizeVariable($request->temperaturehigh);
        $temperatureLow = sanitizeVariable($request->temperaturelow);

        $weighthigh = sanitizeVariable($request->weighthigh);
        $weightlow = sanitizeVariable($request->weightlow);
        $spirometerfevhigh = sanitizeVariable($request->spirometerfevhigh);
        $spirometerfevlow = sanitizeVariable($request->spirometerfevlow);
        $spirometerpefhigh= sanitizeVariable($request->spirometerpefhigh);
        $spirometerpeflow = sanitizeVariable($request->spirometerpeflow);
        $eff_date = Carbon::now();
        $created_by = session()->get('userid');

        $ecgcredetials=ApiECGCredeintials();
        $GroupName=$ecgcredetials[0]->group_name;
        $group_code = $GroupName;
        // dd($group_code);
        $threshold_data = array(
            'group_id' => $GroupName,
            'bpmhigh' => $bpmHigh,
            'bpmlow' => $bpmLow,
            'diastolichigh' => $diastolicHigh,
            'diastoliclow' => $diastolicLow,
            'glucosehigh' => $glucoseHigh,
            'glucoselow' => $glucoseLow,
            'oxsathigh' => $oxSatHigh,
            'oxsatlow' => $oxSatLow,
            'systolichigh' => $systolicHigh,
            'systoliclow' => $systolicLow,
            'temperaturehigh' => $temperatureHigh,
            'temperaturelow' => $temperatureLow,
            /*'weighthigh' => $weighthigh,
            'weightlow' => $weightlow,
            'spirometerfevhigh' => $spirometerfevhigh,
            'spirometerfevlow' => $spirometerfevlow,
            'spirometerpefhigh' => $spirometerpefhigh,
            'spirometerpeflow' => $spirometerpeflow,*/
            'eff_date' => $eff_date
            
        );


            $apidata='{
              "bpmHigh": "'.$bpmHigh.'",
              "bpmLow": "'.$bpmLow.'",
              "diastolicHigh": "'.$diastolicHigh.'",
              "diastolicLow": "'.$diastolicLow.'",
              "glucoseHigh": "'.$glucoseHigh.'",
              "glucoseLow": "'.$glucoseLow.'",
              "oxSatHigh": "'.$oxSatHigh.'",
              "oxSatLow": "'.$oxSatLow.'",
              "systolicHigh": "'.$systolicHigh.'",
              "systolicLow": "'.$systolicLow.'",
              "temperatureHigh": "'.$temperatureHigh.'",
              "temperatureLow": "'.$temperatureLow.'"
            }';
       
         ECGAPIController::getAuthorization();
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$GroupName.'/thresholds');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
               "Authorization: Bearer ".session()->get('TokenId')]
             );
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
              curl_setopt($ch, CURLOPT_POSTFIELDS,$apidata);

                $response = curl_exec($ch);
           
                if (!$response) 
                {
                    return false;
                }                
               
                 $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                 if($errno = curl_errno($ch)) {
                  $error_message = curl_strerror($errno);
                  return "cURL error ({$errno}):\n {$error_message}";
                  die();
              }
               curl_close($ch);


        $GroupThreshold = GroupThreshold::where('group_id',$group_code)->exists();
    if($GroupThreshold==true)
    {
        $threshold_data['updated_by']=$created_by; 
        $update_practice = GroupThreshold::where('group_id',$group_code)->update($threshold_data);
        //return "edit";
    }else{  
        $threshold_data['created_by']=$created_by;
        $threshold_data['updated_by']=$created_by;
        $insert_practice = GroupThreshold::create($threshold_data);
        //return "add";
    }

}

}