@extends('Theme::layouts_2.to-do-master')
@section('page-css')
 @section('page-title')
  Monthly Account Performance Report
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3"> Monthly Account Performance Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Reports::monthly-account-performance-report.summary-filter')
<div class="row mb-4"> 
    <div class="col-md-12 mb-4"> 
        <div class="card text-rightleft"> 
            <div class="card-body">
                <div class="table-responsive">
                <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="2" id="module_name">CCM</th>
                            <th colspan="16">Time Completed</th>
                        </tr>
                         
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="2">< 5 mins</th>
                            <th colspan="2">5-10 mins</th>
                            <th colspan="2">10-15 mins</th>
                            <th colspan="2">15-20 mins</th>
                            <th colspan="2"> >20 mins</th> 
                            <th colspan="2">All Total</th>
                        </tr>
                        <tr>
                            <th>Sr</th>
                            <th>Practice</th>
                            <th colspan="1">Enrolled</th>
                            <th colspan="1">Active</th>
                            <th colspan="1">#</th>
                            <th colspan="1">%</th>
                            <th colspan="1">#</th>
                            <th colspan="1">%</th>
                            <th colspan="1">#</th>
                            <th colspan="1">%</th>
                            <th colspan="1">#</th>
                            <th colspan="1">%</th>
                            <th colspan="1">#</th>
                            <th colspan="1">%</th>
                            <th colspan="1">#</th> 
                            <th colspan="1">%</th>
                            <th>New Enroll</th>
                            <th>AWV's</th> 
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
<style type="text/css">
  th,tr,td {border: 1px solid #dee2e6;}
    /*tr {outline: 1px solid #dee2e6;} */
    /*th {outline: 1px solid #dee2e6;}*/   
</style>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>

    <script type="text/javascript"> 
        var getMonthlyPatientList = function(practicesgrp = 0,practice = 0,modules = 0,from_month = 0,to_month = 0){
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'count', name: 'count'},
                {data: 'patient_count', name: 'patient_count'},
                {data: 'less_five_minutes', name: 'less_five_minutes'},
                {data: 'less_five_minutes', name: 'less_five_minutes', mRender: function(data, type, full, meta){
                    ccmcount = full['less_five_minutes'];
                    if(full['less_five_minutes'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        less_five_minutes = ((ccmcount*100)/full['count']);
                            return parseFloat(less_five_minutes).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'five_ten_minutes', name: 'five_ten_minutes'},
                {data: 'five_ten_minutes', name: 'five_ten_minutes', mRender: function(data, type, full, meta){
                    ccmcount = full['five_ten_minutes'];
                    if(full['five_ten_minutes'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        five_ten_minutes = ((ccmcount*100)/full['count']);
                            return parseFloat(five_ten_minutes).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'ten_fiften_minutes', name: 'ten_fiften_minutes'},
                {data: 'ten_fiften_minutes', name: 'ten_fiften_minutes', mRender: function(data, type, full, meta){
                    ccmcount = full['ten_fiften_minutes'];
                    if(full['ten_fiften_minutes'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        ten_fiften_minutes = ((ccmcount*100)/full['count']);
                            return parseFloat(ten_fiften_minutes).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'fiften_twenty_minutes', name: 'fiften_twenty_minutes'},
                {data: 'fiften_twenty_minutes', name: 'fiften_twenty_minutes', mRender: function(data, type, full, meta){
                    ccmcount = full['fiften_twenty_minutes'];
                    if(full['fiften_twenty_minutes'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        fiften_twenty_minutes = ((ccmcount*100)/full['count']);
                            return parseFloat(fiften_twenty_minutes).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'grether_twenty_minutes', name: 'grether_twenty_minutes'},
                {data: 'grether_twenty_minutes', name: 'grether_twenty_minutes', mRender: function(data, type, full, meta){
                    ccmcount = full['grether_twenty_minutes'];
                    if(full['grether_twenty_minutes'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        grether_twenty_minutes = ((ccmcount*100)/full['count']);
                            return parseFloat(grether_twenty_minutes).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'alltotalnum', name: 'alltotalnum'},
                {data: 'alltotalnum', name: 'alltotalnum', mRender: function(data, type, full, meta){
                    ccmcount = full['alltotalnum'];
                    if(full['alltotalnum'] == 0){
                        return 0;
                    }   
                    if(data!='' && data!='NULL' && data!=undefined){
                        alltotalnum = ((ccmcount*100)/full['count']);
                            return parseFloat(alltotalnum).toFixed(2);
                            }
                        },
                    orderable: true} ,
                {data: 'newenrolledcount', name: 'newenrolledcount'},
                {data: 'id', name: 'id'}
                ];

                if(practicesgrp=='')
                {
                    practicesgrp='null';
                }  
                if(practice=='')
                {
                    practice='null';
                }
                if(modules=='')
                {
                    modules='null';
                }
                if(from_month=='')
                {
                    from_month='null';
                }

                if(to_month=='')
                {
                    to_month='null';
                }
                // if(activedeactivestatus==''){activedeactivestatus= 'null';}
                var url = "/reports/monthly-account-personal-report/search/"+practicesgrp+"/"+practice+"/"+modules+"/"+from_month+"/"+to_month;      
                 //console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}"); 
        }
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            //  $('#cpatient-list').on('shown.bs.collapse', function () {
            //     $($.fn.dataTable.tables(true)).DataTable()
            //     .columns.adjust();
            // });
            // util.getToDoListData(0, {{getPageModuleName()}});
            getPatientData(); 
            util.getToDoListData(0, {{getPageModuleName()}});
            getMonthlyPatientList(); 
            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }

            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
             $("#from_month").val(current_MonthYear);
             $("#to_month").val(current_MonthYear);
      
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

            // $("[name='practices']").on("change", function () {
            //      util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            // });


            util.updateSubModuleList(parseInt(3), $("#sub_module"));
            $("[name='modules']").val(3).attr("selected", "selected").change();
            
        });

        $('#month-reset').click(function(){ 
            
            function convert(str) {
            var date = new Date(str), 
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            }

            var date = new Date(), y = date.getFullYear(), m = date.getMonth();
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date();

            $("#from_month").val(convert(firstDay));
            $("#to_month").val(convert(lastDay));
            $('#practices').val('').trigger('change');
            $('#modules').val("3");
            $('#practicesgrp').val('').trigger('change');
            // $('#activedeactivestatus').val('').trigger('change');
            // var activedeactivestatus = $('#activedeactivestatus').val(); 

        });
   

        $("#month-search").click(function(){
            var practicesgrp = $('#practicesgrp').val();           
            var practice = $('#practices').val();
            var modules =  $('#modules').val();
            var modules_name = $("#modules option:selected").text();
            // var all_modules_name = $("#modules").val
            var from_month = $('#from_month').val(); 
            var to_month= $('#to_month').val(); 
            if(to_month < from_month)
            {
                $('#to_month').addClass("is-invalid");
                $('#to_month').next(".invalid-feedback").html("Please select to-date properly .");
                $('#from_month').addClass("is-invalid");
                $('#from_month').next(".invalid-feedback").html("Please select from-date properly .");   
            } 
            
            else{ 
                $('#to_month').removeClass("is-invalid");
                $('#to_month').removeClass("invalid-feedback");
                $('#from_month').removeClass("is-invalid");
                $('#from_month').removeClass("invalid-feedback");
                //getCareManagerList(practicesgrp,practice,provider,modules,time,caremanagerid,fromdate1,todate1,timeoption);
            }
            if(modules!=0){
                $("#module_name").html(modules_name);
            }else{
                $("#module_name").html('All');   
            } 
            // $("#module_name").html(modules_name); 
            // var activedeactivestatus =$("#activedeactivestatus").val(); 
            getMonthlyPatientList(practicesgrp,practice,modules,from_month,to_month);   
        });
        $('table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        }); 

        var getPatientData=function(){
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/reports/patient-summary', 
                    //data: data,
                    success: function (data) {
                    // var TotalEnreolledPatient=data.TotalEnreolledPatient[0]['count'];
                    //  var Totalpatient=data.Totalpatient[0]['count'];
                    //   var totalnewenroll=data.totalnewenroll[0]['count'];
                    //    $('#totalpatient').html(Totalpatient);
                    //      $('#totalenrolledpatient').html(TotalEnreolledPatient);
                    //    $('#totalnewpatient').html(totalnewenroll);
                    // console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
                    var TotalActiveEnreolledPatient=data.TotalActiveEnreolledPatient[0]['count'];
                    var TotalSuspendedEnrolledPatient=data.TotalSuspendedEnrolledPatient[0]['count'];
                    var Totalpatient=data.Totalpatient[0]['count'];
                    var totalnewenroll=data.totalnewenroll[0]['count'];
                    //     
                        $('#totalpatient').html(Totalpatient); 
                        $('#totalenrolledpatient').html(TotalActiveEnreolledPatient + '/' +TotalSuspendedEnrolledPatient);   
                        $('#totalnewpatient').html(totalnewenroll);
                    

                    }
            });
        }
    </script>
@endsection