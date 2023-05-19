@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Time Log Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                    

                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Users</label>
                       @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"])
                       <!-- selectAllexceptadmin -->
                    </div>

                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Module</label>
                        @selectMasterModule("modules",["id"=>"module", "class"=>"module"])
                    </div>
                    <div class="col-md-2 form-group mb-2">
                        <label for="practicegrp">EMR Practice</label>
                        @selectemrpractice("emr_practice", ["id" => "emr_practice","class" => "select2"])
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <label for="practicegrp">Practice</label>
                        @selectpracticespcp("practices", ["id" => "practices","class" => "select2"])
                        <!-- selectrpmpractices -->
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <label for="practicegrp">Patient</label>
                        @selectpatient("patient", ["id" => "patient","class" => "select2"])  
                    </div>
                  
                    <div class="col-md-2 form-group mb-2">
                        <label for="practicename">Sub Module</label>
                          @select("Sub Module", "sub_module", [], ["id" =>"sub_module", "class"=>"sub_module"]) 
                    </div>                  
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-2 form-group mb-3"> 
                          <label for="activedeactivestatus">Patient Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                         
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
                    <table id="time-logs-list" class="display datatable table-striped table-bordered nowrap no-footer dataTable" style="width:100%">
                    <thead>
                        <tr> 
                            <th width="10px">Sr No.</th>  
                            <th width="15px">Practice</th>
                            <th width="10px">EMR</th>
                            <th width="95px">Patient Name</th>  
                            <th width="75px">DOB</th>
                            <th width="15px">Module</th>
                            <th width="25px">Sub Module</th>
                            <th width="25px">Stage</th> 
                            <th width="25px">Step</th>
                            <th width="25px">Form Name</th>
                            <th width="75px">Record Date</th>                          
                            <th width="15px">Timer ON</th>
                            <th width="15px">Timer OFF</th>
                            <th width="15px">Net Timer</th>
                            <th width="15px">Billable</th>
                            <th width="25px">CM</th>
                            <th width="25px">Activity</th>
                            <th width="35px">Comments</th>
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
      var table1="";
        var patientRecordTime = function(patient=null,practiceid=null,emr=null,caremanagerid=null,module=null,sub_module=null,fromdate=null,todate=null,activedeactivestatus=null) {     
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        pracname = full['pracname'];
                                        if(full['pracname'] == null){
                                            pracname = '';   
                                        }
                                        return pracname;  
                                    },
                                    orderable: true  
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        emr = full['pppracticeemr'];
                                        if(full['pppracticeemr'] == null){
                                            emr = '';   
                                        }
                                        return emr;  
                                    },
                                    orderable: true  
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['pmname'];
                                    if(full['pmname'] == null){
                                        m_Name = ''; 
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        // if(full['profile_img']=='' || full['profile_img']==null) {
                                        //     return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        // } else {
                                        //     return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        // }
                                    return full['pfname']+' '+m_Name+' '+full['plname'];
                                    }
                                },
                                orderable: true
                                },

                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        module_id = full['module_id'];
                                        if(full['module_id'] == null){
                                            module_id = '';   
                                        }
                                        return module_id;  
                                    },
                                    orderable: true  
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        sub_module_id = full['component_id'];
                                        if(full['component_id'] == null){
                                            sub_module_id = '';   
                                        }
                                        return sub_module_id;  
                                    },
                                    orderable: true  
                                },


                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        step_id = full['stage_id'];
                                        if(full['stage_id'] == null){
                                            step_id = '';   
                                        }
                                        return step_id;  
                                    },
                                    orderable: true  
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        stage = full['stage_code'];
                                        if(full['stage_code'] == null){
                                            stage = '';   
                                        }
                                        return stage;  
                                    },
                                    orderable: true  
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        form_name = full['form_name'];
                                        if(full['form_name'] == null){
                                            form_name = '';   
                                        }
                                        return form_name;  
                                    },
                                    orderable: true  
                                },
                                {data: 'record_date', type: 'date-dd-mmm-yyyy', name: 'record_date', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                }, 
                      
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        timer_on = full['timer_on'];
                                        if(full['timer_on'] == null){
                                            timer_on = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return timer_on;
                                        }
                                    },
                                    orderable: true
                                },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        timer_off = full['timer_off'];
                                        if(full['timer_off'] == null){
                                            timer_off = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return timer_off;
                                        }
                                    },
                                    orderable: true
                                },

                               
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        net_time = full['net_time'];
                                        if(full['net_time'] == null){
                                            net_time = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return net_time;
                                        }
                                    },
                                    orderable: true
                                },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        billable = full['billable'];
                                        if(full['billable'] == null){
                                            billable = '';
                                        }else if(full['billable']  == '1'){
                                            billable = 'Yes';
                                        }else{
                                            billable = 'No'; 
                                        }
                            
                                         
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return billable;
                                        } 
                                    },
                                    orderable: true
                                },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        created_by_user = full['created_by_user'];
                                        if(full['created_by_user'] == null){
                                            created_by_user = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return created_by_user;
                                        }
                                    },
                                    orderable: true
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        activity = full['activity'];
                                        if(full['activity'] == null){
                                            activity = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return activity;
                                        }
                                    },
                                    orderable: true
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        comments = full['comments'];
                                        if(full['comments'] == null){
                                            comments = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return comments;
                                        }
                                    },
                                    orderable: true
                                },  

                            ];  

            if(patient==''){patient=null;}
            if(practiceid==''){practiceid=null;}
            if(emr==''){emr=null;}
            if(caremanagerid==''){caremanagerid=null;}
            if(module==''){module = null;}
            if(sub_module==''){sub_module=null;} 
            if(fromdate==''){fromdate=null;}
            if(todate==''){todate=null;}
            if(activedeactivestatus==''){activedeactivestatus=null;} 
         var url = "time-logs-report/"+patient+'/'+practiceid+'/'+emr+'/'+caremanagerid+'/'+module+'/'+sub_module+'/'+fromdate+'/'+todate+'/'+activedeactivestatus;
         table1 = util.renderDataTable('time-logs-list', url, columns, "{{ asset('') }}");
        }

