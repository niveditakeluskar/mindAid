@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">Chart List</h4>  
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>

@include('Rpm::chart-new.filters-alert-history')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">           
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="chartlist-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>
                            <th width="20px">TimeStamp</th>
                            <th width="20px">Patient</th>
                            <th width="20px">DOB</th>
                            <th width="20px">Clinic</th>
                            <!-- <th width="20px">Provider</th>                            
                            <th width="20px">Care Manager</th>
                            <th width="20px">Vital</th>
                            <th width="20px">Range</th>
                            <th width="20px">Reading</th>
                            <th width="20px">CM Notes</th> -->
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
                <form action="{{route('save.rpm.notes')}}" name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="patientname" id="patientname"></label>&nbsp; &nbsp;
                                <label for="patientvital" id="patientvital"></label><br>
                                <input type="hidden"  name="rpm_observation_id" id="rpm_observation_id" value=""/>
                                <input type="hidden" name="patient_id" id="patient_id" value=""/>
                                <input type="hidden" name="vital" id="vital" value=""/>  

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


@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">

        var getAlertHistoryList = function(practicesgrp = null,practices = null,provider = null,timeframe = null,caremanagerid = null) {
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

                                { data: 'patients',name: 'patients'
                                    // mRender: function(data, type, full, meta){
                                    //     m_Name = full['pmname'];
                                    //         if(full['pmname'] == null){
                                    //             m_Name = '';
                                    //     }
                                    //     if(data!='' && data!='NULL' && data!=undefined){
                                            
                                    //        return ["<a href='/rpm/patient-alert-data-list/"+full['pid']+"/"+unitval+"'><img src='http://rcareproto2.d-insights.global/assets/images/faces/avatar.png' width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
                                    //         // return ["<a href='/rpm/device-traning/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                    //     }
                                    // },
                                    // orderable: false
                                },
                                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
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
                                }
                        //         { data: 'name',name: 'name',
                        //             mRender: function(data, type, full, meta){
                        //                 providername = full['providername'];
                        //                     if(full['providername'] == null){
                        //                         providername = '';
                        //                 }
                        //                 else{
                        //                     return providername;
                        //                 }
                                         
                        //             },
                        //             orderable: false
                        //         },
                        //         { data: 'caremanagerfname',name: 'caremanagerfname',
                        //             mRender: function(data, type, full, meta){
                        //                 caremanagerfname = full['caremanagerfname'];
                        //                     if(full['caremanagerfname'] == null){
                        //                         caremanagerfname = '';
                        //                 }
                        //                 else{
                        //                     return full['caremanagerfname']+' '+full['caremanagerlname']; 
                        //                 }
                                        
                        //             },
                        //             orderable: false
                        //         },
                        //         { data: 'unit',name: 'unit',
                        //             mRender: function(data, type, full, meta){
                        //                 r_unit = full['unit'];
                        //                     if(full['unit'] == null){
                        //                         r_unit = '';
                        //                     }
                        //                     else{
                        //                         if(r_unit == '%'){
                        //                             return 'Oxygen';
                        //                         }
                        //                         else if(r_unit == 'beats/minute'){
                        //                             return 'Heartrate';  
                        //                         }
                        //                         else{
                        //                             //mmHg
                                                   
                        //                             return 'Blood Pressure';
                        //                         }
                        //                     }
                                        
                        //             },
                        //             orderable: false  
                        //         },
                        //         { data: 'fname',name: 'fname',
                        //             mRender: function(data, type, full, meta){
                        //                 m_Name = full['mname'];
                        //                     if(full['mname'] == null){
                        //                         m_Name = '';
                        //                 }
                        //                 if(data!='' && data!='NULL' && data!=undefined){
                        //                     return 0;
                        //                 }
                        //             },
                        //             orderable: false
                        //         },
                        //         { data: 'reading',name: 'reading',
                        //             mRender: function(data, type, full, meta){
                        //                 m_reading = full['reading'];
                        //                 m_unit = full['unit'];
                        //                     if(full['reading'] == null){
                        //                         m_reading = '';
                        //                 }
                        //                 else{
                        //                     return m_reading+' '+m_unit;
                        //                 }
                        //                 // if(data!='' && data!='NULL' && data!=undefined){
                        //                 //     return 0;
                        //                 // }
                        //             },
                        //             orderable: false
                        //         },

                        //  // { data: 'action', name: 'action', orderable: false, searchable: false},
                        //  { data: 'action', name: 'action', orderable: false, searchable: false}
                        // // { data: 'pfname', name: 'pfname', 
                        // // mRender: function (data, type, full, meta){
                        // //             //m_Name = full['pmname'];
                        // //  pfname = full['pfname'];
                        // //             plname = full['plname'];
                        // //             unit = full['unit'];
                        // //             pid = full['pid']           
                        // //              return '<button type="button" id="activealertpatientstatus_'+full['pid']+'"class="activealertpatientstatus" name="activealertpatientstatus">Click</button>';
                        // //          }, orderable: false
                        // // }

                        // // {data: null, 'render': function (data, type, full, meta){
                        // //              return '<input type="checkbox" id="activealertpatientstatus_'+full['pid']+'" class="activealertpatientstatus" c  value="'+full['pid']+'">';
                        // //          },
                        // //          orderable: false
                               
                        // //     }
                        ];  

                        if(practicesgrp==''){practicesgrp=null;}
                        if(practices==''){practices=null;} 
                        if(provider==''){provider=null;}            
                        if(timeframe==''){ timeframe=null;}
                        if(caremanagerid==''){ caremanagerid=null; }  
                        
            
               
                var url ="/rpm/chartlist-list/search/"+practicesgrp+"/"+practices+"/"+provider+"/"+timeframe+"/"+caremanagerid;  
                // console.log(url);
                var table1 = util.renderDataTable('chartlist-list', url, columns, "{{ asset('') }}"); 
            
        } 
    </script>
    <script>
     $(document).ready(function() {
       
        getAlertHistoryList(null,null,null,null,null);
        rpmlist.init();
        alerthistory.init();

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

        $("[name='practices']").on("change", function (){
                var practice_id = $(this).val();  
                if(practice_id==0){
                    getAlertHistoryList();  
                }
                if(practice_id!=''){
                    util.getPatientList(parseInt(practice_id),$("#patient"));
                }
                else{
                    getAlertHistoryList();    
                }
               
        });

        $("[name='practices']").on("change", function () {
            var practice_id = $(this).val(); 
            if(practice_id!=''){ 
                util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"));
               
                }
                else{
                    getAlertHistoryList();  
                }
       
        });

        // $("[name='provider']").on("change", function () {
        //     var provider = $(this).val(); 
        //     if(provider!=''){ 
        //         util.getRpmProviderPatientList(parseInt($(this).val()), $("#patient"));
        //         }
        //         else{
        //             getAlertHistoryList();  
        //         }        
        // });

        util.getToDoListData(0, {{getPageModuleName()}});

        });

    $('#searchbutton').click(function(){ 
   
    var practicesgrp = $('#practicesgrp').val(); 
    var practice=$('#practices').val();
    var provider=$('#physician').val(); 
    var timeframe=$('#timeframe').val();  
    var caremanagerid=$('#caremanagerid').val();
    getAlertHistoryList(practicesgrp,practice,provider,timeframe,caremanagerid);

    });

    $('#resetbutton').click(function(){ 
       
        $('#practicesgrp').val('').trigger('change');
        $('#caremanagerid').val('').trigger('change');
        $('#practices').val('').trigger('change');
        $('#physician').val('').trigger('change');
        $('#timeframe').val('').trigger('change');
        
        var practicesgrp =  $('#practicesgrp').val();
        var practice=$('#practices').val();
        var provider=$('#physician').val();
        var timeframe=$('#timeframe').val();
        var caremanagerid=$('#caremanagerid').val(); 
        getAlertHistoryList(practicesgrp,practice,provider,timeframe,caremanagerid);  
            
        });

    function activealertpatientstatus(){ 
       // $('#alert-history-list tbody').on('change','.activealertpatientstatus',function(){
            alert("Hello");
            var table = $('#chartlist-list').DataTable();
            var rowdata = table.row($(this).parents('tr')).data();

           // console.log(rowdata);  
            var pfname = rowdata.pfname;
           // alert(pfname);
            var plname = rowdata.plname;
            var unit   = rowdata.unit;
            var patientname = pfname+" "+plname;
            var rpmid = rowdata.tempid;
            var pid = rowdata.pid;
            
            if(unit=='%'){
                var vital = 'Oxygen';
            }
            else if(unit=='mm[Hg]'){
               var vital = 'Blood Pressure';
            }
            else if(unit=='beats/minute'){
               var vital =  'Heartrate';
            } 
            else{
                var vital = '';
            }

           
            $("#rpm_cm_modal").modal('show');
            $("#patientname").text(patientname);
            $("#patientvital").text(vital);     
            $("#patient_id").val(pid); 
            $("#vital").val(vital); 
            $("#rpm_observation_id").val(rpmid);  
       // });

    };

///// Add On 14-06-2021 

$('#chartlist-list tbody').on('click','.activealertpatientstatus',function(){
        //if($(this).is(":checked")){
            var table = $('#chartlist-list').DataTable();
            var rowdata = table.row($(this).parents('tr')).data();
            //alert("rowdata".rowdata);
            // console.log(rowdata);  
            var pfname = rowdata.pfname;
            var plname = rowdata.plname;
            var unit   = rowdata.unit;
            var patientname = pfname+" "+plname;
            var rpmid = rowdata.tempid;
            var pid = rowdata.pid;
            
            if(unit=='%'){
                var vital = 'Oxygen';
            }
            else if(unit=='mm[Hg]'){
               var vital = 'Blood Pressure';
            }
            else if(unit=='beats/minute'){
               var vital =  'Heartrate';
            } 
            else{
                var vital = '';
            }

           
            $("#rpm_cm_modal").modal('show');
            $("#patientname").text(patientname);
            $("#patientvital").text(vital);     
            $("#patient_id").val(pid); 
            $("#vital").val(vital); 
            $("#rpm_observation_id").val(rpmid);  

        // }
        // else{
        //     alert("2");
        // }   

    }); 


/// End of Address column field 14-06-2021  
  
    </script>
@endsection