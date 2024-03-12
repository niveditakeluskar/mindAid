@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Task Status Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="task_status_form" name="task_status_form"  action ="">
                @csrf
                <div class="form-row">

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"])
                       <!-- selectAllexceptadmin -->
                    </div>

                    <div class="col-md-3 form-group mb-3"> 
                          <label for="selectcategory">Task Status</label> 
                          <select id="taskstatus" name="taskstatus" class="custom-select show-tick" >
                            <option value="" selected>All (Completed,Pending)</option> 
                            <option value="1">Completed</option>
                            <option value="0">Pending</option>                        
                          </select>                         
                        </div>

                    <div class="col-md-3 form-group mb-3">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>

                    <div class="col-md-3 form-group mb-2">
                        <label for="practice">Practice</label>                      
                        @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"]) 
                    </div>
                    <div class="col-md-3 form-group mb-2" id="patientdiv" style="display:none">
                        <label for="patient">Patient</label>
                        @selectpatient("patient", ["id" => "patient","class" => "select2"])  
                    </div> 
                   
                   
                   

                    <div class="col-md-3 form-group mb-3"> 
                          <label for="activedeactivestatus">Patient Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                         
                        </div>

                        <div class="col-md-2 form-group mb-2">
                        <label for="score">Score</label>                      
                        @selectscore("score",["id" => "score", "class" => "form-control select2"]) 
                    </div>

                    <div class="col-md-2 form-group mb-3">
                        <label for="date">Task Start Date</label>
                        @date('fromdate',["id" => "fromdate"])                                               
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="date">Task End Date</label>
                        @date('todate',["id" => "enddate"])                        
                    </div>

                    <div class="col-md-2 form-group mb-2"> 
                        <select id="timeoption"  class="custom-select show-tick" style="margin-top: 23px;">
                            <option value="2">Greater than</option>
                            <option value="1" selected>Less than</option>
                            <option value="3">Equal to</option>
                            <option value="4">All</option>
                        </select>                          
                    </div>
                    <div class="col-md-2 form-group mb-2">                        
                        <label for="time">Time Spent</label>
                        @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])                       
                    </div>
                     
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>
                  
                </div>
                </form>
            </div>
        </div>

    </div>
</div>


 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
                @include('Theme::layouts.flash-message')                
           
                <div class="table-responsive">
                    <table id="task-status-list" class="display datatable table-striped table-bordered nowrap no-footer dataTable" style="width:100%">
                    <thead>
                        <tr> 
                        <th width="10px">Sr No.</th>  
                            <th width="25px">Assigned Care Manager</th>
                            <th width="25px">Practice Name</th>
                            <th width="25px">Patient Name</th>
                            <th width="25px">DOB</th>
                            <th width="30px">Task</th>
                            <th width="30px">Task Date</th>
                            <th width="75px">Task Status</th> 
                            <th width="30px">Task Completion Date</th>
                            <th width="30px">Task Created By</th>
                            <th width="30px">Patient Score</th>
                            <th width="15px">Module</th>
                            <th width="25px">Component</th> 
                            <th width="30px">Task Type</th>
                            <th width="15px">Task Notes</th>
                            <th>Reassign CareManager</th>  
                            <th>Status</th>  
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>


 

<div id="app">
</div>
@endsection

