<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\PatientTimeRecord;
use RCare\Rpm\Models\ContentTemplateUsageHistory;
use RCare\Patients\Models\VitalsObservationNotes;//anand
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientDevices;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Patients\Models\PatientDemographics;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;
use RCare\Rpm\src\Http\Requests\RPMTextAddRequest;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Rpm\Models\Partner_Devices;
use RCare\Ccm\Models\CallWrap; 
use RCare\Rpm\src\Http\Requests\CarenoteAddRequest;
use RCare\Org\OrgPackages\Protocol\src\Models\RPMProtocol;
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;  
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 

class DailyReadingController extends Controller{

    public function dailyReading(Request $request)//patientinfo
    {   
        
        $id = sanitizeVariable($request->route('id'));
        if($id!=""){
            $devices   = Devices::where('status','1')->orderby('id','asc')->get();
            $module_id = getPageModuleName(); 
            $deviceid  = sanitizeVariable($request->route('deviceid'));//* get device id from url
            $patient = Patients::where('id',$id)->get();
            if(!empty($patient[0])){
                $PatientAddress = PatientAddress::where('uid',$patient[0]->uid);
            }else{
                $PatientAddress ="";
            }
            $personal_notes=(PatientPersonalNotes::latest($id,'patient_id') ? PatientPersonalNotes::latest($id,'patient_id')->population() : "");
            $research_study=(PatientPartResearchStudy::latest($id,'patient_id') ? PatientPartResearchStudy::latest($id,'patient_id')->population() : "");
            $patient_threshold = (PatientThreshold::latest($id,'patient_id') ? PatientThreshold::latest($id,'patient_id')->population() : "");
            $patient_providers = PatientProvider::where('patient_id', $id)
                                ->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->where('is_active',1)->orderby('id','desc')->first();
        
            if($module_id!=""){
                $patient_enroll_date = PatientServices::latest_module($id, $module_id);
                $last_time_spend = CommonFunctionController::getNetTimeBasedOnModule($id, $module_id);
            }else{
                $patient_enroll_date ="";
                $last_time_spend ="";
            }

            $services = Module::where('patients_service',1)->where('status',1)->get();
            $patient_demographics = PatientDemographics::where('patient_id', $id)->latest()->first();
            $PatientAddress = PatientAddress::where('patient_id', $id)->latest()->first();
            $PatientDevices = PatientDevices::where('patient_id', $id)->where('status', 1)->latest()->first();
            //echo $id;
            //dd($PatientDevices);
            if(!empty($PatientDevices)){
               // $partnerdevice=Partner_Devices::where('id',$PatientDevices[0]->partner_device_id)->with('device')->get();
                //dd($partnerdevice);
                $data = json_decode($PatientDevices->vital_devices);
                $show_device="";
                $show_device_id="";
                if(isset($data)){


                    foreach($data as $dev_data){
                        $dev=  Devices::where('id',$dev_data->vid)->where('status','1')->orderby('id','asc')->first();
                        $show_device.= $dev->device_name.", ";
                        $show_device_id.= $dev->id.", ";
                    }
                   
                   /* for($i=0;$i<count($data);$i++){
                      
                      if (array_key_exists("vid",$data[$i]))
                      {
                       $dev=  Devices::where('id',$data[$i]->vid)->where('status','1')->orderby('id','asc')->first();
                        $show_device.= $dev->device_name.", ";
                        $show_device_id.= $dev->id.", ";
                      }
                  
                    }*/
                    
                    $patient_assign_device= rtrim($show_device, ', ');
                    $patient_assign_deviceid= rtrim($show_device_id, ', ');
                }
                
            }else{
               // $partnerdevice="";
                $patient_assign_device="";
                $patient_assign_deviceid="";
            }
           // dd("hiiiii".$patient_assign_device);
        }
		$patient_id = $patient[0]->id;
       // dd($patient_assign_device);
         return view('Rpm::daily-review.daily-reading', compact( 'patient', 'devices', 'PatientAddress', 'patient_providers', 'patient_enroll_date', 'last_time_spend', 'services', 'patient_demographics', 'personal_notes', 'research_study','patient_threshold','PatientDevices','deviceid'
            ,'patient_assign_device','patient_assign_deviceid', 'patient_id'
        ));
    }

