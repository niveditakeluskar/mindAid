@extends('Theme::layouts_2.to-do-master')       
@section('page-css')
<style type="text/css">   
    button#patientdetails {
        margin-left: 20px;
        float: right;
    }
</style>
 @section('page-title')

@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Patient Vitals Report</h4>
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
                            <label for="patient">Patient Name</label>
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
                            <th width="15px">Sr No.</th>
                            <th>Organization</th>
                            <th>Practice Name</th>
                            <th>Patient First Name</th>  
                            <th>Patient Last Name</th>
                            <th>DOB</th>   
                            <th>BP & BMI Date</th>                     
                            <th>BP Value</th> 
                            <th>BMI Value</th>
                            <th>HgA1c Date</th> 
                            <th>HgA1c Value</th>
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

        var getPatientVitals = function(practicegrp = null,practices = null,patient = null,fromdatetime=null,todatetime=null,) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null, mRender: function(data, type, full, meta){
                                    practice_grp = full['practicegrp'];
                                    if(full['practicegrp'] == null){
                                        practice_grp = ''; 
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_grp; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    practice_name = full['practice'];
                                    if(full['practice'] == null){
                                        practice_name = ''; 
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_name; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    patient_fname = full['fname'];
                                    if(full['fname'] == null){
                                        patient_fname = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return patient_fname; 
                                    }
                                },
                                orderable: true
                                },
                                 {data: null, mRender: function(data, type, full, meta){
                                    patient_lname = full['lname'];
                                    if(full['lname'] == null){
                                        patient_lname = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return patient_lname; 
                                    }
                                },
                                orderable: true
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        dob = full['dob'];
                                        if(full['dob'] == null){
                                            dob = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             return moment(dob).format('MM-DD-YYYY');      
                                        }
                                    },
                                    orderable: true
                                },
                                // {data:null,
                                //     mRender: function(data, type, full, meta){
                                //         bp_bmi_date = full['bp_bmi_date'];
                                //         if(full['bp_bmi_date'] == null){
                                //             bp_bmi_date = '';
                                //         }
                                //         if(data!='' && data!='NULL' && data!=undefined){
                                //              return bp_bmi_date;      
                                //         }
                                //     }, 
                                //     orderable: true 
                                // },
                                {data: 'bp_bmi_date', type: 'date-dd-mmm-yyyy', name: 'bp_bmi_date', "render":function (value) {
                                    if (value === null) return ""; 
                                        return util.viewsDateFormat(value);
                                    }
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    bp = full['bp'];
                                    if(full['bp'] == null){
                                        bp = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return bp; 
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    bmi = full['bmi'];
                                    if(full['bmi'] == null){
                                        bmi = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return bmi; 
                                    }
                                },
                                orderable: true
                                },

                                {data: 'hga1c_date', type: 'date-dd-mmm-yyyy', name: 'hga1c_date', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    hga1c_value = full['hga1c_val'];
                                    if(full['hga1c_val'] == null){
                                        hga1c_value = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                            return hga1c_value; 
                                    }
                                },
                                orderable: true
                                },

                            ];
             
            
            if(practicegrp==''){ practicegrp=null;}
            if(practices==''){ practices=null;}
            if(patient==''){patient=null;}  
            if(fromdatetime==''){ fromdatetime=null; }
            if(todatetime=='')  { todatetime=null; }
            
            var url = "/reports/patient-vitals-report-search/"+practicegrp+'/'+practices+'/'+patient+'/'+fromdatetime+'/'+todatetime 
            // console.log(url); 
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

    var patientdetailsmodal = function(prac_id,fromdate,todate){
        getChildListReport(prac_id,fromdate,todate);      
        $('#patientdetailmodel').modal('show');
    }
$(document).ready(function(){


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
    var practicegrp = $("input[name='practicesgrp']").val();
    if (practicegrp==''){
        practicegrp = 0;
    }   
    
    $('#fromdate').val(firstDayWithSlashes);                         
    $('#todate').val(currentdate);
    var fromdatetime=$('#fromdate').val();
    var todatetime=$('#todate').val();
    getPatientVitals(practicegrp,practices,patient,fromdatetime,todatetime);       

}); 

$('#searchbutton').click(function(){
     var practicegrp =$('#practicesgrp').val();
     var practices=$('#practices').val();
     var patient=$('#patient').val();
     var fromdatetime=$('#fromdate').val();
     var todatetime=$('#todate').val();   
     getPatientVitals(practicegrp,practices,patient,fromdatetime,todatetime);   
});

$("#month-reset").click(function(){  
   $('#practicesgrp').val('').trigger('change');
   $('#patient').val('').trigger('change');
   $('#practices').val('').trigger('change');
   $('#fromdate').val(firstDayWithSlashes);                       
   $('#todate').val(currentdate);
    var fromdatetime=$('#fromdate').val();
    var todatetime=$('#todate').val(); 
    getPatientVitals(null,null,null,fromdatetime,todatetime);   
});

</script>
@endsection