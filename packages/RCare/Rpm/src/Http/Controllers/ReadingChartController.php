<?php

namespace RCare\Rpm\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RCare\Rpm\Models\Devices;
use RCare\Patients\Models\PatientThreshold;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate; 
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Temp;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Glucose; 
use Calendar;
use DB;

class ReadingChartController extends Controller
{
   
public function getNumberOfReading(Request $request){
$day = sanitizeVariable($request->route('day'));
$patient_id = sanitizeVariable($request->route('patient_id'));
//dd($day);
$queryreading = "select sum(cnt) as readingcnt from (SELECT distinct count(*) as cnt FROM rpm.observations_weight we WHERE  we.patient_id='".$patient_id."' and date(we.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_oxymeter oo  WHERE oo.patient_id='".$patient_id."' and date(oo.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_bp ob  WHERE ob.patient_id='".$patient_id."' and  date(ob.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_heartrate ht  WHERE ht.patient_id='".$patient_id."' and  date(ht.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_temp tp  WHERE tp.patient_id='".$patient_id."' and  date(tp.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_spirometer sp  WHERE sp.patient_id='".$patient_id."' and  date(sp.effdatetime) > current_date - interval '".$day."' day
union
SELECT distinct count(*) as cnt FROM rpm.observations_glucose gl  WHERE gl.patient_id='".$patient_id."' and  date(gl.effdatetime) > current_date - interval '".$day."' day
)rw";
//dd($queryreading);
$countreading = DB::select($queryreading);  

$queryAlert = "select sum(cnt) as alertcnt from (
SELECT count(*) as cnt FROM rpm.observations_weight we WHERE we.patient_id='".$patient_id."' and alert_status=1 and date(we.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_oxymeter oo  WHERE oo.patient_id='".$patient_id."' and alert_status=1 and  date(oo.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_bp ob  WHERE ob.patient_id='".$patient_id."' and alert_status=1 and  date(ob.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_heartrate ht  WHERE ht.patient_id='".$patient_id."' and alert_status=1 and  date(ht.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_temp tp  WHERE tp.patient_id='".$patient_id."' and alert_status=1 and  date(tp.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_spirometer sp  WHERE sp.patient_id='".$patient_id."' and alert_status=1 and  date(sp.effdatetime) > current_date - interval '".$day."' day
union
SELECT count(*) as cnt FROM rpm.observations_glucose gl  WHERE gl.patient_id='".$patient_id."' and alert_status=1 and  date(gl.effdatetime) > current_date - interval '".$day."' day
)rw";

$countalert = DB::select($queryAlert); 


return response()->json(['readingcnt'=>$countreading[0]->readingcnt,'alertcnt'=>$countalert[0]->alertcnt]);


}

    // public function readReadings($patient_id,$deviceid)
    // {
    //     if($deviceid == 1){
    //         $datetime = Observation_Weight::where('patient_id',$patient_id)->pluck('effdatetime');
    //         $uniArray = $datetime->toArray();

    //         $reading = Observation_Weight::where('patient_id',$patient_id)->pluck('weight'); 
    //         $arrayreading1 = $reading->toArray();
    //         $label1 = "Weight(Kg)";

    //         $label2="";
    //         $arrayreading2="";
    //         $label3="";
    //         $arrayreading3="";

    //         return view("Rpm::chart-new.newchartfile_2_daily",compact('uniArray','arrayreading1','arrayreading2','arrayreading3','label1','label2','label2'));
    //     }else if($deviceid == 2){
    //         //dd($patient_id."/".$deviceid);
    //         $datetime1 = Observation_Oxymeter::orderBy('effdatetime')->where('patient_id',$patient_id)->pluck('effdatetime');
    //         $arraydtetime1 = $datetime1->toArray();

    //         $datetime2 = Observation_Heartrate::orderBy('effdatetime')->where('patient_id',$patient_id)->pluck('effdatetime');
    //         $arraydtetime2 = $datetime2->toArray();

    //         $uniArray = array_unique(array_merge($arraydtetime1,$arraydtetime2));

    //         $readingOxy = Observation_Oxymeter::where('patient_id',$patient_id)->pluck('oxy_qty'); 
    //         $arrayreading1 = $readingOxy->toArray();
    //         $label1 = "Saturation-O2(%)";
    //         $countOxy = count($arrayreading1);

    //         $readingHrt = Observation_Heartrate::where('patient_id',$patient_id)->pluck('resting_heartrate'); 
    //         $arrayreading2 = $readingHrt->toArray();
    //         $label2 = "Heart-Rate(beats/minute)";
    //         $countHrt = count($arrayreading2);

    //         $label3="";
    //         $arrayreading3="";

    //         return view("Rpm::chart-new.newchartfile_2_daily",compact('arrayreading1','uniArray','arrayreading2','label1','label2','label3','arrayreading3'));
    //        // return view("Rpm::daily-review.newchartfile",compact('arraydtetime1','arrayreadingOxy','arraydtetime2','arrayreadingHrt'));
    //     }else if($deviceid == 3){
    //         $datetime1 = Observation_BP::where('patient_id',$patient_id)->pluck('effdatetime');
    //         $arraydtetime1 = $datetime1->toArray();

    //         $datetime2 = Observation_Heartrate::orderBy('effdatetime')->where('patient_id',$patient_id)->pluck('effdatetime');
    //         $arraydtetime2 = $datetime2->toArray();

    //         $uniArray = array_unique(array_merge($arraydtetime1,$arraydtetime2));

    //         $readingSys = Observation_BP::where('patient_id',$patient_id)->pluck('systolic_qty'); 
    //         $arrayreading1 = $readingSys->toArray();
    //         $label1 = "Systolic(mmHg)";

    //         $readingDys = Observation_BP::where('patient_id',$patient_id)->pluck('diastolic_qty'); 
    //         $arrayreading2 = $readingDys->toArray();
    //         $label2 = "Diastolic(mmHg)";

    //         $readingHrt = Observation_Heartrate::where('patient_id',$patient_id)->pluck('resting_heartrate'); 
    //         $arrayreading3 = $readingHrt->toArray();
    //         $label3 = "Heart-Rate(beats/minute)";
    //         $countHrt = count($arrayreading3);

    //         // $readingHrt = Observation_BP::where('patient_id',$patient_id)->pluck('resting_heartrate'); 
    //         // $arrayreadingHrt = $reading->toArray(); 

    //         return view("Rpm::chart-new.newchartfile_2_daily",compact('uniArray','arrayreading1','arrayreading2','arrayreading3','label1','label2','label3'));
    //         //return view("Rpm::daily-review.newchartfile",compact('arraydtetime','arrayreadingSys','arrayreadingDys'));

    //     } 
        
    // }

    public function readCalender(){
        return view('Rpm::review-data-link.calender-data');
    }

    public function graphreadSpirometerReadings($patient_id,$deviceid,$month,$year){
        if($deviceid=='5'){
                $datetime = Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)
                ->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                // dd($DateArray);
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('spirometerpefhigh','spirometerpeflow','spirometerfevhigh','spirometerfevlow')
                ->orderBy('created_at','desc')->get();
                $arrayreading2 = $reading2->toArray();
                // dd($arrayreading2);
                $arrLength = count($DateArray);
                $uniArray =array(); 
                $min_threshold_array =isset($arrayreading2[0]['spirometerpeflow'])?$arrayreading2[0]['spirometerpeflow']:''; 
                $max_threshold_array =isset($arrayreading2[0]['spirometerpefhigh'])?$arrayreading2[0]['spirometerpefhigh']:'';
                $min1_threshold_array =isset($arrayreading2[0]['spirometerfevlow'])?$arrayreading2[0]['spirometerfevlow']:'';
                $max1_threshold_array =isset($arrayreading2[0]['spirometerfevhigh'])?$arrayreading2[0]['spirometerfevhigh']:''; 

                for($a=0;$a<$arrLength;$a++){ 
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                }

                $reading =  Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('pef_value');  
                $reading1 = Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('fev_value'); 

                $arrayreading = $reading->toArray();
                $arrayreading1 = $reading1->toArray();

                
                $label = "Spirometer(pef[L/min])";
                $label1 = "Spirometer(fev[L])";

                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 =$min1_threshold_array;
                $arrayreading_max1 =$max1_threshold_array;
                $chart_text ='Spirometer';
                
                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);

        } 
    }
        public function graphreadReadings($patient_id,$deviceid,$month,$year){
        if($deviceid == '1'){
            $datetime = Observation_Weight::where('patient_id',$patient_id)
            ->whereMonth('effdatetime',$month)
            ->whereYear('effdatetime',$year) 
            ->pluck('effdatetime'); 

            $DateArray = $datetime->toArray();
            $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('weighthigh','weightlow')->orderBy('created_at','desc')->get();
            $arrayreading2 = $reading2->toArray();
            // dd($reading2);
            $arrayreading2 = $reading2->toArray();
            $arrLength = count($DateArray);
            $uniArray =array();
            $min_threshold_array =isset($reading2[0]['weightlow'])?$reading2[0]['weightlow']:'';
            $max_threshold_array =isset($reading2[0]['weighthigh'])?$reading2[0]['weighthigh']:'';
            for($a=0;$a<$arrLength;$a++){
               $b = date("M j, g:i a",strtotime($DateArray[$a]));
               $c = array_push($uniArray,$b);
            }
            //print_r($min_threshold_array);echo "<br>";print_r($max_threshold_array);echo "<br>";
            $reading = Observation_Weight::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
            ->pluck('weight');
            $arrayreading = $reading->toArray();
            $label = "Weight(lbs)";

            $arrayreading_min =$min_threshold_array;
            $arrayreading_max =$max_threshold_array;

            $arrayreading_min1 ='';
            $arrayreading_max1 ='';
            $arrayreading1='';
            $label1='';
            $chart_text ='Weight';
            return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);

            //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);
          
        }
        if($deviceid =='2'){
                $datetime = Observation_Oxymeter::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('oxsathigh','oxsatlow')->orderBy('created_at','desc')->get();
                $arrayreading2 = $reading2->toArray();
                // dd($reading2);
                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =isset($reading2[0]['oxsatlow'])?$reading2[0]['oxsatlow']:'';
                $max_threshold_array =isset($reading2[0]['oxsathigh'])?$reading2[0]['oxsathigh']:'';

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                } 

                $reading = Observation_Oxymeter::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('oxy_qty'); 
                $arrayreading = $reading->toArray();
                $label = "Oxygen(%)";
                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';
                $arrayreading1='';
                $label1='';
                $chart_text ='Oximeter';

                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);
                //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);

        }
        if($deviceid =='3'){
                $datetime = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('systolichigh','systoliclow','diastolichigh','diastoliclow')->orderBy('created_at','desc')->get();
                $arrayreading2 = $reading2->toArray();
                //dd($arrayreading2);
                $arrLength = count($DateArray);
                $uniArray =array(); 
                $min_threshold_array =isset($arrayreading2[0]['systoliclow'])?$arrayreading2[0]['systoliclow']:'';
                $max_threshold_array =isset($arrayreading2[0]['systolichigh'])?$arrayreading2[0]['systolichigh']:'';
                $min1_threshold_array =isset($arrayreading2[0]['diastoliclow'])?$arrayreading2[0]['diastoliclow']:'';
                $max1_threshold_array =isset($arrayreading2[0]['diastolichigh'])?$arrayreading2[0]['diastolichigh']:''; 

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                }

                $reading = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('systolic_qty'); 
                $reading1 = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('diastolic_qty'); 
                // ->select('systolic_qty','diastolic_qty')->get();
                $arrayreading = $reading->toArray();
                $arrayreading1 = $reading1->toArray();
                $label = 'Systolic mm[Hg]';
                $label1 = 'Diastolic mm[Hg]';
                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 =$min1_threshold_array;
                $arrayreading_max1 =$max1_threshold_array;
                $chart_text ='Blood Pressure Cuff';
                
                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min, 
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);

        }

        if($deviceid =='4'){
                $datetime = Observation_Temp::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('temperaturehigh','temperaturelow')->orderBy('created_at','desc')->get();
                $DateArray = $datetime->toArray();
                $arrayreading2 = $reading2->toArray();
                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =isset($arrayreading2[0]['temperaturehigh'])?$arrayreading2[0]['temperaturehigh']:'';
                $max_threshold_array =isset($arrayreading2[0]['temperaturelow'])?$arrayreading2[0]['temperaturelow']:'';

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                }

                $reading = Observation_Temp::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('bodytemp'); 
                $arrayreading = $reading->toArray();
                
                $label = "Thermometer(degrees F)";
                $arrayreading1='';
                $label1='';

                $arrayreading_min =$min_threshold_array;
                
                $arrayreading_max =$max_threshold_array;
                

                $arrayreading_min1 ='';
                
                $arrayreading_max1 ='';

                $chart_text ='Temperature';
                
                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);
        }
        
        if($deviceid=='5'){
                $datetime = Observation_Spirometer::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('spirometerpefhigh','spirometerpeflow','spirometerfevhigh','spirometerfevlow')->orderBy('created_at','desc')->get();
                $arrayreading2 = $reading2->toArray();
                //dd($arrayreading2);
                $arrLength = count($DateArray);
                $uniArray =array(); 
                $min_threshold_array =isset($arrayreading2[0]['spirometerpeflow'])?$arrayreading2[0]['spirometerpeflow']:''; 
                $max_threshold_array =isset($arrayreading2[0]['spirometerpefhigh'])?$arrayreading2[0]['spirometerpefhigh']:'';
                $min1_threshold_array =isset($arrayreading2[0]['spirometerfevlow'])?$arrayreading2[0]['spirometerfevlow']:'';
                $max1_threshold_array =isset($arrayreading2[0]['spirometerfevhigh'])?$arrayreading2[0]['spirometerfevhigh']:''; 

                for($a=0;$a<$arrLength;$a++){ 
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                }

                $reading =  Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('pef_value');  
                $reading1 = Observation_Spirometer::where('patient_id',$patient_id)
                ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('fev_value'); 

                $arrayreading = $reading->toArray();
                $arrayreading1 = $reading1->toArray();

               $label = "PEF [L/min]";
                $label1 = "FEV [L]";

                $arrayreading_min =$min_threshold_array;
                $arrayreading_max =$max_threshold_array;
                $arrayreading_min1 =$min1_threshold_array;
                $arrayreading_max1 =$max1_threshold_array;
                $chart_text ='Spirometer';
                
                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);

        }
        if($deviceid=='6'){
                $datetime = Observation_Glucose::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('effdatetime');
                //$uniArray = $datetime->toArray();
                $DateArray = $datetime->toArray();
                $reading2 = PatientThreshold::where('patient_id',$patient_id)
                ->select('glucosehigh','glucoselow')->orderBy('created_at','desc')->get(); 
                $arrayreading2 = $reading2->toArray();
                $arrLength = count($DateArray);
                $uniArray =array();
                $min_threshold_array =isset($arrayreading2[0]['glucoselow'])?$arrayreading2[0]['glucoselow']:'';
                $max_threshold_array =isset($arrayreading2[0]['glucosehigh'])?$arrayreading2[0]['glucosehigh']:'';

                for($a=0;$a<$arrLength;$a++){
                   $b = date("M j, g:i a",strtotime($DateArray[$a]));
                   $c = array_push($uniArray,$b);
                } 

                $reading = Observation_Glucose::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
                ->pluck('value'); 
                $arrayreading = $reading->toArray();
                
                $label = "Glucose(mg/dl)";
                $arrayreading1='';
                $label1='';

                $arrayreading_min =$min_threshold_array; 
                $arrayreading_max =$max_threshold_array;                
                $arrayreading_min1 ='';
                $arrayreading_max1 ='';

                $chart_text ='Glucose';

                return response()->json(
                ['uniArray'=>$uniArray,
                'arrayreading'=>$arrayreading,
                'arrayreading1'=>$arrayreading1,
                'arrayreading_min' =>$arrayreading_min,
                'arrayreading_max' =>$arrayreading_max,
                'arrayreading_min1' =>$arrayreading_min1,
                'arrayreading_max1' =>$arrayreading_max1,
                'label'=>$label,
                'label1'=>$label1,
                'title_name'=>$chart_text,
                ]);
               // return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);
        }
            // return response()->json(
            //     ['uniArray'=>$uniArray,
            //     'arrayreading'=>$arrayreading,
            //     'arrayreading1'=>$arrayreading1,
            //     'arrayreading_min' =>$arrayreading_min,
            //     'arrayreading_max' =>$arrayreading_max,
            //     'arrayreading_min1' =>$arrayreading_min1,
            //     'arrayreading_max1' =>$arrayreading_max1,
            //     'label'=>$label,
            //     'label1'=>$label1,
            //     'label_min'=>$label_min,
            //     'label_max'=>$label_max,
            //     'label_min1'=>$label_min1,
            //     'label_max1'=>$label_max1
            //     ]); 
    }


        // public function graphreadReadings($patient_id,$deviceid,$month,$year){
        // //print_r($patient_id); echo "-"; print_r($deviceid);echo "-"; print_r($days); die;
        // if($deviceid == 1){
        //     $datetime = Observation_Weight::where('patient_id',$patient_id)
        //     ->whereMonth('effdatetime',$month)
        //     ->whereYear('effdatetime',$year) 
        //     ->pluck('effdatetime'); 

        //     $DateArray = $datetime->toArray();
        //     $reading2 = Observation_Weight::where('patient_id',$patient_id)
        //     ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //     ->pluck('threshold'); 

        //     $arrayreading2 = $reading2->toArray();
        //     $arrLength = count($DateArray);
        //     $uniArray =array();
        //     $min_threshold_array =array();
        //     $max_threshold_array =array();
        //     for($a=0;$a<$arrLength;$a++){
        //        $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //        $c = array_push($uniArray,$b);
        //        if(isset($arrayreading2[0])){
        //            $d = explode('-', $arrayreading2[$a]);
        //            $e = array_push($min_threshold_array,(int) $d[0]);
        //            $f = array_push($max_threshold_array,(int) $d[1]);
        //        }
        //     }
        //     //print_r($min_threshold_array);echo "<br>";print_r($max_threshold_array);echo "<br>";
        //     $reading = Observation_Weight::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //     ->pluck('weight');
        //     $arrayreading = $reading->toArray();
        //     $label = "Weight(lbs)";

        //     $arrayreading_min =$min_threshold_array;
        //     $label_min ="minimum(lbs)";

        //     $arrayreading_max =$max_threshold_array;
        //     $label_max ="maximum(lbs)";


        //     $arrayreading_min1 ='';
        //     $label_min1 ='';

        //     $arrayreading_max1 ='';
        //     $label_max1 ='';

        //     $arrayreading1='';
        //     $label1='';
        //     return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);

        //     //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);
          
        // }
        // if($deviceid =='2'){
        //         $datetime = Observation_Oxymeter::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('effdatetime');
        //         //$uniArray = $datetime->toArray();
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_Weight::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',07)->whereYear('effdatetime',$year)
        //         ->pluck('threshold'); 
        //         $arrayreading2 = $reading2->toArray();
        //         $arrLength = count($DateArray);
        //         $uniArray =array();
        //         $min_threshold_array =array();
        //         $max_threshold_array =array();

        //         for($a=0;$a<$arrLength;$a++){
        //            $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //            $c = array_push($uniArray,$b);
        //             if(isset($arrayreading2[$a])){
        //                $d = explode('-', $arrayreading2[$a]);
        //                $e = array_push($min_threshold_array,(int)$d[0]);
        //                $f = array_push($max_threshold_array,(int)$d[1]);
        //            }
        //         }

        //         $reading = Observation_Oxymeter::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('oxy_qty'); 
        //         $arrayreading = $reading->toArray();
        //         $label = "Oxygen(%)";

        //         $arrayreading_min =$min_threshold_array;
        //         $label_min ="minimum(%)";

        //         $arrayreading_max =$max_threshold_array;
        //         $label_max ="maximum(%)";


        //         $arrayreading_min1 ='';
        //         $label_min1 ='';

        //         $arrayreading_max1 ='';
        //         $label_max1 ='';

        //         $arrayreading1='';
        //         $label1='';

        //         return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);
        //         //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);

        // }
        // if($deviceid =='3'){
        //         $datetime = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('effdatetime');
        //         //$uniArray = $datetime->toArray();
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_BP::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('threshold');
        //         // print_r($reading2);
        //         $arrayreading2 = $reading2->toArray();
        //         $arrLength = count($DateArray);
        //         $uniArray =array(); 
        //         $min_threshold_array =array();
        //         $max_threshold_array =array();
        //         $min1_threshold_array =array();
        //         $max1_threshold_array =array(); 

        //         for($a=0;$a<$arrLength;$a++){
        //            $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //            $c = array_push($uniArray,$b);
        //            if(isset($arrayreading2[$a])){
        //                $myArr = explode('/', $arrayreading2[$a]);
        //                $sep = $myArr[0];
        //                $sep1 =$myArr[1]; 
        //                $d = explode('-', $sep); 
        //                $p = explode('-', $sep1);
        //                $e = array_push($min_threshold_array,(int)$d[0]); 
        //                $f = array_push($max_threshold_array,(int)$d[1]);
        //                $g = array_push($min1_threshold_array,(int)$p[0]);
        //                $h = array_push($max1_threshold_array,(int)$p[1]);
        //            }
        //         }

        //         $reading = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('systolic_qty'); 
        //         $reading1 = Observation_BP::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('diastolic_qty'); 
        //         // ->select('systolic_qty','diastolic_qty')->get();
        //         $arrayreading = $reading->toArray();
        //         $arrayreading1 = $reading1->toArray();
        //         $label = "Blood Pressure(Systolic mm[Hg])";
        //         $label1 = "Blood Pressure(Diastolic mm[Hg])";

        //         $arrayreading_min =$min_threshold_array;
        //         $label_min ="minimum(Systolic mm[Hg])";

        //         $arrayreading_max =$max_threshold_array;
        //         $label_max ="maximum(Systolic mm[Hg])";


        //         $arrayreading_min1 =$min1_threshold_array;
        //         $label_min1 ="minimum(Diastolic mm[Hg]')";

        //         $arrayreading_max1 =$max1_threshold_array;
        //         $label_max1 ="maximum(Diastolic mm[Hg]')";
                
        //         return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);

        //         //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'arrayreading1'=>$arrayreading1,'label'=>$label,'label1'=>$label1]);
        // }

        // if($deviceid =='4'){
        //         $datetime = Observation_Temp::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('effdatetime');
        //         //$uniArray = $datetime->toArray();
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_Temp::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('threshold'); 
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_Temp::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('threshold');
        //         // print_r($reading2);
        //         $arrayreading2 = $reading2->toArray();
        //         $arrLength = count($DateArray);
        //         $uniArray =array();
        //         $min_threshold_array =array();
        //         $max_threshold_array =array();

        //         for($a=0;$a<$arrLength;$a++){
        //            $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //            $c = array_push($uniArray,$b);
        //             if(isset($arrayreading2[$a])){
        //                $d = explode('-', $arrayreading2[$a]);
        //                $e = array_push($min_threshold_array,(int)$d[0]);
        //                $f = array_push($max_threshold_array,(int)$d[1]);
        //            }
        //         }

        //         $reading = Observation_Temp::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('bodytemp'); 
        //         $arrayreading = $reading->toArray();
                
        //         $label = "Thermometer(degrees F)";
        //         $arrayreading1='';
        //         $label1='';

        //         $arrayreading_min =$min_threshold_array;
        //         $label_min ="minimum(degrees F)";

        //         $arrayreading_max =$max_threshold_array;
        //         $label_max ="maximum(degrees F)";


        //         $arrayreading_min1 ='';
        //         $label_min1 ="";

        //         $arrayreading_max1 ='';
        //         $label_max1 ="";

        //         return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);

        //         //return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);

        // }
        
        // if($deviceid=='5'){
        //         $datetime = Observation_Spirometer::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('effdatetime');
        //         //$uniArray = $datetime->toArray();
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_Spirometer::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('threshold'); 
        //         $arrayreading2 = $reading2->toArray();
        //         $arrLength = count($DateArray);
        //         $uniArray =array();

        //         $min_threshold_array =array();
        //         $max_threshold_array =array();
        //         $min1_threshold_array =array();
        //         $max1_threshold_array =array(); 

        //         for($a=0;$a<$arrLength;$a++){
        //            $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //            $c = array_push($uniArray,$b);
        //             if(isset($arrayreading2[$a])){
        //                $myArr = explode('/', $arrayreading2[$a]);
        //                $sep = $myArr[0];
        //                $sep1 =$myArr[1]; 
        //                $d = explode('-', $sep); 
        //                $p = explode('-', $sep1);
        //                $e = array_push($min_threshold_array,(int)$d[0]);
        //                $f = array_push($max_threshold_array,(int)$d[1]);
        //                $g = array_push($min1_threshold_array,(int)$p[0]);
        //                $h = array_push($max1_threshold_array,(int)$p[1]);
        //            }
        //         }

        //         $reading = Observation_Spirometer::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('pef_value'); 
        //         //->select('pef_value','fev_value')->get();
        //         $reading1 = Observation_Spirometer::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('fev_value');
        //         $arrayreading = $reading->toArray();
        //         $arrayreading1= $reading1->toArray();
                
        //         $label = "Spirometer(pef[Hg)";
        //         $label1 = "Spirometer(fev[Hg)";

        //         $arrayreading_min =$min_threshold_array;
        //         $label_min ="minimum(pef_value)";

        //         $arrayreading_max =$max_threshold_array;
        //         $label_max ="maximum(pef_value)";

        //         $arrayreading_min1 =$min1_threshold_array;
        //         $label_min1 ="minimum(fev_value')";

        //         $arrayreading_max1 =$max1_threshold_array;
        //         $label_max1 ="maximum(fev_value')";

        //         return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);
        //     //  return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'arrayreading1'=>$arrayreading1,'label'=>$label,'label1'=>$label1]);
        // }
        // if($deviceid=='6'){
        //         $datetime = Observation_Glucose::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('effdatetime');
        //         //$uniArray = $datetime->toArray();
        //         $DateArray = $datetime->toArray();
        //         $reading2 = Observation_Weight::where('patient_id',$patient_id)
        //         ->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('threshold'); 
        //         $arrayreading2 = $reading2->toArray();
        //         $arrLength = count($DateArray);
        //         $uniArray =array();
        //         $min_threshold_array =array();
        //         $max_threshold_array =array();

        //         for($a=0;$a<$arrLength;$a++){
        //            $b = date("M j, g:i a",strtotime($DateArray[$a]));
        //            $c = array_push($uniArray,$b);
        //             if(isset($arrayreading2[$a])){
        //                $d = explode('-', $arrayreading2[$a]);
        //                $e = array_push($min_threshold_array,(int)$d[0]);
        //                $f = array_push($max_threshold_array,(int)$d[1]);
        //            }
        //         } 

        //         $reading = Observation_Glucose::where('patient_id',$patient_id)->whereMonth('effdatetime',$month)->whereYear('effdatetime',$year)
        //         ->pluck('value'); 
        //         $arrayreading = $reading->toArray();
                
        //         $label = "Glucose(mg/dl)";
        //         $arrayreading1='';
        //         $label1='';

        //         $arrayreading_min =$min_threshold_array;
        //         $label_min ="minimum(mg/dl)";

        //         $arrayreading_max =$max_threshold_array;
        //         $label_max ="maximum(mg/dl)";


        //         $arrayreading_min1 ='';
        //         $label_min1 ="";

        //         $arrayreading_max1 ='';
        //         $label_max1 ="";

        //         return response()->json(
        //         ['uniArray'=>$uniArray,
        //         'arrayreading'=>$arrayreading,
        //         'arrayreading1'=>$arrayreading1,
        //         'arrayreading_min' =>$arrayreading_min,
        //         'arrayreading_max' =>$arrayreading_max,
        //         'arrayreading_min1' =>$arrayreading_min1,
        //         'arrayreading_max1' =>$arrayreading_max1,
        //         'label'=>$label,
        //         'label1'=>$label1,
        //         'label_min'=>$label_min,
        //         'label_max'=>$label_max,
        //         'label_min1'=>$label_min1,
        //         'label_max1'=>$label_max1
        //         ]);
        //        // return response()->json(['uniArray'=>$uniArray,'arrayreading'=>$arrayreading,'label'=>$label]);
        // }
        //     // return response()->json(
        //     //     ['uniArray'=>$uniArray,
        //     //     'arrayreading'=>$arrayreading,
        //     //     'arrayreading1'=>$arrayreading1,
        //     //     'arrayreading_min' =>$arrayreading_min,
        //     //     'arrayreading_max' =>$arrayreading_max,
        //     //     'arrayreading_min1' =>$arrayreading_min1,
        //     //     'arrayreading_max1' =>$arrayreading_max1,
        //     //     'label'=>$label,
        //     //     'label1'=>$label1,
        //     //     'label_min'=>$label_min,
        //     //     'label_max'=>$label_max,
        //     //     'label_min1'=>$label_min1,
        //     //     'label_max1'=>$label_max1
        //     //     ]); 
        // }
    //cmnt by priya on 11thfeb22
    // public function getreadCalender($patient_id,$deviceid){
    //  // dd($deviceid);
    //         if($deviceid =='1'){
    //             $readingWeight = Observation_Weight::where('patient_id',$patient_id)->get(); 
    //             // dd($readingWeight);
    //             $data =array();
    //             foreach ($readingWeight as $row) {
    //                 $data[] = array(
    //                       'id'   => $row["id"],
    //                       'title'   =>$row["weight"].' '.ucfirst($row["unit"]),
    //                       'start'   => $row["effdatetime"],
    //                       'end'   => $row["effdatetime"]
    //                      );

    //             }
    //         }
    //         if($deviceid =='2'){
    //             $readingOxy = Observation_Oxymeter::where('patient_id',$patient_id)->get();//->pluck('oxy_qty'); 
    //             $data =array();
    //             foreach ($readingOxy as $row) {
    //                 // print_r($value);
    //                 $data[] = array(
    //                       'id'   => $row["id"],
    //                       'title'   =>$row["oxy_qty"].' '.ucfirst($row["oxy_unit"]),
    //                       'start'   => $row["effdatetime"],
    //                       'end'   => $row["effdatetime"]
    //                      );

    //             }
    //         }
    //         if($deviceid =='3'){
    //             $readingBp = Observation_BP::where('patient_id',$patient_id)->get();
    //              // dd($readingBp);
    //             $data =array(); 
    //                 foreach ($readingBp as $row) {
    //                      // print_r($row);
    //                     $data[] = array(
    //                           'id'   => $row["id"],
    //                           'title'   =>'Systolic -'.$row["systolic_qty"].' '.ucfirst($row['systolic_unit']).'/'.'Diastolic -'.$row["diastolic_qty"].' '.ucfirst($row['systolic_unit']),
    //                           'start'   => $row["effdatetime"],
    //                           'end'   => $row["effdatetime"]
    //                          );
    //                 }

    //             }
    //             if($deviceid =='4'){
    //             $readingTemp = Observation_Temp::where('patient_id',$patient_id)->get();
    //             $data =array();
    //                 foreach ($readingTemp as $row) {
    //                     // print_r($value);
    //                     $data[] = array(
    //                           'id'   => $row["id"],
    //                           'title'   =>$row["bodytemp"].' '.ucfirst($row['unit']),
    //                           'start'   => $row["effdatetime"],
    //                           'end'   => $row["effdatetime"]
    //                          );
    //                 }      

    //             }
    //             if($deviceid =='5'){
    //             $readingSpiro = Observation_Spirometer::where('patient_id',$patient_id)->get();
    //                 $data =array();
    //                 foreach ($readingSpiro as $row) {
    //                     // print_r($value);
    //                     $data[] = array(
    //                           'id'   => $row["id"],
    //                           'title'   =>'Fev -'.$row["fev_value"].' '.ucfirst($row['fev_unit']).'/'.'Pef -'.$row["pef_value"].' '.ucfirst($row['pef_unit']),
    //                           'start'   => $row["effdatetime"],
    //                           'end'   => $row["effdatetime"]
    //                          );

    //                 }
    //             }

    //             if($deviceid =='6'){
    //             $readingGluec = Observation_Glucose::where('patient_id',$patient_id)->get();
    //             $data =array();
    //             foreach ($readingGluec as $row) {
    //                 // print_r($value);
    //                 $data[] = array(
    //                       'id'   => $row["id"],
    //                       'title'   =>$row["value"].' '.ucfirst($row['unit']),
    //                       'start'   => $row["effdatetime"],
    //                       'end'   => $row["effdatetime"]
    //                      );

    //             }

    //         }
            
    //        //echo "<pre>"; print_r($data); die;
    //     return response()->json($data); 
    // }
    public function getreadCalender($patient_id,$deviceid){
     // dd($deviceid);
     $data =array();
            if($deviceid =='1'){
                $readingWeight = Observation_Weight::where('patient_id',$patient_id)->get(); 
                // dd($readingWeight);
                
                foreach ($readingWeight as $row) {
                    $data[] = array(
                          'id'   => $row["id"],
                          'title'   =>$row["weight"].' '.ucfirst($row["unit"]),
                          'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"]),
                          'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"])
                         );

                }
            }
            if($deviceid =='2'){
                $readingOxy = Observation_Oxymeter::where('patient_id',$patient_id)->get();//->pluck('oxy_qty'); 
               
                foreach ($readingOxy as $row) {
                    // print_r($value);
                    $data[] = array(
                          'id'   => $row["id"],
                          'title'   =>$row["oxy_qty"].' '.ucfirst($row["oxy_unit"]),
                          'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"]),
                          'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"])
                         );

                }
            }
            if($deviceid =='3'){
                $readingBp = Observation_BP::where('patient_id',$patient_id)->get();
                 // dd($readingBp);
               
                    foreach ($readingBp as $row) {
                         // print_r($row);
                        $data[] = array(
                              'id'   => $row["id"],
                              'title'   =>'Systolic -'.$row["systolic_qty"].' '.ucfirst($row['systolic_unit']).'/'.'Diastolic -'.$row["diastolic_qty"].' '.ucfirst($row['systolic_unit']),
                              'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"]),
                              'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"])
                             );
                    }

                }
                if($deviceid =='4'){
                $readingTemp = Observation_Temp::where('patient_id',$patient_id)->get();
                
                    foreach ($readingTemp as $row) {
                        // print_r($value);
                        $data[] = array(
                              'id'   => $row["id"],
                              'title'   =>$row["bodytemp"].' '.ucfirst($row['unit']),
                              'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"]),
                              'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"])
                             );
                    }      

                }
                if($deviceid =='5'){
                $readingSpiro = Observation_Spirometer::where('patient_id',$patient_id)->get();
                    
                    foreach ($readingSpiro as $row) {
                        // print_r($value);
                        $data[] = array(
                              'id'   => $row["id"],
                              'title'   =>'Fev -'.$row["fev_value"].' '.ucfirst($row['fev_unit']).'/'.'Pef -'.$row["pef_value"].' '.ucfirst($row['pef_unit']),
                              'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"]),
                              'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                              ($row["effdatetime"])
                             );

                    }
                }

                if($deviceid =='6'){
                $readingGluec = Observation_Glucose::where('patient_id',$patient_id)->get();
                
                foreach ($readingGluec as $row) {
                    // print_r($value);
                    $data[] = array(
                          'id'   => $row["id"],
                          'title'   =>$row["value"].' '.ucfirst($row['unit']),
                          'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"]),
                          'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row["effdatetime"])
                         );

                }

            }
            
           //echo "<pre>"; print_r($data); die;
        return response()->json($data); 
    }
    
}
    