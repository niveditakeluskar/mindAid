@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
 @section('page-title')

@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Clinical-insight</h4>
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
                    <table id="initial-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>

                            <th>Month Year</th>                     

                            <th>CCM Enrolled</th>
                            <th>CCM Active Patients</th> 
                            <th>HTN</th>
                            <th>Diabetes</th>
                            <th>CHF</th>
                            <th>CKD</th> 
                            <th>Hyperlipidemia</th>
                            <th>COPD</th>
                            <th>Asthma</th>
                            <th>Depression</th>
                            <th>Anxiety</th>
                            <th>Dementia</th>
                            <th>Arthritis</th>
                            <th>Other Diagnosis</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>39 & Younger</th>
                            <th>40 to 49</th>
                            <th>50 to 59</th>
                            <th>60 to 69</th>
                            <th>70 to 79</th>
                            <th>80 to 89</th>
                            <th>90 to 99</th>
                            <th>100 & Above</th>
                            <th>Hospitalization</th>
                            <th>ED Visit</th>
                            <th>Urgent Care</th>
                            <th>Social Needs</th>
                            <th>Not Taking Medications AS Prescribed</th>
                            <th>Fell</th>
                            <th>Office Appointment</th>
                            <th>Resource Medication</th>
                            <th>Medication Renewal</th>
                            <th>Called Office Patientbehalf</th>
                            <th>Referral Support</th>
                            <th>NO Other Services</th>
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

        var getinitialList = function(practicesgrp=null,practices=null,provider=null,fromdate1=null,todate1=null,) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null, mRender: function(data, type, full, meta){
                                    month_year = full['month_year'];
                                    if(full['month_year'] == null){
                                        month_year = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return month_year; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){

                                    ccm_enrolled = full['ccm_enrolled'];
                                    if(full['ccm_enrolled'] == null){
                                        ccm_enrolled = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return ccm_enrolled; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    ccm_active_patients = full['ccm_active_patients'];
                                    if(full['ccm_active_patients'] == null){
                                        ccm_active_patients = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return ccm_active_patients; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    htn = full['htn'];
                                    if(full['htn'] == null){
                                        htn = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return htn; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    diabetes = full['diabetes'];
                                    if(full['diabetes'] == null){
                                        diabetes = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return diabetes; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    chf = full['chf'];
                                    if(full['chf'] == null){
                                        chf = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return chf; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    ckd = full['ckd'];
                                    if(full['ckd'] == null){
                                        ckd = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return ckd; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    hyperlipidemia = full['hyperlipidemia'];
                                    if(full['hyperlipidemia'] == null){
                                        hyperlipidemia = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return hyperlipidemia; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    copd = full['copd'];
                                    if(full['copd'] == null){
                                        copd = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return copd; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    asthma = full['asthma'];
                                    if(full['asthma'] == null){
                                        asthma = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return asthma; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    depression = full['depression'];
                                    if(full['depression'] == null){
                                        depression = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return depression; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    anxiety = full['anxiety'];
                                    if(full['anxiety'] == null){
                                        anxiety = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return anxiety; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    dementia = full['dementia'];
                                    if(full['dementia'] == null){
                                        dementia = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return dementia; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    arthritis = full['arthritis'];
                                    if(full['arthritis'] == null){
                                        arthritis = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return arthritis; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    other_diagnosis = full['other_diagnosis'];
                                    if(full['other_diagnosis'] == null){
                                        other_diagnosis = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return other_diagnosis; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    female = full['female'];
                                    if(full['female'] == null){
                                        female = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return female; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    male = full['male'];
                                    if(full['male'] == null){
                                        male = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return male; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    younger = full['younger'];
                                    if(full['younger'] == null){
                                        younger = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return younger; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age40 = full['age_40to49'];
                                    if(full['age_40to49'] == null){
                                        age40 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age40; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age50 = full['age_50to59'];
                                    if(full['age_50to59'] == null){
                                        age50 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age50; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age60 = full['age_60to69'];
                                    if(full['age_60to69'] == null){
                                        age60 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age60; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age70 = full['age_70to79'];
                                    if(full['age_70to79'] == null){
                                        age70 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age70; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age80 = full['age_80to89'];
                                    if(full['age_80to89'] == null){
                                        age80 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age80; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    age90 = full['age_90to99'];
                                    if(full['age_90to99'] == null){
                                        age90 = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return age90; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    above = full['above'];
                                    if(full['above'] == null){
                                        above = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return above; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    hospitalization = full['hospitalization'];
                                    if(full['hospitalization'] == null){
                                        hospitalization = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return hospitalization; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    er_visit = full['er_visit'];
                                    if(full['er_visit'] == null){
                                        er_visit = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return er_visit; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    urgent_care = full['urgent_care'];
                                    if(full['urgent_care'] == null){
                                        urgent_care = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return urgent_care; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    social_needs = full['social_needs'];
                                    if(full['social_needs'] == null){
                                        social_needs = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return social_needs; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    medications_prescribed = full['medications_prescribed'];
                                    if(full['medications_prescribed'] == null){
                                        medications_prescribed = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return medications_prescribed; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    fallen = full['fallen'];
                                    if(full['fallen'] == null){
                                        fallen = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return fallen; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    office_appointment = full['office_appointment'];
                                    if(full['office_appointment'] == null){
                                        office_appointment = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return office_appointment; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    resource_medication = full['resource_medication'];
                                    if(full['resource_medication'] == null){
                                        resource_medication = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return resource_medication; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    medication_renewal = full['medication_renewal'];
                                    if(full['medication_renewal'] == null){
                                        medication_renewal = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return medication_renewal; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    called_office_patientbehalf = full['called_office_patientbehalf'];
                                    if(full['called_office_patientbehalf'] == null){
                                        called_office_patientbehalf = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){

                                       // called_office_patientbehalf = full['called_office_patientbehalf'];
        
                                        //return patient_count + '   '+'<button type="button" id="patientdetails" onclick=patientdetailsmodal("'+prac_id+'") class="btn btn-primary" >Details</button>';
                                         return called_office_patientbehalf; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    referral_support = full['referral_support'];
                                    if(full['referral_support'] == null){
                                        referral_support = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return referral_support; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    no_other_services = full['no_other_services'];
                                    if(full['no_other_services'] == null){
                                        no_other_services = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return no_other_services; 
                                    }
                                },
                                orderable: true
                                }

                            ];
             
           // debugger;
            if(practicesgrp=='')
            { 
                practicesgrp=null;
            } 
             if(practices=='')
            { 
                practices=null;
            } 
             if(provider=='')
            { 
                provider=null;
            } 
            if(fromdate1==''){ fromdate1=null; }
            if(todate1=='')  { todate1=null; }
            

            var url = "/reports/innitalListSearch/"+practicesgrp+'/'+practices+'/'+provider+'/'+fromdate1+'/'+todate1;
            console.log(url); 
            table1 = util.renderDataTable('initial-list', url, columns, "{{ asset('') }}");
              
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

    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    getinitialList(null,null,null,fromdate1,todate1);       
    
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

    var practice=$('#practices').val();
    var provider=$('#physician').val();
    var practicesgrp = $('#practicesgrp').val();
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();   
     getinitialList(practicesgrp,practice,provider,fromdate1,todate1);   
    
});

$("#month-reset").click(function(){       
    $('#practicesgrp').val('').trigger('change');
    $('#practices').val('').trigger('change'); 
    $('#physician').val('').trigger('change');
    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();
    getinitialList(null,null,null,fromdate1,todate1);       
});

</script>
@endsection