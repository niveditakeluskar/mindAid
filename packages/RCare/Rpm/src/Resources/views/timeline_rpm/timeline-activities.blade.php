@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<style type="text/css">
.vl {
  border-left: 3px solid grey;
  display: inline-block;
  height: 60px;

}
.line{
  height: 3px;
  background: black;
  margin-top:-8px;
}
.circle {
    height: 50px;
    width: 50px; 
    position: absolute;
    /* margin: 15px; */
    /* border: 2px solid green; */
    border-radius: 50%;
    margin-left: 20px;
    margin-top: 30px;
}
.reminder-msg-icon {
  height: 40px;
    width: 49px;
    position: absolute;
    padding: 8px;
    border: 3px double currentColor;
    border-radius: 50%;
    margin-left: 15px;
    margin-top: 60px;
}
.stethoscope{
    height: 50px;
    width: 50px;
    position: absolute;
    border: 2px solid blueviolet;
    border-radius: 50%;
    margin-left: 10px;
    margin-top: 40px;
  }
.stethoscope_bp_odd{
  height: 50px;
  width: 50px; 
  position: absolute;
  /* margin: 15px; */
  /* border: 2px solid green; */
  border-radius: 50%;
  margin-left: 10px;
  margin-top: 40px;
}
.stethoscope_oxy_odd{
  height: 50px;
  width: 50px; 
  position: absolute;
  /* margin: 15px; */
  border: 3px solid skyblue;
  border-radius: 50%;
  margin-left: 10px; 
  margin-top: 60px;
}
.stethoscope_weight_odd{
    height: 50px;
    width: 50px;
    position: absolute;
    border: 10px solid #69aac2;
    border-radius: 50%;
    margin-left: 10px;
    margin-top: 60px;
}
.stethoscope_glucose_odd{
  height: 50px;
  width: 50px; 
  position: absolute;
  /* margin: 15px; */
  border: 2px solid #69aac2;
  border-radius: 50%;
  margin-left: 10px;
  margin-top: 65px; 
}
.stethoscope_bp_even{
  height: 50px;
  width: 50px;
  position: absolute;
  /* margin: 15px; */
  /* border: 2px solid green; */
  border-radius: 50%;
  margin-left: 22px;
  margin-top: -40px;
}
.stethoscope_oxy_even{
    height: 50px;
    width: 50px;
    position: absolute;
    /* margin: 15px; */
    border: 3px solid skyblue;
    border-radius: 50%;
    margin-left: 10px;
    margin-top: -60px;
}
.stethoscope_weight_even{
  height: 50px;
  width: 50px;
  position: absolute;
  border: 10px solid #69aac2;
  border-radius: 50%;
  margin-left: 12px;
  margin-top: -50px;
}
.stethoscope_glucose_even{
   height: 50px;
    width: 50px;
    position: absolute;
    /* margin: 15px; */
    border: 2px solid #003c80;
    border-radius: 50%;
    margin-left: 15px;
    margin-top: -60px;
}
.stethoscope1{
  height: 50px;
  width: 50px;
  position: absolute;
  /* margin: 15px; */
  border: 2px solid blueviolet;
  border-radius: 50%;
  margin-left: 12px;
  margin-top: -40px;
}
.circle1 {
    height: 50px;
    width: 50px;
    position: absolute;
    /* margin: 15px; */
    /* border: 2px solid green; */
    border-radius: 50%;
    margin-left: 22px;
    margin-top: -8px;
}
.reminder-msg-icon1{
    height: 40px;
    width: 49px;
    position: absolute;
    padding: 8px;
    border: 3px double currentColor;
    border-radius: 50%;
    margin-left: 18px;
    margin-top: -40px;
}
.num-even{
  font-weight :bolder;
  font-size:larger;
  padding:8px;
}
.num-odd{
  font-weight :bolder;
  font-size:larger;
  padding:8px;
}
</style>

@endsection
@section('main-content')
<div class="breadcrusmb">
  <div class="row">
        <h5 class="card-title mb-3">Timeline Activities</h5>
             <!-- <button type="button" id="exportbutton" class="btn btn-primary">Export to excel</button>    -->
  </div>
