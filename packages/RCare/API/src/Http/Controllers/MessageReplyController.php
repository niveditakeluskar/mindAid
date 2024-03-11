<?php
namespace RCare\API\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientOrders;
use RCare\Patients\Models\PatientReading;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\API\Models\Partner;
use RCare\API\Http\Requests\AddPartnerRequest;
use RCare\API\Models\Webhook;
use RCare\API\Models\WebhookAlert;
use RCare\API\Models\WebhookMessages;
use RCare\API\Models\WebhookOrders;
use RCare\Messaging\Models\MessageLog;
use RCare\TaskManagement\Models\UserPatients;
use Carbon\Carbon;
use DB;
use DataTables;
use Session;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Twilio\Exceptions\TwilioException;
use Twilio\TwiML\MessagingResponse;
use RCare\RCareAdmin\AdminPackages\Configuration\src\Models\Configurations;
class MessageReplyController extends Controller {
    public function smsreply(Request $request){

       /* $m = WebhookMessages::where('id', '6')->get();
        $val = json_decode($m[0]->content);
        //die;
       //$arr = '{"ToCountry":"US","ToState":null,"SmsMessageSid":"SMd77e441d7ea4e08edaf3ed6ea1899e05","NumMedia":"0","ToCity":null,"FromZip":"35204","SmsSid":"SMd77e441d7ea4e08edaf3ed6ea1899e05","FromState":"AL","SmsStatus":"received","FromCity":"BIRMINGHAM","Body":"Hi team","FromCountry":"US","To":"+18554902006","ToZip":null,"NumSegments":"1","MessageSid":"SMd77e441d7ea4e08edaf3ed6ea1899e05","AccountSid":"ACd9b6197b2af3804f15595df5f37c9d78","From":"+12059019147","ApiVersion":"2010-04-01"}';
      // print_r($arr);
       //echo $val->ToCountry;
       $text = strtolower($val->Body);
       print_r($text);
      
           $contactno = substr($val->From,2);
           $contact1=substr($contactno,0,3);
           $contact2=substr($contactno,3,3);
           $contact3=substr($contactno,6,4);
           $phoneno="(".$contact1.") ".$contact2."-".$contact3;
            //$v = Patients::where('mob', $phoneno)->orWhere('home_number',$phoneno)->update(["consent_to_text" => "0"]);
            $pid = Patients::where('mob',  $phoneno)->orWhere('home_number', $phoneno)->get();
            $id = $pid[0]->id;
            //print_r($id);
            $ap = UserPatients::where('patient_id', $id)->where('status', 1)->get();
            //echo $ap[0]->user_id;

       
       die; */
       $content=$request->all();  
        $newcontent=json_encode($content); 
       
        $currenturl = url()->full();	
        if($currenturl == 'https://rcare.d-insights.global/API/sms/reply'){
            $response = Http::post('https://rcareconnect.com/API/sms/reply',$content);
            if ($response->getStatusCode() == 200) {
                $data=array(
                    'content'=>$newcontent ,
                    'created_by'=>'symtech',
                    'rconnect_transfer_flag' => 1         
                );
            }else{
                $data=array(
                    'content'=>$newcontent ,
                    'created_by'=>'symtech',
                    'rconnect_transfer_flag' => 0         
                );
            }
        }else{
            $data=array(
                'content'=>$newcontent ,
                'created_by'=>'symtech',
                'rconnect_transfer_flag' => 0          
            );
        }
       $result= WebhookMessages::create($data);
          if($result)
          {
           
            $newcontent1 = json_decode($newcontent);
            $text = strtolower($newcontent1->Body);
            $contactno = substr($newcontent1->From, 2);
            $contact1=substr($contactno,0,3);
            $contact2=substr($contactno,3,3);
            $contact3=substr($contactno,6,4);
            $phoneno="(".$contact1.") ".$contact2."-".$contact3;

            if(trim($text) == 'stop' || trim($text) == 'unsubscribe'){
                Patients::where('mob',  $phoneno)->orWhere('home_number', $phoneno)->update(["consent_to_text" => "0"]);
            }else if(trim($text) == 'start' || trim($text) == 'subscribe' || trim($text) == 'unstop' || trim($text) == 'resubscribe'){
                Patients::where('mob',  $phoneno)->orWhere('home_number', $phoneno)->update(["consent_to_text" => "1"]);
            }
           
           $getPid =  MessageLog::where('status', 'delivered')->where('to_phone', $phoneno)->orWhere('to_phone', 'like', '%' . $phoneno . '%')->first();
           //dd($dt->patient_id);
            //$pid = Patients::where('id', $getPid->patient_id)->where('mob',  $phoneno)->orWhere('home_number', $phoneno)->get();
            $id = $getPid->patient_id;
            $ap = UserPatients::where('patient_id', $id)->where('status', 1)->get();
           $data = array(
                "message_id" => $newcontent1->MessageSid,
                "patient_id" => $id,
                "module_id" => 3,
                "stage_id" => 0,
                "from_phone" => $newcontent1->From,
                "to_phone" => $newcontent1->To,
                "status" => $newcontent1->SmsStatus,
                "message_date" => Carbon::now(),
                "created_by"  => 0,
                "updated_by" => 0,
                "message" => $text,
                'read_status'=> 1
            );
            
            $insert = MessageLog::create($data);
            return response()->json("Data inserted successfully!");
         }
          else
          {
            return "failed";
          }
    }
} 