    public function getCareNoteData(Request $request)
   {
        $careManagerNotes=VitalsObservationNotes::where('patient_id',$request->patient_id)->orderBy('id', 'desc')->first();
        if(isset($careManagerNotes->notes)){
            $notes=$careManagerNotes->notes;
        }else{
            $notes="";
        }
         return $notes;
   }
    
    public function getDailyDeviceReading($patient_id,$deviceid)
    {       
        $patient_id = sanitizeVariable($patient_id); 
        $device_id = sanitizeVariable($deviceid);
        // dd($device_id);     
        $date1 = date('Y-m-d',strtotime('+1 days'));
        $result=array();

        $date = DatesTimezoneConversion::userToConfigTimeStamp($date1);
          
       for($i=0; $i <= 7 ; $i++) { 
            $newdate = strtotime("-1 day", strtotime($date));
            $date =date("Y-m-d", $newdate);
            $prdt1 = date("Y-m-d", $newdate)." ".date("h:i:s");//previous_date
             
             if($device_id=='1'){
                $query1 = Observation_Weight::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','weight','unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc')->first();
               
                $weight_qty = isset($query1->weight)?$query1->weight:'N/A';
                $weight_unit = isset($query1->unit)?$query1->unit:'';
                $weight_id = isset($query1->id)?$query1->id:'';
                $weight_review = isset($query1->reviewed_flag)?$query1->reviewed_flag:'';
                $weight_addressed = isset($query1->addressed)?$query1->addressed:0;
                $weight_alert_status = isset($query1->alert_status)?$query1->alert_status:0;
                $weight_threshold_type = isset($query1->threshold_type)?$query1->threshold_type:'';
                ($weight_qty !="N/A" && $weight_threshold_type!='')? $weight_th="(".$weight_threshold_type.")" : $weight_th="";

                $result['reading'][]= $weight_qty;
                $result['unit'][]= $weight_unit.$weight_th;
                $result['id'][]= $weight_id;
                $result['review'][]= $weight_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $weight_addressed;
                $result['alert_status'][]= $weight_alert_status;

            }
            else if($device_id=='2')
            {
               
                $query2 = Observation_Oxymeter::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','oxy_qty','oxy_unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc') ->first();   
                $oxy_qty = isset($query2->oxy_qty)?$query2->oxy_qty:'N/A';
                $oxy_unit = isset($query2->oxy_unit)?$query2->oxy_unit:'';
                $oxy_id = isset($query2->id)?$query2->id:'';
                $oxy_review = isset($query2->reviewed_flag)?$query2->reviewed_flag:'';
                $oxy_addressed = isset($query2->addressed)?$query2->addressed:0;
                $oxy_alert_status = isset($query2->alert_status)?$query2->alert_status:0;
                $oxy_threshold_type = isset($query2->threshold_type)?$query2->threshold_type:'';
                ($oxy_qty !="N/A" && $oxy_threshold_type!="")? $oxy_th="(".$oxy_threshold_type.")" : $oxy_th="";

                $result['reading'][]= $oxy_qty;
                $result['unit'][]= $oxy_unit.$oxy_th;
                $result['id'][]= $oxy_id;
                $result['review'][]= $oxy_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $oxy_addressed;
                $result['alert_status'][]= $oxy_alert_status;

            }    
            else if($device_id=='3'){
                $query3 = Observation_BP::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','systolic_qty','diastolic_qty','systolic_unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc')->first();
                $systolic_qty = isset($query3->systolic_qty)?$query3->systolic_qty:'N/A';
                $diastolic_qty = isset($query3->diastolic_qty)?'/'.$query3->diastolic_qty:'';
                $unit =  isset($query3->systolic_unit)? $query3->systolic_unit:'';
                $bp_id = isset($query3->id)?$query3->id:'';
                $bp_review = isset($query3->reviewed_flag)?$query3->reviewed_flag:'';
                $bp_addressed = isset($query3->addressed)?$query3->addressed:0;
                $bp_alert_status = isset($query3->alert_status)?$query3->alert_status:0;
                $bp_threshold_type = isset($query3->threshold_type)?$query3->threshold_type:'';
                ($systolic_qty !="N/A" &&  $bp_threshold_type!="")? $bp_th="(".$bp_threshold_type.")" : $bp_th="";
                
                $result['reading'][]=$systolic_qty.$diastolic_qty;
                $result['unit'][]= $unit.$bp_th;
                $result['id'][]= $bp_id;
                $result['review'][]= $bp_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $bp_addressed;
                $result['alert_status'][]= $bp_alert_status;

            } 
            /*else if($device_id=='4'){
                $query3 = Observation_BP::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','systolic_qty','diastolic_qty','systolic_unit','id','reviewed_flag')->orderby('effdatetime','desc')->first();
               
                $systolic_qty = isset($query3->systolic_qty)?$query3->systolic_qty:'N/A';
                $diastolic_qty = isset($query3->diastolic_qty)?'/'.$query3->diastolic_qty:'';
                $unit =  isset($query3->systolic_unit)? $query3->systolic_unit:'';
                $bp_id = isset($query3->id)?$query3->id:'';
                $bp_review = isset($query3->reviewed_flag)?$query3->reviewed_flag:'';

                $result['reading'][]=$systolic_qty.$diastolic_qty;
                $result['unit'][]= $unit;
                $result['id'][]= $bp_id;
                $result['review'][]= $bp_review;
                $result['effdatetime'][]= $prdt1;
            } */
             else if($device_id=='5')
            {
                $query5 = Observation_Spirometer::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','pef_value','pef_unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc') ->first();   
               
                $SpiroPef_qty = isset($query5->pef_value)?$query5->pef_value:'N/A';
                $Spirofev_qty = isset($query5->fev_value)?'/'.$query5->diastolic_qty:'';

                $SpiroPef_unit = isset($query5->pef_unit)?$query5->pef_unit:'';
                $SpiroFev_unit = isset($query5->fev_unit)?'/'.$query5->fev_unit:'';

                $Spiro_id = isset($query5->id)?$query5->id:'';
                $Spiro_review = isset($query5->reviewed_flag)?$query5->reviewed_flag:'';

                 $Spiro_addressed = isset($query5->addressed)?$query5->addressed:0;
                 $Spiro_alert_status = isset($query5->alert_status)?$query5->alert_status:0;
                 $Spiro_threshold_type = isset($query5->threshold_type)?$query5->threshold_type:'';
                 ($SpiroPef_qty !="N/A" && $Spiro_threshold_type!="")? $Spiro_th="(".$Spiro_threshold_type.")" : $Spiro_th="";
              
                $result['reading'][]= $SpiroPef_qty.$Spirofev_qty;
                $result['unit'][]= $SpiroPef_unit.$SpiroFev_unit.$Spiro_th;
                $result['id'][]= $Spiro_id;
                $result['review'][]= $Spiro_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $Spiro_addressed;
                $result['threshold_type'][]= $Spiro_threshold_type;

            } 
             else if($device_id=='6')
            {
                $query6 = Observation_Glucose::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','value','unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc') ->first();   
                $Glu_qty  = isset($query6->value)?$query6->value:'N/A';
                $Glu_unit = isset($query6->unit)?$query6->unit:'';
                $Glu_id      = isset($query6->id)?$query6->id:'';
                $Glu_review  = isset($query6->reviewed_flag)?$query6->reviewed_flag:'';
                $Glu_addressed  = isset($query6->addressed)?$query6->addressed:0;
                $Glu_alert_status  = isset($query6->alert_status)?$query6->alert_status:0;
                $Glu_threshold_type  = isset($query6->threshold_type)?$query6->threshold_type:'';
                ($Glu_qty !="N/A" && $Glu_threshold_type!="")? $Glu_th="(".$Glu_threshold_type.")" : $Glu_th="";

                $result['reading'][]= $Glu_qty;
                $result['unit'][]= $Glu_unit.$Glu_th;
                $result['id'][]= $Glu_id;
                $result['review'][]= $Glu_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $Glu_addressed;
                $result['alert_status'][]= $Glu_alert_status;

            } 
            
            else if($device_id=='0'){
                $query3 = Observation_Heartrate::where('patient_id',$patient_id)
                ->whereDate('effdatetime',$prdt1)
                ->select('effdatetime','resting_heartrate','resting_heartrate_unit','id','reviewed_flag','addressed','alert_status','threshold_type')->orderby('effdatetime','desc')
                ->first();
                $resting_heartrate = isset($query3->resting_heartrate)? $query3->resting_heartrate:'N/A';
                $resting_heartrate_unit = isset($query3->resting_heartrate_unit)?$query3->resting_heartrate_unit:'';
                $heartrate_id = isset($query3->id)?$query3->id:'';
                $heartrate_review = isset($query3->reviewed_flag)?$query3->reviewed_flag:'0';

                $heartrate_addressed = isset($query3->addressed)?$query3->addressed:0;
                $heartrate_alert_status = isset($query3->alert_status)?$query3->alert_status:0;
                $heartrate_threshold_type = isset($query3->threshold_type)?$query3->threshold_type:'';
                ($resting_heartrate !="N/A" && $heartrate_threshold_type!=""    )? $heartrate_th="(".$heartrate_threshold_type.")" : $heartrate_th="";
              
               
                $result['reading'][]= $resting_heartrate;
                $result['unit'][]= $resting_heartrate_unit.$heartrate_th;
                $result['id'][]=$heartrate_id;
                $result['review'][]= $heartrate_review;
                $result['effdatetime'][]= $prdt1;
                $result['addressed'][]= $heartrate_addressed;
                $result['alert_status'][]= $heartrate_alert_status;

            }
            else{

                $result['reading'][]= 'N/A';
                $result['unit'][]= '';
                $result['id'][]= '';
                $result['review'][]= '';   
                $result['effdatetime'][]= $prdt1;  
                $result['addressed'][]= "0";
                $result['alert_status'][]= "0";
            }    
        }//endfor loop 
            return $result;  
    }

