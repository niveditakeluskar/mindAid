<!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}"> -->
<style type="text/css">
.highcharts-figure, .highcharts-data-table table {
min-width: 310px; 
max-width: 800px;
margin: 1em auto;
}

.highcharts-data-table table {
font-family: Verdana, sans-serif;
border-collapse: collapse;
border: 1px solid #EBEBEB;
margin: 10px auto;
text-align: center;
width: 100%;
max-width: 500px;
}
.highcharts-data-table caption {
padding: 1em 0;
font-size: 1.2em;
color: #555;
}
.highcharts-data-table th {
font-weight: 600;
padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
background: #f8f8f8;
}
.highcharts-data-table tr:hover {
background: #f1f7ff;
} 
/*calender css*/
.fc-content {
color: #fff;
}
</style>
<div class="row mb-4">
<div class="col-md-12">
<div class="card" >
<div class="card-header" >
<div class="form-row">
<span class="col-md-4">
<!-- <h4>Patient Device Reading</h4> -->
<select class="custom-select show-tick"  onchange="setdata()"  id="ddl_days">
<option value="30"> 30 days</option>
<option value="60"> 60 days</option>
<option value="90"> 90 days</option>
</select>
</span>
<span class="col-md-8" style="padding-right:30%">
<!-- @selectDevice('device_name',['id'=>'device_name']) --> 
</span>
</div>  
</div>
<?php 
$module_id    = getPageModuleName();
$submodule_id = getPageSubModuleName();
$stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Review RPM'); 
$step_id = getFormStepId(getPageModuleName(), getPageSubModuleName(), $stage_id,'Review-Rpm-Reading'); 
?>
<div class="card-body"> 
<div class="row"> 
<div class="col-md-12" id ="time_add_success_msg"></div>
<div id="success"></div>
<div class="col-md-12 steps_section">
<div class="title">
<input type="hidden" id="patient_id" value="<?php echo $patient_id; ?>">
<input type="hidden" id="module_id" name="module_id" value="{{$module_id}}">
<input type="hidden" id= "component_id" name="sub_module_id" value="{{$submodule_id}}">
<input type="hidden" name="stage_id" value="{{$stage_id}}">
<input type="hidden" name="step_id" value="{{$step_id}}">
<input type="hidden" name="form_name" value="rpm_review_form"> 
<h5 class="text-center card-title">RPM Readings</h5>
</div>
</div>                   
</div>

<!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
<div class="align-self-start pt-3">
<div class="d-flex justify-content-center">
<div class="mr-2" style="">
<div class="card">
<div class="card-header device_header">Number of days</div>
<div class="card-body device_box">
<span id ="txt_day" class="day"></span>
</div>
</div>
</div>
<div class="mr-2" style="">
<div class="card">
<div class="card-header device_header">Number of Readings</div>
<div class="card-body device_box">
<span id="txt_reading" class="day"></span>


</div>
</div>
</div>
<div class="mr-2" style="">
<div class="card">
<div class="card-header device_header">% Participation</div>
<div class="card-body device_box">
<span id="showpercentage"  class="day"></span>
<span  class="day">%</span>


</div>
</div>
</div>
<div class=" rounded" style="">
<div class="card">
<div class="card-header device_header">Number of Alerts</div>
<div class="card-body device_box">
<span id="txt_alert" class="day"></span>

</div>                               
</div>  
</div>
</div>
</div>

