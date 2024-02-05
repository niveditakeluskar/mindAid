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
  .reminder_msg_icon {
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
  .reminder_msg_icon1{
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
<div class='row'>
  <div class="col-md-12">
    {{ csrf_field() }}
    <?php
        $module_id    = getPageModuleName(); 
        $submodule_id = getPageSubModuleName();
        // $patient_id ='1830627799';
    ?>
    <input type="hidden" id="hidden_id" name="patient_id" value={{$patient_id}}> 
    <input type="hidden" id="module_id" name="module_id" value={{$module_id}}>
    @include('Patients::components.patient-Ajaxbasic-info')
  </div>        
</div>    
<div class="separator-breadcrumb border-top">
  <div class="row mb-3">
        <h5 class="card-title">Timeline Activities</h5>
  </div>
</div>
<div class="row mb-3">
  <div class="col-md-3 form-group mb-3">
    <label for="month">From Month & Year</label> @month('from_month',["id" => "from_month"])
  </div>

  <div class="col-md-2">
    <button type="button" class="btn btn-primary mt-4" id="search-btn">Search</button>
  </div>
  <div class="col-md-2">
    <button type="reset" class="btn btn-primary mt-4" id="month-reset">Reset</button>
  </div>
</div>
<div class="separator-breadcrumb border-top"></div>  
<div class="container" id="search_timeline_activity">
<?php $number_days = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));?>
<input type="text" id="total_num_days" name ="total_num_days" value="{{$number_days}}">
@include('Rpm::timeline_rpm.timeline-activities-ui')
</div>
<div id="app"></div>
@endsection 

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
  $(document ).ready(function() {
  function getMonth(date) {
  var month = date.getMonth() + 1;
  return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
  }

  var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
  var c_year = new Date().getFullYear();
  var current_MonthYear = c_year+'-'+c_month;
  $("#from_month").val(current_MonthYear);

  var patientId = $("#hidden_id").val(); 
  var moduleId = $("input[name='module_id']").val(); 
  util.getPatientDetails(patientId, moduleId);
  $("#search-btn").click(function(){ 
    var month_val = $("#from_month").val(); 
    var patientId = $("#hidden_id").val();
    // alert(month_val);
    var total_days =  $("#total_num_days").val();
    for(n = 1; n <= total_days; n++) { 
      if(n % 2 == 0){
        var  n_even = n;//$('.num-even '+n).length;
        console.log(n_even ,"EVEN",n);
      }else if(n % 2 != 0){
        var n_odd = n;// $('.num-odd '+n).length;
        console.log(n_odd ,"ODD",n);
      }
      // $(".blog-post:eq(" + i  + ")").append("<p>Here's a note</p>");
    }
    //ajax
    $.ajax({
      type: "get",
      url: "/rpm/timeline-daily-activity-search/"+patientId+"/"+month_val+"/timelinedailyactivitysearch",
      success: function(data)
      {//debugger;
        console.log(data ,"search_timeline_activity");
        $('#search_timeline_activity').html(data);
      }
    });
  });

});
</script>