    public function SaveText(RPMTextAddRequest $request) {
        $msg='';
        $contact_via        = "Text";
        $patient_id         = sanitizeVariable($request->patient_id);
        $template_type_id   = sanitizeVariable($request->input('template_type_id'));
        $template_id        = sanitizeVariable($request->input('template'));
        $stage_id           = sanitizeVariable($request->input('stage_id'));
        $text_msg           = sanitizeVariable($request->input('message'));
        $contact_no         = sanitizeVariable($request->input('contact_no'));
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $uid                = sanitizeVariable($request->uid);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $formname            = sanitizeVariable($request->formname);
        $billable           = 1;
        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $text_msg, 
                'phone_no'           => $contact_no,
                'content_title'      => $template_id
            );

            $contenthistory = array(
                'contact_via'   => $contact_via,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid'),
                'patient_id'    => $patient_id
            );
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);
            
            $msg= sendTextMessage($contact_no, $text_msg, $patient_id, $module_id, $stage_id);
            $history_id = $insert_content->id;
            $text_temp  = array('template_id' => $template_id, 'history_id' => $history_id);
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid,$step_id,$formname);//'dailyreadingreview_savetextmsg');
    
            DB::commit();
            return $msg;
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function SaveCareNote(CarenoteAddRequest $request) {//anand
        $patient_id         = sanitizeVariable($request->patient_id);
        $CareManagerNotes   = sanitizeVariable($request->CareManagerNotes);
        $device_id          = sanitizeVariable($request->device_id);
        $device_name        = sanitizeVariable($request->device_name);
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $uid                = sanitizeVariable($request->patient_id);
        $billable           = 1;
        $formname            = sanitizeVariable($request->formname);
        DB::beginTransaction();
        try {
            $CareNote_Save = array(
                'patient_id'    => $patient_id,
                'notes'         => $CareManagerNotes,
                'device_id'     => $device_id,
                'created_by'    => session()->get('userid')
            );

            $insert_content = VitalsObservationNotes::create($CareNote_Save);
            ($insert_content)?$msg="Successfully Saved":$msg="Error !";
                
             //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid,$step_id,$formname);//dailyreadingreview_carenote');
    
            // save comment in topics table
            $sequence          = 10;
            $last_sub_sequence = CallWrap::where('patient_id',$patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
            $new_sub_sequence  = $last_sub_sequence + 1;
            $topic_name = "Care Manager Notes for ". $device_name ." on ". date('m-d-Y')." cm notes";
            $condition_data = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => $topic_name,
                'notes'               => $CareManagerNotes,
                'created_by'          => session()->get('userid') ,
                'patient_id'          => $patient_id,
                'template_type'       => ''
            );
            $checkTopicExist = CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
            if($checkTopicExist == true) {
                CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($condition_data);
            } else {
                $condition_data['sequence']     = $sequence;
                $condition_data['sub_sequence'] = $new_sub_sequence;
                CallWrap::create($condition_data);
            }
  
            DB::commit();
            return $msg;
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function getMonthlyReviewRecord(Request $request)
    {
       $configTZ = config('app.timezone');
       $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
       $patient = sanitizeVariable($request->route('patientid'));
       $monthly   = sanitizeVariable($request->route('monthly'));
       $monthlyto   = sanitizeVariable($request->route('monthlyto'));
       $deviceid   = sanitizeVariable($request->route('deviceid'));

       $pat;
        ($monthly=='' || $monthly=='null' || $monthly=='0')? $monthly=date('Y-m'): $monthly=$monthly;
        ($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')? $monthlyto=date('Y-m-d'): $monthlyto=$monthlyto;
        ($patient!='null')? $pat = $patient: $pat = 'null';
                                       
         
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));

            $toyear = date('Y', strtotime($monthlyto));
            $tomonth = date('m', strtotime($monthlyto)); 
         
            $fromdate=$year.'-'.$month.'-01';
            $to_date=$toyear.'-'.$tomonth.'-01';
            $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
            $todate=date('Y-m-d', $convertdate);
            
             $fdt =$fromdate." "."00:00:00";   
             $tdt = $todate ." "."23:59:59"; 

               $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
               $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt);  


               //  $dt1 = DatesTimezoneConversion::userNewTimeStamp( $fdt);
               // $dt2 = DatesTimezoneConversion::userNewTimeStamp( $tdt);  

        $query = "select
         rwserialid,      
        pid, 
        reading, 
        vital_unit, 
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
        reviewedflag, 
        alert,
        pfname, 
        plname, 
        pmname, 
        pdob , 
        pprofileimg, 
        practicename, 
        providername, 
        caremanagerfname, 
        caremanagerlname,
        rppracticeid,
        vital_threshold,
        rwaddressed,
        rwthreshold_type,
        vitaldeviceid,
        vital_name
        from rpm.sp_reviewmonthlydata($pat,$deviceid,timestamp '".$dt1."', timestamp '".$dt2."')
        order by csseffdate desc"; 
        //dd($query);
         $reviewdata1 = DB::select( DB::raw($query) ); 
         $reviewdata=array_filter($reviewdata1);  
        // dd($reviewdata);
          if(!empty($reviewdata))
       {
        foreach($reviewdata as $r){
            //   dd($r);

             
            /*if($r->vital_unit!='null')
            {

                if($r->vital_unit=="lbs"){
                   $unit = 1;  
                }
                else if($r->vital_unit=="%"){
                   $unit = 2; 
                }
                else if( $r->vital_unit=="mm[Hg]"){  
                    $unit = 3;
                }
                else if( $r->vital_unit=="degrees F"){  
                    $unit = 4;
                }
                else if( $r->vital_unit=="mg/dl"){  
                    $unit = 6;
                }
                else{
                    $unit = 5;
                    }
                    
            
            }
            else{
                $unit = 'null';
            }*/

            //dd($unit);
             if($r->vital_unit == 'beats/minute'){
                                           $unit=0;
                                        }else{
                                            $unit=$r->vitaldeviceid;
                                        }

            //
            $serialid = $r->rwserialid;
            $pat = $r->pid;
            $reviewedstatus = $r->reviewedflag;
            $onlydate = explode(" ",$r->csseffdate);
            $datetimearray = explode("-",$onlydate[0]);
            // dd($datetimearray);
            $arr = array($datetimearray[2],$datetimearray[0],$datetimearray[1]);                                   
            $neweffdate =implode("-",$arr);
            
            $fromdate =$neweffdate." "."00:00:00";    
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate = $neweffdate ." "."23:59:59"; 
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
            $childquery = "select  
            tempid,
            pid, 
            reading,
            unit,
            to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
            reviewedflag,
            addressed,
            alert,
            pfname, 
            plname,
            pmname, 
            pdob , 
            pprofileimg, 
            practicename, 
            providername,  
            caremanagerfname,  
            caremanagerlname,
            vitalthreshold,
            thresholdtype,
            deviceid,
            vital_name
            from rpm.sp_childreviewdailydata(null, null, null,null,'".$pat."',timestamp'".$dt1."',timestamp'".$dt2."','".$unit."','".$reviewedstatus."','".$serialid."')"; 
           // dd($childquery)  ; 
            $reviewchilddata = DB::select( DB::raw($childquery) );
            $r->childdatacount = count($reviewchilddata);
           //print_r(count($reviewchilddata));
             $r->results = $reviewchilddata;
        }
    }
          
        return Datatables::of($reviewdata) 
        ->addIndexColumn()      
        ->addColumn('action', function($row){  
       // print_r($row);  
            $d = $row->csseffdate;
            $newdatetimearray = explode(" ", $d); 
            $neweffdate = $newdatetimearray[0];
            $runit = $row->vital_unit;
            $serialid = $row->rwserialid;
            
            if($runit == 'beats/minute'){
                $unittable ='observationsheartrate';
            }
            else if($runit == 'lbs'){
                $unittable = 'observationsweight';
            }
            else if($runit == '%'){
                $unittable = 'observationsoxymeter';
            }
            else if($runit == 'mm[Hg]' || $runit == 'mmHg'){
                $unittable ='observationsbp';    
            }
            else if($runit == 'degrees F'){
                $unittable = 'observationstemperature';
            }
            else if($runit == 'mg/dl'){
                $unittable = 'observationsglucose';
            }
            else{
                $unittable = 'observationsspirometer';
            }

            if($row->childdatacount==0){  
            }
            else{
                $btn = '<a href="javascript:void(0)" class="reviewdetailsclick_'.$row->childdatacount.'" id="'.$row->pid.'/'.$neweffdate.'/'.$unittable.'/'.$row->reviewedflag.'/'.$serialid.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a>';               
                 return $btn;
            }
        
            }) 
        ->rawColumns(['action'])          
        ->make(true); 

       
    }

    public function getMonthlyReviewChildData(Request $request)
    {
        
        $patient = sanitizeVariable($request->route('patient'));
        $effdate = sanitizeVariable($request->route('effdate'));
        $unittable = sanitizeVariable($request->route('unittable'));
        $reviewedstatus = sanitizeVariable($request->route('reviewedstatus'));
        $serialid = sanitizeVariable($request->route('serialid'));
        // dd($serialid);
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $pat;
        $effecdate;
        $unit;
       
        $datetimearray = explode("-",$effdate);
        $arr = array($datetimearray[2],$datetimearray[0],$datetimearray[1]);                                   
        $neweffdate =implode("-",$arr);
        
        $fromdate =$neweffdate." "."00:00:00";    
        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
        $todate = $neweffdate ." "."23:59:59"; 
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
        

        if($patient!='null')
        {
            if( $patient==0) 
            {
                $pat = 0;  
            }
            else
            {
                $pat = $patient;
            }
        }
        else{
            $pat = 'null';
        }

        if($unittable!='null')
        {
            if( $unittable=='observationsheartrate'){  
                $unit = 0;
            }
            else if( $unittable=='observationsweight'){  
                $unit = 1;
            }
            else if( $unittable=='observationsoxymeter'){  
                $unit = 2;
            }
            else if( $unittable=='observationsbp'){  
                $unit = 3;     
            }
            else if( $unittable=='observationstemperature'){  
                 $unit = 4;
            }
            else if( $unittable=='observationsglucose'){  
                $unit = 6;
            }
            else{
                $unit = 5; //observationsspirometer
            }
        }
        else{
            $unit = 'null';
        }  

        $query = "select 
        tempid, 
        pid, 
        reading,
        unit,
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
        reviewedflag,
        addressed,
        alert,
        pfname, 
        plname,
        pmname, 
        pdob , 
        pprofileimg, 
        practicename, 
        providername,  
        caremanagerfname,
        caremanagerlname,
        rppracticeid,
        vitalthreshold,
        thresholdtype,
        deviceid,
        vital_name
        from rpm.sp_childreviewdailydata(null, null, null,null,'".$pat."',timestamp'".$dt1."',timestamp'".$dt2."','".$unit."','".$reviewedstatus."','".$serialid."')";  
        

        $reviewdata = DB::select( DB::raw($query) );  
   

        return Datatables::of($reviewdata) 
        ->addIndexColumn()      
        ->addColumn('action', function($row){


        $btn = '<a href="javascript:void(0)" class="reviewdetailsclick" id="'.$row->pid.'/'.$row->csseffdate.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a>';
               // $btn ='<img src="http://i.imgur.com/SD7Dz.png" id="'.$row->ppatient_id.'/'.$row->pfromdate.'/'.$row->ptodate.'" >';
                return $btn;
            }) 
        ->rawColumns(['action'])          
        ->make(true); 
    }

    public function recordTime(Request $request){
        $module_id    = getPageModuleName();
        $component_id = $request->component_id;
        $patientid    = $request->patient_id;
        $time_add_record  = array(
            'uid'           => $patientid,
            'patient_id'    => $patientid,
            'record_date'   => Carbon::now(),
            'stage_id'      => 0,
            'timer_on'      => '00:00:00',
            'timer_off'     => '00:01:00', 
            'net_time'      => '00:01:00',
            'billable'      => 1,
            'module_id'     => $module_id,
            'component_id'  => $component_id,
            'created_by'    => session()->get('userid'),
            'updated_by'    => session()->get('userid')
        );
        $result = PatientTimeRecords::create($time_add_record); 
        return $result['response'] =200; 
    }
    

    public function getProtocolFileName(Request $request){
        $deviceid = sanitizeVariable($request->route('deviceid'));
        $filenames="";
        $device="";
        if($deviceid!=""){
         $filename= RPMProtocol::where("device_id",$deviceid)->where('status','1')->first();
         if(!empty($filename)){
             $filenames=$filename->file_name;
             $devices   = Devices::where("id",$deviceid)->where('status','1')->first();
             if(!empty($devices)){
              $parts = explode(" ", $devices->device_name);
              $device = implode('-', $parts);
             } 
         }
        }
     //dd($filenames ."=" .$device);
       return response()->json(['filename'=>$filenames,'devices'=>$device]);
    }
}