@extends('Theme::layouts.master')  
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">Active Alerts</h4>  
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>

@include('Rpm::active-alert.filters-active-alert')
<div id="success"></div>  
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">           
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="active-alert-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>
                            <th width="20px">TimeStamp</th>
                            <th width="25px">Patient</th>
                            <th width="25px">Daily Review</th>
                            <th width="20px">DOB</th>
                            <th width="20px">Clinic</th>
                            <th width="20px">Provider</th>                            
                            <th width="20px">Care Manager</th>
                            <th width="20px">Vital</th>
                            <th width="20px">Range</th>
                            <th width="20px">Reading</th>
                            <th width="20px">Protocol</th>
                            <th width="20px">Addressed</th>
                        </tr> 
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                 <button type="button" class="btn  btn-primary float-right mr-3" id="addressbutton">Addressed</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style=" width: 800px!important; margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Care Manager Notes</h4>
                <button type="button" class="close modalcancel" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('save.alert.rpm.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <?php 
                            $module_id    = getPageModuleName();
                            $submodule_id = getPageSubModuleName(); 
                            $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'active alert Addressed');

                            // echo $module_id;
                            // echo  $submodule_id;
                            // echo  $stage_id;
                        ?>
                    
                        <div class="row"> 
                            <div class="col-md-12 form-group mb-3">
                                <label for="patientname" id="patientname"></label>&nbsp; &nbsp;
                                <label for="patientvital" id="patientvital"></label><br>
                                <input type="hidden"  name="rpm_observation_id" id="rpm_observation_id" value=""/>
                                <input type="hidden" name="care_patient_id" id="patient_id" value=""/>
                                <input type="hidden" name="vital" id="vital" value=""/>  
                                <input type="hidden" name="deviceid" id="deviceid" value=""/> 
                                <input type="hidden" name="unit" id="unit" value=""/> 
                                <input type="hidden" name="csseffdate" id="csseffdate" value=""/>
                                <input type="hidden" name="module_id" value="{{$module_id}}"> 
                                <input type="hidden" id= "component_id" name="component_id" value="{{$submodule_id}}">
                                <input type="hidden" name="stage_id" value="{{$stage_id}}"> 
                                <input type="hidden" name="add_step_id" value="0">
                                <input type="hidden" name="formname" id="formname" value="RPMdailyreviewedcompleted"/> 
                                <input type="hidden" name="reviewstatus" id="reviewstatus" value="1"/> 
                                <input type="hidden" name="table" id="table" value="parent"/> 
                                <input type="hidden" name="hd_timer_start" id="hd_timer_start" value="00:00:00"/>
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
                                <div class="col-lg-6 text-left" id="gotomm"></div>
                                <div class="col-lg-6 text-right">
                                     <button type="submit" class="btn  btn-primary m-1">Submit</button>
                                    <button type="button" class="btn btn-outline-secondary m-1 modalcancel" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
<input type="hidden"  value="{{$roleid}}" id="roleid"/>
</div>

