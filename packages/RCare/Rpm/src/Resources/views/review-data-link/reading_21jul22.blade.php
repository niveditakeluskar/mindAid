<link rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">
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
                       
                       <hr>    <?php if(isset($patient_assign_deviceid) && $patient_assign_deviceid!=""){?>
                                <!--=========//* show all tab (code start)*//==============-->
                                <ul class="nav nav-tabs" id="patientdevicetab" role="tablist">
                                <?php

                               
                               
                                   $Adeviceid = explode(',', $patient_assign_deviceid);
                                
                                
                                if(!empty($devices)){
                                for($i=0;$i<count($devices);$i++){ 
                                    //*active-tab basis of device-id from url
                                        //($i == ($deviceid-1)) ? $active="active" : $active=""; 
                                         ($i == ($Adeviceid[0]-1)) ? $active="active" : $active=""; 
                                        for($j=0;$j<count($Adeviceid);$j++){
                                           if($Adeviceid[$j]-1==$i){
                                        
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link {{$active}} tabclass" id="device-icon-tab_{{$devices[$i]->id}}" data-toggle="tab" href="#deviceid_{{$devices[$i]->id}}" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i><?php echo $devices[$i]->device_name;?></a>
                                </li>
                                <?php }} }}?> 
                                </ul>
                                 
                              <!--=========//* show all tab (code End)*//==============-->
                               <input type="hidden" id="hd_deviceid" value="<?php echo $Adeviceid[0];?>" >
                                

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

                    <div class="table-responsive" id="appendtable" >    
                   
                    </div>
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
                      <?php }?>
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
 
@section('page-js')
<!-- <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script> -->
<!-- calender --> 
<script src="{{asset('assets/js/vendor/calendar/jquery-ui.min.js')}}"></script>
<!-- <script src="{{asset('assets/js/vendor/calendar/moment.min.js')}}"></script>-->
<script src="{{asset('assets/js/vendor/calendar/fullcalendar.min.js')}}"></script>
<!-- graph -->
<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>--> 
<!-- <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->
<script src="{{asset(mix('assets/js/laravel/commonHighchart.js'))}}"></script>

<script src="{{asset(mix('assets/js/laravel/rpmReviewDataLink.js'))}}"></script>

