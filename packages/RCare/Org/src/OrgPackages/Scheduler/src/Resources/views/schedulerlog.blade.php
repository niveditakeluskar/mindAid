@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection 

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Scheduler Log</h4> 
        </div>
        <div class="col-md-1"> 
          
        </div>
    </div>
             
</div>  
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-3 form-group mb-3">  
                      <label>Activity</label>
                      @selectactivitytimertype("activity_id", ["id" => "activity","class" => "select2"])
                    </div>
                    <div class="col-md-3 form-group mb-3">    
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice</label>                  
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                    </div>                  
                    <div class="col-md-3 form-group mb-3">
                        <label for="module">Module</label>
                        <select name="module_id" id="module_id" class="custom-select show-tick select2">
                            <option value="3">CCM</option>
                            <option value="2">RPM</option>  
                        </select>    
                    </div>   
                   
                    
                </div>
                
                <div class="form-row"> 
                    <div class="col-md-2 form-group mb-3">
                        <label for="startdate">Start Date</label>
                        @selectstartdateofscheduler("start_date",["id" => "start_date", "class" => "form-control show-tick select2"])   
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="dateofexecution">Date of Execution</label>
                        @selectdateofexecution("schedulerrecord_date",["id" => "schedulerrecord_date", "class" => "form-control show-tick select2"])   
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="executionstatus">Execution Status</label>
                        <select name="execution_status" id="execution_status" class="custom-select show-tick select2">
                            <option value="1">Executed</option>
                            <option value="0">Failed</option>    
                        </select>  
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="operation">Operation</label>
                        <select name="operation" id="operation" class="custom-select show-tick select2">
                            <option value="1">Add</option>
                            <option value="2">Subtract</option>    
                        </select>  
                    </div>  
                    <div class="col-md-1">
                        <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                    </div>
                    <div class="col-md-1">  
                        <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                    </div>
                </div>                
                </form>
            </div>
        </div>
    </div>
</div>


<div id="success"></div>
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">      
      <div class="card-body"> 
                @include('Theme::layouts.flash-message')      
        <div class="table-responsive">
          <table id="schedulerlog-list" class="display table table-striped table-bordered capital" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">Sr No.</th>
                                <th>Activity</th>
                                <th>Services</th>
                                <th>{{config('global.practice_group')}}</th>
                                <th>Practice</th>
                                <th>Operation</th> 
                                <th>Start Date</th>  
                                <th>Date of Execution</th>          
                                <th>Execution Status</th> 
                                <th>Patient Count</th>
                                <th>Comments</th>
                                <th>Failure Message</th>
                             
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

 



@endsection

@section('page-js')
  


<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">

var getSchedulerloglisting =  function(activity=null,practicesgrp=null,practices=null,module_id=null,startdate=null,dateofexecution=null,executionstatus=null,operation=null) {
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data:'activities.activity', name:'activities.activity'},           
            {data: 'modules.module', name: 'modules.module', orderable: false }, 
            {data: 'practicegroup.practice_name', name: 'practicegroup.practice_name', orderable: false }, 
            {data: 'practices.name', name: 'practices.name', orderable: false }, 
            {data: 'operation', name: 'operation', orderable: false },
            {data: 'start_date',type:'date-mmm-dd-yyyy',name:'start_date',"render":function(value){
                if(value===null) return "";
                    return moment(value).format('MM-DD-YYYY');
                }
            },  
            {data: 'schedulerrecord_date',type:'date-mmm-dd-yyyy',name:'schedulerrecord_date',"render":function(value){ 
                if(value===null) return "";
                    return moment(value).format('MM-DD-YYYY HH:mm:ss'); 
                } 
            },
            // {data: 'execution_status', name: 'execution_status', orderable: false },
            {data: null,mRender: function (data, type, full, meta) {
                if (full['execution_status'] != '' && full['execution_status'] != 'NULL' && full['execution_status'] != undefined) {
                    execution_status = full['execution_status'];
                    if(execution_status == '1'){
                        return 'Executed';
                    }
                    else{
                        return 'Failed'; 
                    }
                   
                } else {
                    return '';
                }
            },
            orderable: false
           },    
           {data: 'patients_count', name: 'patients_count', orderable: false },
           {data: 'comments', name: 'comments', orderable: false },
           {data: 'exception_comments', name: 'exception_comments', orderable: false }   

             ];

            if(activity==''){
                activity=null;
            } 
            if(practicesgrp==''){
                practicesgrp=null;
            } 
            if(practices==''){
                practices=null;
            }
            if(module_id==''){   
                module_id=null;
            } 
            if(startdate==''){
                startdate=null;
            }
             if(dateofexecution==''){
                dateofexecution=null; 
            }
            if(executionstatus=='' || executionstatus==null ){ 
                executionstatus=1;
            }
            if(operation==''  || operation==null){
                operation=1;     
            }
                 
       
        var url = "/org/schedulerlog-report/search/"+activity+'/'+practicesgrp+'/'+practices+'/'+module_id+'/'+startdate+'/'+dateofexecution+'/'+executionstatus+'/'+operation;  
        console.log(url+'schedueduler'); 
        var table1 = util.renderDataTable('schedulerlog-list', url, columns, "{{ asset('') }}");
        // var table = util.renderDataTable('schedulerlog-list', "{{ route('schedulerlog.list') }}", columns, "{{ asset('') }}");  
    };




$(document).ready(function(){
     
    $('#module_id').val('3').trigger('change');
    $('#operation').val('1').trigger('change');
    $('#execution_status').val('1').trigger('change'); 

    var m=$('#module_id').val();
    var o=$('#operation').val();
    var e=$('#execution_status').val();

    getSchedulerloglisting(null,null,null,m,null,null,e,o);

    $("[name='practicesgrp']").on("change", function () { 
        var practicegrp_id = $(this).val(); 
        if(practicegrp_id==0){ 
        }
        if(practicegrp_id!=''){
            util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
        }
        else{
                util.updatePracticeListWithoutOther(001, $("#practices"));    
        }   
    }); 
   
    
    util.getToDoListData(0, {{getPageModuleName()}});  
});

$('#searchbutton').click(function(){
    
     var activity = $('#activity').val();
     var practicesgrp = $('#practicesgrp').val();
     var practices=$('#practices').val();
     var module_id=$('#module_id').val();
     var start_date=$('#start_date').val();
     var schedulerrecord_date=$('#schedulerrecord_date').val();
     var execution_status=$('#execution_status').val(); 
     var operation=$('#operation').val(); 

     getSchedulerloglisting(activity,practicesgrp,practices,module_id,start_date,schedulerrecord_date,execution_status,operation);
     
  
});

$("#month-reset").click(function(){
    
    var activity = null;
    var practicesgrp = null;
    var practices = null;
    var module_id = 3;
    var start_date = null;
    var schedulerrecord_date = null;
    var execution_status = 1;
    var operation = 1;
    
    $('#activity').val('').trigger('change'); 
    $('#practicesgrp').val('').trigger('change');
    $('#practices').val('').trigger('change');
    $('#module_id').val('3').trigger('change'); 
    $('#start_date').val('').trigger('change'); 
    $('#schedulerrecord_date').val('').trigger('change'); 
    $('#execution_status').val('1').trigger('change');
    $('#operation').val('1').trigger('change');

    getSchedulerloglisting(activity,practicesgrp,practices,module_id,start_date,schedulerrecord_date,execution_status,operation);
  
});


 
</script>


@endsection

 
