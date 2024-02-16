@extends('Theme::layouts_2.to-do-master') 
@section('main-content')
<div id="app"></div>
<div class="row mb-4" id="call-sub-steps"> 
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 ">  
                    <div class="card text-left">
                        <div class="card-body" >
                           <!-- <h4 class="card-title mb-3">Practices</h4> -->
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="practice-icon-tab" data-toggle="tab" href="#practice" role="tab" aria-controls="practice" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Scheduler</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="practice-group-icon-tab" data-toggle="tab" href="#practice-group" role="tab" aria-controls="practice-group" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i>Reports Scheduler</a>
                                </li>
                            </ul> 
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="practice" role="tabpanel" aria-labelledby="practice-icon-tab">
                                    @include('Scheduler::scheduler-list')
                                </div>
                                <div class="tab-pane" id="practice-group" role="tabpanel" aria-labelledby="practice-group-icon-tab">
                                    @include('Scheduler::report-scheduler') 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<!-- <script src="{{asset('assets/js/tooltip.script.js')}}"></script> -->
<script type="text/javascript">
// /ReportScheduler-list
var getSchedulerlisting = function () {
    var columns = [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        },
        // {data:'activity', name:'activity'},
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    activityname = full['activity'];
                    return activityname;
                } else {
                    return '';
                } 
            },
            orderable: false
        },

        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    practice_grpname = full['practice_name'];
                    return practice_grpname;
                } else {
                    return '';
                } 
            },
            orderable: false
        },
        

        // {data: "modules", name: "modules", orderable: false },
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    modules = full['modules'];
                    return modules;
                } else {
                    return '';
                }
            },
            orderable: false
        },
        // {data:'day_of_execution', name:'day_of_execution'},
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    date_of_execution = full['sdate_of_execution'];
                    return date_of_execution;
                } else {
                    return '';
                }
            },
            orderable: false
        },

        // {data: 'time_of_execution',name:'time_of_execution'},
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    day_of_execution = full['day_of_execution'];
                    return day_of_execution;
                } else {
                    return '';
                }
            },
            orderable: false
        },

        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    time_of_execution = full['stime_of_execution'];
                    return time_of_execution;
                } else {
                    return '';
                }
            },
            orderable: false
        },
        {data: 'operation', name: 'operation', orderable: false },
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    l_name = full['l_name'];
                    if (full['l_name'] == null && full['f_name'] == null) {
                        l_name = '';
                        return '';
                    } else {
                        return full['f_name'] + ' ' + l_name;
                    }
                } else {
                    return '';
                }
            },
            orderable: false
        },

        // {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
        //    if (value === null) return "";
        //         return util.viewsDateFormatWithTime(value); 
        //     }
        // },

        // {data:'comments', name:'comments'}, 
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    comments = full['comments'];
                    return comments;
                } else {
                    return '';
                }
            },
            orderable: false
        },

        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ];
    var table = util.renderDataTable('scheduler-list', "{{ route('scheduler.list') }}", columns, "{{ asset('') }}");
}; 