<script type="text/javascript">
    var newDate = new Date,
        date = newDate.getDate(),
        month = newDate.getMonth(),
        year = newDate.getFullYear();
       // var time = newDate.getHours() + ":" + newDate.getMinutes() + ":" + newDate.getSeconds();
        var patient_id = $('#patient_id').val();
        var deviceid = $('#hd_deviceid').val();

        $('#cal1').fullCalendar({ 
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay' 
        },
        eventLimit: true,// for all non-TimeGrid views
        eventLimit: 1,
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) { 
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500'); 
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+deviceid,
        selectable: true, 
        selectHelper: true,       
        });

        $('#cal2').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventLimit:true,
        views: {
            timeGridMonth: {
              eventLimit: 1// adjust to 6 only for timeGridWeek/timeGridDay
            }
          },
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // }, 
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip); 
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+2,
        selectable: true, 
        selectHelper: true,       
        });

        $('#cal3').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventLimit:true,
         eventLimit: 1,
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+3,
        selectable: true, 
        selectHelper: true,
       
        });
        $('#cal4').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventLimit:true,
        views: {
            timeGridMonth: {
              eventLimit: 1// adjust to 6 only for timeGridWeek/timeGridDay
            }
          },
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+4,
        selectable: true, 
        selectHelper: true,
       
        });
        $('#cal5').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventLimit:true,
        views: {
            timeGridMonth: {
              eventLimit: 1// adjust to 6 only for timeGridWeek/timeGridDay
            }
          },
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+5,
        selectable: true, 
        selectHelper: true,
       
        });
        $('#cal6').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventLimit:true,
        views: {
            timeGridMonth: {
              eventLimit: 1// adjust to 6 only for timeGridWeek/timeGridDay
            }
          },
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        // },
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' +'Title :' +calEvent.title +'<br>'+'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.tooltipevent').fadeIn('500');
                $('.tooltipevent').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltipevent').css('top', e.pageY + 10);
                $('.tooltipevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
             $(this).css('z-index', 8);
             $('.tooltipevent').remove();
        },
        timezone: "local",
        events:'/rpm/calender-data/'+patient_id+'/'+6,
        selectable: true, 
        selectHelper: true,
        });


    var getPatientAlertHistoryList = function(patient = null,unit = null,fromdate1=null,todate1=null,deviceid=null) {
        var columns = [{data:null,
                                mRender: function(data, type, full, meta){
                                    totaltime = full['csseffdate'];  
                                    if(full['csseffdate'] == null){
                                        totaltime = '';
                                    }
                                    if(full['csseffdate']!='' && full['csseffdate']!='NULL' && full['csseffdate']!=undefined){
                                        return totaltime;
                                    }
                                },
                                orderable: true   
                            }, 
                             {data:null,
                                mRender: function(data, type, full, meta){
                                    threshold = full['threshold'];  
                                    if(full['threshold'] == null){
                                        threshold = '';
                                    }
                                    if(full['threshold']!='' && full['threshold']!='NULL' && full['threshold']!=undefined){
                                        return threshold;
                                    }
                                },
                                orderable: true   
                            },       
                           {data:null,
                                mRender: function(data, type, full, meta){
                                    readingone = full['readingone'];  
                                    if(full['readingone'] == null){
                                        readingone = '';
                                    }
                                    if(full['readingone']!='' && full['readingone']!='NULL' && full['readingone']!=undefined){
                                        return readingone;
                                    }
                                },
                                orderable: true   
                            },

                            
                            { data: null,
                                mRender: function(data, type, full, meta){
                                    readingtwo = full['readingtwo'];
                                        if(full['readingtwo'] == null){
                                            readingtwo = '';
                                    }
                                    if(full['readingtwo']!='' && full['readingtwo']!='NULL' && full['readingtwo']!=undefined){
                                        return full['readingtwo'];
                                                                              }
                                },
                                orderable: false
                            },
                            
                             {data:null,
                                mRender: function(data, type, full, meta){
                                    heartrate_threshold = full['heartrate_threshold'];  
                                    if(full['heartrate_threshold'] == null){
                                        heartrate_threshold = '';
                                    }
                                    if(full['heartrate_threshold']!='' && full['heartrate_threshold']!='NULL' && full['heartrate_threshold']!=undefined){
                                        return heartrate_threshold;
                                    }
                                },
                                orderable: true   
                            },       
                            { data: null,
                                mRender: function(data, type, full, meta){
                                    heartratereading = full['heartratereading'];
                                        if(full['heartratereading'] == null){
                                            heartratereading = '';
                                    }
                                    else{
                                        return heartratereading;
                                    }
                                     
                                },
                                orderable: false
                            },
                            {
            data: null,'render': function(data, type, full, meta) {
                if (full['reviewedflag'] == null || full['reviewedflag'] == 0) {
                    check = '';
                } else {
                    check = 'checked';
                }
                return '<input type="checkbox" id="reviewpatientstatus_' + meta.row + '" onchange="rpmReviewDataLink.reviewStatusChk(this)" class="reviewpatientstatus" name="reviewpatientstatus" value="' + full['pid'] + '" ' + check + '>';
            },
            orderable: false
        },
        {   
            data: null, 'render': function (data, type, full, meta){
                 m_alert = full['alert'];
                if(full['rwaddressed'] == null || full['rwaddressed']==0){
                    check = '';
                    readonly='';
                }else{
                    check = 'checked';  
                    readonly='disabled';
                }
                if(m_alert == 1) {
                    return '<input type="checkbox" id="activealertpatientstatus_'+meta.row+'" class="activealertpatientstatus" name="activealertpatientstatus"  value="'+full['pid']+'" '+check +'  '+readonly+' >';
                } 
            },
            orderable: true
        }
                   

                    // { data: 'action', name: 'action', orderable: false, searchable: false}
                    ];  

                  
                    
                 
        
            var sPageURL = window.location.pathname;
            var arr = sPageURL.split('/');
            

            if(patient==''|| patient==null ){
                var newpatient = arr[3];
                
            }
            else{
                var newpatient = patient;
            }
            if(unit=='' || unit==null){
                var newunit = arr[4];
            }
            else{
                var newunit = unit;
            } 
            if(fromdate1==''){ fromdate1=null; }
            if(todate1=='')  { todate1=null; } 
           
            var url ="/rpm/patient-alert-history-list-device-link/"+newpatient+"/"+newunit+"/"+fromdate1+"/"+todate1;   
            //console.log(url,sPageURL);
            var table1=util.renderDataTable('patient-alert-history-list_'+deviceid, url, columns, "{{ asset('') }}"); 
        
    } 

    function setdata(){
        var ddl_days = $("#ddl_days").val();
        var patient_id = $("#patient_id").val();
        $.ajax({
            type: 'get',
            url: '/rpm/getNumberOfReading/'+ ddl_days+'/'+patient_id,
            success: function(data) {
            $("#txt_day").html(ddl_days);
            $('#txt_reading').html(data.readingcnt);
            $('#txt_alert').html(data.alertcnt);
            
            // var calcPerc = (24) * 100 /30 ;

            var calcPerc = (data.readingcnt) * ddl_days/ 100 ;
            var countPerc = calcPerc.toFixed(2);
            $('#showpercentage').html(countPerc);
            
            }
        }); 
    }  
        

    $('#searchbutton').click(function(){       
        var ref_this = $("ul#patientdevicetab li a.active").attr('id');
        var res = ref_this.split("_");
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val(); 
        vitaltable(res[1],fromdate1,todate1);
    });

    $('#resetbutton').click(function(){
        var ref_this = $("ul#patientdevicetab li a.active").attr('id');
        var res = ref_this.split("_");
        $('#fromdate').val('');
        $('#todate').val('');
        $('#fromdate').val(firstDayWithSlashes);  
        $('#todate').val(currentdate);
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val(); 
        vitaltable(res[1],fromdate1,todate1); 
    });

    function formatDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year,month, day].join('-');
    }

    var currentdate = formatDate();   
    var date = new Date(); 
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
    var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
    var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);

    function vitaltable(value,fromdatevalue,todatevalue) {
       
        $('#appendtable').empty();
        $('#appendtable').append('<div id="AddressSuccess_'+value+'"></div>');
        $('#appendtable').append(' <table id="patient-alert-history-list_'+value+'" class="display table table-striped table-bordered" style="width:100%"><thead id="vitaltableheader'+value+'"></thead><tbody id="vitalstablebody"></tbody> </table>');
        if(value==1){ 
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th width="50px">Threshold</th><th width="50px">Weight</th><th width="5px"></th><th width="5px"></th><th width="5px"></th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');

        getPatientAlertHistoryList(null,'observationsweight',fromdatevalue,todatevalue,value); 
        }   
        else if(value==2){             
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Threshold</th><th  width="50px">SpO2</th><th  width="50px">Perfusion Index</th><th  width="50px">Threshold</th><th  width="50px">Heartrate</th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');
        getPatientAlertHistoryList(null,'observationsoxymeter',fromdatevalue,todatevalue,value);    
        }
        else if(value==3){
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Threshold</th><th  width="50px">Systolic</th><th  width="50px">Diastolic</th><th  width="50px">Threshold</th><th  width="50px">Heartrate</th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');
        getPatientAlertHistoryList(null,'observationsbp',fromdatevalue,todatevalue,value); 
          
        }
        else if(value==4){
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th width="50px">Temperature</th><th width="5px"></th><th width="5px" ></th><th width="5px"></th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');
        getPatientAlertHistoryList(null,'observationstemp',fromdatevalue,todatevalue,value);   
        }
        else if(value==5){
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th width="50px">FEV1 Value</th><th width="50px">PEF Value</th><th width="5px" ></th><th width="5px"></th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');
        getPatientAlertHistoryList(null,'observationsspirometer',fromdatevalue,todatevalue,value);   
        }
        else if(value==6){
           
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th  width="50px">Glucose Level</th><th width="5px"></th><th width="5px"></th><th width="5px"></th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>');  
        getPatientAlertHistoryList(null,'observationsglucose',fromdatevalue,todatevalue,value);   
        }
        else{
        $('#vitaltableheader'+value).html('');
        $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">NA</th><th  width="50px">NA</th><th width="50px">NA</th><th width="50px">NA</th><th width="5px">Review</th><th width="5px">Addressed Alert</th></tr>'); 
        getPatientAlertHistoryList('000000000','observationsbp',fromdatevalue,todatevalue,value);   
        
        } 
         
    }

    $(document).ready(function(){ 
        rpmReviewDataLink.init();
        setdata();
        $('#btn_datalist').click(function(){
            var  months = {
                January : 1,
                February: 2,
                March: 3,
                April: 4,
                May: 5,
                June: 6,
                July: 7,
                August: 8,
                September: 9,
                October: 10,
                November: 11,
                December: 12
            }
               var str = $('#cald-hid').val();
               var myArr = str.split(" ");
               var newMonth = months[myArr[0]];
               var Month ='';
                    if (newMonth < 10) {
                        Month = '0' + newMonth;
                    }else{
                        Month = newMonth; 
                    }
               var newYear= myArr[1];
               var newDate = newYear+"-"+Month+"-"+'01';
               var dateStr = new Date(newYear, Month,1,0); 
               var ret = new Date(dateStr).toISOString();
               var str1 = ret.split("T");
               var lastDay = str1[0]; 
                //firstDayWithSlashes
                       $('#fromdate').val(newDate);
                       $('#todate').val(lastDay);
                        var fromdate1 = $('#fromdate').val(); 
                        var todate1 = $('#todate').val();
                var deviceid=$("#hd_deviceid").val();
                $("#address_btn").html('<button type="button" class="btn btn-primary mt-4 month-reset" id="Addressed"  >Addressed</button>');
                $("#hd_tbl").show();
                vitaltable(deviceid,fromdate1,todate1);
                 $('html, body').animate({scrollTop: $('#appendtable').offset().top }, 'slow'); 
        }); 

    });
</script>
@endsection