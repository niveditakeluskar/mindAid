@extends('Theme::layouts_2.to-do-master')
@section('page-css')
 @section('page-title')
  Care Manager Daily Productivity Report
@endsection 
@endsection
@section('main-content')\
<style type="text/css">
    .DTFC_LeftBodyWrapper{
        display: none !important;
    }
</style>
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3"> Care Manager Daily Productivity Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>  
<div class="separator-breadcrumb border-top"></div>
@include('Reports::caremanager-daily-productivity-report.summary-filter')
 <div class="row mb-4"> 
    <div class="col-md-12 mb-4">  
        <div class="card text-rightleft"> 
            <div class="card-body">
                <div class="table-responsive">
                    <!-- cellspacing="0" cellpadding="0"  -->
                    <table id="caremanager-listing" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="2">Date</th>
                            <th colspan="17" id="search_date"></th> 
                        </tr>
                         
                        <tr>
                            <th colspan="1">Sr No.</th>
                            <th colspan="1">Care Manager</th>
                            <th colspan="1">Extn.</th>
                            <th colspan="1">Location</th>
                            <th colspan="1">Responsibility</th>
                            <th colspan="2">Enrollment</th>
                            <th colspan="2">Scheduling</th>
                            <th colspan="3">Phone Calls</th>
                            <th colspan="2">CCM</th> 
                            <th colspan="2">RPM</th>
                            <th colspan="2">Awvs</th>
                            <th colspan="1">Productivity</th>
                        </tr>
                        <tr>    
                            <th></th> 
                            <th></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1">E/S/CCM/RPM/AWV</th>
                            <th colspan="1">#Spoke to</th>
                            <th colspan="1">#Enrolled</th>
                            <th colspan="1">#Spoke to</th> 
                            <th colspan="1">#Scheduling</th>
                            <th colspan="1">#</th>
                            <th colspan="1">Seconds</th>
                            <th colspan="1">Minutes</th>
                            <th colspan="1">Minutes</th> 
                            <th colspan="1">#Completed</th>
                            <th colspan="1">Minutes</th> 
                            <th colspan="1">#Completed</th>
                            <th colspan="1">Completed</th>
                            <th colspan="1">ACP</th>
                            <th colspan="1">Goal = 240 mins</th>
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

</style>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
    var getDailyCaremanagerList = function(caremanager = null,date = null) {
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'user_name', name: 'user_name'}, 
                {data: 'extension', name: 'extension'},
                {data: 'location', name: 'location'}, 
                {data: 'responsibility', name: 'responsibility'},
                {data: '', name: ''},  
                {data: 'totalenrolled', name: 'totalenrolled'},
                {data: '', name: ''},
                {data: '', name: ''},
                {data: '', name: ''},
                {data: '', name: ''}, 
                {data: '', name: ''},
                {data: 'ccmtotaltimeinminutes', name: 'ccmtotaltimeinminutes'},
                {data: 'ccm_count', name: 'ccm_count'}, 
                {data: 'rpmtotaltimeinminutes', name: 'rpmtotaltimeinminutes'},
                {data: 'rpm_count', name: 'rpm_count'},
                {data: '', name: ''},
                {data: '', name: ''},
                {data: 'productivity', name: 'productivity'}  
                ];

                
                if(caremanager=='')
                {
                    caremanager='null';
                }
                if(date=='')
                {
                    date='null';
                }
                
                var url = "/reports/care-manager-daily-productivity-report/search/"+caremanager+"/"+date;      
                // console.log(url +"wwe");  renderFixedColumnDataTable
                var table = util.renderFixedColumnDataTable('caremanager-listing', url, columns, "{{ asset('') }}");
        }
        


    $(document).ready(function(){ 
        // $($.fn.dataTable.tables(true)).DataTable().columns.adjust(); //UI 
         $('#caremanager-listing').on('shown.bs.collapse', function () {
            $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust();
        });
        util.getToDoListData(0, {{getPageModuleName()}});
        util.getAssignPatientListData(0, 0);
        getDailyCaremanagerList();
        function convert(str) {
          var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
          return [date.getFullYear(), mnth, day].join("-");
        }

        var date = new Date(),
         y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

        var firstDay = new Date(y, m, d);
        $("#fromdate").attr("value", (convert(firstDay)));
        var date = $("#fromdate").val();
        var new_date    = new Date(date),
            yr      = new_date.getFullYear(),
            month   = new_date.getMonth()+1,// add 1month more 
            day     = new_date.getDate(),
            newDate = month + '/' + day + '/' + yr;
        if(date!=''){
            $("#search_date").html(newDate);
        }
        getDailyCaremanagerList(null,date);
    });
    $('#daily-search').click(function(){
        function convert(str) {
          var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
          return [date.getFullYear(), mnth, day].join("-");
        }
       var fromdate=$('#fromdate').val();
       var CurrentDate = new Date();
       y = CurrentDate.getFullYear(), m = CurrentDate.getMonth(), d=CurrentDate.getDate();
       var currentDay = new Date(y, m, d);
       var presentDay = convert(currentDay);

        if(presentDay < fromdate){
            alert('SelectedDate is more than CurrentDate!');
        }else{

            var care_manager_id = $('#care_manager_id').val();
            var date = $('#fromdate').val();
            var new_date    = new Date(date),
                yr      = new_date.getFullYear(),
                month   = new_date.getMonth()+1,// add 1month more 
                day     = new_date.getDate(),
                newDate = month + '/' + day + '/' + yr;
            if(date!=''){
                $("#search_date").html(newDate);
            }
            getDailyCaremanagerList(care_manager_id,date);
        }
    });
    $('#resetbutton').click(function(){
      function convert(str) {
      var date = new Date(str), 
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear(), mnth, day].join("-"); 
      }

        var date = new Date(),
        y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

        var firstDay = new Date(y, m, d);
        $("#fromdate").attr("value", (convert(firstDay)));
        var date = $("#fromdate").val();
        var new_date    = new Date(date),
            yr      = new_date.getFullYear(),
            month   = new_date.getMonth()+1,// add 1month more 
            day     = new_date.getDate(),
            newDate = month + '/' + day + '/' + yr;
        if(date!=''){
            $("#search_date").html(newDate);
        }
        var care_manager_id = $('#care_manager_id').val('').trigger('change'); 
    }); 

    </script>
@endsection