$(document).ready(function() { 
    $("[name='practices']").on("change", function () {
        // alert($(this).val());
        var module_id = $('#module').val();
        var emr =$('#emr_practice').val();
        var practiceId =$(this).val();
    if(practiceId =='' || practiceId=='0'){
        var  practiceId= null;
            util.updatePatientList(parseInt(practiceId),parseInt(module_id), $("#patient"));
    }else if(practiceId!='' && emr!=''){
        util.updatePatientListOnEmr(emr,parseInt(practiceId),parseInt(module_id), $("#patient"));
    }else {
        util.updatePatientList(parseInt($(this).val()),parseInt(module_id), $("#patient"));
    }
});

    $("#emr_practice").on("change",function(){
        var emr = $(this).val();
        var practiceId =$("#practices").val();
        if(practiceId==''){ 
            practiceId=null;
        }
        var module_id = $('#module').val();
        if($(this).val()!=''){
            util.updatePracticeListOnEmr(emr, $("#practices"));
            util.updatePatientListOnEmr(emr,parseInt(practiceId),parseInt(module_id), $("#patient"));
        }else{
            util.updatePracticeListWithoutOther(001, $("#practices"));    
        }  
    });

    function convert(str) {
        var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear(), mnth, day].join("-");
    }
  
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date();
    var fromdate = $("#fromdate").attr("value", (convert(firstDay)));
    var todate   = $("#todate").attr("value", (convert(lastDay)));      
  var currentdate = todate.val();
  var startdate = fromdate.val();
  $('#fromdate').val(startdate);
  $('#todate').val(currentdate);     
  $("[name='modules']").val(3).attr("selected", "selected").change();          
  util.getToDoListData(0, {{getPageModuleName()}});
  $(".module").on("change", function () {
      util.updateSubModuleList(parseInt($(this).val()), $(".sub_module"));
  });
}); 


$('#resetbutton').click(function(){
 function convert(str) {
  var date = new Date(str), 
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-"); 
  }

 var date = new Date(),
 y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

var firstDay = new Date(y, m, 1);
var lastDay = new Date(y, m, d); 
  var fromdate =$("#fromdate").val(convert(firstDay));
  var todate =$("#todate").val(convert(lastDay));
   var practice = null
    var modules = 3;
    var provider = null;
    var timeoption = null;
    var time = null;
    var date = null;
    var practicesgrp = null;
    var activedeactivestatus = null; 

    var practiceid =$('#practices').val('').trigger('change');
    var caremanagerid=$('#caremanagerid').val('').trigger('change');
    var emr =$('#emr_practice').val('').trigger('change');
    var patient =$('#patient').val('').trigger('change');
    var module =$("[name='modules']").val(3).attr("selected", "selected").change(); 
    var sub_module =$('#sub_module').val('').trigger('change');
    var activedeactivestatus =$('#activedeactivestatus').val('').trigger('change');
     // patientRecordTime(patient,practiceid,emr,caremanagerid,module,sub_module,fromdate,todate,activedeactivestatus);
   
});

$('#searchbutton').click(function(){
    var patient =$('#patient').val();
    var practiceid =$('#practices').val();
    var emr =$('#emr_practice').val();
    var caremanagerid=$('#caremanagerid').val(); 
    var module=$('#module').val();     
    var sub_module=$('#sub_module').val();     
    var fromdate=$('#fromdate').val();
    var todate=$('#todate').val();
    // alert(fromdate);
    // alert(todate);
    var activedeactivestatus = $('#activedeactivestatus').val();
    // if(fromdate!= '' && todate!= '' && todate > fromdate)
    //  { 
    //  alert("Please ensure that the To Date is greater than or equal to the From Date.");
    //  return false;
    // }
    if(Date.parse(fromdate) > Date.parse(todate)){
      alert("Invalid Date Range");
    }
    patientRecordTime(patient,practiceid,emr,caremanagerid,module,sub_module,fromdate,todate,activedeactivestatus);
            
  });

</script>
@endsection