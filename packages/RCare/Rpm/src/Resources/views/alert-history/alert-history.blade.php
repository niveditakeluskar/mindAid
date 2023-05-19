@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">Alert History</h4>  
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>

@include('Rpm::alert-history.filters-alert-history')
<div id="success"></div>  
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">           
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="alert-history-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>
                            <th width="20px">TimeStamp</th>
                            <th width="20px">Patient</th>
                            <th width="20px">Alert History Details</th>
                            <th width="20px">DOB</th>
                            <th width="20px">Clinic</th>
                            <th width="20px">Provider</th>                            
                            <th width="20px">Care Manager</th>
                            <th width="20px">Vital</th>
                            <th width="20px">Range</th>
                            <th width="20px">Reading</th>
                            <th width="20px">CM Notes</th>
                            <!-- <th width="20px">Address</th> -->
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


<div class="modal fade" id="rpm_cm_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="
    width: 800px!important;
    margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Care Manager Notes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('save.rpm.alert.history.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal">
                    {{ csrf_field() }}

                    <?php 
                            $module_id    = getPageModuleName();
                            $submodule_id = getPageSubModuleName(); 
                            $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Alert History');

                            echo $module_id;
                            echo $submodule_id;
                            echo $stage_id;
                        ?>
                   
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="patientname" id="patientname"></label>&nbsp; &nbsp; 
                                <label for="patientvital" id="patientvital"></label><br>
                                <input type="hidden"  name="rpm_observation_id" id="rpm_observation_id" value=""/>
                                <input type="hidden" name="patient_id" id="patient_id" value=""/>
                                <input type="hidden" name="vital" id="vital" value=""/>
                                <input type="hidden" name="module_id" value="{{$module_id}}"> 
                                <input type="hidden" id= "component_id" name="component_id" value="{{$submodule_id}}">
                                <input type="hidden" name="stage_id" value="{{$stage_id}}"> 
                                <input type="hidden" name="add_step_id" value="0">
                                <input type="hidden" name="formname" id="formname" value="RPMalerthistory"/> 
                                <input type="hidden" name="hd_timer_start" id="hd_timer_start" value="00:00:00"/>
                                <input type="hidden" name="table" id="table" value="parent"/>   

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
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
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

@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/rpmlist.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/alerthistory.js'))}}"></script>

    <script type="text/javascript">
 var table1 ="";
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

                                { data: 'patients',name: 'patients',},
                                { data: 'review-patients',name: 'review-patients',},
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
                                    orderable: false
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
                                    orderable: false
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
                                    orderable: false
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
                                    orderable: false  
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
                                    orderable: false
                                },

                        
                          { data:null,
                                    mRender: function(data, type, full, meta){
                                   if(window.location.href.indexOf('alert-history/')>0)
                                    {
                                       return full['notes'];
                                     }
                                     else
                                     {
                                        return data['action'];
                                     }
                                    },
                                    orderable: false
                                }
                        
                        ];  

                        if(practicesgrp==''){practicesgrp=null;}
                        if(practices==''){practices=null;} 
                        if(provider==''){provider=null;}            
                        if(timeframe==''){ timeframe=null;}
                        if(caremanagerid==''){ caremanagerid=null; }  
                        if(patient==''){ patient=null; }  
                        
            
               
                 
                if(window.location.href.indexOf('alert-history/')>0)
                {
                   var getUrl=window.location;
                   var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];                
                   var patientid=getUrl.pathname.split('/')[3];
                   var month=getUrl.pathname.split('/')[4];
                   var url ="/rpm/alert-history-export/"+patientid+"/"+month; 
                   var directexport="1";
                }
                else
                {
                	var url ="/rpm/alert-history-list/search/"+practicesgrp+"/"+practices+"/"+provider+"/"+timeframe+"/"+caremanagerid+"/"+patient;
                      var directexport=null;
                }
                var table1 = util.renderDataTable('alert-history-list', url, columns, "{{ asset('') }}",0,"",directexport); 
               
             
        } 

    
            


    </script>
    <script>
     $(document).ready(function() {
      
        var tf = $("#timeframe").val();
        getAlertHistoryList(null,null,null,tf,null);
        rpmlist.init();
        alerthistory.init();
        
        var roleid =  $('#roleid').val(); 
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

        // $("[name='practices']").on("change", function (){
        //         var practice_id = $(this).val();  
        //         if(practice_id==0){
        //             getAlertHistoryList();  
        //         }
        //         if(practice_id!=''){
        //             util.getRpmPatientList(parseInt(practice_id),$("#patient"));
        //         }
        //         else{
        //             getAlertHistoryList();    
        //         }
               
        // });

        // $("[name='practices']").on("change", function () {
        //     var practice_id = $(this).val(); 
        //     if(practice_id!=''){ 
        //         util.getRpmProviderPatientList(parseInt($(this).val()), $("#physician"));
               
        //         }
        //         else{
        //             getAlertHistoryList();  
        //         }
       
        // });

           $("[name='practices']").on("change", function () {
            var practice_id = $(this).val(); 
             $('#patientdiv').show();
                 util.updatePatientList(parseInt($(this).val()),'2', $("#patient")); 
            if(practice_id!=''){               
                util.getRpmProviderPatientList(parseInt($(this).val()), $("#physician"));
                              
                }
                else
                {
                    util.updatePhysicianProviderListWithoutOther(parseInt($(this).val()), $("#physician"));
                    // util.updatePatientList('','2', $("#patient")); 
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
    var timeframe=$('#timeframe').val();  
      var patient=$('#patient').val(); 
    var caremanagerid=$('#caremanagerid').val();
    getAlertHistoryList(practicesgrp,practice,provider,timeframe,caremanagerid,patient);

    });

    $('#resetbutton').click(function(){ 
       
        $('#practicesgrp').val('').trigger('change');
        $('#caremanagerid').val('').trigger('change');
        $('#practices').val('').trigger('change');
        $('#physician').val('').trigger('change');
        $('#timeframe').val('').trigger('change');
        $('#patient').val('').trigger('change');
        
         $('#patientdiv').hide();      
         var tf = $("#timeframe").val();
         getAlertHistoryList(null,null,null,tf,null);   
        });

    // function activealertpatientstatus(){ 
    //    // $('#alert-history-list tbody').on('change','.activealertpatientstatus',function(){
    //         alert("Hello");
    //         var table = $('#alert-history-list').DataTable();
    //         var rowdata = table.row($(this).parents('tr')).data();

    //        // console.log(rowdata);  
    //         var pfname = rowdata.pfname;
    //        // alert(pfname);
    //         var plname = rowdata.plname;
    //         var unit   = rowdata.unit;
    //         var patientname = pfname+" "+plname;
    //         var rpmid = rowdata.tempid;
    //         var pid = rowdata.pid;
            
    //         if(unit=='%'){
    //             var vital = 'Oxygen';
    //         }
    //         else if(unit=='mm[Hg]'){
    //            var vital = 'Blood Pressure';
    //         }
    //         else if(unit=='beats/minute'){
    //            var vital =  'Heartrate';
    //         } 
    //         else{
    //             var vital = '';
    //         }

           
    //         $("#rpm_cm_modal").modal('show');
    //         $("#patientname").text(patientname);
    //         $("#patientvital").text(vital);     
    //         $("#patient_id").val(pid); 
    //         $("#vital").val(vital); 
    //         $("#rpm_observation_id").val(rpmid);  
    //    // });

    // };


  
    </script>
@endsection