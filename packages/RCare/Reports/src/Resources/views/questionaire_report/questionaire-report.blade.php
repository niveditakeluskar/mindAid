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
            <h4 class="card-title mb-3">Questionaire Report</h4>
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
                            <th>Year Month</th>                       
                            <th>BMI Count</th> 
                            <th>BP Count</th>
                            <th>BMI Greater Than 25</th>
                            <th>BMI Less Than 18</th>
                            <th>BP Systolic 140 And Diastolic 90 & Greater</th>
                            <th>BP Systolic 180 And Diastolic 110 & Greater</th> 
                            <th>HgA1c Greater Than 7</th>           
                            <th>HgA1c Less Than 6.9</th>
                            <th>Questions</th>
                            <th>Answers</th>
                            <th>Patient Count</th>
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

        var getquestionairereport = function(practicesgrp = null,practices = null,provider=null,fromdate1=null,todate1=null,genquestionselection=null) { //,genquestionselection=null
         //   $.fn.dataTable.ext.errMode = 'throw';
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, 
                    mRender: function(data, type, full, meta){
                        month_year = full['month_year'];
                        if(full['month_year'] == null){
                            month_year = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['month_year'] == null){
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
                        bmicount = full['bmicount'];
                        if(full['bmicount'] == null){
                            bmicount = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bmicount'] == null){
                                bmicount = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bmicount;
                            }               
                        }
                    },
                    orderable: true
                }, 
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bpcount = full['bpcount'];
                        if(full['bpcount'] == null){
                            bpcount = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bpcount'] == null){
                                bpcount = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bpcount;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bmi_greater_25 = full['bmi_greater_25'];
                        if(full['bmi_greater_25'] == null){
                            bmi_greater_25 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bmi_greater_25'] == null){
                                bmi_greater_25 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bmi_greater_25;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bmi_less_18 = full['bmi_less_18'];
                        if(full['bmi_less_18'] == null){
                            bmi_less_18 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bmi_less_18'] == null){
                                bmi_less_18 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bmi_less_18;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bp_140_90 = full['bp_140_90'];
                        if(full['bp_140_90'] == null){
                            bp_140_90 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bp_140_90'] == null){
                                bp_140_90 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bp_140_90;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        bp_180_110 = full['bp_180_110'];
                        if(full['bp_180_110'] == null){
                            bp_180_110 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['bp_180_110'] == null){
                                bp_180_110 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return bp_180_110;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        hga1c_greater_7 = full['hga1c_greater_7'];
                        if(full['hga1c_greater_7'] == null){
                            hga1c_greater_7 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['hga1c_greater_7'] == null){
                                hga1c_greater_7 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return hga1c_greater_7;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        hga1c_less_7 = full['hga1c_less_7'];
                        if(full['hga1c_less_7'] == null){
                            hga1c_less_7 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['hga1c_less_7'] == null){
                                hga1c_less_7 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return hga1c_less_7;
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
                },
                {data: null, 
                    mRender: function(data, type, full, meta){
                        count = full['count'];
                        if(full['count'] == null){
                            count = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['count'] == null){
                                count = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return count;
                            }               
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
            if(genquestionselection==''){ genquestionselection=null; }

            var url = "/reports/questionaire_list/search/"+practicesgrp+'/'+practices+'/'+provider+'/'+fromdate1+'/'+todate1+'/'+genquestionselection;
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
        getquestionairereport(null,null,null,fromdate1,todate1,null);    

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

        $('#questionairesearchbutton').click(function(){ 
            var practices=$('#practices').val();
            var provider=$('#physician').val();
            var practicesgrp = $('#practicesgrp').val();
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
                
                getquestionairereport(practicesgrp,practices,provider,fromdate1,todate1,genquestionselection);    
            }        

        });

         $('#questionaireresetbutton').click(function(){    
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change'); 
            $('#physician').val('').trigger('change');
            $('#genquestionselection').val('').trigger('change');
            $('#fromdate').val(firstDayWithSlashes);                         
            $('#todate').val(currentdate);
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();           // getquestionairereport(null,null,null,fromdate1,todate1,null); 
           getquestionairereport(null,null,null,fromdate1,todate1,null);   
        });

    }); 
    </script>
@endsection