<hr>    
<!-- ?php if(isset($patient_assign_deviceid) && $patient_assign_deviceid!=""){?> -->
<!--=========//* show all tab (code start)*//==============-->
<ul class="nav nav-tabs" id="patientdevicetab" role="tablist">
<?php
$Adeviceid = explode(',', $patient_assign_deviceid);
if(!empty($devices)){

    // dd($devices, ($deviceid-1));


for($i=0;$i<count($devices);$i++){ 
//*active-tab basis of device-id from url

 if(isset($patient_assign_deviceid) && $patient_assign_deviceid!="")
 {
  ($i == ($Adeviceid[0]-1)) ? $active="active" : $active="";  
 }
 else{
    ($i == (2)) ? $active="active" : $active="";    
 }




// ($i == ($deviceid-1)) ? $active="active" : $active=""; 
// ($i == ($Adeviceid[0]-1)) ? $active="active" : $active=""; 


// for($j=0;$j<count($Adeviceid);$j++){ commented by ashvini on 19th dec 2022 to so all devices
// if($Adeviceid[$j]-1==$i){ commented by ashvini on 19th dec 2022 to so all devices

?>
<li class="nav-item">
<a class="nav-link {{$active}} tabclass" id="device-icon-tab_{{$devices[$i]->id}}" data-toggle="tab" href="#deviceid_{{$devices[$i]->id}}" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i><?php echo $devices[$i]->device_name;?></a>
</li>
<?php 
// }}  commented by ashvini on 19th dec 2022 to so all devices

}}?> 
</ul>

<!--=========//* show all tab (code End)*//==============-->
<input type="hidden" id="hd_deviceid" value=1 >


<div class="row mt-4">
<div class="col">
<div class="card"> 
<!-- <div class="card-header device_header">Number of Alerts</div> -->
<input type="hidden" name="cald-hid" id="cald-hid">
<div class="card-body device_box" id="calender1">
<div class='cal' id='cal1'></div>
</div>
<div class="card-body device_box" id="calender2" style="display: none">
<div class='cal' id='cal2'></div>
</div>
<div class="card-body device_box" id="calender3" style="display: none">
<div class='cal' id='cal3'></div>
</div>                               
<div class="card-body device_box" id="calender4" style="display: none">
<div class='cal' id='cal4'></div>
</div>
<div class="card-body device_box" id="calender5" style="display: none">
<div class='cal' id='cal5'></div>
</div>
<div class="card-body device_box" id="calender6" style="display: none">
<div class='cal' id='cal6'></div>
</div>
</div>
</div>
<div>
<button type="button" id="btn_datalist" class="btn btn-primary">Data List</button>
</div>                               
<div class="col">
<div class="card">
<!-- <div class="card-header device_header">Number of Alerts</div> -->
<div class="card-body device_box">
<div id="container1" style="height: 400px; width: 100%;"></div>
{{-- @include('Rpm::review-data-link.graph-data') --}}
</div>                               
</div>
</div>
</div>



<div  id="hd_tbl" style="display: none">
    <hr>
    <div class="form-row"> 
        <div class="col-md-2 form-group mb-2">
            <label for="date">From Date</label>
            @date('date',["id" => "fromdate"])
        </div>
        <div class="col-md-2 form-group mb-3">
            <label for="date">To Date</label>
            @date('date',["id" => "todate"])
        </div> 
    <div>
    <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
    <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
</div>
<div class="col-md-2 text-right"  id="address_btn"></div>
</div> 
<div class="table-responsive" id="appendtable" ></div>
</div>
<hr>
<div class="card">
    <form action="{{route('save.rpmreview.notes')}}" name="rpm_review_form" id="rpm_review_form" method="POST"> 
        {{ csrf_field() }}
        <?php
            $module_id    = getPageModuleName();
            $submodule_id = getPageSubModuleName();
            $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Review RPM'); 
            $step_id = getFormStepId(getPageModuleName(), getPageSubModuleName(), $stage_id,'Review-Rpm-Caremanager-Notes');
        ?> 
        <input type="hidden" name="uid" value="{{$patient_id}}" />
        <input type="hidden" name="patient_id" value="{{$patient_id}}" />
        <input type="hidden" name="start_time" value="00:00:00">
        <input type="hidden" name="end_time" value="00:00:00"> 
        <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
        <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
        <input type="hidden" name="stage_id" value="{{$stage_id}}" />
        <input type="hidden" name="step_id" value="{{$step_id}}">
        <input type="hidden" name="form_name" id="rpm_review_form" value="rpm_review_form"/> 
        <input type="hidden" name="device_id" id="device_id" />
        <div id ='success'></div>
        <div class="row justify-content-center"> 
            <div class="col-md-12">
                <div class="form-group">
                    <div class="card-body">
                        <label>Care Manager Notes</label> 
                        <textarea name ="notes" class="form-control forms-element notes_class"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn  btn-primary">Save</button>
        </div>
    </form> 
</div>
<!-- ?php }?> -->     
<hr>
<!-- model popup start -->
<div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
<div class="modal-dialog modal-lg" style="
width: 800px!important;
margin-left: 280px">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Care Manager Notes</h4>
<button type="button" class="close modalcancel" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<form action="{{route('save.rpmdevicelink.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class=" form-horizontal">
{{ csrf_field() }}
<?php 
$module_id    = getPageModuleName();
$submodule_id = getPageSubModuleName();
$stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Review RPM'); 
$step_id = getFormStepId(getPageModuleName(), getPageSubModuleName(), $stage_id,'Review-Rpm-DataList');
?>
<div>
<div class="row">
<div class="col-md-12 form-group mb-3">
<input type="hidden" name="hd_timer_start" id="hd_timer_start">
<input type="hidden" name="rpm_observation_id_bp" id="rpm_observation_id_bp" />
<input type="hidden" name="rpm_observation_id_hr" id="rpm_observation_id_hr" />
<input type="hidden" name="care_patient_id" id="care_patient_id" />
<input type="hidden" name="p_id" id="p_id" />
<input type="hidden" name="csseffdate" id="csseffdate" /> <input type="hidden" id="module_id" name="module_id" value="{{$module_id}}">
<input type="hidden" id="component_id" name="component_id" value="{{$submodule_id}}">
<input type="hidden" name="table" id="table" />
<input type="hidden" name="formname" id="formname"/>
<input type="hidden" name="form_name"  id="form_name" value="rpm_cm_form">
<input type="hidden" name="stage_id" id="stage_id" value="{{$stage_id}}" />
<input type="hidden" name="step_id" id="step_id" value="{{$step_id}}"> 
<input type="hidden" name="hd_chk_this" id="hd_chk_this" />
<input type="hidden" name="device_id" id="device_id" />
<input type="hidden" name="rpm_unit_bp" id="rpm_unit_bp" />
<input type="hidden" name="rpm_unit_hr" id="rpm_unit_hr" />
<label for="Notes">Notes<span style="color: red">*</span></label>
<div class="forms-element">
@text("notes",["id"=>"notes"])
</div>
<div class="invalid-feedback"></div>
</div>    
</div>  
<div class="card-footer">  
<div class="mc-footer">
<div class="row">
<div class="col-lg-12 text-right">
<button type="submit" dataid="rpm_cm_form" class="btn btn-primary m-1">Submit</button>
<button type="button" class="btn btn-outline-secondary m-1 modalcancel" data-dismiss="modal">Cancel</button>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- model popup End -->
</div>
</div>
</div>
</div>


<!-- calender --> 
