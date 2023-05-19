@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Care ManagerCare Manager Performance Report
@endsection    
@endsection
@section('main-content')

<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Performance Report</h4> 
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
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Caremanager Name</label>
                        @selectorguser("caremanagerid", ["id" => "caremanagerid", "placeholder" => "Select Users","class" => "select2"])
                    </div> 
                    <div class="col-md-3 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate","name" =>"fromdate"])                           
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate","name" =>"todate"])                           
                    </div>
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="month-search">Search</button>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="reset-btn">Reset</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft">
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>data saved successfully! </strong><span id="text"></span>
               </div>
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                 <div class="row mb-4"> 
    <div class="col-md-12 mb-4"> 
        <div class="card text-rightleft"> 
            <div class="card-body">
                <div class="table-responsive">
                <table id="performance-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="3">Performance Metrics</th>
                            <th colspan="3">Productivity </th>
                        </tr>
                        <tr>
                            <th>Sr</th>
                            <th>Caremanager Name</th>
                            <th colspan="1">Number of Assigned Patient</th>
                            <th colspan="1">Contacted</th>
                            <th colspan="1">Completed</th>
                            <th colspan="1">Billable Sheet</th>
                            <th colspan="1">%Contacted</th>
                            <th colspan="1">%Completed</th>
                            <th colspan="1">%Billable Sheet</th>
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
    var caremanagerPerformance = function(caremanagerid=null,fromdate=null,todate=null) {     
        var columns =  [   
                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'caremanagername', name: 'caremanagername'},
                            {data: 'assignedpatient', name: 'assignedpatient'},
                            {data: 'contacted',name:'contacted'},
                            {data: 'completed',name:'completed'},
                            {data:null,
                                mRender: function(data, type, full, meta){
                                    billable = full['bill'];
                                    if(full['bill'] == null){
                                        billable = '';
                                    }else if(full['bill']  == '1'){
                                        billable = 'Yes';
                                    }else{
                                        billable = 'No'; 
                                    }
                                        
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        return billable;
                                    } 
                                },
                                orderable: true
                            },
                            {data: 'contacted', name: 'contacted', mRender: function(data, type, full, meta){
                                contactedcount = full['contacted'];
                                if(full['contacted'] == 0){
                                    return 0;
                                }   
                                if(data!='' && data!='NULL' && data!=undefined){
                                    contacted = ((contactedcount*100)/full['assignedpatient']);
                                        return parseFloat(contacted).toFixed(2);
                                        }
                                    },
                                orderable: true
                            } ,
                            {data: 'completed', name: 'completed', mRender: function(data, type, full, meta){
                                completedcount = full['completed'];
                                if(full['completed'] == 0){
                                    return 0;
                                }   
                                if(data!='' && data!='NULL' && data!=undefined){
                                    completed = ((completedcount*100)/full['assignedpatient']);
                                        return parseFloat(completed).toFixed(2);
                                        }
                                    },
                                orderable: true
                            } ,
                            {data: 'bill', name: 'bill', mRender: function(data, type, full, meta){
                                billcount = full['bill'];
                                if(full['bill'] == 0){
                                    return 0;
                                }   
                                if(data!='' && data!='NULL' && data!=undefined){
                                    bill = ((billcount*100)/full['assignedpatient']);
                                        return parseFloat(bill).toFixed(2);
                                        }
                                    },
                                orderable: true
                            }
                            // {data:'billable',name:'billable'},  
                        ];  

        
        if(caremanagerid==''){caremanagerid=null;}
        if(fromdate==''){fromdate=null;}
        if(todate==''){todate=null;}
    
        var url = "care-manager-performance-report/search/"+caremanagerid+'/'+fromdate+'/'+todate;
        table1 = util.renderDataTable('performance-list', url, columns, "{{ asset('') }}");
    }
    $(document).ready(function() {
    function convert(str) {
        var date = new Date(str), 
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear(), mnth, day].join("-");
    }
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date();
        $("#fromdate").val(convert(firstDay));
        $("#todate").val(convert(lastDay));
    });

    $("#month-search").click(function(){
        var fromdate=$('#fromdate').val();
        var todate=$('#todate').val();
        var caremanagerid =$('#caremanagerid').val();
        caremanagerPerformance(caremanagerid,fromdate,todate);
    });
    $("#reset-btn").click(function(){
        function convert(str) {
            var date = new Date(str), 
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date();
        $("#fromdate").val(convert(firstDay));
        $("#todate").val(convert(lastDay));
        var fromdate=$('#fromdate').val();
        var todate=$('#todate').val();
        $('#caremanagerid').val('').trigger('change');
        var caremanagerid = $('#caremanagerid').val();
        caremanagerPerformance(caremanagerid,fromdate,todate);
    });
</script>
@endsection