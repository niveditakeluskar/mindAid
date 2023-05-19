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
                            <th></th>
                            <th colspan="2">CCM</th>
                            <th colspan="16">TimeCompleted</th>
                        </tr>
                         
                        <tr>
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
                            <th>Awv's</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>hdkjdasddouwie hjdaksadsa</td>
                        <td>2</td>
                        <td>$320,800</td>
                        <td></td>
                        <td>6</td>
                        <td>1</td> 
                        <td>2</td>
                        <td>$320</td>
                        <td>5421</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                        <td>10</td>
                        <td>11</td>
                        <td>12</td>
                    </tr>
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
    tr {outline: 1px solid #dee2e6;}
    td {outline: 1px solid #dee2e6;} 
    th {outline: 1px solid #dee2e6;}
/*.dt-body-nowrap {
   white-space: nowrap;

}
*/   
</style>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>

    <script type="text/javascript"> 
    $('#patient-list').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ]
                        });

        var getMonthlyPatientList = function(practicesgrp = 0,practice = 0,modules = 0,from_month = 0,to_month = 0) {
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
                ];

                if(practicesgrp=='')
                {
                    practicesgrp=0;
                }  
                if(practice=='')
                {
                    practice=0;
                }
                if(modules=='')
                {
                    modules=0;
                }
                if(from_month=='')
                {
                    from_month=0;
                }

                if(to_month=='')
                {
                    to_month=0;
                }
                
                var url = "/reports/monthly-account-personal-report/search/"+practicesgrp+"/"+practice+"/"+modules+"/"+from_month+"/"+to_month;      
                 //console.log(url);
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}"); 
        }
        $(document).ready(function() {
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
        $("#month-search").click(function(){
            var practicesgrp = $('#practicesgrp').val();           
            var practice = $('#practices').val();
            var modules = $('#modules').val();
            var from_month = $('#from_month').val(); 
            var to_monthly = $('#to_monthly').val(); 
            getMonthlyPatientList(practicesgrp,practice,provider,modules,monthly);   
        });
        $('table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        }); 
    </script>
@endsection