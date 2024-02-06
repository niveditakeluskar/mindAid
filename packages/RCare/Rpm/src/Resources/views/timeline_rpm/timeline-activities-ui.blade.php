
<?php 
//echo date("Y/m/d"). "<br>";  
$number_days = cal_days_in_month(CAL_GREGORIAN, date($month), date($year)); // 31
// //echo date('M Y');
// echo "There are {$number_days} days";
?>
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
    for($i=1;$i<=$number_days;$i++){ 
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
      echo '<span class="reminder_msg_icon1">
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
          echo '<span class="reminder_msg_icon">
          <i class ="i-Mail-Favorite"  data-toggle="tooltip" title ="'.$title_reminder_rpm_reading_value[$i].'" style="color:currentColor;font-size:x-large"></i>
          </span>';
        }
      // if($i % 2!=0){
          if($i==1){
          $style = "style=margin-left:".$a1."px";
          }else{
          $style = "style=margin-left:".$a."px"; 
          }
      }
      echo '<span class="vl" '.$style.'><span class = "num-odd">'.$i++.'</span></span>';
    }
    ?>
  </div>
</div>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
  $(document ).ready(function() {
    
  });

</script>