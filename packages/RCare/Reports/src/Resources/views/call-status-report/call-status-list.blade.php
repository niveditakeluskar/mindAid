@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
 @section('page-title')
    Call Status Report
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Call Status Report</h4>
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
                    <div class="col-md-3 form-group mb-3">  
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice</label>                  
                         @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                    </div>
                   
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        <select name="modules" id="modules" class="custom-select show-tick select2">
                            <option value="3">CCM</option>
                            <option value="2">RPM</option>  
                        </select>  
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
                          <label for="timeperiod">Time Period</label> 
                          <select id="timeperiod" name="timeperiod" class="custom-select show-tick" >
                            <option value="0">Less than 1 month</option> 
                            <option value="1">More than 1 month</option>
                            <option value="2" selected>More than 2 months</option>
                            <option value="3" >More than 3 months</option>                           
                            <option value="4" >More than 4 months</option>  
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



 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-call-status-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="10px">EMR/EHR ID</th>
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th >Practice</th>                          
                            <th >Provider</th>
                            <th>Status</th>
                            <th>Record Date</th> 
                            <th>Record Date Diff</th> 
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

        
        


            var getPatientCallStatusList = function(practicesgrp = null,practice = null,provider=null,modules=null,activedeactivestatus=null,timeperiod=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['pppracticeemr'];
                                        if(full['pppracticeemr'] == null){
                                            practice_emr = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_emr;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['pmname'];
                                    if(full['pmname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_img']=='' || full['profile_img']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        } else {
                                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
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
                                        name = full['pracpracticename'];
                                        if(full['pracpracticename'] == null){
                                            name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['prprovidername'];
                                        if(full['prprovidername'] == null){
                                            name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
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

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['ccsrecdate'];
                                        if(full['ccsrecdate'] == null){
                                            name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['ccsrec_age'];
                                        if(full['ccsrec_age'] == null){
                                            name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },  
                                

                            ];
            
           // debugger;
           if(practicesgrp=='')
            {
                practicesgrp=null;
            } 

            if(practice=='')
            {
                practice=null;
            } 
            
            if(provider=='')
            {
                provider=null;
            }

            if(modules=='')
            {
                modules=null;
            } 
          
            if(activedeactivestatus=='')
            {
               activedeactivestatus=null;
            }

            if(timeperiod=='')
            {
                timeperiod=null;
            }



              
            var url = "/reports/call-status-report/search/"+practicesgrp+'/'+practice+'/'+provider+'/'+modules+'/'+activedeactivestatus+'/'+timeperiod;
            console.log(url);
            var table1 = util.renderDataTable('patient-call-status-list', url, columns, "{{ asset('') }}");
              
        }

            





        
$(document).ready(function(){

    // var patient_id = $("input[name='patient_id']").val();
    // if (patient_id==''){
    //     patient_id = 0;
    // }
   
    // util.getToDoListCalendarData(0, 3); 
    var timeperiod = $('#timeperiod').val(); 
    getPatientCallStatusList(null,null,null,null,null,timeperiod);      
    
    
    $("[name='practicesgrp']").on("change", function () { 
        var practicegrp_id = $(this).val(); 
        if(practicegrp_id==0){
            // getDailyPatientList();  
        }
        if(practicegrp_id!=''){
            util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
        }
        else{
            util.updatePracticeListWithoutOther(001, $("#practices"));    
        }   
    });

    $("[name='practices']").on("change", function () {
      util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
    });


    $("[name='modules']").val(3).attr("selected", "selected").change();
    util.getToDoListData(0, {{getPageModuleName()}});
    util.getAssignPatientListData(0, 0);

   


}); 

$('#searchbutton').click(function(){
     var practice=$('#practices').val();
     var provider=$('#physician').val();
     var modules=$('#modules').val();
     var practicesgrp = $('#practicesgrp').val();
     var activedeactivestatus =$('#activedeactivestatus').val();
     var timeperiod = $('#timeperiod').val();
     getPatientCallStatusList(practicesgrp,practice,provider,modules,activedeactivestatus,timeperiod);  
	
});

$("#month-reset").click(function(){          
    $('#practicesgrp').val('').trigger('change');
    $('#practices').val('').trigger('change'); 
    $('#physician').val('').trigger('change'); 
    $('#modules').val('3').trigger('change'); 
    $('#activedeactivestatus').val('').trigger('change');
    $('#timeperiod').val('').trigger('change');
    getPatientCallStatusList(practicesgrp,practice,provider,modules,activedeactivestatus,timeperiod);  
});





 
    </script>
@endsection