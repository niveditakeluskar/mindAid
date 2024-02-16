@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')   
@endsection 
@endsection
@section('main-content')
<?php
$module_id = '3';
$submodule_id = '19';
$stage_id = getFormStageId($module_id, $submodule_id, 'General Question');
?>
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Patient Questionaire Report</h4>
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
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice</label>                  
                        @selectworklistpractices("practices", ["id" => "practices", "class" => "select2"])   
                    </div>
                    <div class="col-md-3 form-group mb-6">
                        <label for="practicename">Patient Name</label>
                        @selectallworklistccmpatient("Patient",["id" => "patient", "class" => "select2"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="date">From date</label>
                         @date('date',["id" => "fromdate"])                                                 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="date">To date</label>
                        @date('date',["id" => "todate"])                        
                    </div>
                    <div class="col-md-2 form-group mb-3 steps" id="steps"> 
                        <label for="date">Steps</label> 
                        @selectGQ("genquestionselection",$module_id,$stage_id,["id" => "genquestionselection", "class" => "mb-3 bottom"])    
                    </div>
                    <div class="row col-md-2 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="questionairesearchbutton">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="questionaireresetbutton">Reset</button>
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
                    <table id="questionaire-list-table" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Patient Name</th> 
                            <th>Dob</th>                      
                            <th>Practice</th> 
                            <th>BMI</th>
                            <th>BP</th> 
                            <th>HgA1c</th>           
                            <th>Questions</th>
                            <th>Answers</th>
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

        var getpatientquestionairereport = function(practices = null,patient=null,fromdate1=null,todate1=null,genquestionselection=null) { //,genquestionselection=null
         //   $.fn.dataTable.ext.errMode = 'throw';
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
                        dob = full['pdob'];
                        if(full['pdob'] == null){
                            dob = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return moment(dob).format('MM-DD-YYYY');      
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        assignby1 = full['practicename'];
                        if((full['practicename'] == null) ){
                            assignby1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if((full['practicename'] == null) ){
                                assignby1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return assignby1 ;
                            }               
                        }
                    },
                    orderable: true
                }, 
                
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bmi = full['bmi'];
                        if(full['bmi'] == null){
                            bmi = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bmi'] == null){
                                bmi = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bmi;
                            }               
                        }
                    },
                    orderable: true
                },                
                  {data: null, 
                    mRender: function(data, type, full, meta){
                        bp = full['bp'];
                        if(full['bp'] == null){
                            bp = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bp'] == null){
                                bp = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bp;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: null, 
                    mRender: function(data, type, full, meta){
                        hga1c = full['hga1c'];
                        if(full['hga1c'] == null){
                            hga1c = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['hga1c'] == null){
                                hga1c = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return hga1c;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        topic = full['topic'];
                        if(full['topic'] == null){
                            topic = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['topic'] == null){
                                topic = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return topic;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        notes = full['notes'];
                        if(full['notes'] == null){
                            notes = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['notes'] == null){
                                notes = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return notes;
                            }               
                        }
                    },
                    orderable: true
                }
                ];
            
           // debugger; 

            if(practices=='')
            {
                practices=null;
            } 
            
            if(patient=='')
            {
                patient=null;
            }
            if(fromdate1==''){ fromdate1=null; }
            if(todate1=='')  { todate1=null; }
            if(genquestionselection==''){ genquestionselection=null; }

            var url = "/reports/patient_questionaire_list/search/"+practices+'/'+patient+'/'+fromdate1+'/'+todate1+'/'+genquestionselection;
            console.log(url);
            table1 = util.renderDataTable('questionaire-list-table', url, columns, "{{ asset('') }}");
              
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
        getpatientquestionairereport(null,null,fromdate1,todate1,null);    

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
        $('#questionairesearchbutton').click(function(){ 
            var practices=$('#practices').val();
            var patient = $('#patient').val();
            var genquestionselection=$('#genquestionselection').val();
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();

            if(todate1 < fromdate1)
            {                
                $('#todate').addClass("is-invalid");
                $('#todate').next(".invalid-feedback").html("Please select to-date properly .");
                $('#fromdate').addClass("is-invalid");
                $('#fromdate').next(".invalid-feedback").html("Please select from-date properly .");   
            }             
            else{                
                $('#todate').removeClass("is-invalid");
                $('#todate').removeClass("invalid-feedback");
                $('#fromdate').removeClass("is-invalid");
                $('#fromdate').removeClass("invalid-feedback");
                
                getpatientquestionairereport(practices,patient,fromdate1,todate1,genquestionselection);    
            }        

        });

         $('#questionaireresetbutton').click(function(){    
            $('#patient').val('').trigger('change');
            $('#practices').val('').trigger('change');
            $('#genquestionselection').val('').trigger('change');
            $('#fromdate').val(firstDayWithSlashes);                         
            $('#todate').val(currentdate);
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();           // getpatientquestionairereport(null,null,null,fromdate1,todate1,null); 
           getpatientquestionairereport(null,null,fromdate1,todate1,null);   
        });

    }); 
    </script>
@endsection