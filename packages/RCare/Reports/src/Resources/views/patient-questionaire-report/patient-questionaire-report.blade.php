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
            <h4 class="card-title mb-3">Patient Questionnaire Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
             <form id="patient_questionnaire_report" name="patient_questionnaire_report" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectworklistpractices("practices", ["id" => "practices", "class" => "select2"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
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
                        <!-- style="display: none;" -->
                        <label for="date">Steps</label> 
                        <!-- module_id stage_id --> 
                        @selectGQ("genquestionselection",$module_id,$stage_id,["id" => "genquestionselection", "class" => "mb-3 bottom"])    
                    </div>
                    <div class="row col-md-2 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="patientquestionairesearchbutton">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="patientquestionaireresetbutton">Reset</button>
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
                    <table id="patient-questionaire-list-table" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width ="10px">Sr No.</th>
                            <th width ="10px">Month Year</th>                       
                            <th width ="10px">Patient</th> 
                            <th width ="10px">Practice</th>
                            <th width ="10px">DOB</th>
                            <th width ="10px">BMI</th>
                            <th width ="10px">BP</th>
                            <th width ="10px">HgA1c</th>
                            <th width ="10px">Questions</th>
                            <th width ="10px">Answers</th>
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
                        topic_month_year = full['topic_month_year'];
                        vitals_month_year = full['vitals_month_year'];
                        hga1c_month_year = full['hga1c_month_year'];
                        if(full['topic_month_year'] == null && full['vitals_month_year'] == null &&  full['hga1c_month_year'] == null){
                            month_year = '';
                        }
                        if(full['topic_month_year']!=null){
                            month_year = topic_month_year;
                        } else if(full['vitals_month_year']!=null){
                            month_year = vitals_month_year;
                        }else if(full['hga1c_month_year']!=null){
                            month_year = hga1c_month_year;
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['topic_month_year'] == null && full['vitals_month_year'] == null &&  full['hga1c_month_year'] == null){
                                month_year = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return month_year;
                            }               
                        }
                    },
                    orderable: true
                },                
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
                {data: null, 
                    mRender: function(data, type, full, meta){
                        practicename = full['practicename'];
                        if(full['practicename'] == null){
                            practicename = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['practicename'] == null){
                                practicename = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return practicename;
                            }               
                        }
                    },
                    orderable: true
                },
                // {data: null, type: 'date-mm-dd-yyyy',
                //     mRender: function(data, type, full, meta){
                //         pdob = full['pdob'];
                //         if(full['pdob'] == null){
                //             pdob = '';
                //         }
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             if(full['pdob'] == null){
                //                 pdob = '';
                //             }
                //             if(data!='' && data!='NULL' && data!=undefined){
                //                 return pdob;
                //             }               
                //         }
                //     },
                //     orderable: true
                // },
                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                    if (value === null) return "";
                    return util.viewsDateFormat(value);
                    }
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
                        option = full['option'];
                        if(full['option'] == null){
                            option = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['option'] == null){
                                option = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return option;
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
            table1 = util.renderDataTable('patient-questionaire-list-table', url, columns, "{{ asset('') }}");
              
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
            //console.log($(this).val()+"test"+{{ getPageModuleName() }});
            if($(this).val()=='0' || $(this).val()==''){
                //getToDoListReport('0');
                util.updatePatientList(parseInt(''), {{ getPageModuleName() }}, $("#patient"));
            } else {    
                util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
                    
            }
                //util.updatePatientList(parseInt($(this).val()), $("#patient"));
        });

        $('#patientquestionairesearchbutton').click(function(){ 
            var practices=$('#practices').val();
            var patient=$('#patient').val();
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
            if(fromdate1 == '' || fromdate1 == null){
                $('#fromdate').val(firstDayWithSlashes);                         
                $('#todate').val(currentdate);
                var fromdate1=$('#fromdate').val();
                var todate1=$('#todate').val(); 
                getpatientquestionairereport(practices,patient,fromdate1,todate1,genquestionselection); 
            } 

        });

         $('#patientquestionaireresetbutton').click(function(){  
            $('#practices').val('').trigger('change'); 
            $('#patient').val('').trigger('change');
            $('#genquestionselection').val('').trigger('change');
            $('#fromdate').val(firstDayWithSlashes);                         
            $('#todate').val(currentdate);
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();           // getquestionairereport(null,null,null,fromdate1,todate1,null); 
            getpatientquestionairereport(null,null,fromdate1,todate1,null);   
        });

    }); 
    </script>
@endsection