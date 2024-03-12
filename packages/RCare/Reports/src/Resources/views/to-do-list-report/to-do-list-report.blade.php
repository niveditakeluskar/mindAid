@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Task Management</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
             <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice</label>
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])  
                     
                    </div>
                    <!-- <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician","class"=>"select2"])
                    </div> --> 
                    <div class="col-md-2 form-group mb-3">
                        <label for="patient">Patient</label>
                         @selectpatient("patient", ["class" => "select2","id" => "patient"])                               
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Caremanager </label>
                            @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="activedeactivestatus">Status</label> 
                        <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                        <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                        <option value="1">Active</option>
                        <option value="0">Suspended</option>
                        <option value="2" >Deactivated</option>                           
                        <option value="3" >Deceased</option>
                        </select>                          
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="taskstatus">Task Status</label> 
                          <select id="taskstatus" name="taskstatus" class="custom-select show-tick" >
                            <option value="" selected>All (Pending,Complete,Unmarked completed,Suspended)</option> 
                            <option value="0">Pending</option>
                            <option value="1">Completed</option>
                            <option value="2">Unmarked Completed</option>                           
                            <option value="3">Suspended</option>
                          </select>                          
                    </div>
                    <div class="row col-md-2 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="todolistsearchbutton">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="todolistresetbutton">Reset</button>
                        </div>
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
                 <div class="alert alert-success" id="todo-success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Task action successfully! </strong><span id="text"></span>
                </div>
                <div class="table-responsive">
                    <table id="task_todolist" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width ="10px">Sr No.</th>
                            <th width ="10px">Patient Name</th> 
                            <th width ="10px">Module</th>
                            <th width = "10px">Patient status</th>                      
                            <th width ="10px">Task</th> 
                            <th width ="10px">Date Scheduled</th>
                            <th width ="10px">Task Time</th>
                            <th width ="10px">Assigned By</th> 
                            <th>Assigned To</th>          
                            <th width ="10px">Task Status</th>
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
        var getflyout = function (){
            util.getToDoListData(0, {{ getPageModuleName() }});
            //util.getToDoListCalendarData(0, {{ getPageModuleName() }});
        }
        var getToDoListReport = function(caremanagerid=null,practice=null,patient=null,taskstatus=null,activedeactivestatus=null) {
            $.fn.dataTable.ext.errMode = 'throw';
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null,
                    mRender: function(data, type, full, meta){
                        patientf = full['pfname'];
                        patientl = full['plname'];
                        //patient = patientf +" "+ patientl;

                        if((full['pfname'] == null) || (full['plname'] == null)){
                            patientf = '';
                            patientl = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return  patientf +" "+ patientl;
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
                {data: null, 
                    mRender: function(data, type, full, meta){
                    status = full['pstatus'];
                        if(full['pstatus'] == 1){
                            status = 'Active';
                        } 
                        if(full['pstatus'] == 0){
                            status = 'Suspended';
                        }
                        if(full['pstatus'] == 2){ 
                            status = 'Deactived';
                        }
                        if(full['pstatus'] == 3){ 
                            status = 'Deceased';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                        return status;
                        }
                    },
                    orderable: true, searchable: false
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        task = full['tmtask'];
                        if(full['tmtask'] == null){
                            task = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['tmtask'] == null){
                                task = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return task;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, type: 'date-mm-dd-yyyy',
                    mRender: function(data, type, full, meta){
                        date_scheduled = full['tt'];
                        if(full['tt'] == null){
                            date_scheduled = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['tt'] == null){
                                date_scheduled = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return date_scheduled;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        tasktime = full['tmtasktime'];
                        if(full['tmtasktime'] == null){
                            tasktime = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['tmtasktime'] == null){
                                tasktime = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return tasktime;
                            }               
                        }
                    },
                    orderable: true
                },
                
                {data: null, 
                    mRender: function(data, type, full, meta){
                        assignby1 = full['createdbyfname'];
                        assignby2 = full['createdbylname'];
                        if((full['createdbyfname'] == null) || (full['createdbylname'] == null)){
                            assignby1 = '';
                            assignby2 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['createdbyfname'] == null) || (full['createdbylname'] == null)){
                                assignby1 = '';
                                assignby2 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return assignby1 + " " + assignby2;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: null, 
                 mRender: function(data, type, full, meta){
                    assignto1 = full['caremanagerfname'];
                    assignto2 = full['caremanagerlname'];
                        if((full['caremanagerfname'] == null) || (full['caremanagerlname'] == null)){
                            assignto1 = '';
                            assignto2 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['caremanagerfname'] == null) || (full['caremanagerlname'] == null)){
                                assignto1 = '';
                                assignto2 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return assignto1 + " " + assignto2;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: 'action', name: 'action', orderable: false, searchable: false}

                ];
            
           // debugger; 
           if(caremanagerid==''){caremanagerid=null;}
            if(practice=='')
            {
                practice=null;
            }
            if(patient=='')
            {
                patient=null;
            } 
            if(taskstatus==''){taskstatus=null;} 
            if(activedeactivestatus==''){activedeactivestatus=null;}
          

                // var url = "/reports/daily-report/search/"+practice+'/'+provider+'/'+modules+'/'+date+'/'+time+'/'+timeoption;
                var url = "/reports/task-management/search/"+caremanagerid+'/'+practice+'/'+patient+'/'+taskstatus+'/'+activedeactivestatus;
                // console.log(url); 
                util.renderDataTable('task_todolist', url, columns, "{{ asset('') }}");
              
        }        
    $(document).ready(function(){    
        $.fn.dataTable.ext.errMode = 'throw';

        //util.getToDoListCalendarData(0, {{ getPageModuleName() }});
        util.getToDoListData(0, {{ getPageModuleName() }});
        util.getAssignPatientListData(0, 0);

        $("[name='practices']").on("change", function () {
            //console.log($(this).val()+"test"+{{ getPageModuleName() }});
            if($(this).val()=='0' || $(this).val()==''){
                //getToDoListReport('0');
                util.updatePatientList(parseInt(''), {{ getPageModuleName() }}, $("#patient"));
            } else {    
                util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
                    
            }
                //util.updatePatientList(parseInt($(this).val()), $("#patient"));
        });

        // $("[name='patient']").on("change", function () {    
        //     //getToDoListReport($(this).val());
        // });

        $('#todolistsearchbutton').click(function(){ 
            var caremanagerid =$("#caremanagerid").val(); 
            var practice=$('#practices').val();
            //var provider=$('#physician').val();
            var patient =$('#patient').val(); 
            var taskstatus = $('#taskstatus').val();
            var activedeactivestatus =$('#activedeactivestatus').val();
            // alert('a'+taskstatus+'b'+activedeactivestatus); 
            getToDoListReport(caremanagerid,practice,patient,taskstatus,activedeactivestatus);  

        });
         $('#todolistresetbutton').click(function(){
            $("#caremanagerid").val('').trigger('change'); 
            $('#practices').val('').trigger('change');
            $('#patient').val('').trigger('change');
            $('#activedeactivestatus').val('').trigger('change'); 
            $('#taskstatus').val('').trigger('change'); 
            //$('#patientStatus option[value=""]').attr("selected","selected");
        });

    }); 

   

    function todoliststatus(rowid,status_flag){
        var practice=$('#practices').val();
        //var provider=$('#physician').val();
        var patient =$('#patient').val();
        var activedeactivestatus =$('#activedeactivestatus').val();
        var taskstatus =$('#taskstatus').val(); 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
 
        var data = {
            id: rowid,
            status_flag : status_flag      
        }
        //alert(status_flag);
        $.ajax({ 
            type: 'POST',
            url: '/reports/task-management-statuschange',
            data: {id: rowid, status_flag : status_flag},
            success: function (data) { 
                $("#todo-success-alert").show();
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                //getToDoListReport(practice,patient,modules,activedeactivestatus);
                getflyout();
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