<div id="app"></div>
@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>  
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/rpmlist.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/activealert.js'))}}"></script>
    <script type="text/javascript">

        var getAlertHistoryList = function(practicesgrp = null,practices = null,provider = null,timeframe = null,caremanagerid = null,patient=null) {
            var columns = [
                               { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['csseffdate'];  
                                        if(full['csseffdate'] == null){
                                            totaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: true   
                                },  

                                   { data: 'pfname',name: 'pfname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['pmname'];
                                            if(full['pmname'] == null){
                                                m_Name = '';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){  
                                            return ["<a href='/rpm/monthly-monitoring/"+full['pid']+"'>"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
                                        }
                                    },
                                    orderable: false
                                },
                                { 
                                    data: 'pfname', name: 'pfname',
                                    mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){  
                                            return ["<a href='/rpm/daily-review/"+full['pid']+"/"+full['deviceid']+"'><button type='button' class='btn btn-primary'>Click</button>"]+'</a>';
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob',
                                    "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                         
                                { data: 'name',name: 'name',
                                    mRender: function(data, type, full, meta){
                                        practice_name = full['practicename'];
                                            if(full['practicename'] == null){
                                                practice_name = '';
                                        }
                                        else{
                                            return practice_name;
                                        }
                                         
                                    },
                                    orderable: true
                                },
                                { data: 'name',name: 'name',
                                    mRender: function(data, type, full, meta){
                                        providername = full['providername'];
                                            if(full['providername'] == null){
                                                providername = '';
                                        }
                                        else{
                                            return providername;
                                        }
                                         
                                    },
                                    orderable: true
                                },
                                { data: 'caremanagerfname',name: 'caremanagerfname',
                                    mRender: function(data, type, full, meta){
                                        caremanagerfname = full['caremanagerfname'];
                                            if(full['caremanagerfname'] == null){
                                                caremanagerfname = '';
                                        }
                                        else{
                                            return full['caremanagerfname']+' '+full['caremanagerlname']; 
                                        }
                                        
                                    },
                                    orderable: true
                                },
                                { data: 'unit',name: 'unit',
                                    mRender: function(data, type, full, meta){
                                        r_unit = full['unit'];
                                        if(full['unit'] == null){
                                                r_unit = '';
                                                // return 'Weight';
                                            }
                                            else{
                                                if(full['unit'] == '%'){
                                                    // return 'Pulse Oximeter';
                                                    return 'Oxygen';
                                                }
                                                else if(full['unit'] == 'beats/minute'){
                                                    // return 'Spirometer';
                                                    return 'Heartrate';
                                                }
                                                else if(full['unit'] == 'mm[Hg]' ){
                                                    //mmHg
                                                    // return 'Blood Pressure Cuff'
                                                    return 'Blood Pressure';
                                                }
                                                else if(full['unit'] == 'mg/dl'){
                                                    return 'Glucose';
                                                }
                                                else if(full['unit'] == 'L' || full['unit'] == 'L/min'){
                                                    return 'FEV1 and PEF';
                                                }
                                                else if(full['unit'] == 'degrees F'){
                                                    return 'Temperature';
                                                }
                                                else{
                                                    return 'Weight'
                                                }
                                            }
                                        
                                    },
                                    orderable: true  
                                },
                                 { data: 'threshold',name: 'threshold',
                                    mRender: function(data, type, full, meta){
                                        threshold = full['threshold']; 
                                       var thresholdtype=full['threshold_type']; 
                                            if(full['threshold'] == null){
                                                threshold = '';
                                        }
                                       else{
                                        if(thresholdtype==null)
                                        {
                                            thresholdtype='';
                                        }
                                        return threshold+" ("+thresholdtype+")"; 
                                       }
                                    },
                                    orderable: true
                                },
                                { data: 'reading',name: 'reading',
                                    mRender: function(data, type, full, meta){
                                        m_reading = full['reading'];
                                        m_unit = full['unit'];
                                            if(full['reading'] == null){
                                                m_reading = '';
                                        }
                                        else{
                                            return m_reading+' '+m_unit;
                                        }
                                        // if(data!='' && data!='NULL' && data!=undefined){
                                        //     return 0;
                                        // }
                                    },
                                    orderable: true
                                },


                        { data: 'action', name: 'action', orderable: false, searchable: false},
                        {data: null, 'render': function (data, type, full, meta){
                                     if(full['Addressed'] == null || full['Addressed']==0){
                                        check = '';
                                    }else{
                                        check = 'checked';  
                                    }
                                     return '<input type="checkbox" id="activealertpatientstatus_'+meta.row+'" class="activealertpatientstatus" name="activealertpatientstatus" '+check+'>';
                                 },
                                 orderable: true
                               
                            }
                        ];  

                        if(practicesgrp==''){practicesgrp=null;}
                        if(practices==''){practices=null;} 
                        if(provider==''){provider=null;}            
                        if(timeframe==''){ timeframe=null;}
                        if(caremanagerid==''){ caremanagerid=null; }   
                         if(patient==''){ patient=null; }   
                        
            
               
                var url ="/rpm/active-alert-list/search/"+practicesgrp+"/"+practices+"/"+provider+"/"+timeframe+"/"+caremanagerid+"/"+patient;  
                console.log(url);
                var table1 = util.renderDataTable('active-alert-list', url, columns, "{{ asset('') }}"); 
            
        } 
    </script>
    <script>
     $(document).ready(function() {
       
        getAlertHistoryList(null,null,null,null,null);
        rpmlist.init();
        activealert.init();

        var roleid =  $('#roleid').val(); 
        //alert(roleid);
        if(roleid == '5'){
        $('#cmdiv').hide();    
    }
       
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

     

        $("[name='practices']").on("change", function () {
            var practice_id = $(this).val(); 
            $('#patientdiv').show();
             util.updatePatientList(parseInt($(this).val()),'2', $("#patient"));    
            if(practice_id!=''){ 
                util.updatePcpPhysicianList(parseInt($(this).val()), $("#physician")); //added by priya 25feb2021 for remove other option
                //util.getRpmProviderPatientList(parseInt($(this).val()), $("#physician"));
                }
                else
                {
                    util.updatePhysicianProviderListWithoutOther(parseInt($(this).val()), $("#physician"));
                }
                
        });

         $("[name='provider']").on("change", function () {
            var provider = $(this).val(); 
              $('#patientdiv').show();
            if(provider!=''){ 
                 util.getRpmProviderPatientList(parseInt($(this).val()), $("#patient"));
                }
                else
                {
                    util.updatePatientList(parseInt($(this).val()),'2', $("#patient")); 

                } 
                      
        });

        util.getToDoListData(0, {{getPageModuleName()}});

        });

    $('#searchbutton').click(function(){ 
   
    var practicesgrp = $('#practicesgrp').val(); 
    var practice=$('#practices').val();
    var provider=$('#physician').val(); 
     var patient=$('#patient').val(); 
    var timeframe=$('#timeframe').val();  
    var caremanagerid=$('#caremanagerid').val();
    getAlertHistoryList(practicesgrp,practice,provider,timeframe,caremanagerid,patient);

    });

    $('.modalcancel').click(function(){ 
         var table = $('#active-alert-list').DataTable();      
         var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', false);
    });

    $('#resetbutton').click(function(){ 
       
        $('#practicesgrp').val('').trigger('change');
        $('#caremanagerid').val('').trigger('change');
        $('#practices').val('').trigger('change');
        $('#physician').val('').trigger('change');
        $('#timeframe').val('').trigger('change');
         $('#patient').val('').trigger('change');
        
        var practicesgrp =  $('#practicesgrp').val();
        var practice=$('#practices').val();
        var provider=$('#physician').val();
        var timeframe=$('#timeframe').val();
        var caremanagerid=$('#caremanagerid').val(); 
        getAlertHistoryList(practicesgrp,practice,provider,timeframe,caremanagerid);  
            
        });

    // function valueChanged()
    // {
    //     var q = $(this);
    //     console.log(q);
    //     if($('.activealertpatientstatus').is(":checked"))   
    //     var table = $('#active-alert-list').DataTable();
    //     // var r = $(this).parent('tr');
    //     var d =  $(this).closest("tr");     
    //     console.log(d);  
    //     var rowdata = table.row($(this).closest('tr')).data();  
    //     console.log(rowdata);   
    //     $("#rpm_cm_form")[0].reset();
    //     $("#rpm_cm_modal").modal('show');
    //     // else
    //     //     alert('ashu1');
    // } 
  var patientarray=[];
            var rpmid=[];
            var vitalarray=[];
            var deviceid=[];
            var unitarray=[];
            var csseffdatearray=[];
    $('#active-alert-list tbody').on('change','.activealertpatientstatus',function(){
        if($(this).is(":checked")){
            var table = $('#active-alert-list').DataTable();
            var rowdata = table.row($(this).parents('tr')).data();
         
            var pfname = rowdata.pfname;
            var plname = rowdata.plname;
            var unit   = rowdata.unit;
            var patientname = pfname+" "+plname;
         //   var rpmid = rowdata.tempid;
            var pid = rowdata.pid;
          //  var deviceid=rowdata.deviceid;
             var csseffdate=rowdata.csseffdate;
           
               

             var urlmm="/rpm/monthly-monitoring/"+pid;
            $('#gotomm').html('<a href="'+urlmm+'"><u>Go To Monthly Monitoring</u></a>');
             
              if(unit=='%'){
                var vital = 'Oxygen';
            }
            else if(unit=='mm[Hg]'){
               var vital = 'Blood Pressure';
            }
            else if(unit=='beats/minute'){
               var vital =  'Heartrate';
            } 
            else if(unit=='mg/dl'){
                var vital =  'Glucose';
            }
            else if(unit=='lbs'){
                var vital =  'Weight';
            }
            else if(unit=='degrees F'){
                var vital =  'Temperature';
            }
            else{
                var vital = 'Spirometer';
            }

             if (($.inArray(pid, patientarray) != -1 || patientarray.length === 0) && ($.inArray(vital, vitalarray) != -1 || vitalarray.length === 0))
                 {
                   patientarray.push(pid);
                   rpmid.push(rowdata.tempid);
                   vitalarray.push(vital);
                   deviceid.push(rowdata.deviceid);
                   unitarray.push(unit);
                   csseffdatearray.push(csseffdate);  
                 }
                else
                {
                 $(this).prop('checked', false);
                  alert("Please select same patient and same type of vital!");
                  
                }
           
          //  $("#rpm_cm_modal").modal('show');
            $("#patientname").text(patientname);
            $("#patientvital").text(vitalarray);     
            $("#patient_id").val(patientarray); 
            $("#vital").val(vitalarray); 
            $("#rpm_observation_id").val(rpmid);  
            $("#deviceid").val(deviceid);  
            $("#unit").val(unitarray); 
            $("#csseffdate").val(csseffdatearray); 

        }
        else{
           // alert("2");
        }   

    }); 

        $('#addressbutton').click(function(){
            $("#rpm_cm_modal").modal('show');
           
          });
  
    </script>
@endsection