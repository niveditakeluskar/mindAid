@extends('Theme::layouts_2.to-do-master')
@section('page-title')Monthly Monitoring - @endsection
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/styles/vendor/multiselect/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables/editor.dataTables.min.css')}}">
    <style> 
        #symptoms_0, input.symptoms, #goals_0, input.goals, #tasks_0, input.tasks {
            margin-bottom: 5px;
        }
    </style>
@endsection
<?php 
    // if(isset($patient) && isset($patient_enroll_date) && $patient_enroll_date != null && count($patient_enroll_date)>0) {
    if(isset($patient_enroll_date) && $patient_enroll_date != null && count($patient_enroll_date)>0) {
        $patient_id = $patient_enroll_date[0]->patient_id;
?>
    @section('main-content')
    <div class="separator-breadcrumb "></div>
    <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient_id }}">
    
      <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        @csrf
        <div class="col-md-12">
            {{ csrf_field() }}
            <?php
                $module_id    = getPageModuleName();
                $submodule_id = getPageSubModuleName();
                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
                $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Family-Emergency-Contact');
            ?>
            @include('Patients::components.patient-Ajaxbasic-info')
            @include('Ccm::monthly-monitoring.sub-steps.patient-monthly-monitoring-details')
        </div>
    </div>
    
    <div class="container">
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"></h4> 
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" style="padding-top:0px;">
                        {{--@include('Patients::components.patient-basic-info')--}}
                        <div class="row mb-4"> 
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">   
                                        <div class="form-group ccmPatientData" id="diagnosis-codes" name="diagnosis_codes" style="display:none"> 
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.care-plan-template')
                                        </div>
                                        <div class="form-group ccmPatientData" id="medications" name="medications" style="display:none">
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.medications') 
                                        </div>
                                        <div class="form-group ccmPatientData" id="vitalsHealth" name="vitalsHealth" style="display:none">
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking') 
                                        </div>
                                        <div class="form-group ccmPatientData" id="healthcare-services" name="healthcare-services" style="display:none">
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service') 
                                        </div>
                                        <div class="form-group ccmPatientData" id="allergy-information" name="allergy_information" style="display:none">
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.allergy')
                                        </div>
                                    </div>        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div id="app"> </div>
    @endsection
@section('page-js')
<script src="{{asset(mix('assets/js/laravel/ccmMonthlyMonitoring.js'))}}"></script>
<script src="{{asset(mix('assets/js/laravel/ccmcpdcommonJS.js'))}}"></script>
<script src="{{asset(mix('assets/js/laravel/carePlanDevelopment.js'))}}"></script>
<script src="{{asset(mix('assets/js/laravel/patientEnrollment.js'))}}"></script>

<!-- <script src="{{asset('assets/js/vendor/calendar/jquery-ui.min.js')}}"></script> -->
 <script src="{{asset('assets/js/vendor/calendar/fullcalendar.min.js')}}"></script> 
