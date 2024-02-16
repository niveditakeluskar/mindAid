@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
 @section('page-title')
    Call Additional Services Report
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Call and Additional Services Report</h4>
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
                      
                        <div class="col-md-2 form-group mb-3">
                            <label for="practice">Practice</label>
                            @selectrpmpractices("practices", ["id" => "practices","class" => "select2"])
                        </div>
                    
                        <div class="col-md-2 form-group mb-3">
                            <label for="provider">Patient Name</label>
                            @selectpatient("patient_id", ["id" => "patient","class" => "select2"])   
                        </div>
                        
                        <div class="col-md-2 form-group mb-2">

                        <label for="date">From Date</label>
                            @date('date',["id" => "fromdate"])          
                        </div>

                        <div class="col-md-2 form-group mb-3">
                            <label for="date">To Date</label>
                            @date('date',["id" => "todate"])                     
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
                            <th width="15px">Sr. No.</th>
                            <th width="200px">Patient</th>
                            <th width="100px">Practice</th>
                            <th>Call Record Date</th>
                            <th>Call Status</th> 
                            <!-- <th width="97px">Emr entry completed</th> -->
                            <th>Schedule office appointment</th>                          
                            <th>Resources for Medication</th>
                            <th>Medical Renewal</th> 
                            <th>Called Office Patient Behalf</th> 
                            <th>Referral Support</th>  
                            <th>No. Other Services</th>                        
                            <th>Assigned Care Manager</th> 
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

        
        


        var getPatientCallActivityServiceList = function(practices = null,patient = null,fromdate1=null,todate1=null,) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        patient_name = full['patient_name'];
                                        if(full['patient_name'] == null){
                                            patient_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return patient_name;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    practicename = full['practicename'];
                                    if(full['practicename'] == null){
                                        practicename = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return practicename; 
                                    }
                                },
                                orderable: true
                                },


                                {data: 'call_record_date', type: 'date-dd-mmm-yyyy', name: 'call_record_date', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        call_continue_status = full['call_continue_status'];
                                        if(full['call_continue_status'] == null){
                                            call_continue_status = '';
                                        } 
                                        if(full['call_continue_status'] == '000' || full['call_continue_status'] == 000){
                                            call_continue_status = ''; 
                                        } 
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return call_continue_status;
                                        }
                                    },
                                    orderable: true
                                },
                              
                                // {data:null,
                                //     mRender: function(data, type, full, meta){
                                //        emr_entry_completed = full['emr_entry_completed'];
                                //         if(full['emr_entry_completed'] == null){
                                //            emr_entry_completed = '';
                                //         }
                                //         if(data!='' && data!='NULL' && data!=undefined){
                                //             return emr_entry_completed;
                                //         }
                                //     },
                                //     orderable: true
                                // },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        schedule_office_appointment = full['schedule_office_appointment']; 
                                        if(full['schedule_office_appointment'] == null){
                                            schedule_office_appointment = ''; 
                                        }
                                        if(full['schedule_office_appointment'] == 1){
                                            schedule_office_appointment = 'Yes';
                                        }
                                        if(full['schedule_office_appointment'] == 0){
                                            schedule_office_appointment = 'No'; 
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return schedule_office_appointment;
                                        }
                                    },
                                    orderable: true
                                },
                                
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        resources_for_medication = full['resources_for_medication'];
                                        if(full['resources_for_medication'] == null){
                                            resources_for_medication = '';
                                        }
                                        if(full['resources_for_medication'] == 1){
                                            resources_for_medication = 'Yes';
                                        }
                                        if(full['resources_for_medication'] == 0){
                                            resources_for_medication = 'No';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return resources_for_medication;
                                        }
                                    },
                                    orderable: true
                                },


                                {data:null,
                                    mRender: function(data, type, full, meta){
                                       medical_renewal = full['medical_renewal'];
                                        if(full['medical_renewal'] == null){
                                           medical_renewal = '';
                                        }
                                        if(full['medical_renewal'] == 1){
                                           medical_renewal = 'Yes';
                                        }
                                        if(full['medical_renewal'] == 0){
                                           medical_renewal = 'No';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return medical_renewal;
                                        }
                                    },
                                    orderable: true
                                },
                                

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        called_office_patientbehalf = full['called_office_patientbehalf'];
                                        if(full['called_office_patientbehalf'] == null){
                                           called_office_patientbehalf = '';
                                        }
                                        if(full['called_office_patientbehalf'] == 1){
                                           called_office_patientbehalf = 'Yes';
                                        }
                                        if(full['called_office_patientbehalf'] == 0){
                                           called_office_patientbehalf = 'No';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return called_office_patientbehalf;
                                        }
                                    },
                                    orderable: true
                                },

                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                       referral_support = full['referral_support'];
                                        if(full['referral_support'] == null){
                                           referral_support = '';
                                        }
                                        if(full['referral_support'] == 1){
                                           referral_support = 'Yes';
                                        }
                                        if(full['referral_support'] == 0){ 
                                           referral_support = 'No';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return referral_support;
                                        }
                                    },
                                    orderable: true
                                },

                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        no_other_services = full['no_other_services'];
                                        if(full['no_other_services'] == null){
                                            no_other_services = '';
                                        }
                                        if(full['no_other_services'] == 1){
                                            no_other_services = 'Yes';
                                        }
                                        if(full['no_other_services'] == 0){
                                            no_other_services = 'No';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return no_other_services;
                                        }
                                    },
                                    orderable: true
                                },
                                
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        caremanager = full['caremanager'];
                                        if(full['caremanager'] == null){
                                            caremanager = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return caremanager;
                                        }
                                    },
                                    orderable: true
                                },  
                                

                            ];
             
           // debugger;
           if(patient=='')
            {
                patient=null;
            } 

            if(practices=='')
            { 
                practices=null;
            } 
            if(fromdate1==''){ fromdate1=null; }
            if(todate1=='')  { todate1=null; }
            

            var url = "/reports/callActivityServiceListSearch/"+practices+'/'+patient+'/'+fromdate1+'/'+todate1 
            // var url = "/reports/call-status-report/search/"+practicesgrp+'/'+practice+'/'+provider+'/'+modules+'/'+activedeactivestatus+'/'+timeperiod;
            console.log(url); 
            var table1 = util.renderDataTable('patient-call-status-list', url, columns, "{{ asset('') }}");
              
        } 


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

    
$(document).ready(function(){
    $("[name='practices']").on("change", function () {
        var practiceId =$(this).val();
        if(practiceId =='' || practiceId=='0'){
            var  practiceId= null;
                // util.updatePatientList(parseInt(practiceId),parseInt(module_id), $("#patient"));
        }else if(practiceId!=''){
            util.getPatientList(parseInt(practiceId),$("#patient")); 
        }else { 
            // util.updatePatientList(parseInt($(this).val()),parseInt(module_id), $("#patient"));
        }
    });
    var patient = $("input[name='patient_id']").val();
    if (patient==''){
        patient = 0; 
    }
    var practices = $("input[name='practices']").val();
    if (practices==''){
        practices = 0;
    }
        
        $('#fromdate').val(firstDayWithSlashes);                         
        $('#todate').val(currentdate);
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val();
    getPatientCallActivityServiceList(practices,patient,fromdate1,todate1);       
    
    
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


    


}); 

$('#searchbutton').click(function(){
     var practices=$('#practices').val();
     var patient=$('#patient').val();
     var fromdate1=$('#fromdate').val();
     var todate1=$('#todate').val();   
     getPatientCallActivityServiceList(practices,patient,fromdate1,todate1);   
	
});

$("#month-reset").click(function(){          
    $('#patient').val('').trigger('change');
   $('#practices').val('').trigger('change');
   $('#fromdate').val(firstDayWithSlashes);                       
   $('#todate').val(currentdate);
    var practices=$('#practices').val();
    var patient=$('#patient').val(); 
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
});

</script>
@endsection