var getReportSchedulerlisting = function () {
    getPatientData();
    var columns = [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        },
        // {data:'activity', name:'activity'},
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    reportname = full['display_name'];
                    return reportname;
                } else {
                    return '';
                } 
            },
            orderable: false
        },
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    l_name = full['l_name'];
                    if (full['l_name'] == null && full['f_name'] == null) {
                        l_name = '';
                        return '';
                    } else {
                        return full['f_name'] + ' ' + l_name;
                    }
                } else {
                    return '';
                }
            },
            orderable: false
        }, 

        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    frequency = full['frequency'];
                    return frequency;
                } else {
                    return '';
                } 
            },
            orderable: false
        },
        
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    report_format = full['report_format'];
                    return report_format;
                } else {
                    return '';
                }
            },
            orderable: false
        },
        // {data:'day_of_execution', name:'day_of_execution'},
        {data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    date_of_execution = full['sdate_of_execution'];
                    return date_of_execution;
                } else {                    
                    return '';
                }
            },
            orderable: false
        },

        // {data: 'time_of_execution',name:'time_of_execution'},
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    day_of_execution = full['day_of_execution'];
                    return day_of_execution;
                } else {
                    return '';
                }
            },
            orderable: false
        },

        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    time_of_execution = full['stime_of_execution'];
                    return time_of_execution;
                } else {
                    return '';
                }
            },
            orderable: false
        },
        
        {
            data: null,
            mRender: function (data, type, full, meta) {
                if (data != '' && data != 'NULL' && data != undefined) {
                    l_name = full['l_name'];
                    if (full['l_name'] == null && full['f_name'] == null) {
                        l_name = '';
                        return '';
                    } else {
                        return full['f_name'] + ' ' + l_name;
                    }
                } else {
                    return '';
                }
            },
            orderable: false
        },

        {data: 'updated_at',type:'date-mmm-dd-yyyy',name:'updated_at',"render":function(value){ 
                if(value===null) return "";
                    return moment(value).format('MM-DD-YYYY HH:mm:ss'); 
                } 
            },

        // {
        //     data: null,
        //     mRender: function (data, type, full, meta) {
        //         if (data != '' && data != 'NULL' && data != undefined) {
        //             updated_at = full['updated_at'];
        //             if (full['updated_at'] == null ) {
        //                 updated_at = '';
        //                 return '';
        //             } else {
        //                 return updated_at;
        //             }
        //         } else {
        //             return '';
        //         }
        //     },
        //     orderable: false
        // },

        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ];
    var table = util.renderDataTable('reportscheduler-list', "{{ route('Reportscheduler.list') }}", columns, "{{ asset('') }}");

};


	$(document).ready(function () {
        var pURL = $(location).attr("href");
        //alert(pURL);
        var pathArray = window.location.pathname.split('/');
        //alert(pathArray);
        if(pathArray == ",org,reportScheduler"){
        	//alert("working");
        	var element = document.getElementById('practice-icon-tab');
        element.classList.remove('active');

        var element = document.getElementById('practice-group-icon-tab');
        element.classList.add('active');

        var element = document.getElementById('practice');
        element.classList.remove('show' , 'active');

        var element = document.getElementById('practice-group');
        element.classList.add('show' , 'active');
        }

        scheduler.init();
	    getSchedulerlisting();	    
	    reportScheduler.init();
        getReportSchedulerlisting(); 
	    util.getToDoListData(0, {{ getPageModuleName() }});
        util.getAssignPatientListData(0, 0);
    });
    
    //Multiple Dropdown Select
    $('.multiDrop').on('click', function (event) { 
        event.stopPropagation();
        $(this).next('ul').slideToggle(); 
    });
    
    $("form[name='AddReportSchedulerForm'] #frequency").change(function () {
        // alert($(this).val());
        if ($(this).val() == "weekly"){
            $("form[name='AddReportSchedulerForm'] #week").show();
            $("form[name='AddReportSchedulerForm'] #days").hide();
            $("form[name='AddReportSchedulerForm'] #month").hide(); 
            // $("form[name='AddReportSchedulerForm'] #years").hide();
        }else if ($(this).val() == "monthly") { 
            $("form[name='AddReportSchedulerForm'] #month").hide();
            $("form[name='AddReportSchedulerForm'] #days").show();
            $("form[name='AddReportSchedulerForm'] #week").hide();
            // $("form[name='AddReportSchedulerForm'] #years").hide();
        }else if ($(this).val() == "yearly") {
            // $("form[name='AddReportSchedulerForm'] #years").show();
            $("form[name='AddReportSchedulerForm'] #days").show();
            $("form[name='AddReportSchedulerForm'] #month").show();
            $("form[name='AddReportSchedulerForm'] #week").hide();

        } 
        else { 
            $("form[name='AddReportSchedulerForm'] #days").hide();
            $("form[name='AddReportSchedulerForm'] #month").hide();
            $("form[name='AddReportSchedulerForm'] #week").hide();
            // $("form[name='AddReportSchedulerForm'] #years").hide();
        } 
    });

    $(document).on('click', function () {
        if (!$(event.target).closest('.wrapMulDrop').length) {
            $('.wrapMulDrop ul').slideUp();
            // $('#drpdwn_task_notes').hide(); //hide notes 
        }
    }); 
    
    $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
        var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
        // alert(x);
        if (x != "") {
            $('.multiDrop').html(x + " " + "selected");
        } else if (x < 1) {
            $('.multiDrop').html('Select Responsibility<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }
    });

  
	$("#activity").change(function(){
	    $("#selected_activity_details1").html('');
	    $("#selected_activity_details2").html('');
	    $("#selected_activity_details3").html('');
	    $("#selected_activity_details4").html('');
	    $('#dynamic_practice_grp').html('');
	    $('#dynamic_timerequired').html('');
	    $('#dynamic_practices').html('');
	    var id = $(this).val();
	        $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	        }); 
	    if(id!=''){
	        $.ajax({ 
	         type:'get',
	         url:'/org/ajax/populateActivitySchedulerForm' + "/" + id, 
	         
	            success: function (data) { 
	                var activity = data.static['activity'];
	                var activity_type = data.static['activity_type'];
	                if(data.static['timer_type']==2){
	                    var timer_type = 'Standard';
	                }else if(data.static['timer_type']==3){
	                    var timer_type = 'Backend';
	                } 
	                var default_time = data.static['default_time'];
	                $("#selected_activity_details1").html("<b>Activity Type :</b>" + activity_type);
	                $("#selected_activity_details2").html("<b>Activity :</b> " + activity);
	                $("#selected_activity_details3").html("<b>Timer Type :</b> " + timer_type);
	                
	                if(data.static['paramdata']=== undefined || data.static['paramdata']==='undefined'){
	                    $("#selected_activity_details4").html("<b>Default time :</b> " + default_time);
	                }
	                var paramdata_count = data.static['paramdata'].length;  
	                // alert(paramdata_count +'paramdata_count');
	                for(parameterscnt = 0; parameterscnt < paramdata_count; parameterscnt++){
	                    var practice_grp = data.static['paramdata'][parameterscnt].practice_group;
	                    var practice_name_array = data.static['paramdata'][parameterscnt].practice_id_array;
	                    //alert(practice_name_array);
	                    if(practice_grp =="null" || practice_grp ==null){
	                        $("#selected_activity_details4").html("<b>Default time :</b> " + default_time);
	                    }else{ 
	                        $("#selected_activity_details4").html('');
	                        if(data.static['paramdata'][parameterscnt].practicegroup.practice_name!=''){
	                            var practice_group = '<b> Organization :</b> '+ data.static['paramdata'][parameterscnt].practicegroup.practice_name;
	                        }else{
	                            var practice_group =''; 
	                        }
	                        if(data.static['paramdata'][parameterscnt].time_required!=''){
	                            var time_required = '<b> Time spent :</b> '+ data.static['paramdata'][parameterscnt].time_required;
	                        }else{
	                            var time_required =''; 
	                        }
	                        if(data.static['paramdata'][parameterscnt].practice_id_array!=''){
	                            var practice_name = '<b> Practices :</b> '+ data.static['paramdata'][parameterscnt].practice_id_array;
	                        }else{
	                            var practice_name =''; 
	                        }
	                    }
	                    var inputs= "<p id='parameterscnt'>"+practice_group+"</p>";
	                    $("#dynamic_practice_grp").append(inputs);
	                    var timeinputs= "<p id='parameterscnt'>"+time_required+"</p>";
	                    $("#dynamic_timerequired").append(timeinputs);
	                    var practice_name= "<p id='parameterscnt'>"+practice_name+"</p>";
	                    $("#dynamic_practices").append(practice_name);
	                }
	            }
	        });
	    }
	}); 

    var getPatientData=function()
    {
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/reports/patient-summary', 
            //data: data,
            success: function (data) { console.log(data);
                var Totalallpatient=data.Totalallpatient[0]['count'];
                var totalPatientActive=data.totalPatientActive[0]['count'];
                var TotalPatientAssignTask=data.TotalPatientAssignTask[0]['count'];
                var ToltalNonAssignedPatient=totalPatientActive-TotalPatientAssignTask;
                 
                $('#totalpatient').html(Totalallpatient);
                $('#totalPatientActive').html(totalPatientActive);
                $('#totalPatientAssignTask').html(TotalPatientAssignTask);
                $('#totalnonassignedpatient').html(ToltalNonAssignedPatient);
                         

               // console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
               

            }
        });
    }
</script>
@endsection