@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   
    <script type="text/javascript">


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

      var table1="";  

      var taskstatusreport = function(caremanagerid=null,practicesgrp=null,practiceid=null,patient=null,taskstatus=null,fromdate=null,enddate=null,activedeactivestatus=null,score=null,timeoption=null,time=null) {       
                                         
                                         var columns =  [   
                                                             {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                             
                                                             {data: null, mRender: function(data, type, full, meta){ 
                                                                 caremanagerfname = full['caremanagerfname'];
                                                                 if(full['caremanagerfname']==null){
                                                                     caremanagerfname = ''; 
                                                                     return caremanagerfname;  
                                                                 }
                                                                 else{
                                                                
                                                                     return full['caremanagerfname']+' '+full['caremanagerlname'];    
                                                                 }
                                                                 },
                                                                 orderable: true
                                                             },
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     practicename = full['pracpracticename'];
                                                                     if(full['pracpracticename'] == null){
                                                                         practicename = '';   
                                                                     }
                                                                     return practicename;  
                                                                 },
                                                                 orderable: true  
                                                             },
                             
                                                             {data: null, mRender: function(data, type, full, meta){
                                                                 m_Name = full['pmname'];
                                                                 if(full['pmname'] == null){
                                                                     m_Name = ''; 
                                                                 }
                                                                 if(data!='' && data!='NULL' && data!=undefined){
                                                                 return full['pfname']+' '+m_Name+' '+full['plname'];
                                                                 }
                                                             },
                                                             orderable: true
                                                             },
                             
                                                             {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                                                 if (value === null) return "";
                                                                     return moment(value).format('MM-DD-YYYY');
                                                                 }
                                                             },
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     task = full['tmtask'];
                                                                     if(full['tmtask'] == null){
                                                                         task = '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                         return task;
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             }, 
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     taskdate = full['tmtaskdate'];
                                                                     if(full['tmtaskdate'] == null){
                                                                         taskdate = '';
                                                                         return '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                          return moment(taskdate).format('MM-DD-YYYY');      
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             },
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     taskstatus = full['tmtaskstatus'];
                                                                     if(full['tmtaskstatus'] == null){
                                                                         taskstatus = '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                         return taskstatus;
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             },
                                                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     taskcompletiondate = full['tmtaskcompletiondate'];
                                                                     if(full['tmtaskcompletiondate'] == null){
                                                                         taskcompletiondate = '';
                                                                         return '';  
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                          return moment(taskcompletiondate).format('MM-DD-YYYY');      
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             },  
                             
                                                             {data: null, mRender: function(data, type, full, meta){ 
                                                                 
                                                                 if(full['createdbyfname'] == null){
                                                                     createdbyfname = '';
                                                                     return  createdbyfname ;  
                                                                     }else{
                                                                         return full['createdbyfname']+' '+full['createdbylname'];
                                                                     }
                                                                
                                                             },
                                                             orderable: true
                                                             },
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     score = full['tmscore'];
                                                                     if(full['tmscore'] == null){
                                                                         score = '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                         return score;
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             },
                                                           
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     module_id = full['mmodulename'];
                                                                     if(full['mmodulename'] == null){
                                                                         module_id = '';   
                                                                     }
                                                                     return module_id;  
                                                                 },
                                                                 orderable: true  
                                                             },
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     sub_module_id = full['mccomponentsname'];
                                                                     if(full['mccomponentsname'] == null){
                                                                         sub_module_id = '';   
                                                                     }
                                                                     return sub_module_id;  
                                                                 },
                                                                 orderable: true  
                                                             },
                                                             
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     followuptaskcategory = full['fufollowuptaskcategory'];
                                                                     if(full['fufollowuptaskcategory'] == null){
                                                                         followuptaskcategory = '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                         return followuptaskcategory;
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             }, 
                             
                                                             {data:null,
                                                                 mRender: function(data, type, full, meta){
                                                                     tasknotes = full['tmtasknotes'];
                                                                     if(full['tmtasknotes'] == null){
                                                                         tasknotes = '';
                                                                     }
                                                                     if(data!='' && data!='NULL' && data!=undefined){
                                                                         return tasknotes;
                                                                     }
                                                                 },
                                                                 orderable: true
                                                             },
                                                             {data: 'action', name: 'action', orderable: false, searchable: false},
                                                             {data: 'action2', name: 'action2', orderable: false, searchable: false}
                             
                                                             // { data: 'ptrtotaltime', name: 'ptrtotaltime',
                                                             //     mRender: function(data, type, full, meta){
                                                             //     // $(row).css('color', 'red');
                                                             //     if(data!='' && data!='NULL' && data!=undefined){
                                                             //         return full["ptrtotaltime"];   
                                                             //     } else {
                                                             //         return '';
                                                             //     }
                                                             //     }, orderable: true
                                                             // }
                             
                                                         ];  
                             
                                         if(caremanagerid==''){caremanagerid=null;}
                                         if(patient==''){patient=null;}
                                         if(practicesgrp==''){practicesgrp=null;}
                                         if(practiceid==''){practiceid=null;}          
                                         if(taskstatus==''){taskstatus=null;}            
                                         if(activedeactivestatus==''){activedeactivestatus=null;} 
                                         if(score==''){ score=null; }  
                                         if(fromdate==''){ fromdate=null; }
                                         if(enddate==''){ enddate=null; }
                                         if(time=='') {
                                             time='00:20:00';
                                         }
                                         if(time=='00:00:00') {
                                             time='00:00:00';
                                         }
                                         if(timeoption=='') {
                                             timeoption=1;
                                         }
                             
                                        
                                         var url = "/reports/task-status-report-search/"+caremanagerid+'/'+practicesgrp+'/'+practiceid+'/'+patient+'/'+taskstatus+'/'+fromdate+'/'+enddate+'/'+activedeactivestatus+'/'+score+'/'+timeoption+'/'+time;
                                         console.log(url);
                                         table1 = util.renderDataTable('task-status-list', url, columns, "{{ asset('') }}");
                                     }