<script src="{{asset(mix('assets/js/laravel/commonHighchart.js'))}}"></script>
<script src="{{asset(mix('assets/js/laravel/rpmReviewDataLink.js'))}}"></script>
<script type="text/javascript">
    var newDate = new Date,
    date = newDate.getDate(),
    month = newDate.getMonth(),
    year = newDate.getFullYear();
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
        var columns = [
            {
                data:null,
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
            {
                data:null,
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
            {
                data:null,
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
            { 
                data: null,
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
            {
                data:null,
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
            { 
                data: null,
                mRender: function(data, type, full, meta){
                    heartratereading = full['heartratereading'];
                    if(full['heartratereading'] == null){
                        heartratereading = '';
                    } else{
                        return heartratereading;
                    }
                },
                orderable: false
            },
            /*{
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
        */
		];
        var sPageURL = window.location.pathname;
        var arr = sPageURL.split('/');
        if(patient==''|| patient==null ){
            var newpatient = arr[3];
        } else {
            var newpatient = patient;
        }
        if(unit=='' || unit==null){
            var newunit = arr[4];
        } else {
            var newunit = unit;
        } 
        if(fromdate1==''){ fromdate1=null; }
        if(todate1=='')  { todate1=null; } 
        var url ="/rpm/patient-alert-history-list-device-link/"+newpatient+"/"+newunit+"/"+fromdate1+"/"+todate1;   
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
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th width="50px">Threshold</th><th width="50px">Weight</th><th width="5px"></th><th width="5px"></th><th width="5px"></th></tr>');
            getPatientAlertHistoryList(null,'observationsweight',fromdatevalue,todatevalue,value); 
        } else if(value==2){             
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Threshold</th><th  width="50px">SpO2</th><th  width="50px">Perfusion Index</th><th  width="50px">Threshold</th><th  width="50px">Heartrate</th></tr>');
            getPatientAlertHistoryList(null,'observationsoxymeter',fromdatevalue,todatevalue,value);    
        } else if(value==3){
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Threshold</th><th  width="50px">Systolic</th><th  width="50px">Diastolic</th><th  width="50px">Threshold</th><th  width="50px">Heartrate</th></tr>');
            getPatientAlertHistoryList(null,'observationsbp',fromdatevalue,todatevalue,value); 
        } else if(value==4) {
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th width="50px">Temperature</th><th width="5px"></th><th width="5px" ></th><th width="5px"></th></tr>');
            getPatientAlertHistoryList(null,'observationstemp',fromdatevalue,todatevalue,value);   
        } else if(value==5) {
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th width="50px">FEV1 Value</th><th width="50px">PEF Value</th><th width="5px" ></th><th width="5px"></th></tr>');
            getPatientAlertHistoryList(null,'observationsspirometer',fromdatevalue,todatevalue,value);   
        } else if(value==6) {
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th width="50px"></th><th  width="50px">Threshold</th><th  width="50px">Glucose Level</th><th width="5px"></th><th width="5px"></th><th width="5px"></th></tr>');  
            getPatientAlertHistoryList(null,'observationsglucose',fromdatevalue,todatevalue,value);   
        } else {
            $('#vitaltableheader'+value).html('');
            $('#vitaltableheader'+value).html('<tr><th width="50px">TimeStamp</th><th colspan="7">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">NA</th><th  width="50px">NA</th><th width="50px">NA</th><th width="50px">NA</th></tr>'); 
            getPatientAlertHistoryList('000000000','observationsbp',fromdatevalue,todatevalue,value);
        }
    }
	
	function getCallWrapupActivities(){
       
       $.ajax({
       type: 'get',
       url: '/ccm/monthly-monitoring-call-wrap-up-activities/activities',
       success: function(response) {

        // console.log(response);
        // console.log(response.length);
        // console.log(response[0].activity);

        // alert('patientdetails function called');
       
         
        for (var j = 0; j < response.length ; j++) {
            var a = response[j].activity;
            var a2 = a.replace(/ /g, "_");
            a1 = a2.replace(/\//g, "_");
            var acttype = [];
       
           
            var classname = "RRclass "+a1;  
            // var result = a.split(" ").join("_");
            // var txt = '<div><input type="checkbox" name="acttype[]" id="acttype'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
            
            if(response[j].activity_type == 'Routine Response'){
             
                var txt = '<div><input type="checkbox" name="routineresponse['+a1+']" id="routineresponse_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #routinediv').append(txt);

            }else if(response[j].activity_type == 'Urgent/Emergent Response'){
                
                var txt = '<div><input type="checkbox" name="urgentemergentresponse['+a1+']" id="urgentemergentresponse_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #emergentdiv').append(txt);

            }else if(response[j].activity_type == 'Referral/Order Support'){

                var txt = '<div><input type="checkbox" name="referralordersupport['+a1+']" id="referralordersupport_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #referraldiv').append(txt);

            }else if(response[j].activity_type == 'Medication Support'){

               
                var txt = '<div><input type="checkbox" name="medicationsupport['+a1+']" id="medicationsupport_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #medicationdiv').append(txt);
                 
            }else if(response[j].activity_type == 'Verbal Education/Review with Patient'){

                var txt = '<div><input type="checkbox" name="verbaleducationreviewwithpatient['+a1+']" id="verbaleducationreviewwithpatient_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #verbaldiv').append(txt);

            }else if(response[j].activity_type == 'Mailed Documents'){  

                   
                var txt = '<div><input type="checkbox" name="maileddocuments['+a1+']" id="maileddocuments_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #maileddiv').append(txt);

            }else if(response[j].activity_type == 'Resource Support'){

                var txt = '<div><input type="checkbox" name="resourcesupport['+a1+']" id="resourcesupport_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #resourcediv').append(txt);

            }else if(response[j].activity_type == 'Veterans Services'){

                var txt = '<div><input type="checkbox" name="veteransservices['+a1+']" id="veteransservices_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
                $('form[name="callwrapup_form"] #veteransdiv').append(txt);   

            }else if(response[j].activity_type == 'Authorized CM Only'){ 

               
            var txt = '<div><input type="checkbox" name="authorizedcmonly['+a1+']" id="authorizedcmonly_'+a1+'" value="1" class="'+classname+'" formControlName="checkbox" /><span>'+a+'</span><br></div>';  
            $('form[name="callwrapup_form"] #authorizeddiv').append(txt);     
            }


           
            // $('form[name="callwrapup_form"] #routinediv').append('<div><input type="checkbox" name="interaction_with_Office_staff" id="interaction_with_Office_staff" value="1" class="RRclass interaction_with_Office_staff" formControlName="checkbox" /><span>"interaction_with_Office_staff"</span><br></div>');
            // $('form[name="callwrapup_form"] #routinediv').append(txt);      
        
        } 
      
       
       
       }
   }); 

}


    $(document).ready(function(){ 
		getCallWrapupActivities();
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


 <script type="text/javascript">
        var editor;
        var time = "<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>";
        var splitTime = time.split(":");
        var H = splitTime[0];
        var M = splitTime[1];
        var S = splitTime[2];
        $("#timer_start").val(time);
        $("#patient_time").val(time);
        $(document).ready(function(){ 
            util.stepWizard('tsf-wizard-2');
            util.getRelationBuild($("#patient_id").val());
            var crnoteval1= $('#call_preparation_condition_requirnment_notes').val();
            $("input[name='billable']").val(1);
			
            if(crnoteval1==' ' || crnoteval1=='undefined') {
            $('#call_preparation_note').css('display','none');
            } else {
            $('#call_preparation_note').css('display','block');
            }
            var crnoteval2= $('#call_preparation_report_requirnment_notes').val();
            if(crnoteval2=='' || crnoteval2=='undefined') {
                $('#call_preparation_requirnment').css('display','none');
            } else {
                $('#call_preparation_requirnment').css('display','block');
            }
            var crnoteval3= $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_condition_requirnment_notes').val();
            if(crnoteval3==' ' || crnoteval3=='undefined') {
                $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display','none');
            } else {
                $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display','block');
            }  
            var crnoteval4= $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_report_requirnment_notes').val();
            if(crnoteval4=='' || crnoteval4=='undefined') {
                $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display','none');
            } else {
                $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display','block');
            }
            
            $("#start").hide();
            $("#pause").show();
            //following code updated by pranali on 14June2021 -- condition removed for timer
            $("#time-container").val(AppStopwatch.startClock);
            ccmcpdcommonJS.init();
            ccmMonthlyMonitoring.init();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 
            editor = new $.fn.dataTable.Editor( {
                ajax: "{{ route('update.call.wrap.up.inline', $patient_id )}}",
                table: "#callwrap-list",
                fields: [
                    {
                        label: "CareManager Notes:",
                        name: "notes"
                    }, 
                    {
                        label: "Action Taken:",
                        name: "action_taken"
                    }, 
                ],
            });
            
            $('#callwrap-list').on( 'click', 'tbody td:not(:first-child)', function () { 
                editor.inline(this, {
                    buttons: {
                        label: '&gt;', fn: function () {
                            this.submit(); 
                        }
                    }
                });
            });
            // var table =  $('#callwrap-list');
            // table.DataTable().ajax.reload();

            //general-questions.blade.php starts  here
            $('.firsttbox').click();
            $('#RenderGeneralQuestion90').click();
            $( "#genquestionselection" ).change(function() {
                <?php
                foreach($dtsteps as $val){
                    $id = $val['id'];
                    echo "$('#general_question_form_$id').hide();";
                }
                ?>
                $("#general_question_form_"+this.value).show();
            });
            
            // dropdown change go on top
                $(".bottom").change(function(){ 
                    var scrollPos = $("#success-alert").offset().top;
                    $(window).scrollTop(scrollPos);
                    <?php
                    foreach($dtsteps as $val){
                        $id = $val['id'];
                        echo "$('#general_question_form_$id').hide();";
                    }
                    ?>
                    $("#genquestionselection").val(this.value);
                    $("#general_question_form_"+this.value).show();
                });
                // dropdown change go on top
                <?php
                $i = 0;
                foreach ($genQuestion as $values) {
                    
                    $editGq = json_decode($values['template']);     
                    echo "$('#RenderGeneralQuestion$i').click();";  
                    
                    $i++;
                }       
                ?>
                
                //$('#RenderGeneralQuestion0').click();
                //$('#RenderGeneralQuestion1').click();
                $("#genquestionselection").find('option:contains("General Question")').attr('selected','selected').trigger('change');
                //$("#genquestionselection :nth(1)").prop("selected","selected").change();
				//alert("call form billable "+$("#callstatus_form input[name='billable']").val());
        });
        var assetBaseUrl = "{{ asset('') }}";
        var copy_img = "assets/images/copy_icon.png";
        var excel_img = "assets/images/excel_icon.png";
        var pdf_img = "assets/images/pdf_icon.png";
        var csv_img = "assets/images/csv_icon.png";
    
        var dt=$('#callwrap-list').DataTable( {
            dom: '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
            buttons : [
                {
                    extend:    'copyHtml5',
                    text:      '<img src="'+assetBaseUrl+copy_img+'" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
                },
                {
                    extend:    'excelHtml5',
                    text:      '<img src="'+assetBaseUrl+excel_img+'" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                    titleAttr: 'Excel'
                },
                {
                    extend:    'csvHtml5',
                    text:      '<img src="'+assetBaseUrl+csv_img+'" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                    titleAttr: 'CSV'
                },
                {
                    extend:    'pdfHtml5',
                    text:      '<img src="'+assetBaseUrl+pdf_img+'" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                    titleAttr: 'PDF'
                }
            ],
            ajax: "{{ route('monthly.monitoring.call.wrap.up', $patient_id ) }}",
            columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'topic', name: 'topic'},
                        {data: 'notes', name: 'notes'},
                        {data: 'action_taken', name: 'action_taken'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [ 0, 'asc' ],
            "columnDefs": [{
                "targets": '_all',
                "defaultContent": ""
            }]
        });
           
        $('#answer').change(function(){
            if(this.value == 3){
                $("#txt-msg").show();
                $("#save_schedule_call").html('Send Text Message / Schedule Call');
            } else {
                $("#txt-msg").hide();
                $("#save_schedule_call").html('Schedule Call');
            }
        });
        
        function ajaxRenderTree(obj, label, id, count, tree_key, seq, tempid) {
            var tree = JSON.stringify(obj);
            var treeObj = JSON.parse(tree);
            var parentId = $("#" + id + '_' + tree_key + '_' +count).closest('.mb-4').attr('id');
            if ($("#" + id + '_' + tree_key + '_' +count).is(':checked')) {
                $("#" + id + '_' + tree_key + '_' +count).closest('#' + parentId)
                .find('input[type=radio]').not($("#" + id + '_' + tree_key + '_' +count))
                .prop('checked', false);
            }
            var treelength = (id + '' + tree_key).length;
            $("#question" + tree_key).find('.' + treelength).remove();
            if (treeObj[count].hasOwnProperty('qs')) {
                const propOwn = Object.getOwnPropertyNames(treeObj[count].qs);
                var qtncount = propOwn.length;
                for (var j = 1; j <= 15; j++) {
                    if ((treeObj[count].qs).hasOwnProperty(j)){
                        var ids = id + '' + j;
                        var tkey2 = tree_key + '' + ids;
                        var qs_label = label.replace("[val]", "");
                        var question_label = qs_label + "[qs][" + j + "][q]";
                        var optkey = ids + '' + tree_key;
                        if ((treeObj[count].qs[j]).hasOwnProperty('opt')) {
                            var cls = 'radioVal';
                        } else {
                            var cls = 'radionotval';
                        }
                        if ((id).length == 2) {
                            var tkey = tree_key;
                        } else {
                            var tkey = tree_key + '' + ids.slice(0, -1);
                        }
                        var qtn = treeObj[count].qs[j].q;
                        var qtn_n = qtn.replace("'","&apos;");  
                        var qtn_n_val = qtn_n.replace( /[\r\n]+/gm, "" );   
                        seq = seq + 1;
                        $("#question" + tkey).append('<div class="mb-4 '+cls+' '+treelength+'" id="'+tree_key+'general_question'+ids+'"><label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="'+question_label+'" value="'+qtn_n_val+'">'+qtn_n+'</label><input type="hidden" name="sq['+tempid+']['+seq+']" value="'+seq+'"><div class="d-inline-flex mb-2 col-md-12" id="options'+optkey+'"></div><p class="message" style="color:red"></p><div id="question'+tkey2+'"></div></div>');
                        if ((treeObj[count].qs[j]).hasOwnProperty('opt')) {
                            var obj1 = JSON.stringify(treeObj[count].qs[j].opt);
                            var optcount = Object.getOwnPropertyNames(treeObj[count].qs[j].opt).length;
                            for (var i = 1; i <= 15; i++) {
                                if((treeObj[count].qs[j].opt).hasOwnProperty(i)){
                                    optlabelkey = ids + '_' + tree_key+'_'+i;
                                    var op_label = question_label.replace("[q]", "");
                                    var option_label = op_label + '[opt][' + i + '][val]';
                                    var hidden_opt_id = 'opt' + ids + '' + i + '' + tree_key;
                                    var treeobj_i_val = treeObj[count].qs[j].opt[i].val;
                                    var treeobj_i_val_mod = treeobj_i_val.replace("'","&apos;");
                                    var obj1_n = obj1.replace("'","&apos;");

                                    if (treeObj[count].qs[j].opt[i].val == 'default yes') {
                                        var treeobjvar = treeObj[count].qs[j].opt;
                                        $("#options" + optkey).append("<input type='text' class='form-control col-md-5' name='" + option_label + "' id='" + optlabelkey + "' value=''><label class='radio radio-primary mr-3'><input type='hidden' id='" + hidden_opt_id + "' value='" + option_label + "'></label><input type='radio' style='display:none' checked>");
                                        ajaxRenderTree1(treeObj[count].qs[j].opt, ids, j, i, tree_key, seq, tempid);
                                    } else {
                                        if(treeObj[count].qs[j].AF == '1'){
                                            $("#options" + optkey).append("<label class='checkbox  checkbox-primary mr-3'><input type='hidden' id='" + hidden_opt_id + "' value='" + option_label + "'><input type='checkbox' name='" + option_label + "' id='" + optlabelkey + "' value='" + treeobj_i_val_mod + "' onchange='ajaxRenderTree1(" + obj1_n + "," + ids + "," + j + "," + i + "," + tree_key + "," + seq + "," + tempid + ")' ><span>" + treeobj_i_val_mod + "</span><span class='checkmark'></span></label>");
                                        } else {
                                            $("#options" + optkey).append("<label class='radio radio-primary mr-3'><input type='hidden' id='" + hidden_opt_id + "' value='" + option_label + "'><input type='radio' name='" + option_label + "' id='" + optlabelkey + "' value='" + treeobj_i_val_mod + "' onchange='ajaxRenderTree1(" + obj1_n + "," + ids + "," + j + "," + i + "," + tree_key + "," + seq + "," + tempid + ")' ><span>" + treeobj_i_val_mod + "</span><span class='checkmark'></span></label>");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        function ajaxRenderTree1(obj, id, count, objCount, tree_key, seq, tempid) {
            var label = $("#opt" + id + '' + objCount + '' + tree_key).val();
            ajaxRenderTree(obj, label, id, objCount, tree_key, seq, tempid);
        }

        function checkQuestion(obj, i) { 
            var tree = JSON.stringify(obj);
            var treeobj = JSON.parse(tree);
            for (j = 1; j < 10; j++) {
                if ((treeobj.qs.opt).hasOwnProperty(j)) {
                    var prnt = $('input[value="' + (treeobj.qs.q).replace( /[\r\n]+/gm, "" ) + '"]').parents('.mb-4').attr('id');
                    $('#' + prnt).find('input:radio[value="' + treeobj.qs.opt[j].val + '"], input:checkbox[value="' + treeobj.qs.opt[j].val + '"]').attr('checked', true).change();
                    if($('#' + prnt).find('input[type=text]').attr('id')){
                        var textid = $('#' + prnt).find('input[type=text]').attr('id');
                        $('#'+textid).val(treeobj.qs.opt[j].val);
                    }
                    var obj1 = treeobj.qs.opt;
                    renderEditquestion(obj1, j, i);
                }
            }
        }

        function renderEditquestion(obj1, i, nct) {
            var tree = JSON.stringify(obj1);
            var treeobj = JSON.parse(tree);
            if(nct == '0'){
                //alert(treeobj[i]);
            }
            var l = 1;
            if (treeobj[i].hasOwnProperty('qs')) {
                Object.keys(treeobj[i].qs).forEach(function(key) {
                    if ((treeobj[i].qs[key]).hasOwnProperty('opt')) {
                        Object.keys(treeobj[i].qs[key].opt).forEach(function(j) {
                            if ((treeobj[i].qs[key].opt).hasOwnProperty(j)) {
                               if(nct == 0){
                                  // alert((treeobj[i].qs[key].q).replace( /[\r\n]+/gm, "" )); 
                                }
                                var qval = (treeobj[i].qs[key].q).replace( /[\r\n]+/gm, "" );
                                var prnt = $('input[value="' + qval + '"]').parents('.mb-4').attr('id');
                                if(prnt != undefined){
                                    var string1= prnt; 
                                    var result = string1.substring(string1.indexOf('general_question') + 1);
                                    var strValue = result;
                                    strValue = strValue.replace('general_question','').replace('eneral_question','');
                                    strValue1 = string1.replace('general_question'+strValue,'');
                                    if($('#options'+strValue+''+strValue1).find('input[type=text]').attr('id')){
                                        var inputbox = $('#options'+strValue+''+strValue1).find('input[type=text]').attr('id');
                                        $("#"+inputbox).val(treeobj[i].qs[key].opt[j].val);
                                    }
                                }
                                if(nct == 0){
                                   // alert(qval);
                                   // alert($('input[value="' + qval + '"]').attr('type'));
                                }
                                $('#' + prnt).find('input:radio[value="' + treeobj[i].qs[key].opt[j].val + '"], input:checkbox[value="' + treeobj[i].qs[key].opt[j].val + '"]').attr('checked', true).change();
                                renderEditquestion(treeobj[i].qs[key].opt, j, nct);
                            }
                        });
                    }
                });
            }
        }

        jQuery('#allergies_id').click(function(){               
            $('.modal-title').html('Allergies');
            jQuery('.ccmPatientData').hide();
            jQuery('#'+$(this).attr('target')).show();
        });
       
        // call preparation checkbox
        $("input.CRclass").click(function () {
            if($("form[name='research_follow_up_preparation_followup_form'] input[name='condition_requirnment1']").prop("checked") == true || $("form[name='research_follow_up_preparation_followup_form'] input[name='condition_requirnment2']").prop("checked") == true || $("form[name='research_follow_up_preparation_followup_form'] input[name='condition_requirnment3']").prop("checked") == true){
                $("form[name='research_follow_up_preparation_followup_form'] input[name='condition_requirnment4']").prop('checked', false);        
                $('#research_follow_up_note').css('display','block');
            } else {       
                $("form[name='research_follow_up_preparation_followup_form'] textarea[name='condition_requirnment_notes']").val('');
                $('#research_follow_up_note').css('display','none');
            }   
            if($("form[name='call_preparation_preparation_followup_form'] input[name='condition_requirnment1']").prop("checked") == true || $("form[name='call_preparation_preparation_followup_form'] input[name='condition_requirnment2']").prop("checked") == true || $("form[name='call_preparation_preparation_followup_form'] input[name='condition_requirnment3']").prop("checked") == true){
                $("form[name='call_preparation_preparation_followup_form'] input[name='condition_requirnment4']").prop('checked', false);     
                $("#call_preparation_note").css('display','block');
            } else {       
                $("form[name='call_preparation_preparation_followup_form'] textarea[name='condition_requirnment_notes']").val('');
                $("#call_preparation_note").css('display','none');
            }   
        });

        $("input[name='condition_requirnment4']").click(function () {
            $("input[name='condition_requirnment1']").prop('checked', false); 
            $("input[name='condition_requirnment2']").prop('checked', false); 
            $("input[name='condition_requirnment3']").prop('checked', false); 
            $("textarea[name='condition_requirnment_notes']").val('');
            $(".notes").hide();
        });

        $("input.RRclass").click(function () {
            if($("form[name='research_follow_up_preparation_followup_form'] input[name='report_requirnment1']").prop("checked") == true || $("form[name='research_follow_up_preparation_followup_form'] input[name='report_requirnment2']").prop("checked") == true ||$("form[name='research_follow_up_preparation_followup_form'] input[name='report_requirnment4']").prop("checked") == true || $("form[name='research_follow_up_preparation_followup_form'] input[name='report_requirnment5']").prop("checked") == true ){
                $("form[name='research_follow_up_preparation_followup_form'] input[name='report_requirnment3']").prop('checked', false);        
                $("#research_follow_up_requirnment").show();
            } else {        
                $("form[name='research_follow_up_preparation_followup_form'] textarea[name='report_requirnment_notes']").val('');
                $("#research_follow_up_requirnment").hide();
            }
            if($("form[name='call_preparation_preparation_followup_form'] input[name='report_requirnment1']").prop("checked") == true || $("form[name='call_preparation_preparation_followup_form'] input[name='report_requirnment2']").prop("checked") == true ||$("form[name='call_preparation_preparation_followup_form'] input[name='report_requirnment4']").prop("checked") == true || $("form[name='call_preparation_preparation_followup_form'] input[name='report_requirnment5']").prop("checked") == true){
                $("form[name='call_preparation_preparation_followup_form'] input[name='report_requirnment3']").prop('checked', false);        
                $("#call_preparation_requirnment").show();
            } else {        
                $("form[name='call_preparation_preparation_followup_form'] textarea[name='report_requirnment_notes']").val('');
                $("#call_preparation_requirnment").hide();
            }
        });

        $("input[name='report_requirnment3']").click(function () {
            $("input[name='report_requirnment1']").prop('checked', false); 
            $("input[name='report_requirnment2']").prop('checked', false);
            $("input[name='report_requirnment4']").prop('checked', false); 
            $("input[name='report_requirnment5']").prop('checked', false); 
            $("textarea[name='report_requirnment_notes']").val('');
            $(".rep_req_note").hide();
        });

        function saveGeneralQuestions(code){
            $("#time-container").val(AppStopwatch.pauseClock);
            var timer_start = $("#timer_start").val();
            var timer_paused = $("#time-container").text().replace(/ /g, '');
            $("input[name='start_time']").val(timer_start);
            $("input[name='end_time']").val(timer_paused);
            $("#timer_end").val(timer_paused);
            $("#time-container").val(AppStopwatch.startClock);
            var stage_id = $("#stage_id").val();//= 11;
            var valid = true;
            $('#generalQue'+code).attr("disabled", true);
            var check_singal_question = 0;
            
            $("form[name='general_question_form_"+code+"'] div.radioVal ").each(function() {
                check_singal_question = $('input:radio:checked, input:checkbox:checked', this).length;
                if(check_singal_question > 0){
                    return false;
                }
            });
           
            $("form[name='general_question_form_"+code+"'] div.radioVal ").each(function() {
                if ($('input:radio, input:checkbox', this).length > 0) {
                    if (!$('input:radio:checked, input:checkbox:checked', this).length > 0) {
                        if(check_singal_question > 0){
                            if(confirm("You have not answered all of the questions.  Do you want to save anyway?")){
                                valid = true;
                                return false;
                            } else {
                                $('p.message', this).text("Please select at least one!");
                                valid = false;
                                return false;
                            }
                        } else{
                            $('p.message', this).text("Please select at least one!");
                            valid = false;
                        }
                    } else {
                        $('p.message', this).text("");
                    }
                }
            });
            
            $("form[name='general_question_form_"+code+"'] div#in-pain").each(function() {    
                if($('textarea',this).val()) {
                    var str = $('textarea',this).val(); 
                    var regex = new RegExp("^[a-zA-Z0-9- . , #]+$");
                    if (regex.test(str)) {
                        $('p.txtmsg', this).text("");
                    } else{  
                        // $('p.txtmsg', this).text("Invalid text format!");
                    }
                } 
            });  
       
            if (valid == true) {
                $.ajax({
                    type: 'post',
                    url: '/ccm/saveGeneralQuestion',
                    data: $("form#general_question_form_"+code).serialize() + '&timer_start=' + timer_start + '&timer_paused=' + timer_paused + '&stage_id=' + stage_id,
                    success: function(response) {
                        $("#timer_start").val(timer_paused);
                        $('.general_success').show();
                        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
                        setTimeout(function(){ $('.general_success').fadeOut(); }, 3000);
                        $('.nexttab').show();
                        var table =  $('#callwrap-list');
                        table.DataTable().ajax.reload();
                        $('#generalQue'+code).attr("disabled", false);
                    },
                });
            } else {
                setTimeout(function(){ $('#generalQue'+code).attr("disabled", false); }, 3000);
            }
            return valid;
        }
            
        function nexttab() {             
            $('#ccm-call-close-icon-tab').click();
        }

        $('body').on('click', '.delete_callwrap', function () {
            var id = $(this).data('id');
            if(confirm("Are you sure you want to Delete this notes")){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#time-container").val(AppStopwatch.pauseClock);
                var timer_start = $("#timer_start").val();
                var timer_paused = $("#time-container").text().replace(/ /g, '');
                $("input[name='start_time']").val(timer_start);
                $("input[name='end_time']").val(timer_paused);
                $("#timer_end").val(timer_paused);
                $("#time-container").val(AppStopwatch.startClock);
                var url = '/ccm/delete-callwrapup-notes/'+id;
                $.ajax({
                    type   : 'get',
                    url    : url,
                    data   : {"id": id, "timer_start": timer_start, "timer_paused": timer_paused},
                    success: function(response) {
                        util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
                        $("#time-container").val(AppStopwatch.pauseClock);
                        var table =  $('#callwrap-list');
                        table.DataTable().ajax.reload();
                        $(".last_time_spend").html(response);
                        $("#time-container").html(response);
                    }
                });
            } else {
                return false;  
            }
        });

    //   alert("calling from ccm patientdetails"); 
        // var d=$("form[name='care_plan_form'] #diagnosis_id").val();
        // alert(d);  
        
    //   var url  = window.location.href; 
    //   parts = url.split("/"),
    //   patientid = parts[parts.length-1];
        patientid = $("#patient_id").val();
        util.getDiagnosisCount(patientid);
        util.getDistinctDiagnosisCountForBubble(patientid);    
        
    var getFollowupList = function(patient_id=null,module_id=null) {
        var columns =  [ 
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data:'task_notes',name:'task_notes'},
            {data:'task',name:'task'},
            {data:'notes',name:'notes',render: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        return full['notes'] + '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+full['id']+'" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
                    }else{ return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+full['id']+'" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>';}
                }
            },
            {data: 'tt',type: 'date-dd-mm-yyyy',
                "render":function (value) {
                    if (value === null) return "";
                    return util.viewsDateFormat(value);
                }
            }, 
            {data: 'task_time', name:'task_time',
                render:function (data, type, full, meta) {
                    if(data!='' && data!='NULL' && data!=undefined){
                        return full['task_time'];
                    }else{
                        return '';
                    }
                } 
            },  
            {data: 'action', name: 'action', orderable: false, searchable: false}, 
            {data:'task_completed_at',type: 'date-dd-mm-yyyy h:i:s', name: 'task_completed_at',"render":function (value) {
                if (value === null) return "";
                    return util.viewsDateFormat(value);
                }
            }, 
            {data:null,
                render:function (data, type, full, meta) {
                    if(data!='' && data!='NULL' && data!=undefined){
                        return full['f_name'] +' '+ full['l_name'];
                    }else{
                        return ''; 
                    }
                }
            },
        ];
        var url = "/ccm/patient-followup-task/"+patient_id+'/'+module_id+"/followuplist";
        util.renderDataTable('task-list', url, columns, "{{ asset('') }}"); 
    }

    $( document ).ready(function() {    
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    }); 

    var inc_followup_task = 0; 
    $('#add_followup_task').click(function () {
        var followupTaskElement = $('#followupTaskDrpdwn_0').html();
        inc_followup_task++;
        $('#append_followup_task').append('<div class="btn_remove" id="btn_remove_followup_task_' + inc_followup_task + '"><div class="col-md-12"><div class="row ml-1"><div class="col-md-4 form-group">@text("task_name[]", ["id"=>"task_name_' + inc_followup_task + '","class"=>"form-element form-control mt-2","placeholder"=>"Task"])</div><div class="col-md-4 form-group selects" id="followupTaskDrpdwn_' + inc_followup_task + '"><div class="invalid-feedback"></div></div>@hidden("selected_task_name[]", ["id"=>"selected_task_name_' + inc_followup_task + '","class"=>"form-element form-control mt-2"])<div class="col-md-4 form-group"><label class="radio radio-primary col-md-4 float-left"><input type="radio" id="scheduled_' + inc_followup_task +'" class="status_flag" name="status_flag['+ inc_followup_task +']" value="0" formControlName="radio" checked><span>To be Scheduled</span><span class="checkmark"></span></label><label class="radio radio-primary col-md-4 float-left"><input type="radio" id="completed_' + inc_followup_task +'" class="status_flag" name="status_flag['+ inc_followup_task +']" value="1" formControlName="radio"><span>Completed</span><span class="checkmark"></span></label><div class="invalid-feedback"></div></div></div><div class="row ml-1"><div class="col-md-6 form-group"><textarea name="notes[]" class="forms-element form-control" id="notes_' + inc_followup_task + '" placeholder="Notes"></textarea><div class="invalid-feedback"></div></div><div class="col-md-2 form-group">@date("task_date[]",["id"=>"task_date_' + inc_followup_task + '","class"=>"dateField"])</div></div></div><i class="remove-icons i-Remove float-right mb-3" id="remove_followup_task_' + inc_followup_task + '" title="Remove Follow-up Task"></i><hr></div>');
        $('#followupTaskDrpdwn_' + inc_followup_task).append(followupTaskElement);
    });
    $(document).on('change','.selects select', function() { 
        var nam_val = $(this).find("option:selected").text(); 
        var nearesDiv = $(this).closest('div').attr("id");
        var split = nearesDiv.split('_');
        $('#selected_task_name_'+ split[1]).val(nam_val);       
    }); 

    function convert(str) {
      var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join("-");
    }

    $(document).on('click','.status_flag',function() {   
        var checked = $(this).val();
        var nearesDiv = $(this).attr("id");
        var split = nearesDiv.split('_');
        if(checked=='0'){ 
            $('#task_date_'+ split[1]).val('');
            $('#task_date_'+ split[1]).prop('readonly', false);
        }else{
            var date = new Date(),
            y = date.getFullYear(), m = date.getMonth(), d=date.getDate();
            var today_date = new Date(y, m, d);
            $('#task_date_'+ split[1]).val(convert(today_date));
            $('#task_date_'+ split[1]).prop('readonly', true); 
        }
    });


    function add_score(value, question, id, input_type){
       var drop_vale = 0;
       var text_value = 0;
       var radio_value = 0;
       var check_value = 0;
       var textarea_value = 0;
      // alert(question);
      //debugger;
        if(input_type == 'check'){
            var old = $("#"+input_type+'-score-'+question+''+id).val();
            if(event.srcElement.checked){
                var newval = parseInt(old) + parseInt(value);
            }else{
                var newval = parseInt(old) - parseInt(value);
            }
            $("#"+input_type+'-score-'+question+''+id).val(newval);
            
        }else if(input_type == 'drop'){
            $("#"+input_type+'-score-'+question+''+id).val(value[value.selectedIndex].id);
        }else{
            
            $("#"+input_type+'-score-'+question+''+id).val(value);
        }
        //alert(question+''+id);
        /*if($("#drop-score-"+question).val() != undefined){
            drop_vale = $("#drop-score-"+question).val();
        }
        if($("#text-score-"+question).val() != undefined){
            text_value = $("#text-score-"+question).val();
        }
        if($("#radio-score-"+question).val() != undefined){
            radio_value = $("#radio-score-"+question).val();
        }
        if($("#check-score-"+question).val() != undefined){
            check_value = $("#check-score-"+question).val();
        }
        if($("#textarea-score-"+question).val() != undefined){
            textarea_value = $("#textarea-score-"+question).val();
        }*/
        //alert(id);
        //var textstr = question.replace(/\d+/g, '');
        var number = parseInt(id);
        question = question+''+String(number).slice(0, 1);
      //  alert(String(number).slice(0, 1));

        $('.drop-'+question.slice(0,-1)).each(function(){
            if(this.value != undefined){
                drop_vale = parseInt(drop_vale) + parseInt(this.value);
            }
        });

        $('.text-'+question.slice(0,-1)).each(function(){
            if(this.value != undefined){
                text_value = parseInt(text_value) + parseInt(this.value);
            }
        });

        $('.radio-'+question.slice(0,-1)).each(function(){
            if(this.value != undefined){
                radio_value = parseInt(radio_value) + parseInt(this.value);
            }
        });

        $('.check-'+question.slice(0,-1)).each(function(){
            if(this.value != undefined){
                check_value = parseInt(check_value) + parseInt(this.value);
            }
        });

        $('.textarea-'+question.slice(0,-1)).each(function(){
            if(this.value != undefined){
                textarea_value = parseInt(textarea_value) + parseInt(this.value);
            }
        });
        //$('.rades').map(function(){ alert(this.value);}).get()
        var final_score = parseInt(drop_vale) + parseInt(text_value) + parseInt(radio_value) + parseInt(check_value) + parseInt(textarea_value) ;
       // alert(question);
        $("#score-"+question.slice(0,-1)).val(final_score);
        $("#total-"+question.slice(0,-1)).html(final_score);
    }
	
	
    </script>



    <!-- reading js -->
    
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection
<?php } else { ?>
    @section('main-content')
    <div>@include("Theme::patient-doesnt-exist")</div>
    @endsection
<?php } ?>