</div>  
<div class='row'>
<div class="col-md-12">
  {{ csrf_field() }}
  <?php
       $module_id    = getPageModuleName();
       $submodule_id = getPageSubModuleName();
  ?>
  <input type="hidden" id="hidden_id" name="patient_id" value={{$patient_id}}> 
  <input type="hidden" id="module_id" name="module_id" value={{$module_id}}>
  @include('Patients::components.patient-Ajaxbasic-info')
</div>        
</div>    
<?php //echo date("Y/m/d"). "<br>";  
$number_days = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y")); // 31
echo date('M Y');//"There are {$number_days} days";
?>
<div class='row'>
  <div class="col-md-12">
    <!-- <div class="col-md-4 form-group mb-3"> 
    <label for="month">Month & Year</label>
      @month('monthly',["id" => "monthly","name"=>"monthly"])
    </div>
    <div class="col-md-4 form-group mb-3">
        <button type="button" class="btn btn-primary mt-4 ml-1" id="search-btn">Search</button>
    </div> -->

  </div>        
</div>
<div class="separator-breadcrumb border-top"></div>  
<div class="container">
	<div class="row">
      <div class="col-md-12">
          <div class="card-body mt-5">
            <?php 
              $distance = 0; 
              $a = $distance+35;
              $a1= $distance+20; 
              $store_bp_day=array();
              $store_oxy_day = array();
              $store_weight_day = array();
              $store_glucose_day = array();
              $store_spiro_day = array();
              $store_task_day = array();
              $store_reminder_day = array();
              $title_day_bp_value = array();
              $title_day_oxy_value = array();
              $title_day_weight_value = array();
              $title_day_glucose_value = array();
              $title_day_spiro_value = array();
              $title_todo_key_value = array();
              $title_reminder_rpm_reading_value = array();
              for($j=0;$j<=$number_days;$j++){
                //bp Value
                if(isset($ob_bp[$j]->effdatetime)){ 
                  $db_day_bp = date("d", strtotime($ob_bp[$j]->effdatetime));
                  if($db_day_bp <=9 ){
                   $db_day_bp =  ltrim($db_day_bp, "0");  
                  }
                  array_push($store_bp_day,$db_day_bp);
                  $title_day_bp_value[$db_day_bp] = 'Date -'.$ob_bp[$j]->effdatetime.'Systolic - '.$ob_bp[$j]->systolic_qty.'Diastolic - '.$ob_bp[$j]->diastolic_qty;    
                } 
                //Oxy Value
                if(isset($ob_oxy[$j]->effdatetime)){ 
                  $db_day_oxy = date("d", strtotime($ob_oxy[$j]->effdatetime));
                  if($db_day_oxy <=9 ){
                    $db_day_oxy =  ltrim($db_day_oxy, "0");  
                  }
                  array_push($store_oxy_day,$db_day_oxy);
                  $title_day_oxy_value[$db_day_oxy] = 'Date -'.$ob_oxy[$j]->effdatetime.'Oxy Qty - '.$ob_oxy[$j]->oxy_qty;    
                }
                //weight value 
                if(isset($ob_weight[$j]->effdatetime)){ 
                  $db_day_weight = date("d", strtotime($ob_weight[$j]->effdatetime));
                  if($db_day_weight <=9 ){
                    $db_day_weight =  ltrim($db_day_weight, "0");  
                  } 
                  array_push($store_weight_day,$db_day_weight);
                  $title_day_weight_value[$db_day_weight] = 'Date -'.$ob_weight[$j]->effdatetime.'Weight - '.$ob_weight[$j]->weight;    
                }
                //Glucose value
                if(isset($ob_glucose[$j]->effdatetime)){ 
                  $db_day_glucose = date("d", strtotime($ob_glucose[$j]->effdatetime));
                  if($db_day_glucose <=9 ){
                    $db_day_glucose =  ltrim($db_day_glucose, "0");  
                  } 
                  array_push($store_glucose_day,$db_day_glucose);
                  $title_day_glucose_value[$db_day_glucose] = 'Date -'.$ob_glucose[$j]->effdatetime.'Value - '.$ob_glucose[$j]->value;    
                }
                // spirometer value 
                
                if(isset($ob_spiro[$j]->effdatetime)){ 
                  $db_day_spiro = date("d", strtotime($ob_spiro[$j]->effdatetime));
                  if($db_day_spiro <=9 ){
                    $db_day_spiro =  ltrim($db_day_spiro, "0");  
                  } 
                  array_push($store_spiro_day,$db_day_spiro);
                  $title_day_spiro_value[$db_day_spiro] = 'Date -'.$ob_spiro[$j]->effdatetime.'Pef Value - '.$ob_spiro[$j]->pef_value.'Fev Value - '.$ob_spiro[$j]->fev_value;    
                }
                //todolist value
                if(isset($to_do_list[$j]->task_date)){ 
                  $task_day = $to_do_list[$j]->task_date;//date("m-d-Y", strtotime($to_do_list[$j]->task_date));
                  $ex_task_day = explode('-',$task_day);//date('d',strtotime($task_day));
                  $db_task_day = $ex_task_day[1];
                  if($db_task_day <=9 ){
                    $db_task_day =  ltrim($db_task_day, "0");  
                  }
                  array_push($store_task_day,$db_task_day);
                  $title_todo_key_value[$db_task_day] = 'Task Date -'.$task_day.'Task Notes - '.$to_do_list[$j]->task_notes.'Status - '.$to_do_list[$j]->status;      
                } 

                //reminder 
                if(isset($reminder_rpm_reading[$j]->created_at)){ 
                  $reminder_day = $reminder_rpm_reading[$j]->created_at;// date("d", strtotime($reminder_rpm_reading[$j]->created_at));
                  $ex_reminder_day = explode('-',$reminder_day);//date('d',strtotime($task_day));
                  $db_reminder_day = $ex_reminder_day[1];
                  if($db_reminder_day <=9 ){
                    $db_reminder_day =  ltrim($db_reminder_day, "0");  
                  }
                  array_push($store_reminder_day,$db_reminder_day);
                  $title_reminder_rpm_reading_value[$db_reminder_day] = 'Reminder Date -'.$reminder_day.'Reminder Message -'.$reminder_rpm_reading[$j]->message.'Status -'.$reminder_rpm_reading[$j]->status;      
                }
              } 
              // dd($store_task_day);
              // dd($store_reminder_day);
            for($i=1;$i<=$number_days;$i++){ 
              $countedValues = array_count_values($store_bp_day);
              foreach ($countedValues as $key => $value) { 
                //     if ($value > 1) { 
                //echo "$key appears $value times\n"; 
                // for($a=1; $a<=$value;$a++){
                //   if($i==$key){
                //     if (in_array($i, $store_bp_day))
                //     {
                //       echo '<span class="stethoscope1"><i class ="i-stethoscope" data-toggle="tooltip" title ="'.$title_day_bp_value[$i].'" style="color:green;font-size:x-large"></i></span>';
                //     }
                    
                //   } 
                // }
              }

            if($i % 2==0){ 
            if (in_array($i, $store_bp_day))
             { 
               echo '<span class="stethoscope_bp_even" data-toggle="tooltip" title ="'.$title_day_bp_value[$i].'">
               <img src ="/assets/images/rpm_timeline_activity/bpicon.png">
               </span>';
             }
             if (in_array($i, $store_oxy_day)) 
             {
               echo '<span class="stethoscope_oxy_even" data-toggle="tooltip" title ="'.$title_day_oxy_value[$i].'">
               <img src ="/assets/images/rpm_timeline_activity/pulse-oximeter-2689775-2237496.png">
               </span>';
             }
             if (in_array($i, $store_weight_day))
             {
               echo '<span class="stethoscope_weight_even" data-toggle="tooltip" title ="'.$title_day_weight_value[$i].'">
               <img src ="/assets/images/rpm_timeline_activity/weight.png">
               </span>';
             }
             if (in_array($i, $store_glucose_day))
             {
              echo '<span class="stethoscope_glucose_even" data-toggle="tooltip" title ="'.$title_day_glucose_value[$i].'"> 
              <img src ="/assets/images/rpm_timeline_activity/glucose-icon.png">
              </span>';
             }
             if (in_array($i, $store_spiro_day))
             {
              echo '<span class="stethoscope1" data-toggle="tooltip" title ="'.$title_day_spiro_value[$i].'">
              <img src ="/assets/images/rpm_timeline_activity/spirometer_icon.png">
              </span>'; 
             }
             if (in_array($i, $store_task_day)) 
             { 
              echo '<span class="circle1"><i class ="i-Telephone"  data-toggle="tooltip" title ="'.$title_todo_key_value[$i].'" style="color:red;font-size:xx-large"></i></span>';
             }
             if (in_array($i, $store_reminder_day)) 
             { 
              echo '<span class="reminder-msg-icon1">
              <i class ="i-Mail-Favorite"  data-toggle="tooltip" title ="'.$title_reminder_rpm_reading_value[$i].'" style="color:currentColor;font-size:x-large"></i>
              </span>';
             }
             
            // if($i % 2==0){ 
              $style = "style=margin-left:".$a."px"; 
              echo '<span class="vl" '.$style.'></span>';
              echo '<span class = "num-even">'.$i.'</span>';
            }
            }
            ?>
            <div class="line"></div> 
            <?php 
            for($i=1;$i<=$number_days;$i++){
              if($i % 2!=0){
                if (in_array($i, $store_bp_day))
                { 
                  echo '<span class="stethoscope_bp_odd" data-toggle="tooltip" title ="'.$title_day_bp_value[$i].'">
                  <img src ="/assets/images/rpm_timeline_activity/bpicon.png">
                  </span>';
                } 
                if (in_array($i, $store_oxy_day))
                {
                  echo '<span class="stethoscope_oxy_odd" data-toggle="tooltip" title ="'.$title_day_oxy_value[$i].'">
                  <img src ="/assets/images/rpm_timeline_activity/pulse-oximeter-2689775-2237496.png">
                  </span>';
                }
                if (in_array($i, $store_weight_day))
                {
                  echo '<span class="stethoscope_weight_odd" data-toggle="tooltip" title ="'.$title_day_weight_value[$i].'">
                  <img src ="/assets/images/rpm_timeline_activity/weight.png">
                  </span>';
                }
                if (in_array($i, $store_glucose_day))
                {
                  echo '<span class="stethoscope_glucose_odd" data-toggle="tooltip" title ="'.$title_day_glucose_value[$i].'">
                  <img src ="/assets/images/rpm_timeline_activity/glucose-icon.png">
                  </span>';
                }
                if (in_array($i, $store_spiro_day))
                {
                  echo '<span class="stethoscope" data-toggle="tooltip" title ="'.$title_day_spiro_value[$i].'">
                  <img src ="/assets/images/rpm_timeline_activity/spirometer_icon.png">
                  </span>';
                }
                if (in_array($i, $store_task_day))
                { 
                  echo '<span class="circle"><i class ="i-Telephone"  data-toggle="tooltip" title ="'.$title_todo_key_value[$i].'" style="color:red;font-size:xx-large"></i></span>';
                }
                if (in_array($i, $store_reminder_day)) 
                { 
                  echo '<span class="reminder-msg-icon">
                  <i class ="i-Mail-Favorite"  data-toggle="tooltip" title ="'.$title_reminder_rpm_reading_value[$i].'" style="color:currentColor;font-size:x-large"></i>
                  </span>';
                }
              // if($i % 2!=0){
                  if($i==1){
                  $style = "style=margin-left:".$a1."px";
                  }else{
                  $style = "style=margin-left:".$a."px"; 
                  }
                echo '<span class="vl" '.$style.'><span class = "num-odd">'.$i++.'</span></span>';
              }
            }
            ?>
          </div>
      </div>
    </div>
	</div>
</div>

<div id="app"></div>
@endsection 
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    var tdate = new Date();
    var twoDigitMonth = ((tdate.getMonth().length + 1) === 1) ? (tdate.getMonth() + 1) : '0' + (tdate.getMonth() + 1);
    var yyyy = tdate.getFullYear(); //yields year
    var currentDate = yyyy + "-" + twoDigitMonth;
    $('#monthly').val(currentDate);
  var patientId = $("#hidden_id").val(); 
  var moduleId = $("input[name='module_id']").val(); 
  util.getPatientDetails(patientId, moduleId);
  $("#search-btn").click(function(){ 
    var month_val = $("#monthly").val(); 
    var patientId = $("#hidden_id").val();
    alert(month_val);
    $.ajax({
      type: "get",
      url: "/rpm/timeline-daily-activity/"+patientId+"/"+month_val+"/timelinedailyactivitysearch",
      success: function(data)
      {//debugger;
        //alert(data);
      }
    });
  });
});
</script>