$(document).ready(function() {

var currentdate = formatDate();
$('#fromdate').val(currentdate);
$('#enddate').val(currentdate);
var fromdate =  $('#fromdate').val(); 
var enddate =   $('#enddate').val(); 
       

$("[name='practicesgrp']").on("change", function () { 
        var practicegrp_id = $(this).val(); 
        if(practicegrp_id==0){
           // getCareManagerList();  
        }
        if(practicegrp_id!=''){
            util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
        }
        else{
            //getCareManagerList();      
        }   
});

$("[name='practices']").on("change", function (){
        var practice_id = $(this).val();  
        if(practice_id==0){
            // getPatientList();  
        }
        if(practice_id!=''){
            // alert(practice_id);
            $('#patientdiv').show();
            // alert("hello");
            util.getPatientList(parseInt(practice_id),$("#patient"));
        }
        else{
            // getPatientList();  
            $('#patientdiv').hide();     
        }
       
});

$('#timeoption').change(function() {
    $checkvalue=$('#timeoption').val();
    if($checkvalue=='4') {
        $('#time').val('00:00:00'); 
        $('#time').prop( "disabled", true);
    } else {
        $('#time').val('00:20:00');    
        $('#time').prop( "disabled", false);
    }
});

if($('#timeoption').val()==1){
    $('#time').val('00:20:00');
}

$('#searchbutton').click(function(){ 
//debugger
var caremanagerid=$('#caremanagerid').val();
var practicesgrp=$('#practicesgrp').val();
var practice=$('#practices').val();
var patient=$('#patient').val();
var taskstatus=$('#taskstatus').val();
var activedeactivestatus=$('#activedeactivestatus').val();
var score=$('#score').val();
var fromdate=$('#fromdate').val();
var enddate=$('#enddate').val();
var timeoption = $('#timeoption').val();
var time = $('#time').val();
if(enddate < fromdate)
{                
$('#enddate').addClass("is-invalid");
$('#enddate').next(".invalid-feedback").html("Please select to-date properly .");
$('#fromdate').addClass("is-invalid");
$('#fromdate').next(".invalid-feedback").html("Please select from-date properly .");   
} else{

$('#enddate').removeClass("is-invalid");
$('#enddate').removeClass("invalid-feedback");
$('#fromdate').removeClass("is-invalid");
$('#fromdate').removeClass("invalid-feedback");

if(time == '00:00:00') {
    time = '00:00:00';
}
var time_regex = new RegExp(/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/);
if(time!="") {
    if(time.length!=8) {
        $('#time').addClass("is-invalid"); 
        $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
    } else {
        if(time_regex.test(time)) { 
            $('#time').removeClass("is-invalid"); 
            $('#time').removeClass("invalid-feedback");     
            taskstatusreport(caremanagerid,practicesgrp,practice,patient,taskstatus,fromdate,enddate,activedeactivestatus,score,timeoption,time); 
        } else {
            $('#time').addClass("is-invalid");
            $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
        }
    }
} else {  
    $('#time').removeClass("is-invalid"); 
    $('#time').removeClass("invalid-feedback");     
taskstatusreport(caremanagerid,practicesgrp,practice,patient,taskstatus,fromdate,enddate,activedeactivestatus,score,timeoption,time); 
}
setTimeout(function() {
    //deactiveDataInGreyColor();   
    //do something special
}, 5000);

taskstatusreport(caremanagerid,practicesgrp,practice,patient,taskstatus,fromdate,enddate,activedeactivestatus,score,timeoption,time);    
}       
});
});

$('#resetbutton').click(function(){ 
     
$('#caremanagerid').val('').trigger('change');
$('#practicesgrp').val('').trigger('change');
$('#practices').val('').trigger('change');
$('#patient').val('').trigger('change');
$('#taskstatus').val('').trigger('change');   
$('#activedeactivestatus').val('').trigger('change');  
$('#score').val('').trigger('change');
var currentdate = formatDate(); 
$('#fromdate').val(currentdate);
$('#enddate').val(currentdate);   
var timeoption = null;
var time = null;
$('#time').val('00:20:00');
$('#timeoption').val('1').trigger('change'); 

var caremanagerid=$('#caremanagerid').val(); 
var practicesgrp=$('#practicesgrp').val();
var practice=$('#practices').val();
var patient=$('#patient').val();
var taskstatus=$('#taskstatus').val();            
var activedeactivestatus = $('#activedeactivestatus').val();
var score=$('#score').val();
var fromdate=$('#fromdate').val();     
var enddate=$('#enddate').val();
var time=$('#time').val();
var timeoption=$('#timeoption').val();
taskstatusreport(caremanagerid,practicesgrp,practice,patient,taskstatus,fromdate,enddate,activedeactivestatus,score,timeoption,time);  


  
});


function reassignPatientandTask(patient,taskid,user)
{

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

   var data = {
        patient: patient,
        user: user,
        taskid:taskid         
    }
   
    $.ajax({
    type: 'POST',
    url: '/reports/task-status-report-user-form',
    data: data,
    success: function (data) {
        $("#assign-success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        // getPatientData();
        taskstatusreport();
        // setTimeout($("#assign-success-alert").hide(), 30000);
        setTimeout(function () {
            // Closing the alert
            $('#assign-success-alert').alert('close');
        }, 3000);
    }
});

} 


function todoliststatus(taskid,status_flag)
{
var practice=$('#practices').val();
//var provider=$('#physician').val();
var patient =$('#patient').val();
var activedeactivestatus =$('#activedeactivestatus').val();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var data = {
    taskid: taskid,
    status_flag : status_flag      
}


$.ajax({ 
    type: 'POST',
    url: '/reports/task-status-report-statuschange',
    data: {taskid: taskid, status_flag : status_flag},
    success: function (data) { 
        $("#todo-success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        //getToDoListReport(practice,patient,modules,activedeactivestatus);
        // getflyout();
        //setTimeout($("#todo-success-alert").hide(), 20000);
        setTimeout(function () {
            // Closing the alert
            $('#todo-success-alert').alert('close');
        }, 3000);

    }
});
}



</script>
@endsection